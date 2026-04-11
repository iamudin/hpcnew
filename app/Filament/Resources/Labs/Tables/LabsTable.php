<?php

namespace App\Filament\Resources\Labs\Tables;

use App\Filament\Resources\Labs\LabResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LabsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_labor')
                    ->searchable(),
                TextColumn::make('laboran.name')
                ->description(fn($record)=>$record->laboran->email)   // contoh akses relasi nested (laboran -> email)
                    ->searchable(),
                TextColumn::make('kalab.nama')
                ->description(fn($record)=>$record->kalab->user->email)   // contoh akses relasi nested (kalab -> user -> email)
                    ->label('Kepala Lab')
                    ->numeric()
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
           ViewAction::make()
    ->modal(false)                    // nonaktifkan modal
    ->url(fn ($record) => LabResource::getUrl('view', ['record' => $record])),
                EditAction::make()->visible(fn($record) => auth()->user()->id === $record->laboran_id),  // hanya tampilkan edit jika user adalah laboran yang bertanggung jawab
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
