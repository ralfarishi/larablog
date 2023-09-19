<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Posts;

class HomeController extends Controller
{
	public function index()
	{
		$posts = Posts::where('active', 1)->withCount('comments')->latest()->paginate(4);

		$categories = Categories::withCount(['posts' => function ($query) {
			$query->where('active', 1);
		}])->get();

		return view('home', compact('posts', 'categories'));
	}

	public function test()
	{
		return view('auth.login');
	}
}
