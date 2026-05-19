<?php

namespace Tests\Feature\Models;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
  use RefreshDatabase;

  public function test_user_has_many_posts(): void
  {
    $user = User::factory()->create();
    Post::factory()
      ->count(2)
      ->create(['user_id' => $user->id]);

    $this->assertCount(2, $user->posts);
  }
}
