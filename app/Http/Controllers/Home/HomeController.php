<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Posts;

class HomeController extends Controller
{
	public function index()
	{
		$posts = Posts::where('active', 1)
			->withCount('comments as comment_count')
			->orderBy('comment_count', 'desc')
			->latest()
			->paginate(4);

		$categories = Categories::withCount(['posts' => function ($query) {
			$query->where('active', 1);
		}])->get();

		$tags = Posts::where('active', 1)->pluck('tags')->flatMap(function ($tags) {
			return explode(',', $tags);
		})->unique()->reject(function ($tag) {
			return empty($tag); // Hapus tag yang kosong
		});

		// dd($tags);

		return view('home', compact('posts', 'categories', 'tags'));
	}

	public function test()
	{
		return view('auth.login');
	}
}