<?php

namespace App\Filament\Resources\Peminjamen\Schemas;

use App\Models\JadwalKosong;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class PeminjamanForm
{
    public static function configure(Schema $schema): Schema {

        return $schema
            ->components([
                Section::make('Informasi Peminjaman')
                    ->components([

                        // 1. Pilih Laboratorium
                        Select::make('lab_id')
                            ->label('Laboratorium')
                            ->placeholder('Pilih Laboratorium')
                            ->relationship('lab', 'nama_labor')   // sesuaikan dengan nama kolom di tabel labs
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('jadwal_kosong_id', null);
                                $set('tanggal_mulai', null);
                                $set('tanggal_selesai', null);
                            }),

                        DatePicker::make('tanggal')
                            ->dehydrated(false)
                            ->visible(fn (string $operation) => $operation === 'create') 
                            ->label('Tanggal Peminjaman')
                            ->required()
                            ->minDate(now()->format('Y-m-d'))
                            ->live()
                            ->afterStateUpdated(fn(Set $set) => $set('jadwal_kosong_id', null)),
                        Radio::make('jadwal_kosong_id')
                            ->label(function (Get $get) {
                                $labId = $get('lab_id');
                                $tanggal = $get('tanggal');

                                // Jika lab atau tanggal belum dipilih
                                if (!$labId || !$tanggal) {
                                    return 'Pilih Jadwal Kosong yang Tersedia';
                                }

                                $hari = strtolower(Carbon::parse($tanggal)->dayName);

                                $jadwals = JadwalKosong::where('lab_id', $labId)
                                    ->where('aktif', true)
                                    ->where(function ($query) use ($tanggal, $hari) {
                                        $query->where('tanggal', $tanggal)
                                            ->orWhere(function ($q) use ($hari) {
                                                $q->whereNull('tanggal')->where('hari', $hari);
                                            });
                                    })
                                    ->get();

                                if ($jadwals->isEmpty()) {
                                    return 'Jadwal tidak tersedia';
                                }

                                // Cek apakah ada yang belum dipinjam
                                $adaYangTersedia = false;

                                foreach ($jadwals as $jadwal) {
                                    $start = Carbon::parse($tanggal . ' ' . $jadwal->jam_mulai);
                                    $end = Carbon::parse($tanggal . ' ' . $jadwal->jam_selesai);

                                    $sudahDipinjam = Peminjaman::where('lab_id', $labId)
                                        ->whereIn('status', ['approved', 'confirmed_laboran'])
                                        ->where(function ($query) use ($start, $end) {
                                            $query->where('tanggal_mulai', '<', $end)
                                                ->where('tanggal_selesai', '>', $start);
                                        })
                                        ->exists();

                                    if (!$sudahDipinjam) {
                                        $adaYangTersedia = true;
                                        break;
                                    }
                                }

                                return $adaYangTersedia
                                    ? 'Pilih Jadwal Kosong yang Tersedia'
                                    : 'Jadwal tidak tersedia, Silahkan piih tanggal lain atau hubungi laboran!';
                            })
                            ->required()
                            ->live()
                            ->hidden(fn(Get $get) => !$get('lab_id') || !$get('tanggal'))  // sembunyikan jika lab atau tanggal belum dipilih
                            ->columnSpanFull()
                            ->columns(1)                    // Ubah ke 2 atau 4 jika mau
                            ->gridDirection('row')
                            ->options(function (Get $get) {
                                $labId = $get('lab_id');
                                $tanggal = $get('tanggal');

                                if (!$labId || !$tanggal) {
                                    return [];
                                }

                                $hari = strtolower(Carbon::parse($tanggal)->dayName);

                                $jadwals = JadwalKosong::where('lab_id', $labId)
                                    ->where('aktif', true)
                                    ->where(function ($query) use ($tanggal, $hari) {
                                        $query->where('tanggal', $tanggal)
                                            ->orWhere(function ($q) use ($hari) {
                                                $q->whereNull('tanggal')->where('hari', $hari);
                                            });
                                    })
                                    ->get();

                                $options = [];

                                foreach ($jadwals as $jadwal) {
                                    $start = Carbon::parse($tanggal . ' ' . $jadwal->jam_mulai);
                                    $end = Carbon::parse($tanggal . ' ' . $jadwal->jam_selesai);

                                    // Pengecekan overlap yang BENAR
                                    $sudahDipinjam = Peminjaman::where('lab_id', $labId)
                                        ->whereIn('status', ['approved', 'confirmed_laboran'])
                                        ->where(function ($query) use ($start, $end) {
                                        $query->where('tanggal_mulai', '<', $end)
                                            ->where('tanggal_selesai', '>', $start);
                                    })
                                        ->exists();

                                    if (!$sudahDipinjam) {
                                        $options[$jadwal->id] = "{$jadwal->jam_mulai} — {$jadwal->jam_selesai}";
                                    }
                                }

                                return $options;
                            })
                            ->descriptions(function (Get $get) {
                                $labId = $get('lab_id');
                                $tanggal = $get('tanggal');

                                if (!$labId || !$tanggal) {
                                    return [];
                                }

                                $hari = strtolower(Carbon::parse($tanggal)->dayName);

                                $jadwals = JadwalKosong::where('lab_id', $labId)
                                    ->where('aktif', true)
                                    ->where(function ($query) use ($tanggal, $hari) {
                                        $query->where('tanggal', $tanggal)
                                            ->orWhere(function ($q) use ($hari) {
                                                $q->whereNull('tanggal')->where('hari', $hari);
                                            });
                                    })
                                    ->get();

                                $descriptions = [];

                                foreach ($jadwals as $jadwal) {
                                    $start = Carbon::parse($tanggal . ' ' . $jadwal->jam_mulai);
                                    $end = Carbon::parse($tanggal . ' ' . $jadwal->jam_selesai);

                                    $sudahDipinjam = Peminjaman::where('lab_id', $labId)
                                        ->whereIn('status', ['approved', 'confirmed_laboran'])
                                        ->where(function ($query) use ($start, $end) {
                                            $query->where('tanggal_mulai', '<', $end)
                                                ->where('tanggal_selesai', '>', $start);
                                        })
                                        ->exists();

                                    if (!$sudahDipinjam) {
                                        $descriptions[$jadwal->id] = $jadwal->keterangan ?? 'Tersedia untuk dipinjam';
                                    }
                                }

                                return $descriptions;
                            })
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $jadwalId = $get('jadwal_kosong_id');
                                if ($jadwalId) {
                                    $jadwal = JadwalKosong::find($jadwalId);
                                    if ($jadwal) {
                                        $tanggal = $get('tanggal') ?? now()->format('Y-m-d');
                                        $set('tanggal_mulai', "{$tanggal} {$jadwal->jam_mulai}");
                                        $set('tanggal_selesai', "{$tanggal} {$jadwal->jam_selesai}");
                                    }
                                }
                            }),
                        DateTimePicker::make('tanggal_mulai')
                            ->label('Tanggal & Jam Mulai')
                            ->required()
                            ->seconds(false)
                            ->hidden(fn(Get $get) => !$get('jadwal_kosong_id'))
                            ->readOnly()
                            ->native(false),

                        DateTimePicker::make('tanggal_selesai')
                            ->label('Tanggal & Jam Selesai')
                            ->required()
                            ->seconds(false)
                            ->readOnly()

                            ->hidden(fn(Get $get) => !$get('jadwal_kosong_id'))
                            ->native(false)
                            ->afterOrEqual('tanggal_mulai'),

                    ])
                    ->columnSpanFull()
                    ->columns(1),

                Section::make('Detail Peminjaman')
                    ->columnSpanFull()
                    ->hidden(fn(Get $get) => !$get('jadwal_kosong_id'))
                    ->components([

                        Textarea::make('catatan_mahasiswa')
                            ->label('Catatan Tambahan')
                            ->placeholder('Misal: Butuh proyektor, atau catatan khusus lainnya')
                            ->maxLength(300),
                        Textarea::make('keperluan')
                            ->label('Keperluan')
                            ->placeholder('Jelaskan secara singkat dan jelas keperluan peminjaman laboratorium')
                            ->required()
                            ->maxLength(500)
                            ->rows(4),

                        FileUpload::make('surat_peminjaman')
                            ->label('Surat Peminjaman (PDF)')
                            ->directory('surat-peminjaman')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5120)
                            ->downloadable(),
                    ]),


            ]);
    }
}
