<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query) => $query->withoutGlobalScopes()
            )
            ->columns([
                TextColumn::make('name')->label('Nama')
                    ->searchable(),
                TextColumn::make('role')->default(function ($record) {
                   return str($record->role)->upper();
                          })->badge()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                 ToggleColumn::make('is_active')
    ->label('Status Akun')
    ->onColor('success')
    ->offColor('danger')
    ->onIcon('heroicon-o-check')
    ->offIcon('heroicon-o-x-mark')
    ->tooltip(fn ($record) => $record->is_active ? 'Aktif' : 'Nonaktif'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->visible(fn($record) => $record->role == 'laboran'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
