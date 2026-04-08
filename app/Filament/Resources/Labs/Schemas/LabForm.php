<?php

namespace App\Filament\Resources\Labs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LabForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_labor')
                    ->required(),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
                Select::make('laboran_id')
                    ->relationship('laboran', 'name')
                    ->required(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
