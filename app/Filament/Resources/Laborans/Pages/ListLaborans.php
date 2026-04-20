<?php

namespace App\Filament\Resources\Laborans\Pages;

use App\Filament\Resources\Laborans\LaboranResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLaborans extends ListRecords
{
    protected static string $resource = LaboranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
