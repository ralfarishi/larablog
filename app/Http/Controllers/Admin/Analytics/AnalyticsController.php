<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
  public function index(): View
  {
    // Cache the entire analytics payload for 15 minutes.
    // Analytics is admin-only and doesn't need real-time precision.
    $analytics = Cache::remember('admin_analytics', 900, function () {
      $thisWeek = now()->startOfWeek();
      $lastWeek = now()->subWeek()->startOfWeek();
      $lastWeekEnd = now()->subWeek()->endOfWeek();

      // ── Zone 1: Stat Cards ──────────────────────────────────────────
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

      // ── Zone 2: Bar Chart — posts published per week (8 weeks) ──────
      $posts = Post::where('status', 'published')
        ->where('created_at', '>=', now()->subWeeks(8)->startOfWeek())
        ->get(['id', 'created_at']);

      $weeklyPosts = $posts
        ->groupBy(fn($post) => $post->created_at->format('oW'))
        ->map(fn($group) => (object) ['total' => $group->count()]);

      $chartLabels = [];
      $chartData = [];
      for ($i = 7; $i >= 0; $i--) {
        $date = now()->subWeeks($i)->startOfWeek();
        $yw = $date->format('oW');
        $chartLabels[] = $date->format('M d');
        $chartData[] = $weeklyPosts->get($yw)?->total ?? 0;
      }

      // ── Zone 3a: Top 5 Most Commented Posts ─────────────────────────
      $topCommented = Post::withCount('comments')
        ->where('status', 'published')
        ->orderByDesc('comments_count')
        ->limit(5)
        ->get(['id', 'title', 'slug', 'status', 'comments_count']);

      // ── Zone 3b: Idle Drafts (draft, untouched > 7 days) ────────────
      $idleDrafts = Post::where('status', 'draft')
        ->where('updated_at', '<', now()->subDays(7))
        ->with('user:id,name')
        ->orderBy('updated_at')
        ->limit(10)
        ->get(['id', 'title', 'slug', 'status', 'user_id', 'updated_at']);

      // ── Average Reading Time — DB aggregate on persisted column ──────
      // No longer loads all posts into PHP memory.
      $avgReadingTime = (int) ceil(
        (float) (Post::where('status', 'published')->avg('reading_time') ?? 0),
      );

      // ── Average Engagement Score — DB-level aggregation ──────────────
      $postsWithCounts = Post::where('status', 'published')
        ->withCount(['comments', 'bookmarks'])
        ->get(['id', 'comments_count', 'bookmarks_count']);

      $totalEngagement = $postsWithCounts->sum(
        fn($p) => $p->bookmarks_count * 2 + $p->comments_count,
      );
      $avgEngagement =
        $postsWithCounts->count() > 0
          ? round($totalEngagement / $postsWithCounts->count(), 1)
          : 0.0;

      // ── Activity Feed ────────────────────────────────────────────────
      $recentComments = Comment::with(['user:id,name', 'post:id,title'])
        ->latest()
        ->limit(10)
        ->get()
        ->map(
          fn($item) => [
            'type' => 'comment',
            'user' => $item->user->name ?? 'Anonymous',
            'target' => $item->post->title ?? 'Deleted Post',
            'time' => $item->created_at,
            'content' => 'Commented: "' . Str::limit($item->content, 40) . '"',
            'icon' => 'ph ph-chat-circle-dots text-amber-500 bg-amber-500/10',
          ],
        );

      $recentUsers = User::latest()
        ->limit(10)
        ->get(['id', 'name', 'email', 'role', 'created_at'])
        ->map(
          fn($item) => [
            'type' => 'user',
            'user' => $item->name,
            'target' => $item->email,
            'time' => $item->created_at,
            'content' => 'Joined as ' . ucfirst($item->role),
            'icon' => 'ph ph-user-plus text-emerald-500 bg-emerald-500/10',
          ],
        );

      $recentPostsCreated = Post::with('user:id,name')
        ->latest()
        ->limit(10)
        ->get(['id', 'title', 'user_id', 'created_at'])
        ->map(
          fn($item) => [
            'type' => 'post',
            'user' => $item->user->name ?? 'Anonymous',
            'target' => $item->title,
            'time' => $item->created_at,
            'content' => 'Drafted/Published an article',
            'icon' => 'ph ph-newspaper text-indigo-500 bg-indigo-500/10',
          ],
        );

      $activityFeed = collect()
        ->concat($recentComments)
        ->concat($recentUsers)
        ->concat($recentPostsCreated)
        ->sortByDesc('time')
        ->take(8);

      return compact(
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
        'avgReadingTime',
        'avgEngagement',
        'activityFeed',
      );
    });

    return view('admin.analytics.index', $analytics);
  }
}
