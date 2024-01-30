<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
	/**
	 * Display the registration view.
	 */
	public function create(): View
	{
		return view('auth.register');
	}

	/**
	 * Handle an incoming registration request.
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function store(UserRequest $request): RedirectResponse
	{
		$data = $request->validated();

		$data['slug'] = Str::slug($request->name);
		$data['password'] = Hash::make($request->password);

		$user = User::create($data);

		event(new Registered($user));

		// Auth::login($user);

		// return redirect(RouteServiceProvider::HOME);

		return to_route('login')->with('tsuccess', 'Account has been created!');
	}
}