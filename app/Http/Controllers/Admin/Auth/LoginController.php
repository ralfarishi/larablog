<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
	public function index()
	{
		return view('auth.login');
	}

	public function register(Request $request)
	{
		$request->validate(
			[
				'name' => 'required|string|unique:users,name',
				'email' => 'required|email|unique:users,email',
				'password' => 'required|min:5'
			],
			[
				'name.required' => 'Harap mengisi nama!',
				'name.unique' => 'Nama sudah digunakan. Coba nama lain.',
				'email.required' => 'Harap mengisi email!',
				'email.unique' => 'Email sudah digunakan. Coba email lain.',
				'password.required' => 'Harap mengisi password!',
				'password.min' => 'Password minimal 5 karakter!',
			]
		);

		$data = $request->all();

		$data['slug'] = Str::slug($request->name);
		$data['password'] = Hash::make($request->password);

		User::create($data);

		return to_route('login')->with('success', 'Berhasil membuat akun');
	}

	public function login(Request $request)
	{
		$credentials = $request->validate([
			'email' => 'required|email',
			'password' => 'required',
		]);

		if (Auth::attempt($credentials)) {
			$request->session()->regenerate();

			return to_route('dashboard');
		}

		return to_route('login')->with('danger', 'Credential not match in out database!');
	}

	public function logout(Request $request)
	{
		Auth::logout();
		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return to_route('home');
	}
}
