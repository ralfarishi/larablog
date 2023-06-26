<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class NewAuthController extends Controller
{
	public function index()
	{
		return view('auth.login');
	}

	public function login(Request $request)
	{
		$credentials = $request->validate([
			'email' => ['required', 'email'],
			'password' => ['required'],
		]);

		if (Auth::attempt($credentials)) {
			$request->session()->regenerate();

		return redirect()->intended('/admin/dashboard');
		}

		Session::flash('status', 'failed');
		Session::flash('message', 'Credential not match in our database!');

		return redirect('/login');

		// return back()->withErrors([
		//   'email' => 'The provided credentials do not match our records.',
		// ])->onlyInput('email');
	}

	public function logout(Request $request)
	{
		Auth::logout();
		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return redirect('/');
	}
}