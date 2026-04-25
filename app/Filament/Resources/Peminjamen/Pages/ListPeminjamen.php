<?php

namespace App\Filament\Resources\Peminjamen\Pages;

use App\Filament\Resources\Peminjamen\PeminjamanResource;
use App\Filament\Resources\Peminjamen\Widgets\PeminjamanChart;
use App\Filament\Resources\Peminjamen\Widgets\PeminjamanOverview;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListPeminjamen extends ListRecords
{
      use ExposesTableToWidgets;
    protected static string $resource = PeminjamanResource::class;
  
    protected function getHeaderActions(): array {
        return [
    CreateAction::make()->hidden(fn() => auth()->user()->isKalab()),
        ];
    }
  protected function getHeaderWidgets(): array
    {
        return [
            PeminjamanOverview::class,
        ];
    }

}
