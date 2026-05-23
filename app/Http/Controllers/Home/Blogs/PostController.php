<?php

declare(strict_types=1);

namespace App\Http\Controllers\Home\Blogs;

use App\Events\CommentPosted;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Bookmark;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\CommentReceived;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
  public function show(string $slug): View
  {
    $post = Post::where('slug', $slug)
      ->where('status', 'published')
      ->with(['user:id,name,slug', 'category:id,name,slug', 'tags:id,name,slug'])
      ->firstOrFail();

    // Related posts via the proper tags pivot relationship (no LIKE full-table-scan)
    // Use getRelation() instead of ->tags to bypass the legacy 'tags' string
    // column on the Post model. Eloquent's getAttribute() returns the column
    // value before checking loaded relations, so ->tags gives a string, not
    // the BelongsToMany collection. getRelation() skips that ambiguity entirely.
    $tagIds = $post->getRelation('tags')->pluck('id')->all();
    $relatedPosts = Post::where('status', 'published')
      ->where('id', '!=', $post->id)
      ->where(function ($query) use ($post, $tagIds): void {
        $query->where('category_id', $post->category_id)
          ->orWhereHas('tags', fn ($q) => $q->whereIn('tags.id', $tagIds));
      })
      ->with([
        'category:id,name,slug',
        'user:id,name,slug',
        'media',
      ])
      ->withCount('comments')
      ->latest()
      ->limit(3)
      ->get();

    // SEO — generate meta description from content
    $pTag        = getParagraphTagOnly($post->content) ?? '';
    $firstPeriod = strpos($pTag, '.');
    $firstSentence = $firstPeriod !== false ? substr($pTag, 0, $firstPeriod + 1) : $pTag;
    $canonicalUrl  = url('/article/' . $post->slug);
    $blogImage     = $post->image_url;

    SEOTools::setTitle($post->title);
    SEOTools::setDescription($firstSentence);
    SEOTools::setCanonical($canonicalUrl);
    SEOTools::opengraph()->setTitle($post->title);
    SEOTools::opengraph()->setDescription($firstSentence);
    SEOTools::opengraph()->setUrl(url('/'));
    SEOTools::twitter()->setTitle($post->title);
    SEOTools::twitter()->setDescription($firstSentence);
    SEOTools::twitter()->setImage($blogImage);

    $isBookmarked =
      auth()->check() &&
      Bookmark::where('user_id', auth()->id())
        ->where('post_id', $post->id)
        ->exists();

    // Use the shared cached sidebar data instead of re-querying categories and tags
    $sidebarData = getSidebarData();

    return view(
      'blog.post',
      array_merge(
        compact('post', 'relatedPosts', 'isBookmarked'),
        $sidebarData,
      ),
    );
  }

  public function storeComment(
    StoreCommentRequest $request,
    string $slug,
  ): RedirectResponse|JsonResponse {
    $post   = Post::where('slug', $slug)->firstOrFail();
    $userId = $request->user()->id;

    $data              = $request->validated();
    $data['user_id']   = $userId;
    $data['post_id']   = $post->id;
    $data['content']   = strip_tags($data['content']); // XSS guard

    $comment = Comment::create($data);
    $comment->load('user', 'post');

    // Notify the post author (not if they commented on their own post)
    if ($post->user_id !== $userId) {
      $post->user->notify(new CommentReceived($post, $request->user()));
    }

    // Broadcast to all OTHER viewers of this post (toOthers excludes submitter's socket)
    broadcast(new CommentPosted($comment))->toOthers();

    if ($request->expectsJson()) {
      $user   = $request->user();
      $avatar = filter_var($user->display_picture ?? '', FILTER_VALIDATE_URL)
        ? $user->display_picture
        : $user->profile_picture_url;

      return response()->json([
        'id'          => $comment->id,
        'content'     => $comment->content,
        'created_at'  => $comment->created_at->format('M d, Y'),
        'user_id'     => $user->id,
        'user_name'   => $user->name,
        'user_avatar' => $avatar,
        'user_role'   => $user->role,
      ]);
    }

    return back()->with('tsuccess', 'Comment successfully sent!');
  }

  public function postByUser(string $slug): View
  {
    $user  = User::where('slug', $slug)->firstOrFail();
    $posts = $user
      ->posts()
      ->where('status', 'published')
      ->select(['id', 'title', 'slug', 'status', 'user_id', 'category_id', 'created_at'])
      ->with([
        'user:id,name,slug',
        'category:id,name,slug',
        'media',
      ])
      ->withCount('comments')
      ->latest()
      ->paginate(4);

    $sidebarData = getSidebarData();

    return view('post-by-user', compact('user', 'posts'), $sidebarData);
  }
}
