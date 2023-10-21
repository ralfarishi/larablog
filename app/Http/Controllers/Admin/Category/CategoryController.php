<?php

namespace App\Http\Controllers\Admin\Category;

use App\Models\Categories;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;

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

	public function store(CategoryRequest $request)
	{
		$data = $request->validated();

		Categories::create($data);

		return to_route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
	}

	public function destroy($id)
	{
		$category = Categories::findOrfail($id);

		if ($category->posts->count() > 0) {
			return to_route('kategori.index')->with('warning', 'Tidak dapat menghapus kategori karena masih ada artikel yang berhubungan.');
		}

		$category->delete();

		return to_route('kategori.index')->with('danger', 'Kategori berhasil dihapus!');
	}
}
