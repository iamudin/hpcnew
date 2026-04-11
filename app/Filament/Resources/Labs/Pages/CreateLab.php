<?php

namespace App\Filament\Resources\Labs\Pages;

use App\Filament\Resources\Labs\LabResource;
use App\Models\JadwalKosong;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateLab extends CreateRecord
{
    protected static string $resource = LabResource::class;

protected function afterCreate(): void
    {
        $lab = $this->record;

        $startDate = Carbon::parse($this->data['tanggal_mulai_semester']);
        $endDate   = Carbon::parse($this->data['tanggal_selesai_semester']);

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        $dataJadwal = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $namaHari = $hariList[$date->dayOfWeekIso - 1];
        $isWeekend = in_array($namaHari, ['Sabtu', 'Minggu']);

        $jamMulai   = $isWeekend ? '08:00:00' : '17:00:00';
        $jamSelesai = $isWeekend ? '22:00:00' : '22:00:00';
            // Cek apakah jadwal untuk tanggal ini sudah ada
            $exists = JadwalKosong::where('lab_id', $lab->id)
                ->where('tanggal', $date->format('Y-m-d'))
                ->exists();

            if (!$exists) {
                $dataJadwal[] = [
                    'lab_id'       => $lab->id,
                    'tanggal'      => $date->format('Y-m-d'),
                    'hari'         => $namaHari,
                    'jam_mulai'    => $jamMulai,     // ubah sesuai kebutuhan
                    'jam_selesai'  => $jamSelesai ,
                    'keterangan'   => $isWeekend 
                    ? "Jadwal akhir pekan semester {$date->format('Y')}" 
                    : "Jadwal kosong semester {$date->format('Y')}",
                    'aktif'        => true,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }
        }

        if (!empty($dataJadwal)) {
            JadwalKosong::insert($dataJadwal);
        }
    }
   protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['laboran_id'] = auth()->id();   // paksa isi dari user login

    return $data;
}
}
