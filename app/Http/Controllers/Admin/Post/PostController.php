<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
  public function __construct(private readonly TagService $tagService) {}

  public function index(): View
  {
    return view('admin.posts.list');
  }

  public function create(): View
  {
    $categories = Category::all();
    $post = new Post();
    $availableTags = Tag::pluck('name')->all();

    return view('admin.posts.create', compact('categories', 'post', 'availableTags'));
  }

  public function store(CreatePostRequest $request): RedirectResponse
  {
    $data = $request->validated();

    $data['user_id'] = $request->user()->id;
    $data['slug'] = Str::slug($data['title']);

    $image = $request->file('image');
    $tagInput = $data['tags'] ?? '';
    // Remove fields not on the posts table before insert
    unset($data['image'], $data['tags']);

    $data['allowed_comment'] = filter_var($data['allowed_comment'], FILTER_VALIDATE_BOOLEAN);

    $post = Post::create($data);

    if ($image) {
      $post->clearMediaCollection('cover');
      $post->addMedia($image)->toMediaCollection('cover');
    }

    if (!empty($tagInput)) {
      $this->tagService->sync($post, $tagInput);
    }

    Cache::forget('sidebar_data');

    return to_route('article.index')->with('success', 'Article has been created.');
  }

  public function edit(string $id): View
  {
    $post = Post::where('slug', $id)->firstOrFail();
    $categories = Category::all();
    $availableTags = Tag::pluck('name')->all();

    return view('admin.posts.edit', compact('post', 'categories', 'availableTags'));
  }

  public function update(UpdatePostRequest $request, string $id): RedirectResponse
  {
    $post = Post::where('slug', $id)->firstOrFail();
    $data = $request->validated();

    $data['slug'] = Str::slug($data['title']);
    $data['allowed_comment'] = filter_var($data['allowed_comment'], FILTER_VALIDATE_BOOLEAN);

    $tagInput = $data['tags'] ?? '';
    // Remove fields not on the posts table before update
    unset($data['image'], $data['tags']);

    if ($request->hasFile('image')) {
      $post->addMedia($request->file('image'))->toMediaCollection('cover');
    }

    $post->update($data);

    if (!empty($tagInput)) {
      $this->tagService->sync($post, $tagInput);
    }

    Cache::forget('sidebar_data');

    return to_route('article.index')->with('info', 'Article has been updated.');
  }

  public function toggleStatus(Request $request, string $id): JsonResponse|RedirectResponse
  {
    $post = Post::where('slug', $id)->firstOrFail();

    $post->status = match ($post->status) {
      'published' => 'draft',
      'draft' => 'published',
      'hidden' => 'draft', // admin unhides → back to draft
    };
    $post->save();

    $label = ucfirst($post->status);

    if ($request->expectsJson()) {
      return response()->json([
        'success' => true,
        'status' => $post->status,
        'message' => "Article has been moved to {$label}.",
      ]);
    }

    return back()->with('info', "Article has been moved to {$label}.");
  }

  public function destroy(Request $request, string $id): JsonResponse|RedirectResponse
  {
    $post = Post::where('slug', $id)->firstOrFail();

    if ($post->status === 'published') {
      if ($request->expectsJson()) {
        return response()->json(
          [
            'success' => false,
            'message' => 'This article is published. Unpublish it first before deleting.',
          ],
          422,
        );
      }

      return to_route('article.index')->with(
        'warning',
        'This article is published. Unpublish it first before deleting.',
      );
    }

    $post->delete();

    if ($request->expectsJson()) {
      return response()->json([
        'success' => true,
        'message' => 'Article has been deleted.',
      ]);
    }

    return to_route('article.index')->with('danger', 'Article has been deleted.');
  }
}
