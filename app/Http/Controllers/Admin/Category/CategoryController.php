<?php

namespace App\Http\Controllers\Admin\Category;

use App\Models\Categories;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$model = Categories::with(['posts'])->latest();
			return DataTables::of($model)
				->addColumn('actions', function ($model) use ($request) {
					$id = $model->id;
					$link = $request->url() . '/' . $id;
					return '
						<div class="d-flex align-items-center">
							<a href="" data-delete-url="' . $link . '" class="btn btn-danger btn-sm mx-auto delete-data" data-toggle="modal" data-target="#deleteModal"><span class="fas fa-trash"></span></a>
						</div>
					';
				})
				->addColumn('total_posts', function ($model) use ($request) {
					return $model->posts->count();
				})
				->addColumn('icon', function ($model) use ($request) {
					return '<i class="' . $model->icon . '"></i>';
				})
				->rawColumns(['actions', 'icon'])
				->make(true);
		}

		return view('admin.categories.list');
	}

	public function create()
	{
		return view('admin.categories.create');
	}

	public function store(Request $request)
	{
		$request->validate(
			[
				'name' => 'required|regex:/^[\w]+$/',
				'icon' => [
					'required',
					'regex:/^(fa-solid|fa-regular|fa-brands)\s+[a-z0-9-]+$/i'
				]
			],
			[
				'name.required' => 'Harap masukkan nama kategori!',
				'icon.required' => 'Harap masukkan icon!',
				'name.regex' => 'Nama kategori hanya boleh terdiri dari satu kata tanpa spasi & tanpa simbol apapun!',
				'icon.regex' => 'Harap input icon berdasarkan class pada font awesome!'
			]
		);

		$data = $request->all();

		Categories::create($data);

		return to_route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
	}

	public function destroy($id)
	{
		$data = Categories::findOrfail($id);

		$data->delete();

		return to_route('kategori.index')->with('danger', 'Kategori berhasil dihapus!');
	}
}