<?php

namespace App\Http\Controllers\Home;

use App\Models\Posts;
use App\Http\Controllers\Controller;

class TagsController extends Controller
{
	public function index($tag)
	{
		// Cari semua artikel yang memiliki tag yang sama dengan $tag
		$posts = Posts::with(['user', 'comments', 'category'])->where('active', 1)->where('tags', 'LIKE', "%$tag%")->paginate(4);

		if ($posts->isEmpty()) {
			abort(404);
		}

		$selectedTag = $tag;

		$sidebarData = getSidebarData();

		return view('tags', compact('posts', 'selectedTag'), $sidebarData);
	}
}
