<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Comments;
use App\Models\Posts;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
	public function index()
	{
		if (Auth::user()->role == 'admin') {
			$totalPosts = Posts::count();
			$totalComments = Comments::count();
		} else {
			$totalPosts = Posts::where('user_id', Auth::user()->id)->count();
			$totalComments = Comments::whereHas('post', function ($query) {
				$query->where('user_id', Auth::user()->id);
			})->count();
		}

		$totalCategories = Categories::count();
		$totalUsers = User::count();

		return view('admin.dashboard', [
			'totalPosts' => $totalPosts,
			'totalComments' => $totalComments,
			'totalCategories' => $totalCategories,
			'totalUsers' => $totalUsers
		]);
	}
}
