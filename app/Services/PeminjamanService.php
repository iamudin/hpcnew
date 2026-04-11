<?php

namespace App\Services;

use App\Models\Peminjaman;
use App\Models\JadwalKosong;
use Carbon\Carbon;

class PeminjamanService
{
    public function approveAndSplitJadwal(Peminjaman $peminjaman): void
    {
        if ($peminjaman->status !== 'approved') {
            return;
        }

        $date = $peminjaman->tanggal_mulai->toDateString();     // 2026-01-20
        $hari = strtolower($peminjaman->tanggal_mulai->dayName); // senin
        $reqStart = $peminjaman->tanggal_mulai->format('H:i:s');
        $reqEnd   = $peminjaman->tanggal_selesai->format('H:i:s');

        // Cari semua jadwal kosong yang overlap (recurring + specific)
        $jadwals = JadwalKosong::where('lab_id', $peminjaman->lab_id)
            ->where('aktif', true)
            ->where(function ($q) use ($date, $hari) {
                $q->where('tanggal', $date)                    // specific
                  ->orWhere(function ($q2) use ($hari) {
                      $q2->whereNull('tanggal')->where('hari', $hari); // recurring
                  });
            })
            ->where('jam_mulai', '<', $reqEnd)
            ->where('jam_selesai', '>', $reqStart)
            ->get();

        foreach ($jadwals as $jadwal) {
            $origStart = $jadwal->jam_mulai;
            $origEnd   = $jadwal->jam_selesai;

            // Buat sisa SEBELUM peminjaman
            if ($reqStart > $origStart) {
                JadwalKosong::create([
                    'lab_id'      => $jadwal->lab_id,
                    'hari'        => null,                    // specific date
                    'tanggal'     => $date,
                    'jam_mulai'   => $origStart,
                    'jam_selesai' => $reqStart,
                    'keterangan'  => 'Sisa jadwal kosong (sebelum peminjaman)',
                    'aktif'       => true,
                ]);
            }

            // Buat sisa SETELAH peminjaman
            if ($reqEnd < $origEnd) {
                JadwalKosong::create([
                    'lab_id'      => $jadwal->lab_id,
                    'hari'        => null,
                    'tanggal'     => $date,
                    'jam_mulai'   => $reqEnd,
                    'jam_selesai' => $origEnd,
                    'keterangan'  => 'Sisa jadwal kosong (setelah peminjaman)',
                    'aktif'       => true,
                ]);
            }

            // Optional: Nonaktifkan jadwal original hanya untuk tanggal ini
            // (kita tetap biarkan recurring tetap aktif)
        }
    }
}