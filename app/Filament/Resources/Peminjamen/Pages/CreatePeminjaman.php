<?php

namespace App\Filament\Resources\Peminjamen\Pages;

use App\Filament\Resources\Peminjamen\PeminjamanResource;
use App\Services\WaSender;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePeminjaman extends CreateRecord
{
    protected static string $resource = PeminjamanResource::class;

protected function afterCreate():void
{
    if(auth()->user()->isMahasiswa())
{
            $data = $this->record;
            // format tanggal biar rapi
            $tanggal = \Carbon\Carbon::parse($data->tanggal_mulai)
                ->translatedFormat('d F Y');
            $diajukan = \Carbon\Carbon::parse($data->created_at)
                ->translatedFormat('d F Y H:i:s');
            // pesan WA
            $message = "📢 *Sistem Peminjaman Laboratorium *\n\n"
                . "Halo laboran {$data->lab->nama_labor},\n\n"
                . "Saat ini ada  permohonan peminjaman laboratorium :\n\n"
                . "📋 Detail Pengajuan:\n"
                . "• Tanggal Peminjaman : {$tanggal}\n"
                . "• Tanggal Pengajuan : {$diajukan}\n"
                . "• Keperluan : {$data->keperluan}\n\n"
                . "Status: *Menunggu Persetujuan*\n\n"
                . "Silahkan ditindaklanjuti ya...\n\n"
                . "Terima kasih 🙏";

            // kirim WA (tanpa nunggu response)
            $nohp = $data->lab->laboran->nohp;

            app(WaSender::class)->send($nohp, $message);

        } 
}
    protected  function getFormActions(): array
{
    return [
        Action::make('create')
            ->label('Ajukan Peminjaman')
            ->color('success')
            ->icon('heroicon-o-paper-airplane')
            ->submit('create')
            ->keyBindings(['mod+s']),

        Action::make('cancel')
            ->label('Batal')
            ->url($this->getResource()::getUrl('index'))
            ->color('gray')
    ];
}

public function mount(): void
{
    parent::mount();
        if (auth()->user()->isMahasiswa() && !auth()->user()?->mahasiswa?->profileIsComplete()) {

            if (!auth()->user()?->mahasiswa->profileIsComplete()) {

                Notification::make()
                    ->title('Profil Belum Lengkap')
                    ->body('Silakan lengkapi profil terlebih dahulu sebelum melakukan peminjaman.')
                    ->danger()
                    ->send();

                // 🔥 redirect ke halaman profile
                $this->redirect('/auth/profile');

                return;
            }
        }
}
   protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['mahasiswa_id'] = auth()->user()->isMahasiswa() ? auth()->user()->mahasiswa->id : null;   // paksa isi dari user login

    return $data;
}
}
