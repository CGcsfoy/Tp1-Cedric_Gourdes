<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Critic;
use App\Models\User;
use App\Models\Film;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CriticFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'score' => $this->faker->randomFloat(1, 1, 10),
            'comment' => $this->faker->paragraph(),
            'user_id' => User::query()->inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'film_id' => Film::query()->inRandomOrder()->first()->id ?? Film::factory()->create()->id,
        ];
    }
}
