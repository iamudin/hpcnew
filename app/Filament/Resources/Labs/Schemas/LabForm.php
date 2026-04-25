<?php

namespace App\Filament\Resources\Labs\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LabForm
{
    public static function configure(Schema $schema): Schema
    {
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

                // =========================
                // SECTION KALAB
                // =========================
                Section::make('Kepala Laboratorium (Kalab)')
                    ->description('Informasi Kepala laboratorium')
                    ->relationship('kalab')

                    // 🔥 TOMBOL HAPUS
                    ->headerActions([

                        Action::make('removeKalab')
                            ->label('Hapus Kalab')
                            ->icon('heroicon-o-trash')
                            ->color('danger')
                            ->requiresConfirmation()
                            ->visible(fn ($record) => $record?->nama)
                            ->action(function ($record, $livewire) {
                                DB::transaction(function () use ($record) {

                                    $kalab = $record;
                                    if ($kalab) {
                                        // hapus user
                                        if ($kalab->user) {
                                            $kalab->user->delete();
                                        }

                                        // hapus kalab
                                        $kalab->delete();
                                    }

                                    // putus relasi
                                    $record->update([
                                        'kalab_id' => null,
                                    ]);
                                });
                                $livewire->refreshFormData([
                                    'kalab',
                                ]);
                                // refresh form biar langsung kosong
                            }),

                    ])

                    ->schema([

                        // =========================
                        // DATA KALAB
                        // =========================
                        Section::make(null)
                            ->schema([

                                TextInput::make('nip')
                                    ->label('NIP')
                                    ->required()
                                    ->nullable(),

                                TextInput::make('nama')
                                    ->label('Nama Kalab')
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) {
                                        $set('user.name', $state);
                                    }),

                                TextInput::make('nohp')
                                ->required()

                                    ->label('Nomor HP')
                                    ->tel()
                                    ->nullable(),

                            ]),

                        // =========================
                        // AKUN USER
                        // =========================
                        Section::make('Akun User Kalab')
                            ->description('Untuk keperluan login Kepala Labor')
                            ->relationship('user')
                            ->schema([

                                Hidden::make('name')
                                    ->default(fn ($get) => $get('..nama'))
                                    ->dehydrated(true),

                                Hidden::make('role')
                                    ->default('kepala_laboran')
                                    ->dehydrated(true),

                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                TextInput::make('password')
                                    ->label('Password')
                                    ->password()
                                    ->revealable()
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->required(fn (string $operation) => $operation === 'create')
                                    ->maxLength(255),

                            ])
                            ->columns(2),

                    ])
                    ->columns(2),

            ])
            ->columns(1);
    }
}