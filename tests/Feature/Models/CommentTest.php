<?php

namespace Tests\Feature\Models;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
  use RefreshDatabase;

  public function test_comment_belongs_to_a_post(): void
  {
    $post = Post::factory()->create();
    $comment = Comment::factory()->create(['post_id' => $post->id]);

    $this->assertInstanceOf(Post::class, $comment->post);
    $this->assertEquals($post->id, $comment->post->id);
  }

  public function test_comment_belongs_to_a_user(): void
  {
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['user_id' => $user->id]);

    $this->assertInstanceOf(User::class, $comment->user);
    $this->assertEquals($user->id, $comment->user->id);
  }
}
