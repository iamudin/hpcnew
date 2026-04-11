<?php

namespace App\Filament\Resources\Labs\RelationManagers;

use App\Filament\Resources\JadwalKosongs\Tables\JadwalKosongsTable;
use App\Filament\Resources\Labs\LabResource;
use Dom\Text;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                EditAction::make(),
            ])
            ->headerActions([
            ]);
    }
}
