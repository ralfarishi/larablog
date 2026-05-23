<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class CommentReceived extends Notification
{
  use Queueable;

  /**
   * Create a new notification instance.
   */
  public function __construct(
    public Post $post,
    public User $commenter,
    public string $message = 'commented on your article',
  ) {}

  /**
   * Get the notification's delivery channels.
   *
   * @return array<int, string>
   */
  public function via(object $notifiable): array
  {
    return ['database', 'broadcast'];
  }

  /**
   * Get the array representation of the notification.
   *
   * @return array<string, mixed>
   */
  public function toArray(object $notifiable): array
  {
    return $this->toDatabase($notifiable);
  }

  /**
   * Get the database representation of the notification.
   */
  public function toDatabase(object $notifiable): array
  {
    return [
      'post_id' => $this->post->id,
      'post_title' => $this->post->title,
      'post_slug' => $this->post->slug,
      'commenter_id' => $this->commenter->id,
      'commenter_name' => $this->commenter->name,
      'message' => $this->message,
      'url' => route('post', $this->post->slug),
    ];
  }

  /**
   * Get the broadcastable representation of the notification.
   */
  public function toBroadcast(object $notifiable): BroadcastMessage
  {
    return new BroadcastMessage([
      'post_id' => $this->post->id,
      'post_title' => $this->post->title,
      'commenter_id' => $this->commenter->id,
      'commenter_name' => $this->commenter->name,
      'message' => $this->message,
      'url' => route('post', $this->post->slug),
    ]);
  }
}
