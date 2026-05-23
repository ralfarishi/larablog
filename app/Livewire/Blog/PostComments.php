<?php

declare(strict_types=1);

namespace App\Livewire\Blog;

use App\Events\CommentPosted;
use App\Models\Comment;
use App\Models\Post;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PostComments extends Component
{
  use WithPagination;

  public Post $post;

  public string $content = '';

  public function mount(Post $post): void
  {
    $this->post = $post;
  }

  #[On('echo:post.{post.slug},CommentPosted')]
  public function onCommentPosted($event): void
  {
    // Livewire will automatically refresh the component if we don't do anything,
    // but we can also manually push the comment if we want to be more efficient.
    // For simplicity, we let it re-render.
  }

  public function postComment(): void
  {
    if (!$this->post->allowed_comment) {
      return;
    }

    $this->validate([
      'content' => 'required|min:10',
    ]);

    $comment = Comment::create([
      'user_id' => auth()->id(),
      'post_id' => $this->post->id,
      'content' => $this->content,
      'is_active' => true,
    ]);

    $this->content = '';

    // Broadcast event for Reverb
    event(new CommentPosted($comment));

    session()->flash('message', 'Comment posted!');
  }

  public function render()
  {
    // Paginated with user.media eager loaded to avoid N+1 avatar queries.
    // The unbounded ->get() caused memory explosion on high-traffic posts.
    $comments = $this->post
      ->comments()
      ->where('active', true)
      ->with(['user', 'user.media'])
      ->latest()
      ->paginate(20);

    return view('livewire.blog.post-comments', [
      'comments' => $comments,
    ]);
  }
}
