<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Notifications\NewUserRegistered;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
  public function create(): View
  {
    return view('auth.register');
  }

  public function store(RegisterRequest $request): RedirectResponse
  {
    $validated = $request->validated();

    $randomBg = sprintf('%06X', random_int(0, 0xffffff));

    $user = User::create([
      'name' => $validated['name'],
      'email' => $validated['email'],
      'role' => 'reader', // always default; role cannot be self-assigned at registration
      'password' => $validated['password'], // auto-hashed by 'hashed' cast
      'slug' => Str::slug($validated['name']),
      'display_picture' =>
        'https://ui-avatars.com/api/?name=' .
        Str::slug($validated['name'], '+') .
        '&size=100&color=fff&background=' .
        $randomBg,
    ]);

    // Notify admin of new registration
    $admin = User::where('role', 'admin')->first();
    $admin?->notify(new NewUserRegistered($user));

    event(new Registered($user));

    return to_route('login')->with('tsuccess', 'Account created! You can now sign in.');
  }
}
