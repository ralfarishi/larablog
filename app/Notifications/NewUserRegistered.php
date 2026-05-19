<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewUserRegistered extends Notification
{
  use Queueable;

  /**
   * Create a new notification instance.
   */
  public function __construct(
    public User $user,
    public string $message = 'just created an account',
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
      'commenter_id' => $this->user->id,
      'commenter_name' => $this->user->name,
      'message' => $this->message,
      'url' => route('user.index'),
    ];
  }

  /**
   * Get the broadcastable representation of the notification.
   */
  public function toBroadcast(object $notifiable): BroadcastMessage
  {
    return new BroadcastMessage([
      'commenter_id' => $this->user->id,
      'commenter_name' => $this->user->name,
      'message' => $this->message,
      'url' => route('user.index'),
    ]);
  }
}
