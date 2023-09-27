<?php

namespace App\Http\Controllers\Home\Blogs;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Comments;
use App\Models\Posts;
use Illuminate\Http\Request;

class PostController extends Controller
{
	public function show($slug)
	{
		$post = Posts::where('slug', $slug)->where('active', 1)->firstOrFail();

		$activeComments = $post->comments()->where('active', 1)->get();
		$totalComments = $activeComments->count();

		$categories = Categories::withCount(['posts' => function ($query) {
			$query->where('active', 1);
		}])->get();

		// get the latest post
		// $latestPosts = Posts::where('active', 1)
		// 	->where('id', '!=', $post->id)
		// 	->latest()
		// 	->limit(5)
		// 	->get();

		// get the related post based on category
		$category = $post->category;
		$relatedPosts = Posts::where('category_id', $category->id)
			->where('id', '!=', $post->id)
			->orWhere(function ($query) use ($post) {
				// Menggunakan subquery untuk mencocokkan artikel berdasarkan tag yang sama
				$query->where('id', '!=', $post->id)
					->where(function ($query) use ($post) {
						// Memisahkan dan mencocokkan tag-tag
						$tags = explode(',', $post->tags);
						foreach ($tags as $tag) {
							$query->orWhere('tags', 'LIKE', '%' . $tag . '%');
						}
					});
			})
			->latest()
			->limit(5)
			->get();

		// get tags per post
		$postTags = explode(',', $post->tags);

		// dd($postTags);

		// get all tags
		$tags = Posts::where('active', 1)->pluck('tags')->flatMap(function ($tags) {
			return explode(',', $tags);
		})->unique()->reject(function ($tag) {
			return empty($tag); // Hapus tag yang kosong
		});

		return view('blog.post', compact('post', 'activeComments', 'totalComments', 'relatedPosts', 'categories', 'postTags', 'tags'));
	}

	public function storeComment(Request $request, $id)
	{
		$data = $request->validate(
			[
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

		$data = $request->all();

		// escape html char for each inputs
		foreach ($data as $key => $value) {
			$data[$key] = strip_tags($value);
		}

		$post = Posts::where('slug', $id)->firstOrFail();

		$comment = new Comments();
		$comment->fill($data);
		$comment->post_id = $post->id;

		$comment->save();

		return back()->with('success', 'Komentar berhasil terkirim!');
	}
}
