<?php
namespace App\Rules;

use App\Models\JadwalKosong;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AvailableSlotRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $request = request();
        $labId   = $request->lab_id;
        $start   = Carbon::parse($request->tanggal_mulai);
        $end     = Carbon::parse($request->tanggal_selesai);
        $date    = $start->toDateString();
        $hari    = strtolower($start->dayName);

        // Cek ada jadwal kosong (specific atau recurring)
        $adaJadwal = JadwalKosong::where('lab_id', $labId)
            ->where('aktif', true)
            ->where(function ($q) use ($date, $hari) {
                $q->where('tanggal', $date)
                  ->orWhere(fn($q2) => $q2->whereNull('tanggal')->where('hari', $hari));
            })
            ->where('jam_mulai', '<=', $start->format('H:i:s'))
            ->where('jam_selesai', '>=', $end->format('H:i:s'))
            ->exists();

        if (!$adaJadwal) {
            $fail('Tidak ada jadwal kosong untuk waktu yang dipilih.');
            return;
        }

        // Cek overlap dengan peminjaman yang sudah APPROVED
        $overlap = Peminjaman::where('lab_id', $labId)
            ->whereIn('status', ['approved', 'confirmed_laboran'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('tanggal_mulai', [$start, $end])
                  ->orWhereBetween('tanggal_selesai', [$start, $end])
                  ->orWhere(function ($q) use ($start, $end) {
                      $q->where('tanggal_mulai', '<=', $start)
                        ->where('tanggal_selesai', '>=', $end);
                  });
            })
            ->exists();

        if ($overlap) {
            $fail('Waktu tersebut sudah dipinjam oleh orang lain.');
        }
    }
}