<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PreviewController extends Controller
{
  public function preview(string $slug): View|RedirectResponse
  {
    $post = Post::where('slug', $slug)->firstOrFail();

    if ($post->status === 'published') {
      return to_route('article.index')->with('warning', "Can't preview a published article.");
    }

    // Load tags from the pivot relation (not the legacy string column)
    $tags = $post->tags()->orderBy('name')->get();

    return view('blog.preview', compact('post', 'tags'));
  }
}
