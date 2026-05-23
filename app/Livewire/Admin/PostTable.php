<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;
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

    Cache::forget('sidebar_data');

    $this->dispatch('toast', message: 'Article has been deleted.', type: 'error');
  }

  public function toggleStatus(string $slug)
  {
    $post = Post::where('slug', $slug)->firstOrFail();

    $post->status = $post->status === 'published' ? 'draft' : 'published';
    $post->save();

    Cache::forget('sidebar_data');

    $statusText = ucfirst($post->status);
    $this->dispatch('toast', message: "Article status updated to {$statusText}.", type: 'success');
  }

  public function render()
  {
    $posts = Post::query()
      ->with(['user:id,name,email', 'category:id,name', 'tags:id,name'])
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
