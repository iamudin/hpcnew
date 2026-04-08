<?php

namespace Database\Factories;

use App\Models\Lab;
use Illuminate\Database\Eloquent\Factories\Factory;

class JadwalKosongFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'lab_id' => Lab::factory(),
            'hari' => fake()->randomElement(["senin","selasa","rabu","kamis","jumat","sabtu","minggu"]),
            'jam_mulai' => fake()->time(),
            'jam_selesai' => fake()->time(),
            'keterangan' => fake()->word(),
            'aktif' => fake()->boolean(),
        ];
    }
}
