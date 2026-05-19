<?php

namespace Tests\Unit\Support;

use App\Models\Post;
use App\Models\User;
use App\Support\CustomMediaPathGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

class CustomMediaPathGeneratorTest extends TestCase
{
  use RefreshDatabase;

  private CustomMediaPathGenerator $generator;

  protected function setUp(): void
  {
    parent::setUp();
    $this->generator = new CustomMediaPathGenerator();
  }

  public function test_it_generates_path_for_post_media(): void
  {
    $post = Post::factory()->create();
    $media = new Media();
    $media->id = 42;
    $media->model_type = Post::class;
    $media->model_id = $post->id;

    $this->assertEquals('images/articles/42/', $this->generator->getPath($media));
    $this->assertEquals(
      'images/articles/42/conversions/',
      $this->generator->getPathForConversions($media),
    );
    $this->assertEquals(
      'images/articles/42/responsive-images/',
      $this->generator->getPathForResponsiveImages($media),
    );
  }

  public function test_it_generates_path_for_user_media(): void
  {
    $user = User::factory()->create();
    $media = new Media();
    $media->id = 100;
    $media->model_type = User::class;
    $media->model_id = $user->id;

    $this->assertEquals('images/users/100/', $this->generator->getPath($media));
    $this->assertEquals(
      'images/users/100/conversions/',
      $this->generator->getPathForConversions($media),
    );
    $this->assertEquals(
      'images/users/100/responsive-images/',
      $this->generator->getPathForResponsiveImages($media),
    );
  }

  public function test_it_generates_path_for_other_media(): void
  {
    $media = new Media();
    $media->id = 999;
    $media->model_type = 'Some\Other\Model';

    $this->assertEquals('images/others/999/', $this->generator->getPath($media));
  }
}
