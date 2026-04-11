<?php

namespace App\Filament\Resources\Peminjamen\Pages;

use App\Filament\Resources\Peminjamen\PeminjamanResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPeminjaman extends EditRecord
{
    protected static string $resource = PeminjamanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // DeleteAction::make(),
            Action::make('Kembalikan')
                ->label('Kembalikan')
                ->color('danger')
                ->icon('heroicon-o-x-mark')
                ->url(fn() => $this->getResource()::getUrl('index'))
                ->keyBindings(['mod+d']),
        ];
    }
     protected  function getFormActions(): array
{
        return [
            Action::make('edit')
                ->label('Ajukan Peminjccaman')
                ->color('success')
                ->hidden() // hanya aktif jika status masih pending
                ->icon('heroicon-o-paper-airplane')
                ->submit('create')
                ->keyBindings(['mod+s']),
        ];
        }
}
