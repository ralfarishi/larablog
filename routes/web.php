<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Blog\PostsController;
use App\Http\Controllers\Blog\SearchController;
use App\Http\Controllers\Admin\Comments\AdminCommentsController;
use App\Http\Controllers\Admin\Dashboard\AdminDashboardController;
use App\Http\Controllers\Admin\Posts\AdminPostsController;
use App\Http\Controllers\Admin\Users\AdminUsersController;
use App\Http\Controllers\NewAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get("/artikel/{id}", [PostsController::class, 'show'])->name('post');
Route::post("/artikel/{id}/save-comment",[PostsController::class, 'storeComment']);
Route::get('blog/search', [SearchController::class,'index'])->name('blog.search');

Route::fallback(function (){
    return view('404');
});

Route::middleware('guest')->group(function () {
    Route::get("/login", [NewAuthController::class, 'index'])->name('login');
    Route::post("/login", [NewAuthController::class,'login']);
});

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('/artikel', AdminPostsController::class);
    
    Route::get('/komentar', [AdminCommentsController::class, 'index'])->name('admin.komentar');
    Route::get('/komentar/{id}/edit', [AdminCommentsController::class, 'edit'])->name("admin.comments.edit");
    Route::patch('/komentar/{id}', [AdminCommentsController::class, 'update'])->name('admin.comments.update');
    Route::delete('/komentar/{id}', [AdminCommentsController::class, 'destroy'])->name('admin.comments.delete');
    
    Route::resource('/users', AdminUsersController::class);

    Route::post("/logout", [NewAuthController::class,'logout'])->name('logout');
});