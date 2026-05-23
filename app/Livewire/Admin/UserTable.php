<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
  use WithPagination;

  public string $search = '';

  public function updatingSearch()
  {
    $this->resetPage();
  }

  #[On('deleteConfirmed')]
  public function delete(string $slug)
  {
    $user = User::where('slug', $slug)->firstOrFail();

    if ($user->id === auth()->id()) {
      session()->flash('warning', 'You cannot delete your own account.');

      return;
    }

    $user->delete();
    session()->flash('danger', 'User has been deleted.');
  }

  public function render()
  {
    $users = User::query()
      ->with('media')
      ->withCount('posts')
      ->when($this->search, function ($query) {
        $query
          ->where('name', 'like', '%' . $this->search . '%')
          ->orWhere('email', 'like', '%' . $this->search . '%');
      })
      ->latest()
      ->paginate(10);

    return view('livewire.admin.user-table', [
      'users' => $users,
    ]);
  }
}
