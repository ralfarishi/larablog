<?php

declare(strict_types=1);

namespace App\Http\Controllers\Home\Blogs;

use App\Events\CommentPosted;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Bookmark;
use App\Models\Category;
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
    $post = Post::where('slug', $slug)->where('status', 'published')->firstOrFail();

    $activeComments = $post->comments()->where('active', true)->with('user.media')->get();
    $totalComments = $activeComments->count();

    $categories = Category::withCount([
      'posts' => fn($q) => $q->where('status', 'published'),
    ])->get();

    $category = $post->category;
    $relatedPosts = Post::where('status', 'published')
      ->where('id', '!=', $post->id)
      ->where(function ($query) use ($post): void {
        $tags = array_filter(array_map('trim', explode(',', (string) $post->tags)));
        $query->where('category_id', $post->category_id)->orWhere(function ($q) use ($tags): void {
          foreach ($tags as $tag) {
            $q->orWhere('tags', 'LIKE', '%' . $tag . '%');
          }
        });
      })
      ->with(['category', 'user', 'media'])
      ->withCount('comments')
      ->latest()
      ->limit(3)
      ->get();

    $postTags = explode(',', $post->tags);

    // Fetch tags via pivot — avoids loading all posts into PHP memory
    $tags = \App\Models\Tag::whereHas('posts', fn($q) => $q->where('status', 'published'))->get();

    // SEO
    $pTag = getParagraphTagOnly($post->content) ?? '';
    $firstPeriod = strpos($pTag, '.');
    $firstSentence = $firstPeriod !== false ? substr($pTag, 0, $firstPeriod + 1) : $pTag;
    $canonicalUrl = url('/article/' . $post->slug);
    $blogImage = $post->image_url;

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

    return view(
      'blog.post',
      compact(
        'post',
        'activeComments',
        'totalComments',
        'relatedPosts',
        'categories',
        'postTags',
        'tags',
        'isBookmarked',
      ),
    );
  }

  public function storeComment(
    StoreCommentRequest $request,
    string $slug,
  ): RedirectResponse|JsonResponse {
    $post = Post::where('slug', $slug)->firstOrFail();
    $userId = $request->user()->id;

    $data = $request->validated();
    $data['user_id'] = $userId;
    $data['post_id'] = $post->id;
    $data['content'] = strip_tags($data['content']); // XSS guard

    $comment = Comment::create($data);
    $comment->load('user', 'post');

    // Notify the post author (not if they commented on their own post)
    if ($post->user_id !== $userId) {
      $post->user->notify(new CommentReceived($post, $request->user()));
    }

    // Broadcast to all OTHER viewers of this post (toOthers excludes submitter's socket)
    broadcast(new CommentPosted($comment))->toOthers();

    if ($request->expectsJson()) {
      $user = $request->user();
      $avatar = filter_var($user->display_picture ?? '', FILTER_VALIDATE_URL)
        ? $user->display_picture
        : $user->profile_picture_url;

      return response()->json([
        'id' => $comment->id,
        'content' => $comment->content,
        'created_at' => $comment->created_at->format('M d, Y'),
        'user_id' => $user->id,
        'user_name' => $user->name,
        'user_avatar' => $avatar,
        'user_role' => $user->role,
      ]);
    }

    return back()->with('tsuccess', 'Comment successfully sent!');
  }

  public function postByUser(string $slug): View
  {
    $user = User::where('slug', $slug)->firstOrFail();
    $posts = $user
      ->posts()
      ->where('status', 'published')
      ->with(['user', 'comments', 'category', 'media'])
      ->latest()
      ->paginate(4);

    $sidebarData = getSidebarData();

    return view('post-by-user', compact('user', 'posts'), $sidebarData);
  }
}
