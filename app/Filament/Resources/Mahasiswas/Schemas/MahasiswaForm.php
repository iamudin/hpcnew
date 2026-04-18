<?php

namespace App\Filament\Resources\Mahasiswas\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;

class MahasiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Foto Profile')
                    ->schema([
                   Placeholder::make('-')
    ->content(function ($record) {
        $avatar = $record->user?->avatar;

        if (!$avatar) {
            return 'Tidak ada foto';
        }

        $url = asset('storage/' . $avatar);

        return new HtmlString("
            <img src='{$url}' style='width: 100%;  object-fit: cover; border-radius:40px' />
        ");
    })
                    ])->columns(1),
                 Section::make('Data Mahasiswa')
                  ->description('Informasi Singkat Mahasiswa')
                  ->components([
                    TextInput::make('nim')
                    ->required(),
                TextInput::make('nama')
                 ->live()                      // penting agar bisa di-copy otomatis
                            ->afterStateUpdated(function ($state, $set) {
                                $set('user.name', $state);   // otomatis copy ke field name di User
                            }),
                TextInput::make('nohp'),
                TextInput::make('semester'),
                TextInput::make('prodi'),
                  ]),
                Section::make('Akun Mahasiswa')
                  ->description('Untuk keperluan login Mahasiswa')
                            ->relationship('user')             // relationship di model Kalab ke User
                            ->schema([
                  
                          Hidden::make('name')
                                    ->default(fn ($get) => $get('..nama'))   // fallback jika live tidak cukup
                                    ->dehydrated(true),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true),   // ignore record saat edit

                                TextInput::make('password')
                                    ->label('Password')
                                    ->password()
                                    ->revealable()
                                    ->dehydrated(fn ($state) => filled($state))   // hanya kirim jika diisi
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))  // hashing otomatis
                                    ->required(fn (string $operation) => $operation === 'create')  // wajib saat create
                                    ->maxLength(255),
                            ])->visible(fn() => auth()->user()->isAdmin())   // hanya tampil saat create
                        
            ])->columns(2);
    }
}
