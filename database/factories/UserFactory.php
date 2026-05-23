<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
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
      'password' => Hash::make('password'),
      'role' => fake()->randomElement(['admin', 'writer', 'reader']),
      'display_picture' => null,
      'remember_token' => Str::random(10),
    ];
  }
}
