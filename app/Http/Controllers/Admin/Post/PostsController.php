<?php

namespace App\Http\Controllers\Admin\Post;

use App\Models\Posts;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Categories;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			if (Auth::user()->role == 'admin') {
				$model = Posts::with(['category', 'comments', 'user'])->orderBy('active', 'DESC')->latest();
			} else {
				$model = Posts::where('user_id', Auth::user()->id)->with(['category', 'comments', 'user'])->orderBy('active', 'DESC')->latest();
			}

			return DataTables::of($model)
				->addColumn('status', function ($model) use ($request) {
					return $model->active ? '<span class="badge bg-success">Published</span>' : '<span class="badge bg-info">Draft</span>';
				})
				->filterColumn('status', function ($query, $keyword) {
					if ($keyword == 'published') {
						$query->where('active', true);
					} elseif ($keyword == 'draft') {
						$query->where('active', false);
					}
				})
				->addColumn('actions', function ($model) use ($request) {
					$id = $model->slug;
					$link = $request->url() . '/' . $id;
					return '
						<a href="' . route('article.edit', $id) . ' " class="btn btn-primary btn-sm" title="Edit"><span class="bi bi-pencil-square"></span></a>
						<a href="' . route('preview', $model->slug) . ' " class="btn btn-warning btn-sm text-white mx-1" title="Preview"><span class="bi bi-eye-fill"></span></a>
						<a href="" data-delete-url="' . $link . '" class="btn btn-danger btn-sm delete-data" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Delete"><span class="bi bi-trash3-fill"></span></a>
					';
				})
				->addColumn('comment_count', function ($model) use ($request) {
					return $model->comments->count();
				})
				->addColumn('writer', function ($model) use ($request) {
					return $model->user->name;
				})
				->rawColumns(['actions', 'status'])
				->make(true);
		}
		return view('admin.posts.list');
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		$categories = Categories::all();
		$post = new Posts();

		return view('admin.posts.create', compact('categories', 'post'));
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(CreatePostRequest $request)
	{
		$data = $request->validated();

		$data['user_id'] = Auth::user()->id;
		$data['slug'] = Str::slug($request->title);

		$data['image'] = $request->file('image')->store('images/blogs', 'public');

		Posts::create($data);

		return to_route('article.index')->with('success', 'Article has been created.');
	}

	/**
	 * Display the specified resource.
	 */
	public function show(string $id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(string $id)
	{
		$post = Posts::where('slug', $id)->firstOrFail();
		$categories = Categories::all();

		return view("admin.posts.edit", compact('post', 'categories'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdatePostRequest $request, string $id)
	{
		$data = $request->validated();

		$post = Posts::findOrFail($id);

		$data['slug'] = Str::slug($request->title);

		if ($request->hasFile('image')) {
			$data['image'] = $request->store('images/blogs', 'public');
		}

		$post->update($data);

		return to_route('article.index')->with('info', 'Article has been updated.');
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		$post = Posts::where('slug', $id)->firstOrFail();

		if ($post->active === 1) {
			return to_route('article.index')->with('warning', 'This article already published. Set to unpublished first to delete article.');
		}

		$imagePath = public_path('uploads/' . $post->image);

		if (file_exists($imagePath)) {
			unlink($imagePath);
		}

		$post->delete();

		return to_route('article.index')->with('danger', 'Article has been deleted.');
	}
}
