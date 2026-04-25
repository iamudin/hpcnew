<?php

namespace App\Filament\Resources\Labs\Schemas;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class LabForm
{

    public static function configure(Schema $schema): Schema {
        return $schema
            ->components([
                TextInput::make('nama_labor')
                    ->required()
                    ->label('Nama Laboratorium'),

                Textarea::make('deskripsi')
                    ->columnSpanFull(),
Section::make('Jadwal Semester')
    ->schema([
        DatePicker::make('tanggal_mulai_semester')
            ->label('Tanggal Mulai Semester')
            ->required(fn (string $operation) => $operation === 'create') 
            ->dehydrated(false)
            ->live(),

        DatePicker::make('tanggal_selesai_semester')
            ->label('Tanggal Selesai Semester')
            ->required(fn (string $operation) => $operation === 'create') 
            ->dehydrated(false)

            ->afterOrEqual('tanggal_mulai_semester'),
    ])
    ->columns(2),
                // Laboran = user yang sedang login (hidden + otomatis)
              

                // === Bagian Kalab (1-to-1) ===
             Section::make('Kepala Laboratorium (Kalab)')
                  ->description('Informasi Kepala laboratorium')
                    ->relationship('kalab')                    // relationship di model Lab ke Kalab
                    ->schema([
                        Section::make(null)
                        ->schema([
                        // Field dari tabel kalabs
                        TextInput::make('nip')
                            ->label('NIP')
                            ->nullable(),
                            TextInput::make('nama')
                            ->label('Nama Kalab')
                            ->live()                      // penting agar bisa di-copy otomatis
                            ->afterStateUpdated(function ($state, $set) {
                                $set('user.name', $state);   // otomatis copy ke field name di User
                            }),
                        TextInput::make('nohp')
                            ->label('Nomor HP')
                            ->tel()
                            ->nullable()]),

                        // Nested form untuk User (name, email, password)
                        Section::make('Akun User Kalab')
                  ->description('Untuk keperluan logn Kepala Labor')

                            ->relationship('user')             // relationship di model Kalab ke User
                            ->schema([
                  
                          Hidden::make('name')
                                    ->default(fn ($get) => $get('..nama'))   // fallback jika live tidak cukup
                                    ->dehydrated(true),
                                Hidden::make('role')
                                    ->default('kepala_laboran')   // fallback jika live tidak cukup
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
                            ])
                            ->columns(2),
                    ])
                    ->columns(2),
            ])
            ->columns(1);
    }
}