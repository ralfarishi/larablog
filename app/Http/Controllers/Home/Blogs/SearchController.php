<?php

namespace App\Http\Controllers\Home\Blogs;

use App\Models\Posts;
use App\Models\Categories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
	public function index(Request $request)
	{
		$query = $request->input('query');

		$categories = Categories::withCount(['posts' => function ($query) {
			$query->where('active', 1);
		}])->get();

		$results = Posts::where('title', 'like', '%' . $query . '%')
			->orWhere('content', 'like', '%' . $query . '%')
			->where('active', 1)
			->paginate(4);

		return view('search', compact('results', 'query', 'categories'));
	}
}
