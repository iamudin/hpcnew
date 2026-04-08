<?php

namespace Database\Factories;

use App\Models\Lab;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class KalabFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nip' => fake()->word(),
            'nama' => fake()->word(),
            'nohp' => fake()->word(),
            'prodi' => fake()->word(),
            'lab_id' => Lab::factory(),
            'user_id' => User::factory(),
        ];
    }
}
