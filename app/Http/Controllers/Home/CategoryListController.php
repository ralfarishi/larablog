<?php

declare(strict_types=1);

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryListController extends Controller
{
  public function show($category)
  {
    $category = Category::where('name', $category)->firstOrFail();

    $posts = $category
      ->posts()
      ->with(['user', 'category', 'media'])
      ->where('status', 'published')
      ->latest()
      ->withCount('comments')
      ->paginate(4);

    $sidebarData = getSidebarData();

    return view('category', compact('category', 'posts'), $sidebarData);
  }
}
