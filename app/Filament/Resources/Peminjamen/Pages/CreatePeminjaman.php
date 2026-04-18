<?php

namespace App\Filament\Resources\Peminjamen\Pages;

use App\Filament\Resources\Peminjamen\PeminjamanResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePeminjaman extends CreateRecord
{
    protected static string $resource = PeminjamanResource::class;
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
