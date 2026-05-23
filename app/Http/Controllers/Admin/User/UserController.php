<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserController extends Controller
{
  public function index(Request $request): View
  {
    return view('admin.users.list');
  }

  public function create(): View
  {
    return view('admin.users.create');
  }

  public function store(UserRequest $request): RedirectResponse
  {
    $data = $request->validated();

    $data['slug'] = Str::slug($data['name']);
    $data['password'] = Hash::make($data['password']);

    $avatar = $request->file('display_picture');
    unset($data['display_picture']);

    $user = User::create($data);

    if ($avatar) {
      $user->addMedia($avatar)->toMediaCollection('avatar');
    }

    return to_route('user.index')->with('success', 'User has been created.');
  }

  public function edit(string $slug): View
  {
    $user = User::where('slug', $slug)->firstOrFail();

    return view('admin.users.edit', compact('user'));
  }

  public function update(UpdateUserRequest $request, string $slug): RedirectResponse
  {
    $user = User::where('slug', $slug)->firstOrFail();
    $data = $request->validated();

    $data['slug'] = Str::slug($data['name']);

    if ($request->filled('password')) {
      $data['password'] = Hash::make($data['password']);
    } else {
      unset($data['password']);
    }

    if ($request->hasFile('display_picture')) {
      $user->addMedia($request->file('display_picture'))->toMediaCollection('avatar');
      $data['display_picture'] = null;
    } elseif ($request->boolean('default-image')) {
      $user->clearMediaCollection('avatar');
      $randomBg = sprintf('%06X', random_int(0, 0xffffff));
      $nameForAvatar = urlencode($data['name'] ?? $user->name);
      $data[
        'display_picture'
      ] = "https://ui-avatars.com/api/?name={$nameForAvatar}&size=100&color=fff&background={$randomBg}";
    } else {
      unset($data['display_picture']);
    }

    unset($data['default-image']);
    $user->update($data);

    return back()->with('info', 'Profile has been updated.');
  }

  public function destroy(Request $request, string $slug): JsonResponse|RedirectResponse
  {
    $user = User::where('slug', $slug)->firstOrFail();

    if ($user->id === auth()->id()) {
      if ($request->expectsJson()) {
        return response()->json(
          [
            'success' => false,
            'message' => 'You cannot delete your own account.',
          ],
          422,
        );
      }

      return to_route('user.index')->with('warning', 'You cannot delete your own account.');
    }

    $user->delete();

    if ($request->expectsJson()) {
      return response()->json([
        'success' => true,
        'message' => 'User has been deleted.',
      ]);
    }

    return to_route('user.index')->with('danger', 'User has been deleted.');
  }
}
