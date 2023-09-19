<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;

class CategoryListController extends Controller
{
	public function show($category)
	{
		$category = Categories::where('name', $category)->firstOrFail();
		$categories = Categories::withCount('posts')->get();

		$posts = $category->posts()
			->where('active', 1)
			->latest()
			->withCount('comments')
			->paginate(4);

		return view('category', compact('category', 'posts', 'categories'));
	}
}
