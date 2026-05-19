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
      ->with(['user', 'comments', 'category', 'media'])
      ->where('status', 'published')
      ->latest()
      ->paginate(4);

    $selectedTag = $tagModel->name;

    $sidebarData = getSidebarData();

    return view('tags', compact('posts', 'selectedTag'), $sidebarData);
  }
}
