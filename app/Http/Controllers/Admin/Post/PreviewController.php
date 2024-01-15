<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Models\Posts;

class PreviewController extends Controller
{
	public function preview($slug)
	{
		$post = Posts::where('slug', $slug)->firstOrFail();

		if ($post->active == 1) {
			return to_route('article.index')->with('warning', 'Tidak bisa melihat preview artikel yang sudah di-publish!');
		}

		$tags = explode(',', $post->tags);

		return view('blog.preview', compact('post', 'tags'));
	}
}
