<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Stevebauman\Location\Facades\Location;

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

		$isLoginSuccess = Auth::attempt($credentials);

		if (!$isLoginSuccess) {
			$this->storeLoginHistory($request, $isLoginSuccess, 'Gagal login');

			return to_route('login')->with('error', 'Credential not match in our database!');
		}

		$request->session()->regenerate();

		$this->storeLoginHistory($request, $isLoginSuccess, 'Melakukan login');

		return to_route('dashboard');
	}

	public function logout(Request $request)
	{
		$userEmail = Auth::user()->email;

		$this->storeLoginHistory($request, true, 'Melakukan logout', $userEmail);

		Auth::logout();
		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return to_route('home');
	}


	protected function storeLoginHistory(Request $request, $status, $activity, $userEmail = null)
	{
		$user = Auth::user();

		$email = $userEmail ? optional($user)->email : $request->email;

		$userAgent = $request->header('User-Agent');

		try {
			$response = Http::get('https://api.ipify.org?format=json');

			$data = $response->json();

			$publicIp = $data['ip'];

			$locationData = Location::get($publicIp);
		} catch (\Exception $e) {
			// Tangani kesalahan jika terjadi
			return 'Unable to retrieve IP.';
		}

		LoginHistory::insert([
			'email' => $email,
			'activity' => $activity,
			'status' => $status,
			'ip_address' => $locationData->ip,
			'user_agent' => $userAgent,
			'city' => $locationData->cityName . ', ' . $locationData->regionName ?? null,
			'latitude' => $locationData->latitude ?? null,
			'longitude' => $locationData->longitude ?? null,
			'created_at' => now(),
		]);
	}
}