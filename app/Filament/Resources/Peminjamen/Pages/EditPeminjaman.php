<?php

namespace App\Filament\Resources\Peminjamen\Pages;

use App\Filament\Resources\Peminjamen\PeminjamanResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
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
                ->label('Perbarui Data Peminjaman')
                ->color('success')
                ->hidden( fn() => $this->record->status !== 'pending') // hanya aktif jika status masih pending
                ->icon('heroicon-o-paper-airplane')
                ->submit('create')
                ->keyBindings(['mod+s']),
        ];
        }
protected function authorizeAccess(): void
{
    parent::authorizeAccess();

    if (!in_array($this->record->status, ['pending']) && auth()->user()->isMahasiswa()) {

        Notification::make()
            ->title('Akses Ditolak')
            ->body('Data Peminjaman sudah berada ditahap proses lebih lanjut, tidak bisa diedit kembali.')
            ->danger()
            ->send();

        redirect($this->getResource()::getUrl('index'));

    }
}
}
