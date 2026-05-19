<?php

declare(strict_types=1);

namespace App\Livewire\Blog;

use App\Models\Comment;
use App\Models\Post;
use Livewire\Attributes\On;
use Livewire\Component;

class PostComments extends Component
{
  public Post $post;
  public string $content = '';

  public function mount(Post $post)
  {
    $this->post = $post;
  }

  #[On('echo:post.{post.slug},CommentPosted')]
  public function onCommentPosted($event)
  {
    // Livewire will automatically refresh the component if we don't do anything,
    // but we can also manually push the comment if we want to be more efficient.
    // For simplicity, we let it re-render.
  }

  public function postComment()
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
    // Assuming there is an event CommentPosted
    event(new \App\Events\CommentPosted($comment));

    session()->flash('message', 'Comment posted!');
  }

  public function render()
  {
    $comments = $this->post->comments()->where('active', true)->with('user')->get();

    return view('livewire.blog.post-comments', [
      'comments' => $comments,
    ]);
  }
}
