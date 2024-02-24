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
				->addColumn('image', function ($data) use ($request) {
					$image = $data->display_picture;
					if (filter_var($image, FILTER_VALIDATE_URL)) {
						return '
							<div class="avatar avatar-lg">
								<img src="' . $image . '" alt="Avatar" id="preview_image"/>
							</div>
						';
					} else {
						return '
							<div class="avatar avatar-lg">
								<img src="' . asset('uploads/' . $image) . '" alt="Avatar" id="preview_image"/>
							</div>
						';
					}
				})
				->addColumn('actions', function ($data) use ($request) {
					$slug = $data->slug;
					$link = $request->url() . '/' . $slug;
					return '
						<a href="' . route('user.edit', $slug) . ' " class="btn btn-primary btn-sm" title="Edit"><span class="bi bi-pencil-square"></span></a>
						<a href="" data-delete-url="' . $link . '" class="btn btn-danger btn-sm delete-data" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Delete"><span class="bi bi-trash-fill"></span></a>
					';
				})
				->rawColumns(['image', 'actions'])
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
				'display_picture' => 'nullable|sometimes|mimes:png,jpg,jpeg|max:500',
				'password' => 'nullable|sometimes|min:8',
			]
		);

		$data = $request->all();

		$user = User::findOrFail($id);

		$data['slug'] = Str::slug($request->name);

		$isDefaultImageChecked = $request->input('default-image');
		$randomHexColor = sprintf('%06X', rand(0, 0xFFFFFF));
		$defaultDisplayPicture = 'https://ui-avatars.com/api/?name=' . Str::slug($request->name, '+') . '&size=100&color=fff&background=' . $randomHexColor;

		if ($isDefaultImageChecked) {
			$imagePath = public_path('uploads/' . $user->display_picture);

			if (file_exists($imagePath)) {
				unlink($imagePath);
			}

			$data['display_picture'] = $defaultDisplayPicture;
		} else {
			if ($request->hasFile('display_picture')) {
				$data['display_picture'] = $request->file('display_picture')->store('images/users', 'public');
			}
		}

		if (filter_var($user->display_picture, FILTER_VALIDATE_URL)) {
			$urlParts = parse_url($user->display_picture);
			parse_str($urlParts['query'], $queryParams);

			$newName = str_replace('-', '+', Str::lower($data['name']));

			$queryParams['name'] = $newName;

			$updatedUrl = $urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path'] . '?' . http_build_query($queryParams);

			$data['display_picture'] = $updatedUrl;
		}

		if ($request->password) {
			$data['password'] = Hash::make($request->password);
		} else {
			unset($data['password']);
		}

		$user->update($data);

		$newUrl = url('/dashboard/user/' . $user->slug . '/edit');

		return redirect($newUrl)->with('info', 'User data has been updated.');
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

		$imagePath = public_path('uploads/' . $data->display_picture);

		if (file_exists($imagePath)) {
			unlink($imagePath);
		}

		$data->delete();

		return to_route('user.index')->with('danger', 'User has been deleted.');
	}
}
