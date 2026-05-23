<?php

declare(strict_types=1);

namespace App\Livewire\Blog;

use App\Models\Post;
use Livewire\Component;

class BookmarkToggle extends Component
{
  public Post $post;

  public bool $isBookmarked = false;

  public function mount(Post $post)
  {
    $this->post = $post;
    $this->isBookmarked =
      auth()->check() && auth()->user()->bookmarks()->where('post_id', $this->post->id)->exists();
  }

  public function toggle()
  {
    if (!auth()->check()) {
      return $this->redirect(route('login'));
    }

    $user = auth()->user();

    if ($this->isBookmarked) {
      $user->bookmarks()->where('post_id', $this->post->id)->delete();
      $this->isBookmarked = false;
    } else {
      $user->bookmarks()->create(['post_id' => $this->post->id]);
      $this->isBookmarked = true;
    }

    $this->dispatch(
      'toast',
      message: $this->isBookmarked ? 'Added to bookmarks' : 'Removed from bookmarks',
    );
  }

  public function render()
  {
    return view('livewire.blog.bookmark-toggle');
  }
}
