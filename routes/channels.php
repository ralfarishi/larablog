<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
  return (int) $user->id === (int) $id;
});

// Private channel for custom real-time notifications (NotificationCreated event)
Broadcast::channel('notifications.{userId}', function ($user, $userId) {
  return (int) $user->id === (int) $userId;
});
