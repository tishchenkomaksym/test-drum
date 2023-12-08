<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'status' => $this->faker->randomElement(['todo', 'done']),
            'priority' => $this->faker->numberBetween(1, 5),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->text(),
            'completed_at' => $this->faker->boolean(20) ? $this->faker->dateTimeBetween('-1 months', 'now') : null,
        ];
    }
}
