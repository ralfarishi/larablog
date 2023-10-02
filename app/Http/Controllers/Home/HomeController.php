<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Posts;
use Artesaos\SEOTools\Facades\SEOTools;

class HomeController extends Controller
{
	public function index()
	{
		$posts = Posts::where('active', 1)
			->withCount('comments as comment_count')
			->with(['category', 'comments', 'user'])
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

		$twitterCardImage = asset('images/blog-header.jpg');

		SEOTools::twitter()->setImage($twitterCardImage);

		return view('home', compact('posts', 'categories', 'tags'));
	}
}
