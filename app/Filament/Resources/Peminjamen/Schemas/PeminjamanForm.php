<?php

namespace App\Filament\Resources\Peminjamen\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PeminjamanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('lab_id')
                    ->relationship('lab', 'id')
                    ->required(),
                Select::make('mahasiswa_id')
                    ->relationship('mahasiswa', 'id')
                    ->required(),
                DateTimePicker::make('tanggal_mulai')
                    ->required(),
                DateTimePicker::make('tanggal_selesai')
                    ->required(),
                TextInput::make('keperluan')
                    ->required(),
                TextInput::make('surat_peminjaman')
                    ->required(),
                Select::make('status')
                    ->options([
            'pending' => 'Pending',
            'confirmed_laboran' => 'Confirmed laboran',
            'pending_kepala' => 'Pending kepala',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ])
                    ->default('pending')
                    ->required(),
                DateTimePicker::make('confirmed_laboran_at'),
                DateTimePicker::make('approved_at'),
                DateTimePicker::make('rejected_at'),
                Textarea::make('catatan_laboran')
                    ->columnSpanFull(),
                Textarea::make('catatan_kepala')
                    ->columnSpanFull(),
            ]);
    }
}
