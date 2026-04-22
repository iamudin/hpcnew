<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               Placeholder::make('info')
    ->content(new HtmlString('
        <div style="background:#ccc;padding:20px;">
            ⚠️ Form ini khusus digunakan untuk Tambah/Edit akun <b>Laboran</b>.
        </div>
    '))
    ->columnSpanFull(),
                TextInput::make('name')
                    ->required(),

                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('password')
                                    ->label('Password')
                                    ->password()
                                    ->revealable()
                                    ->dehydrated(fn ($state) => filled($state))   
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))  
                                    ->required(fn (string $operation) => $operation === 'create')  
                                    ->maxLength(255),
                 
            ]);
    }
}
