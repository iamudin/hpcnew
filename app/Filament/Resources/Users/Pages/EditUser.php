<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

protected function authorizeAccess(): void
{
    abort_unless($this->record->role === 'laboran', 403,"Hanya Bisa edit akun laboran");
}
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
