<?php

namespace App\Http\Controllers\Blog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PostsModel;
use App\Models\CommentsModel;

class PostsController extends Controller
{
	/**
	 * Show post
	 *
	 *
	 */
	public function show($id)
	{
		$posts = PostsModel::where('slug', $id)->firstOrFail();
		$data['post'] = $posts;

		$data['active_comments'] = CommentsModel::where('post_id', $posts->id)->where('active', 1)->get();
			
		return view("blog.post", $data);
	}

	public function storeComment(Request $request, $id)
	{
		$inputs = $request->validate([
			'user_name' => 'required|string|max:100',
			'user_email' => 'required|email|max:100',
			'content' => 'required|string',
		],
		[
			'user_name.required' => 'Nama tidak boleh kosong!',
			'user_email.required' => 'Email tidak boleh kosong!',
			'content.required' => 'Komentar tidak boleh kosong!',
		]
	);

		$inputs = $request->all();

		// escape html char for each inputs
		foreach ($inputs as $key => $value) {
      $inputs[$key] = strip_tags($value);
    }
		
		$post = PostsModel::where('slug', $id)->firstOrFail();
		// $user = User::findOrFail($id);

		$comment  = new CommentsModel();
		$comment->fill($inputs);
		$comment->post_id = $post->id;
		// $comment->user_id = $user->id;
		$comment->save();

		return back()->with("success", "Success! Komentar kamu sudah terkirim");
	}
}