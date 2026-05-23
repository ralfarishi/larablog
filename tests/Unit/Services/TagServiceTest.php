<?php

namespace Tests\Unit\Services;

use App\Models\Post;
use App\Services\TagService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagServiceTest extends TestCase
{
  use RefreshDatabase;

  private TagService $service;

  protected function setUp(): void
  {
    parent::setUp();
    $this->service = new TagService();
  }

  public function test_it_syncs_comma_separated_tags_to_post(): void
  {
    $post = Post::factory()->create();

    $this->service->sync($post, 'Laravel, PHP, Web Testing');

    $tags = $post->tags()->get();
    $this->assertCount(3, $tags);

    $this->assertTrue($tags->contains('name', 'Laravel'));
    $this->assertTrue($tags->contains('slug', 'laravel'));
    $this->assertTrue($tags->contains('name', 'PHP'));
    $this->assertTrue($tags->contains('name', 'Web Testing'));
    $this->assertTrue($tags->contains('slug', 'web-testing'));
  }

  public function test_it_handles_empty_or_whitespace_only_tags(): void
  {
    $post = Post::factory()->create();

    $this->service->sync($post, 'Laravel, , PHP,  ');

    $tags = $post->tags()->get();
    $this->assertCount(2, $tags);
    $this->assertTrue($tags->contains('name', 'Laravel'));
    $this->assertTrue($tags->contains('name', 'PHP'));
  }
}
