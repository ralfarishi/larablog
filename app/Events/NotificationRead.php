<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class NotificationRead implements ShouldBroadcast
{
  use InteractsWithSockets, SerializesModels;

  public function __construct(
    public readonly string $userId,
    public readonly ?string $notificationId = null,
    public readonly bool $all = false,
  ) {}

  public function broadcastOn(): array
  {
    return [new PrivateChannel('App.Models.User.' . $this->userId)];
  }

  /**
   * Explicit event name — Echo listens with '.NotificationRead' (dot-prefix, no namespace).
   */
  public function broadcastAs(): string
  {
    return 'NotificationRead';
  }

  public function broadcastWith(): array
  {
    return [
      'notification_id' => $this->notificationId,
      'all' => $this->all,
    ];
  }
}
