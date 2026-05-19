<?php

namespace Tests\Feature\Models;

use App\Models\Bookmark;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookmarkTest extends TestCase
{
  use RefreshDatabase;

  public function test_bookmark_belongs_to_a_user(): void
  {
    $user = User::factory()->create();
    $bookmark = Bookmark::factory()->create(['user_id' => $user->id]);

    $this->assertInstanceOf(User::class, $bookmark->user);
    $this->assertEquals($user->id, $bookmark->user->id);
  }

  public function test_bookmark_belongs_to_a_post(): void
  {
    $post = Post::factory()->create();
    $bookmark = Bookmark::factory()->create(['post_id' => $post->id]);

    $this->assertInstanceOf(Post::class, $bookmark->post);
    $this->assertEquals($post->id, $bookmark->post->id);
  }
}
