<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Livewire\Component;

class DashboardStats extends Component
{
  public string $period = 'this_week'; // 'this_week', 'this_month', 'all_time'

  public function getStatsProperty()
  {
    $user = auth()->user();
    $isAdmin = $user->role === 'admin';

    $startDate = match ($this->period) {
      'this_week' => now()->subDays(7),
      'this_month' => now()->subDays(30),
      'all_time' => now()->subYears(10),
      default => now()->subDays(7),
    };

    if ($isAdmin) {
      $totalPosts = Post::where('created_at', '>=', $startDate)->count();
      $totalComments = Comment::where('created_at', '>=', $startDate)->count();
      $totalCategories = Category::where('created_at', '>=', $startDate)->count();
      $totalUsers = User::where('created_at', '>=', $startDate)->count();
    } else {
      $totalPosts = Post::where('user_id', $user->id)
        ->where('created_at', '>=', $startDate)
        ->count();
      $totalComments = Comment::whereHas('post', fn($q) => $q->where('user_id', $user->id))
        ->where('created_at', '>=', $startDate)
        ->count();
      $totalCategories = Category::where('created_at', '>=', $startDate)->count();
      $totalUsers = User::where('created_at', '>=', $startDate)->count();
    }

    return [
      'totalPosts' => $totalPosts,
      'totalComments' => $totalComments,
      'totalCategories' => $totalCategories,
      'totalUsers' => $totalUsers,
      'isAdmin' => $isAdmin,
    ];
  }

  public function render()
  {
    return view('livewire.admin.dashboard-stats', [
      'stats' => $this->getStatsProperty(),
    ]);
  }
}
