<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $name = fake()->name();
    return [
      'name' => $name,
      'slug' => Str::slug($name),
      'email' => fake()->unique()->safeEmail(),
      'password' => \Illuminate\Support\Facades\Hash::make('password'),
      'role' => fake()->randomElement(['admin', 'writer', 'reader']),
      'display_picture' => null,
      'remember_token' => Str::random(10),
    ];
  }
}
