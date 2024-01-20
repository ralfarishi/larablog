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
					$slug = $data->slug;
					$link = $request->url() . '/' . $slug;
					return '
						<a href="' . route('user.edit', $slug) . ' " class="btn btn-primary btn-sm" title="Edit"><span class="bi bi-pencil-square"></span></a>
						<a href="" data-delete-url="' . $link . '" class="btn btn-danger btn-sm delete-data" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Delete"><span class="bi bi-trash-fill"></span></a>
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

		return to_route('user.index')->with('success', 'New user has been created.');
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
		$user = User::where('slug', $id)->firstOrFail();

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
				'password' => 'nullable|sometimes|min:8'
			]
		);

		$data = $request->all();

		$user = User::findOrFail($id);

		$data['slug'] = Str::slug($request->name);

		if ($request->password) {
			$data['password'] = Hash::make($request->password);
		} else {
			unset($data['password']);
		}

		$user->update($data);

		return to_route('user.index')->with('info', 'User data has been updated.');
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		$data = User::where('slug', $id)->firstOrFail();

		if ($data->role == 'admin') {
			return to_route('user.index')->with('warning', 'Cannot delete an ADMIN.');
		}

		$data->delete();

		return to_route('user.index')->with('danger', 'User has been deleted.');
	}
}
