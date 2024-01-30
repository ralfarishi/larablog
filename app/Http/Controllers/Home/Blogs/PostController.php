<?php

namespace App\Http\Controllers\Home\Blogs;

use App\Models\Posts;
use App\Models\Comments;
use App\Models\Categories;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\User;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Auth;

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

		// get all tags
		$tags = Posts::where('active', 1)->pluck('tags')->flatMap(function ($tags) {
			return array_map('strtolower', explode(',', $tags));
		})->unique()->reject(function ($tag) {
			return empty($tag); // Hapus tag yang kosong
		});

		/*
		 * Generate on page SEO
		*/
		// get blog description
		$content = $post->content;

		$pTag = getParagraphTagOnly($content);

		$firstPeriodPosition = strpos($pTag, '.'); // find the first '.' (dot) symbol in content

		if ($firstPeriodPosition === false) {
			$firstSentence = $pTag;
		} else {
			$firstSentence = substr($pTag, 0, $firstPeriodPosition + 1);
		}

		// get URL
		$baseUrl = url('/');
		$canonicalUrl = $baseUrl . '/blog/' . $post->slug;
		$blogImage = $baseUrl . '/uploads/' . $post->image;

		// generate SEO
		SEOTools::setTitle($post->title);
		SEOTools::setDescription($firstSentence);
		SEOTools::setCanonical($canonicalUrl);

		SEOTools::opengraph()->setTitle($post->title);
		SEOTools::opengraph()->setDescription($firstSentence);
		SEOTools::opengraph()->setUrl($baseUrl);

		SEOTools::twitter()->setTitle($post->title);
		SEOTools::twitter()->setDescription($firstPeriodPosition);
		SEOTools::twitter()->setImage($blogImage);

		return view('blog.post', compact(
			'post',
			'activeComments',
			'totalComments',
			'relatedPosts',
			'categories',
			'postTags',
			'tags'
		));
	}

	public function storeComment(StoreCommentRequest $request, $id)
	{
		$data = $request->validated();

		$data['user_id'] = Auth::user()->id;

		// escape html char for each inputs
		foreach ($data as $key => $value) {
			$data[$key] = strip_tags($value);
		}

		$post = Posts::where('slug', $id)->firstOrFail();

		$comment = new Comments();
		$comment->fill($data);
		$comment->post_id = $post->id;

		$comment->save();

		return back()->with('tsuccess', 'Comment successfully sent!');
	}

	public function postByUser($slug)
	{
		$user = User::where('slug', $slug)->firstOrFail();

		if (!$user) {
			abort(404);
		}

		$posts = $user->posts()->with(['user', 'comments'])->latest()->paginate(4);

		$sidebarData = getSidebarData();

		return view('post-by-user', compact('user', 'posts'), $sidebarData);
	}
}
