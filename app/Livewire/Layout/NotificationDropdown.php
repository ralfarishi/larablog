<?php

declare(strict_types=1);

namespace App\Livewire\Layout;

use App\Events\NotificationRead;
use Livewire\Attributes\On;
use Livewire\Component;

class NotificationDropdown extends Component
{
  public int $userId;

  public function mount(): void
  {
    $this->userId = auth()->id();
  }

  public function getNotificationsProperty()
  {
    return auth()->user()->notifications()->latest()->take(20)->get();
  }

  /**
   * Derives count from the already-fetched collection — no extra DB query.
   */
  public function getUnreadCountProperty(): int
  {
    return $this->notifications->whereNull('read_at')->count();
  }

  #[
    On(
      'echo-private:App.Models.User.{userId},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated',
    ),
  ]
  public function onNotificationBroadcast($event): void
  {
    // Re-render
  }

  #[On('echo-private:App.Models.User.{userId},NotificationRead')]
  public function onNotificationRead($event): void
  {
    // Re-render
  }

  public function markAsRead(string $id): mixed
  {
    $notification = auth()->user()->notifications()->findOrFail($id);
    $notification->markAsRead();

    broadcast(new NotificationRead(userId: (string) auth()->id(), notificationId: $id))->toOthers();

    $data = $notification->data;
    if (!empty($data['post_slug'])) {
      return $this->redirect(route('post', $data['post_slug']));
    }

    return null;
  }

  public function markAllRead(): void
  {
    // Single bulk UPDATE instead of N individual markAsRead() calls
    auth()->user()->unreadNotifications()->update(['read_at' => now()]);
    broadcast(new NotificationRead(userId: (string) auth()->id(), all: true))->toOthers();
  }

  public function deleteNotif(string $id): void
  {
    auth()->user()->notifications()->findOrFail($id)->delete();
  }

  public function clearAll(): void
  {
    auth()->user()->notifications()->delete();
  }

  public function render()
  {
    return view('livewire.layout.notification-dropdown');
  }
}
