<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'title' => fake()->sentence(),
      'slug' => fake()->unique()->slug(),
      'content' => fake()->paragraphs(3, true),
      'image' => null,
      'user_id' => User::factory(),
      'category_id' => Category::factory(),
      'allowed_comment' => fake()->boolean(),
      'status' => fake()->randomElement(['draft', 'published', 'hidden']),
    ];
  }
}
