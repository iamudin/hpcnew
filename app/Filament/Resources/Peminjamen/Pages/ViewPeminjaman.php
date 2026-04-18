<?php

namespace App\Filament\Resources\Peminjamen\Pages;

use App\Filament\Resources\Peminjamen\PeminjamanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPeminjaman extends ViewRecord
{
    protected static string $resource = PeminjamanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->visible(fn($record)=>auth()->user()->isMahasiswa() && $record->status === 'pending' || auth()->user()->isLaboran()),
        ];
    }
}
