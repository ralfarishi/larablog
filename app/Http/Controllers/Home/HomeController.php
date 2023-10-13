<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
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

		$twitterCardImage = asset('images/blog-header.jpg');

		SEOTools::twitter()->setImage($twitterCardImage);

		$sidebarData = getSidebarData();

		return view('home', compact('posts'), $sidebarData);
	}
}
