<?php

namespace App\Filament\Resources\Peminjamen\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PeminjamenTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lab.id')
                    ->searchable(),
                TextColumn::make('mahasiswa.id')
                    ->searchable(),
                TextColumn::make('tanggal_mulai')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('tanggal_selesai')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('keperluan')
                    ->searchable(),
                TextColumn::make('surat_peminjaman')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('confirmed_laboran_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('approved_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('rejected_at')
                    ->dateTime()
                    ->sortable(),
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
