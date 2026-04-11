<?php

namespace App\Filament\Resources\Peminjamen\Pages;

use App\Filament\Resources\Peminjamen\PeminjamanResource;
use Filament\Actions\Action;
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
   protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['mahasiswa_id'] = '1';   // paksa isi dari user login

    return $data;
}
}
