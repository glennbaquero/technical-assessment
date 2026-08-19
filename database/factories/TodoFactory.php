<?php

namespace Database\Factories;

use App\Enums\TodoStatus;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->unique()->sentence(4),
            'description' => fake()->optional(0.7)->paragraph(),
            'status' => TodoStatus::Pending,
            'due_date' => fake()->optional(0.5)->dateTimeBetween('now', '+3 weeks'),
            'completed_at' => null,
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Indicate that the todo is completed.
     */
    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            $completedAt = fake()->dateTimeBetween($attributes['created_at'] ?? '-1 month', 'now');

            return [
                'status' => TodoStatus::Completed,
                'completed_at' => $completedAt,
            ];
        });
    }
}
