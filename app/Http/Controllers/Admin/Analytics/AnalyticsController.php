<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
  public function index(): View
  {
    // ── Zone 1: Stat Cards ──────────────────────────────────────
    $thisWeek = now()->startOfWeek();
    $lastWeek = now()->subWeek()->startOfWeek();
    $lastWeekEnd = now()->subWeek()->endOfWeek();

    $totalPublished = Post::where('status', 'published')->count();
    $totalComments = Comment::count();
    $newUsersThisWeek = User::where('created_at', '>=', $thisWeek)->count();
    $newUsersPrevWeek = User::whereBetween('created_at', [$lastWeek, $lastWeekEnd])->count();

    $publishedThisWeek = Post::where('status', 'published')
      ->where('created_at', '>=', $thisWeek)
      ->count();
    $publishedLastWeek = Post::where('status', 'published')
      ->whereBetween('created_at', [$lastWeek, $lastWeekEnd])
      ->count();
    $commentsThisWeek = Comment::where('created_at', '>=', $thisWeek)->count();
    $commentsLastWeek = Comment::whereBetween('created_at', [$lastWeek, $lastWeekEnd])->count();

    // ── Zone 2: Bar Chart — posts published per week (8 weeks) ──
    $weeklyPosts = Post::select(
      DB::raw('YEARWEEK(created_at, 1) as yw'),
      DB::raw('COUNT(*) as total'),
    )
      ->where('status', 'published')
      ->where('created_at', '>=', now()->subWeeks(8))
      ->groupBy('yw')
      ->orderBy('yw')
      ->get()
      ->keyBy('yw');

    // Build 8 contiguous week labels + counts (fill gaps with 0)
    $chartLabels = [];
    $chartData = [];
    for ($i = 7; $i >= 0; $i--) {
      $date = now()->subWeeks($i)->startOfWeek();
      $yw = $date->format('oW'); // ISO year + week number
      $chartLabels[] = $date->format('M d');
      $chartData[] = $weeklyPosts->get($yw)?->total ?? 0;
    }

    // ── Zone 3a: Top 5 Most Commented Posts ─────────────────────
    $topCommented = Post::withCount('comments')
      ->where('status', 'published')
      ->orderByDesc('comments_count')
      ->limit(5)
      ->get();

    // ── Zone 3b: Idle Drafts (draft, untouched > 7 days) ────────
    $idleDrafts = Post::where('status', 'draft')
      ->where('updated_at', '<', now()->subDays(7))
      ->with('user')
      ->orderBy('updated_at')
      ->limit(10)
      ->get();

    return view(
      'admin.analytics.index',
      compact(
        'totalPublished',
        'totalComments',
        'newUsersThisWeek',
        'newUsersPrevWeek',
        'publishedThisWeek',
        'publishedLastWeek',
        'commentsThisWeek',
        'commentsLastWeek',
        'chartLabels',
        'chartData',
        'topCommented',
        'idleDrafts',
      ),
    );
  }
}
