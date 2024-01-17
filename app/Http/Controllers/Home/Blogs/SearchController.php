<?php

namespace App\Http\Controllers\Home\Blogs;

use App\Models\Posts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
	public function index(Request $request)
	{
		$query = $request->input('q');

		$results = Posts::with(['user', 'comments'])
			->where('active', 1)
			->where(function ($q) use ($query) {
				$q->where('title', 'like', '%' . $query . '%')
					->orWhere('content', 'like', '%' . $query . '%');
			})
			->paginate(4);

		$sidebarData = getSidebarData();

		return view('search', compact('results', 'query'), $sidebarData);
	}
}
