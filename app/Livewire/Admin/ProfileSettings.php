<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileSettings extends Component
{
  use WithFileUploads;

  public User $user;
  public $photo;
  public bool $use_ui_avatar = false;

  public string $name = '';
  public string $email = '';
  public string $role = '';
  public string $password = '';

  public function mount(User $user)
  {
    $this->user = $user;
    $this->name = $user->name;
    $this->email = $user->email;
    $this->role = $user->role;
    $this->use_ui_avatar = !$this->user->hasMedia('avatar');
  }

  public function updatedPhoto()
  {
    $this->validate([
      'photo' => 'image|max:1024',
    ]);
    $this->use_ui_avatar = false;
  }

  public function save()
  {
    $rules = [
      'name' => 'required|string|max:255',
      'email' => 'required|email|max:255|unique:users,email,' . $this->user->id,
      'role' => 'required|string',
      'photo' => 'nullable|image|max:1024',
      'password' => 'nullable|min:8',
    ];

    $this->validate($rules);

    // Detect if name changed before we update the database
    $nameChanged = $this->user->name !== $this->name;

    $data = [
      'name' => $this->name,
      'email' => $this->email,
    ];

    // Only update role if current user is admin and we are not editing ourselves if we want to be strict,
    // but the previous code allowed it except if editing an admin (disabled).
    if (auth()->user()->role === 'admin' && $this->user->role !== 'admin') {
      $data['role'] = $this->role;
    }

    if ($this->password) {
      $data['password'] = bcrypt($this->password);
    }

    $this->user->update($data);

    // Check if they currently have physical media before we process
    $hadMedia = $this->user->hasMedia('avatar');

    if ($this->use_ui_avatar) {
      if ($hadMedia) {
        $this->user->clearMediaCollection('avatar');
      }

      // Check if they already have a ui-avatars URL set in display_picture
      $hasUiAvatar = !empty($this->user->display_picture) && str_contains($this->user->display_picture, 'ui-avatars.com');

      // We only generate/update a default UI avatar if:
      // - They had custom media (switching back to default)
      // - The name was changed (so it needs a new avatar text)
      // - They do not have a UI avatar URL set at all
      if ($hadMedia || $nameChanged || !$hasUiAvatar) {
        $randomBg = sprintf('%06X', random_int(0, 0xffffff));
        $generatedUrl =
          'https://ui-avatars.com/api/?name=' .
          \Illuminate\Support\Str::slug($this->name, '+') .
          '&size=100&color=fff&background=' .
          $randomBg;
        $this->user->update(['display_picture' => $generatedUrl]);
      }
    } elseif ($this->photo) {
      // If a new photo is uploaded and toggle is OFF
      $this->user->addMedia($this->photo->getRealPath())->toMediaCollection('avatar');
      $this->user->update([
        'display_picture' => null, // Clear legacy column, let Spatie handle it
      ]);
    }

    $this->dispatch('toast', message: 'Profile successfully updated.', type: 'success');
  }

  public function render()
  {
    return view('livewire.admin.profile-settings');
  }
}
