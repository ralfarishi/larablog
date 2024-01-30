<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\Admin\Comment\CommentsController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Post\PostsController;
use App\Http\Controllers\Admin\Post\PreviewController;
use App\Http\Controllers\Admin\User\LoginHistoryController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Home\Blogs\PostController;
use App\Http\Controllers\Home\Blogs\SearchController;
use App\Http\Controllers\Home\CategoryListController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\TagsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/blog/{id}', [PostController::class, 'show'])->name('post');
Route::post('/blog/{id}', [PostController::class, 'storeComment'])->name('store-comment');

Route::get('/blog/user/{slug}', [PostController::class, 'postByUser'])->name('post-by-user');

Route::get('/category/{category}', [CategoryListController::class, 'show'])->name('categories');

Route::get('/tag/{tag}', [TagsController::class, 'index'])->name('post-by-tag');

Route::middleware(['auth', 'verified', 'role:admin,writter'])->prefix('admin')->group(function () {
	Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

	Route::resources([
		'article' => PostsController::class,
		'comment' => CommentsController::class,
	]);

	Route::get('/article/p/{id}', [PreviewController::class, 'preview'])->name('preview');

	Route::post("/logout", [LoginController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
	Route::resources([
		'category' => CategoryController::class,
		'user' => UserController::class
	]);

	Route::controller(LoginHistoryController::class)->group(function () {
		Route::get('/login-history', 'index')->name('login-history.index');
		Route::delete('/login-history', 'destroy')->name('login-history.destroy');
	});
});

require __DIR__ . '/auth.php';
