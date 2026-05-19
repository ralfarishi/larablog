<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'post_id' => \App\Models\Post::factory(),
      'user_id' => \App\Models\User::factory(),
      'content' => fake()->paragraph(),
      'active' => fake()->boolean(80),
    ];
  }
}
