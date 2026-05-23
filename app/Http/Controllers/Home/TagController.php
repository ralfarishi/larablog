<?php

declare(strict_types=1);

namespace App\Http\Controllers\Home;

use App\Models\Post;
use App\Models\Tag;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
  public function index($tag)
  {
    $tagModel = Tag::where('slug', $tag)->orWhere('name', $tag)->firstOrFail();

    $posts = $tagModel
      ->posts()
      ->select(['posts.id', 'posts.title', 'posts.slug', 'posts.status', 'posts.user_id', 'posts.category_id', 'posts.created_at'])
      ->with([
        'user:id,name,slug',
        'category:id,name,slug',
        'media',
      ])
      ->withCount('comments')
      ->where('status', 'published')
      ->latest()
      ->paginate(4);

    $selectedTag = $tagModel->name;

    $sidebarData = getSidebarData();

    return view('tags', compact('posts', 'selectedTag'), $sidebarData);
  }
}
