<?php

namespace App\Filament\Resources\Labs\Pages;

use App\Filament\Resources\Labs\LabResource;
use App\Models\JadwalKosong;
use Carbon\Carbon;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLab extends EditRecord
{
    protected static string $resource = LabResource::class;


    protected function afterSave(): void {
        $lab = $this->record;

        // Hanya generate jika tanggal semester diubah atau belum ada jadwal sama sekali
        if (!$this->shouldGenerateJadwal()) {
            return;
        }

        $startDate = Carbon::parse($this->data['tanggal_mulai_semester'] ?? $lab->tanggal_mulai_semester);
        $endDate = Carbon::parse($this->data['tanggal_selesai_semester'] ?? $lab->tanggal_selesai_semester);

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        $dataJadwal = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $namaHari = $hariList[$date->dayOfWeekIso - 1];
            $isWeekend = in_array($namaHari, ['Sabtu', 'Minggu']);

            $jamMulai = $isWeekend ? '08:00:00' : '17:00:00';
            $jamSelesai = $isWeekend ? '22:00:00' : '22:00:00';

            $keterangan = $isWeekend
                ? "Jadwal akhir pekan semester {$date->format('Y')}"
                : "Jadwal kosong semester {$date->format('Y')}";
            // Cek apakah jadwal untuk tanggal ini sudah ada di lab ini
            $exists = JadwalKosong::where('lab_id', $lab->id)
                ->where('tanggal', $date->format('Y-m-d'))
                ->exists();

            if (!$exists) {
                $dataJadwal[] = [
                    'lab_id' => $lab->id,
                    'tanggal' => $date->format('Y-m-d'),
                    'hari' => $namaHari,
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                    'keterangan' => $keterangan,
                    'aktif' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($dataJadwal)) {
            JadwalKosong::insert($dataJadwal);
        }
    }
    private function shouldGenerateJadwal(): bool {
        $oldStart = $this->record->getOriginal('tanggal_mulai_semester');
        $oldEnd = $this->record->getOriginal('tanggal_selesai_semester');

        $newStart = $this->data['tanggal_mulai_semester'] ?? null;
        $newEnd = $this->data['tanggal_selesai_semester'] ?? null;

        // Jika tanggal semester berubah, generate ulang
        if ($newStart && $newEnd && ($newStart !== $oldStart || $newEnd !== $oldEnd)) {
            return true;
        }

        // Jika belum ada jadwal sama sekali untuk lab ini, generate
        return !JadwalKosong::where('lab_id', $this->record->id)->exists();
    }
    protected function getHeaderActions(): array {
        return [
            DeleteAction::make(),
        ];
    }
}
