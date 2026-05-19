<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\User;
use Illuminate\Console\Command;
use Throwable;

class MigrateMediaToLibrary extends Command
{
  protected $signature = 'migrate:media-to-library';
  protected $description = 'Migrate existing blog images and user avatars to Spatie Media Library';

  public function handle(): int
  {
    // 1. Migrate Posts
    $postCount = 0;

    foreach (Post::all() as $post) {
      if (
        $post->hasMedia('cover') ||
        empty($post->image) ||
        filter_var($post->image, FILTER_VALIDATE_URL) !== false
      ) {
        continue;
      }

      $path = public_path('uploads/' . $post->image);

      if (file_exists($path)) {
        try {
          $post->addMedia($path)->preservingOriginal()->toMediaCollection('cover');
          $postCount++;
        } catch (Throwable) {
        }
      }
    }

    // 2. Migrate Users
    $userCount = 0;

    foreach (User::all() as $user) {
      if (
        $user->hasMedia('avatar') ||
        empty($user->display_picture) ||
        filter_var($user->display_picture, FILTER_VALIDATE_URL) !== false
      ) {
        continue;
      }

      $path = public_path('uploads/' . $user->display_picture);

      if (file_exists($path)) {
        try {
          $user->addMedia($path)->preservingOriginal()->toMediaCollection('avatar');
          $userCount++;
        } catch (Throwable) {
        }
      }
    }

    $this->info("Successfully migrated {$postCount} post images and {$userCount} user avatars.");

    return self::SUCCESS;
  }
}
