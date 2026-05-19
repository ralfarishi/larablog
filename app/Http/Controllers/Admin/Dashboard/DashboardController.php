<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
  public function index(Request $request): View
  {
    $user = $request->user();
    $isAdmin = $user->role === 'admin';
    $thisWeek = now()->startOfWeek();
    $lastWeek = now()->subWeek()->startOfWeek();
    $lastWeekEnd = now()->subWeek()->endOfWeek();

    if ($isAdmin) {
      $totalPosts = Post::count();
      $totalComments = Comment::count();

      $postsThisWeek = Post::where('created_at', '>=', $thisWeek)->count();
      $postsLastWeek = Post::whereBetween('created_at', [$lastWeek, $lastWeekEnd])->count();
      $commentsThisWeek = Comment::where('created_at', '>=', $thisWeek)->count();
      $commentsLastWeek = Comment::whereBetween('created_at', [$lastWeek, $lastWeekEnd])->count();

      $recentPosts = Post::with(['category', 'user'])
        ->withCount('comments')
        ->latest()
        ->limit(5)
        ->get();

      $idleDraftsCount = Post::where('status', 'draft')
        ->where('updated_at', '<', now()->subDays(7))
        ->count();

      $newUsersThisWeek = User::where('created_at', '>=', $thisWeek)->count();
    } else {
      $totalPosts = Post::where('user_id', $user->id)->count();
      $totalComments = Comment::whereHas(
        'post',
        fn($q) => $q->where('user_id', $user->id),
      )->count();

      $postsThisWeek = Post::where('user_id', $user->id)
        ->where('created_at', '>=', $thisWeek)
        ->count();
      $postsLastWeek = Post::where('user_id', $user->id)
        ->whereBetween('created_at', [$lastWeek, $lastWeekEnd])
        ->count();
      $commentsThisWeek = Comment::whereHas('post', fn($q) => $q->where('user_id', $user->id))
        ->where('created_at', '>=', $thisWeek)
        ->count();
      $commentsLastWeek = Comment::whereHas('post', fn($q) => $q->where('user_id', $user->id))
        ->whereBetween('created_at', [$lastWeek, $lastWeekEnd])
        ->count();

      $recentPosts = Post::where('user_id', $user->id)
        ->with('category')
        ->withCount('comments')
        ->latest()
        ->limit(5)
        ->get();

      $idleDraftsCount = Post::where('user_id', $user->id)
        ->where('status', 'draft')
        ->where('updated_at', '<', now()->subDays(7))
        ->count();

      $newUsersThisWeek = 0;
    }

    $totalCategories = Category::count();
    $totalUsers = User::count();

    // Compute real deltas as signed integers
    $postsDelta = $postsThisWeek - $postsLastWeek;
    $commentsDelta = $commentsThisWeek - $commentsLastWeek;

    return view(
      'admin.dashboard',
      compact(
        'totalPosts',
        'totalComments',
        'totalCategories',
        'totalUsers',
        'postsDelta',
        'commentsDelta',
        'recentPosts',
        'idleDraftsCount',
        'newUsersThisWeek',
        'isAdmin',
      ),
    );
  }
}
