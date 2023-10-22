<?php

namespace App\Http\Controllers\Admin\Comment;

use App\Models\Comments;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			if (Auth::user()->role == 'admin') {
				$model = Comments::with('post')->latest();
			} else {
				$model = Comments::whereHas('post', function ($query) {
					$query->where('user_id', Auth::user()->id);
				})->with('post')->latest();
			}

			return DataTables::of($model)
				->addColumn('actions', function ($model) use ($request) {
					$id = $model->id;
					$link = $request->url() . '/' . $id;
					return '
						<div class="d-flex align-items-center">
							<a href="' . route('komentar.edit', $id) . '" class="btn btn-primary btn-sm"><span class="fas fa-edit"></span></a>
							<a href="" data-delete-url="' . $link . '" class="btn btn-danger btn-sm mx-2 delete-data" data-toggle="modal" data-target="#deleteModal"><span class="fas fa-trash"></span></a>
						</div>
					';
				})
				->addColumn('post', function ($model) use ($request) {
					//Show Post title on which user has Commented
					return $model->post->title;
				})
				->filterColumn('post', function ($query, $keyword) {
					// Implement custom filtering for the article column
					$query->whereHas('post', function ($query) use ($keyword) {
						$query->where('title', 'like', '%' . $keyword . '%');
					});
				})
				->addColumn('status', function ($model) use ($request) {
					return $model->active ? '<span class="badge bg-success">Shown</span>' : '<span class="badge bg-danger">Not Shown</span>';
				})
				->rawColumns(['actions', 'status', 'post'])
				->make(true);
		}

		return view('admin.comments.list');
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		//
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
		$comment = Comments::findOrFail($id);

		return view('admin.comments.edit', compact('comment'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $id)
	{
		$data = $request->all();

		$comment = Comments::findOrFail($id);
		$comment->fill($data);

		$comment->save();

		return to_route('komentar.index')->with('info', 'Komentar berhasil diperbarui!');
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		$comment = Comments::findOrFail($id);

		$comment->delete();

		return to_route('komentar.index')->with('danger', 'Komentar berhasil dihapus!');
	}
}
