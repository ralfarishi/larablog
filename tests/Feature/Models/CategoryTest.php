<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
  use RefreshDatabase;

  public function test_category_has_many_posts(): void
  {
    $category = Category::factory()->create();
    Post::factory()
      ->count(3)
      ->create(['category_id' => $category->id]);

    $this->assertCount(3, $category->posts);
    $this->assertInstanceOf(Post::class, $category->posts->first());
  }
}
