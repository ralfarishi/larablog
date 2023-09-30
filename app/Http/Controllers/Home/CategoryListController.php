<?php

namespace App\Http\Controllers\Home;

use App\Models\Posts;
use App\Models\Categories;
use App\Http\Controllers\Controller;

class CategoryListController extends Controller
{
	public function show($category)
	{
		$category = Categories::where('name', $category)->firstOrFail();

		$categories = Categories::withCount(['posts' => function ($query) {
			$query->where('active', 1);
		}])->get();

		$posts = $category->posts()
			->where('active', 1)
			->latest()
			->withCount('comments')
			->paginate(4);

		$tags = Posts::where('active', 1)->pluck('tags')->flatMap(function ($tags) {
			return explode(',', $tags);
		})->unique()->reject(function ($tag) {
			return empty($tag); // Hapus tag yang kosong
		});

		$posts->load('comments');

		return view('category', compact('category', 'posts', 'categories', 'tags'));
	}
}