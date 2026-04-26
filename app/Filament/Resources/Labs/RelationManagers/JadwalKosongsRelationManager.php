<?php

namespace App\Filament\Resources\Labs\RelationManagers;

use App\Filament\Resources\JadwalKosongs\Tables\JadwalKosongsTable;
use App\Filament\Resources\Labs\LabResource;
use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class JadwalKosongsRelationManager extends RelationManager
{
    protected static string $relationship = 'jadwal';

    public  function form(Schema $schema): Schema
    {    return $schema
            ->components([
          
                Select::make('hari')
                    ->options([
            'senin' => 'Senin',
            'selasa' => 'Selasa',
            'rabu' => 'Rabu',
            'kamis' => 'Kamis',
            'jumat' => 'Jumat',
            'sabtu' => 'Sabtu',
            'minggu' => 'Minggu',
        ])
                    ->default(null),
                TimePicker::make('jam_mulai'),
                TimePicker::make('jam_selesai'),
                TextInput::make('keterangan')
                    ->default(null),
                Toggle::make('aktif')
                    ->required(),
            ]);
    }
    public function table(Table $table): Table
    {
        return $table
            // ->query(fn($query): Builder => $query->with('peminjaman'))
            ->columns([
          
                TextColumn::make('hari')
                ->description(fn($record)=>$record->tanggal->format('d M Y'))
                ->searchable()
                    ->badge(),
                TextColumn::make('jam_mulai')
                    ->time()
                    ->sortable(),
                TextColumn::make('jam_selesai')
                    ->time()
                    ->sortable(),
                TextColumn::make('keterangan')
                    ->searchable(),
            TextColumn::make('status_pinjam')->default(fn($record)=> $record->peminjaman ? 'Ada peminjaman' : 'Belum ada'),
            
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make()->visible(fn($record) => !$record->peminjaman),
                DeleteAction::make()->visible(fn($record) => !$record->peminjaman)
            ])
               ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
            ]);
    }
}
