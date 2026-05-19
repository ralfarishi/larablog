<?php

namespace Database\Factories;

use App\Models\LoginHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LoginHistory>
 */
class LoginHistoryFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'email' => fake()->safeEmail(),
      'activity' => fake()->randomElement(['login', 'logout']),
      'status' => fake()->boolean(),
      'ip_address' => fake()->ipv4(),
      'user_agent' => fake()->userAgent(),
      'city' => fake()->city(),
      'latitude' => fake()->latitude(),
      'longitude' => fake()->longitude(),
      'logout_at' => fake()->optional()->dateTime(),
    ];
  }
}
