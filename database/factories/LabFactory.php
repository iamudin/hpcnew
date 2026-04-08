<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LabFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nama_labor' => fake()->regexify('[A-Za-z0-9]{255}'),
            'deskripsi' => fake()->text(),
            'laboran_id' => fake()->word(),
            'user_id' => User::factory(),
        ];
    }
}
