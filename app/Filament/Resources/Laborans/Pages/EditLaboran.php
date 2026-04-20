<?php

namespace App\Filament\Resources\Laborans\Pages;

use App\Filament\Resources\Laborans\LaboranResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLaboran extends EditRecord
{
    protected static string $resource = LaboranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
