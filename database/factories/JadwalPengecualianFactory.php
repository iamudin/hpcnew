<?php

namespace Database\Factories;

use App\Models\Lab;
use Illuminate\Database\Eloquent\Factories\Factory;

class JadwalPengecualianFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'jadwal_kosong_id' => fake()->word(),
            'lab_id' => Lab::factory(),
            'tanggal' => fake()->date(),
            'jam_mulai' => fake()->time(),
            'jam_selesai' => fake()->time(),
            'tipe' => fake()->randomElement(["tambah","hapus"]),
            'alasan' => fake()->word(),
        ];
    }
}
