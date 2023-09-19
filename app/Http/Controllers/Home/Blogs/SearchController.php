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
		// Ambil kata kunci pencarian dari form
		$query = $request->input('query');

		$categories = Categories::withCount(['posts' => function ($query) {
			$query->where('active', 1);
		}])->get();

		// Lakukan pencarian ke dalam model Posts (sesuaikan dengan model yang Anda gunakan)
		$results = Posts::where('title', 'like', '%' . $query . '%')
			// ->orWhere('content', 'like', '%' . $query . '%')
			->where('active', 1) // Pastikan hanya mencari artikel yang aktif
			->get();

		return view('search', compact('results', 'query', 'categories'));
	}
}