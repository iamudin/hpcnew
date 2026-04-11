<?php

namespace App\Filament\Resources\JadwalKosongs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class JadwalKosongForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('lab_id')
                    ->relationship('lab', 'nama_labor')
                    ->required(),
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
}
