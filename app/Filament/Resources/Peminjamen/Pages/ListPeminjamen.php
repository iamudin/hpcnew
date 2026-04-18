<?php

namespace App\Filament\Resources\Peminjamen\Pages;

use App\Filament\Resources\Peminjamen\PeminjamanResource;
use App\Filament\Resources\Peminjamen\Widgets\PeminjamanOverview;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\HtmlString;

class ListPeminjamen extends ListRecords
{
      use ExposesTableToWidgets;
    protected static string $resource = PeminjamanResource::class;
  
    protected function getHeaderActions(): array {
        return [
    CreateAction::make(),
        ];
    }
  protected function getHeaderWidgets(): array
    {
        return [
            PeminjamanOverview::class,
        ];
    }

}
