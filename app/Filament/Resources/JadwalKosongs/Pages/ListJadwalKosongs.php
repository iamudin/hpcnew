<?php

namespace App\Filament\Resources\JadwalKosongs\Pages;

use App\Filament\Resources\JadwalKosongs\JadwalKosongResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJadwalKosongs extends ListRecords
{
    protected static string $resource = JadwalKosongResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
