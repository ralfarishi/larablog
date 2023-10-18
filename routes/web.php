<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\Admin\Comment\CommentsController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Post\PostsController;
use App\Http\Controllers\Admin\Post\PreviewController;
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

Route::middleware(['guest'])->group(function () {
	Route::get('/login', [LoginController::class, 'index'])->name('login');
	Route::post('/login', [LoginController::class, 'login'])->name('auth');
	Route::post('/register', [LoginController::class, 'register'])->name('register');
});

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/blog/{id}', [PostController::class, 'show'])->name('post');
Route::post('/blog/{id}', [PostController::class, 'storeComment'])->name('store-comment');

Route::get('/blog/user/{slug}', [PostController::class, 'postByUser'])->name('post-by-user');

Route::get('/kategori/{kategori}', [CategoryListController::class, 'show'])->name('categories');

Route::get('/tag/{tag}', [TagsController::class, 'index'])->name('post-by-tag');

Route::middleware('auth')->prefix('dashboard')->group(function () {
	Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

	Route::resources([
		'artikel' => PostsController::class,
		'komentar' => CommentsController::class,
	]);

	Route::get('/artikel/p/{id}', [PreviewController::class, 'preview'])->name('preview');

	Route::post("/logout", [LoginController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'is.admin'])->prefix('dashboard')->group(function () {
	Route::resources([
		'kategori' => CategoryController::class,
		'user' => UserController::class
	]);
});
