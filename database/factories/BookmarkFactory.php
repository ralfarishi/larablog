<?php

namespace Database\Factories;

use App\Models\Bookmark;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bookmark>
 */
class BookmarkFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'user_id' => \App\Models\User::factory(),
      'post_id' => \App\Models\Post::factory(),
    ];
  }
}
