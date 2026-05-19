<?php

namespace Tests\Feature\Models;

use App\Models\Bookmark;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
  use RefreshDatabase;

  public function test_post_belongs_to_a_user(): void
  {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    $this->assertInstanceOf(User::class, $post->user);
    $this->assertEquals($user->id, $post->user->id);
  }

  public function test_post_belongs_to_a_category(): void
  {
    $category = Category::factory()->create();
    $post = Post::factory()->create(['category_id' => $category->id]);

    $this->assertInstanceOf(Category::class, $post->category);
    $this->assertEquals($category->id, $post->category->id);
  }

  public function test_post_has_many_comments(): void
  {
    $post = Post::factory()->create();
    Comment::factory()
      ->count(3)
      ->create(['post_id' => $post->id]);

    $this->assertCount(3, $post->comments);
    $this->assertInstanceOf(Comment::class, $post->comments->first());
  }

  public function test_post_has_many_bookmarks(): void
  {
    $post = Post::factory()->create();
    Bookmark::factory()
      ->count(2)
      ->create(['post_id' => $post->id]);

    $this->assertCount(2, $post->bookmarks);
    $this->assertInstanceOf(Bookmark::class, $post->bookmarks->first());
  }

  public function test_post_belongs_to_many_tags(): void
  {
    $post = Post::factory()->create();
    $tags = Tag::factory()->count(2)->create();
    $post->tags()->attach($tags->pluck('id'));

    $this->assertCount(2, $post->tags()->get());
    $this->assertInstanceOf(Tag::class, $post->tags()->get()->first());
  }

  public function test_post_casts_allowed_comment_to_boolean(): void
  {
    $post = Post::factory()->create(['allowed_comment' => 1]);

    $this->assertTrue($post->allowed_comment);
  }

  public function test_post_is_only_searchable_when_published(): void
  {
    $publishedPost = Post::factory()->create(['status' => 'published']);
    $draftPost = Post::factory()->create(['status' => 'draft']);

    $this->assertTrue($publishedPost->shouldBeSearchable());
    $this->assertFalse($draftPost->shouldBeSearchable());
  }

  public function test_post_returns_correct_searchable_array_structure(): void
  {
    $post = Post::factory()->create([
      'title' => 'Test Title',
      'content' => '<p>Test Content</p>',
    ]);

    $searchableArray = $post->toSearchableArray();

    $this->assertArrayHasKey('id', $searchableArray);
    $this->assertArrayHasKey('title', $searchableArray);
    $this->assertArrayHasKey('content', $searchableArray);
    $this->assertEquals('Test Title', $searchableArray['title']);
    $this->assertEquals('Test Content', $searchableArray['content']);
  }
}
