<?php

namespace App\Http\Controllers\Blog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CategoriesModel;
use App\Models\PostsModel;
use App\Models\User;
use App\Models\CommentsModel;

class PostsController extends Controller {

    /**
     * Show post 
     * 
     *  
     */
	public function show($id) 
    {
        
		$posts = PostsModel::active()->where('slug', $id)->firstOrFail();

        $data['post'] = $posts;
        
		return view("blog.post", $data);
	}

    public function storeComment(Request $request, $id)
    {

        $inputs = $request->validate([
            'user_name' => 'required',
            'user_email' => 'required',
            'content' => 'required',
        ]);

        $inputs = $request->all();
        
        $post = PostsModel::active()->where('slug', $id)->firstOrFail();
        // $user = User::findOrFail($id);

        $comment  = new CommentsModel();
        $comment->fill($inputs);
        $comment->post_id = $post->id;
        // $comment->user_id = $user->id;
        $comment->save();

        return back()->with("success", "Success! Komentar kamu sudah terkirim");

    }
}