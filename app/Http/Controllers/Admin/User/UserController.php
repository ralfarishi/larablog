<?php

namespace App\Http\Controllers\Admin\User;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$data = User::get();

			return DataTables::of($data)
				->addColumn('total_posts', function ($data) use ($request) {
					return $data->posts->count();
				})
				->addColumn('actions', function ($data) use ($request) {
					$id = $data->id;
					$link = $request->url() . '/' . $id;
					return '
						<a href="' . route('user.edit', $id) . ' " class="btn btn-primary btn-sm" title="Edit"><span class="fas fa-edit"></span></a>
						<a href="" data-delete-url="' . $link . '" class="btn btn-danger btn-sm delete-data" data-toggle="modal" data-target="#deleteModal" title="Delete"><span class="fas fa-trash"></span></a>
					';
				})
				->rawColumns(['actions'])
				->make(true);
		}

		return view('admin.users.list');
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		return view('admin.users.create');
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(UserRequest $request)
	{
		$data = $request->validated();

		$data['slug'] = Str::slug($request->name);
		$data['password'] = Hash::make($request->password);

		User::create($data);

		return to_route('user.index')->with('success', 'Berhasil membuat pengguna baru!');
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
		$user = User::where('id', $id)->firstOrFail();

		return view('admin.users.edit', compact('user'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $id)
	{
		$request->validate(
			[
				'name' => 'required|string',
				'email' => 'required|email',
				'password' => 'sometimes|min:5'
			],
			[
				'name.required' => 'Harap mengisi nama!',
				'email.required' => 'Harap mengisi email!',
				'password.min' => 'Password minimal 5 karakter!',
			]
		);

		$data = $request->all();

		$user = User::findOrFail($id);

		$data['slug'] = Str::slug($request->name);

		if ($request->has('password')) {
			$data['password'] = Hash::make($request->password);
		}

		$user->update($data);

		return to_route('user.index')->with('info', 'Berhasil memperbarui data pengguna!');
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		$data = User::findOrFail($id);

		if ($data->role == 'admin') {
			return to_route('user.index')->with('warning', 'Tidak dapat menghapus seorang ADMIN!');
		}

		dd('lewat if nya');

		$data->delete();

		return to_route('user.index')->with('danger', 'Berhasil menghapus user!');
	}
}
