<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Post;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PostTable extends Component
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
    $post = Post::where('slug', $slug)->firstOrFail();

    // Authorization check could be added here or via policy

    $post->delete();

    \Illuminate\Support\Facades\Cache::forget('sidebar_data');

    $this->dispatch('toast', message: 'Article has been deleted.', type: 'error');
  }

  public function toggleStatus(string $slug)
  {
    $post = Post::where('slug', $slug)->firstOrFail();

    $post->status = $post->status === 'published' ? 'draft' : 'published';
    $post->save();

    \Illuminate\Support\Facades\Cache::forget('sidebar_data');

    $statusText = ucfirst($post->status);
    $this->dispatch('toast', message: "Article status updated to {$statusText}.", type: 'success');
  }

  public function render()
  {
    $posts = Post::query()
      ->with(['user', 'category', 'tags'])
      ->withCount(['comments', 'bookmarks'])
      ->when($this->search, function ($query) {
        $query
          ->where('title', 'like', '%' . $this->search . '%')
          ->orWhereHas('user', function ($q) {
            $q->where('name', 'like', '%' . $this->search . '%');
          });
      })
      ->latest()
      ->paginate(10);

    return view('livewire.admin.post-table', [
      'posts' => $posts,
    ]);
  }
}
