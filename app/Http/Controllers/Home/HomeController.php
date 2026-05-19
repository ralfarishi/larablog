<?php

declare(strict_types=1);

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Post;

use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
  public function index()
  {
    $posts = Post::where('status', 'published')
      ->withCount('comments')
      ->with(['category', 'user', 'media'])
      ->latest()
      ->paginate(4);

    $twitterCardImage = asset('images/blog-header.jpg');

    SEOTools::twitter()->setImage($twitterCardImage);

    $sidebarData = getSidebarData();

    return view('home', compact('posts'), $sidebarData);
  }
}
