<?php

namespace App\Filament\Resources\JadwalKosongs\Pages;

use App\Filament\Resources\JadwalKosongs\JadwalKosongResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJadwalKosong extends EditRecord
{
    protected static string $resource = JadwalKosongResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
