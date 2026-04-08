<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MahasiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nim' => fake()->word(),
            'nama' => fake()->word(),
            'nohp' => fake()->word(),
            'semester' => fake()->word(),
            'prodi' => fake()->word(),
            'user_id' => User::factory(),
        ];
    }
}
