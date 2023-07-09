<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PostsModel;
use Auth;


class HomeController extends Controller
{

    /**
     * Show home page
     *  
     * 
     * @return view
     */
    public function index()
    {

    	$data = array();
      $data['recent_posts'] = PostsModel::OrderBy('created_at', 'Desc')->where('active', '1')->limit(6)->get();
        
    	return view('home', $data);
    }

    public function detail()
    {
    	// $data = array();
      // $data['recent_posts'] = PostsModel::OrderBy('created_at', 'Desc')->where('active', '1')->limit(6)->get();
        
    	return view('blog.post_v2');
    }
}