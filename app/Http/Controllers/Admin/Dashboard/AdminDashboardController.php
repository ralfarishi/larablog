<?php

namespace App\Http\Controllers\Admin\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CommentsModel;
use App\Models\PostsModel;

class AdminDashboardController extends Controller
{
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		// $data = array();

		$num_unread_comments = CommentsModel::where('read','!=','1')->count();
		$total_posts = PostsModel::count();

		$data['num_unread_comments'] = $num_unread_comments;
		$data['total_posts'] = $total_posts;

		return view("admin.dashboard", $data);
	}

	public function v2()
	{
		return view('admin.dashboard_v2');
	}

	public function adminLogin()
	{
		return view('auth.login_v2');
	}

	public function createArticle()
	{
		return view('admin.posts.post_v2.create');
	}

	public function listArticle()
	{
		return view('admin.posts.post_v2.list');
	}

	public function listComments()
	{
		return view('admin.comments.list_v2');
	}

	public function listUsers()
	{
		return view('admin.users.list_v2');
	}
}