<?php

declare(strict_types=1);

namespace App\Livewire\Layout;

use App\Events\NotificationRead;
use Livewire\Attributes\On;
use Livewire\Component;

class NotificationDropdown extends Component
{
  public int $userId;

  public function mount()
  {
    $this->userId = auth()->id();
  }

  public function getNotificationsProperty()
  {
    return auth()->user()->notifications()->latest()->take(20)->get();
  }

  public function getUnreadCountProperty()
  {
    return auth()->user()->unreadNotifications()->count();
  }

  #[
    On(
      'echo-private:App.Models.User.{userId},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated',
    ),
  ]
  public function onNotificationBroadcast($event)
  {
    // Re-render
  }

  #[On('echo-private:App.Models.User.{userId},NotificationRead')]
  public function onNotificationRead($event)
  {
    // Re-render
  }

  public function markAsRead(string $id)
  {
    $notification = auth()->user()->notifications()->findOrFail($id);
    $notification->markAsRead();

    broadcast(new NotificationRead(userId: (string) auth()->id(), notificationId: $id))->toOthers();

    $data = $notification->data;
    if (!empty($data['post_slug'])) {
      return $this->redirect(route('post', $data['post_slug']));
    }
  }

  public function markAllRead()
  {
    auth()->user()->unreadNotifications->markAsRead();
    broadcast(new NotificationRead(userId: (string) auth()->id(), all: true))->toOthers();
  }

  public function deleteNotif(string $id)
  {
    auth()->user()->notifications()->findOrFail($id)->delete();
  }

  public function clearAll()
  {
    auth()->user()->notifications()->delete();
  }

  public function render()
  {
    return view('livewire.layout.notification-dropdown');
  }
}
