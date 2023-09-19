<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\Admin\Comment\CommentsController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Post\PostsController;
use App\Http\Controllers\Home\Blogs\PostController;
use App\Http\Controllers\Home\Blogs\SearchController;
use App\Http\Controllers\Home\CategoryListController;
use App\Http\Controllers\Home\HomeController;
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
});

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/blog/{id}', [PostController::class, 'show'])->name('post');
Route::post('/blog/{id}', [PostController::class, 'storeComment'])->name('store-comment');

Route::get('/{kategori}', [CategoryListController::class, 'show'])->name('categories');

Route::middleware('auth')->prefix('admin')->group(function () {
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

	Route::controller(PostsController::class)->group(function () {
		Route::get('/artikel', 'index')->name('list-posts');
		Route::get('/artikel/buat-artikel', 'create')->name('create-post');
		Route::post('/artikel/buat-artikel', 'store')->name('store-post');
		Route::get('/artikel/edit-artikel/{id}', 'edit')->name('edit-post');
		Route::patch('/artikel/edit-artikel/{id}', 'update')->name('update-post');
		Route::delete('/artikel/{id}', 'destroy')->name('delete-post');
	});

	Route::controller(CommentsController::class)->group(function () {
		Route::get('/komentar', 'index')->name('list-comments');
		Route::get('/komentar/{id}', 'edit')->name('edit-comment');
		Route::patch('/komentar/{id}', 'update')->name('update-comment');
		Route::delete('/komentar/{id}', 'destroy')->name('delete-comment');
	});

	Route::controller(CategoryController::class)->group(function () {
		Route::get('/kategori', 'index')->name('list-categories');
		Route::get('/kategori/buat-kategori', 'create')->name('create-category');
		Route::post('/kategori/buat-kategori', 'store')->name('store-category');
		Route::delete('/kategori/{id}', 'destroy')->name('delete-category');
	});

	Route::post("/logout", [LoginController::class, 'logout'])->name('logout');
});
