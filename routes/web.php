<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Analytics\AnalyticsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\Admin\Comment\CommentController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Post\PostController as AdminPostController;
use App\Http\Controllers\Admin\Post\PreviewController;
use App\Http\Controllers\Admin\User\LoginHistoryController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Home\Blogs\PostController;
use App\Http\Controllers\Home\BookmarkController;
use App\Http\Controllers\Home\CategoryListController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\TagController;
use Illuminate\Support\Facades\Route;

// ──────────────────────────────────────────────
// Public routes
// ──────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{category}', [CategoryListController::class, 'show'])->name('categories');
Route::get('/tag/{tag}', [TagController::class, 'index'])->name('post-by-tag');

Route::get('/article/{slug}', [PostController::class, 'show'])->name('post');
Route::get('/article/user/{slug}', [PostController::class, 'postByUser'])->name('post-by-user');

// ──────────────────────────────────────────────
// Notification routes (auth required)
// ──────────────────────────────────────────────
Route::middleware('auth')->group(function (): void {
  // Reader Dashboard & Bookmarks
  Route::get('/my/dashboard', \App\Livewire\Blog\ReaderDashboard::class)->name('reader.dashboard');
  // Redirect legacy /my/bookmarks to the unified reader dashboard
  Route::redirect('/my/bookmarks', '/my/dashboard')->name('bookmark.index');
});

// ──────────────────────────────────────────────
// Admin + Writer dashboard
// ──────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:admin,writer'])
  ->prefix('dashboard')
  ->group(function (): void {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resources([
      'article' => AdminPostController::class,
      'comment' => CommentController::class,
      'user' => UserController::class,
    ]);

    Route::get('/article/p/{slug}', [PreviewController::class, 'preview'])->name('preview');
  });

// ──────────────────────────────────────────────
// Admin-only dashboard
// ──────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:admin'])
  ->prefix('dashboard')
  ->group(function (): void {
    Route::resources([
      'category' => CategoryController::class,
    ]);

    Route::controller(LoginHistoryController::class)->group(function (): void {
      Route::get('/login-history', 'index')->name('login-history.index');
      Route::delete('/login-history', 'destroy')->name('login-history.destroy');
    });

    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
  });

// ──────────────────────────────────────────────
// Search route
// ──────────────────────────────────────────────
Route::get('/search', [\App\Http\Controllers\Home\SearchController::class, 'index'])->name('search');

// ──────────────────────────────────────────────
// Error Page Preview Routes (Dev Only)
// ──────────────────────────────────────────────
if (app()->environment('local')) {
  Route::get('/_debug/error/{code}', function (int $code) {
    $validCodes = [401, 402, 403, 404, 419, 429, 500, 503];
    if (!in_array($code, $validCodes)) {
      abort(404);
    }
    return response()->view("errors.{$code}", [], $code);
  })->name('debug.error');
}

require __DIR__ . '/auth.php';
