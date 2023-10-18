<?php

namespace App\Http\Controllers\Home;

use App\Models\Categories;
use App\Http\Controllers\Controller;

class CategoryListController extends Controller
{
	public function show($category)
	{
		$category = Categories::where('name', $category)->firstOrFail();

		$posts = $category->posts()
			->with(['user'])
			->where('active', 1)
			->latest()
			->withCount('comments')
			->paginate(4);

		$sidebarData = getSidebarData();

		$posts->load('comments');

		return view('category', compact('category', 'posts'), $sidebarData);
	}
}
