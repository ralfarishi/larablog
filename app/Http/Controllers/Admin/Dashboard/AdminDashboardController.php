<?php

namespace App\Http\Controllers\Admin\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CommentsModel;
use App\Models\ContactModel;
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
}