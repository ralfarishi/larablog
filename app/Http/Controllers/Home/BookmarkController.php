<?php

declare(strict_types=1);

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class BookmarkController extends Controller
{
  public function index(): View
  {
    $bookmarks = \App\Models\Bookmark::where('user_id', auth()->id())
      ->with(['post.user', 'post.category', 'post.media'])
      ->latest()
      ->paginate(12);

    return view('blog.bookmarks', compact('bookmarks'));
  }
}
