<?php

namespace Database\Factories;

use App\Models\Lab;
use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeminjamanFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'lab_id' => Lab::factory(),
            'mahasiswa_id' => Mahasiswa::factory(),
            'tanggal_mulai' => fake()->dateTime(),
            'tanggal_selesai' => fake()->dateTime(),
            'keperluan' => fake()->regexify('[A-Za-z0-9]{500}'),
            'surat_peminjaman' => fake()->regexify('[A-Za-z0-9]{255}'),
            'status' => fake()->randomElement(["pending","confirmed_laboran","pending_kepala","approved","rejected"]),
            'catatan_laboran' => fake()->text(),
            'catatan_kepala' => fake()->text(),
        ];
    }
}
