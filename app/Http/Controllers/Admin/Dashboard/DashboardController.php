<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Comments;
use App\Models\Posts;

class DashboardController extends Controller
{
	public function index()
	{
		$total_posts = Posts::count();
		$total_comments = Comments::count();
		$total_categories = Categories::count();

		return view('admin.dashboard', [
			'total_posts' => $total_posts,
			'total_comments' => $total_comments,
			'total_categories' => $total_categories
		]);
	}
}
