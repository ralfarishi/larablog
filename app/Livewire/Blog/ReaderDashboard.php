<?php

declare(strict_types=1);

namespace App\Livewire\Blog;

use App\Models\Bookmark;
use App\Models\Comment;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ReaderDashboard extends Component
{
  use WithFileUploads;
  use WithPagination;

  public string $activeTab = 'bookmarks';

  // Profile Settings Form Properties
  public string $name = '';
  public string $email = '';
  public string $password = '';
  public string $password_confirmation = '';
  public $avatar;

  public function mount(): void
  {
    $user = auth()->user();
    if ($user) {
      $this->name = $user->name;
      $this->email = $user->email;
    } else {
      $this->redirect(route('login'));
    }
  }

  public function setTab(string $tab): void
  {
    if (in_array($tab, ['bookmarks', 'comments', 'profile'])) {
      $this->activeTab = $tab;
      $this->resetPage('bookmarks-page');
      $this->resetPage('comments-page');
    }
  }

  public function updateProfile(): void
  {
    $user = auth()->user();
    if (!$user) {
      return;
    }

    $this->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
      'password' => ['nullable', 'string', 'min:8', 'confirmed'],
      'avatar' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:1024'],
    ]);

    $user->name = $this->name;
    $user->email = $this->email;

    if ($this->password) {
      $user->password = Hash::make($this->password);
    }

    $user->save();

    if ($this->avatar) {
      $user->clearMediaCollection('avatar');
      $user->addMedia($this->avatar->getRealPath())->toMediaCollection('avatar');
    }

    $this->reset(['password', 'password_confirmation', 'avatar']);
    $this->dispatch('toast', message: 'Profile updated successfully.');
  }

  public function removeBookmark(int $bookmarkId): void
  {
    $user = auth()->user();
    if ($user) {
      $user->bookmarks()->where('id', $bookmarkId)->delete();
      $this->dispatch('toast', message: 'Bookmark removed.');
    }
  }

  public function deleteComment(int $commentId): void
  {
    $user = auth()->user();
    if ($user) {
      $comment = $user->comments()->find($commentId);
      if ($comment) {
        $comment->delete();
        $this->dispatch('toast', message: 'Comment deleted.');
      } else {
        $this->dispatch('toast', message: 'Unauthorized action.');
      }
    }
  }

  public function render()
  {
    $user = auth()->user();
    if (!$user) {
      return <<<'HTML'
        <div>Redirecting...</div>
      HTML;
    }

    $bookmarks = $this->activeTab === 'bookmarks'
      ? $user->bookmarks()
          ->with(['post.category', 'post.user'])
          ->latest()
          ->paginate(6, ['*'], 'bookmarks-page')
      : collect();

    $comments = $this->activeTab === 'comments'
      ? $user->comments()
          ->with('post')
          ->latest()
          ->paginate(10, ['*'], 'comments-page')
      : collect();

    return view('livewire.blog.reader-dashboard', [
      'bookmarks' => $bookmarks,
      'comments' => $comments,
      'user' => $user,
    ])
      ->extends('layouts.templates')
      ->section('content-id');
  }
}
