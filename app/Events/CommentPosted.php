<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class CommentPosted implements ShouldBroadcast
{
  use InteractsWithSockets, SerializesModels;

  public function __construct(public readonly Comment $comment) {}

  public function broadcastOn(): array
  {
    return [new Channel('post.' . $this->comment->post->slug)];
  }

  /** Explicit name so Echo listens with '.CommentPosted' (dot-prefix, no namespace) */
  public function broadcastAs(): string
  {
    return 'CommentPosted';
  }

  public function broadcastWith(): array
  {
    $user = $this->comment->user;
    $avatar = filter_var($user->display_picture ?? '', FILTER_VALIDATE_URL)
      ? $user->display_picture
      : $user->profile_picture_url;

    return [
      'id' => $this->comment->id,
      'content' => $this->comment->content,
      'created_at' => $this->comment->created_at->format('M d, Y'),
      'user_id' => $user->id,
      'user_name' => $user->name,
      'user_avatar' => $avatar,
      'user_role' => $user->role,
    ];
  }
}
