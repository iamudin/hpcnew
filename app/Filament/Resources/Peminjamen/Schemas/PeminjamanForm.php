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
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class PeminjamanForm
{
    public static function configure(Schema $schema): Schema {

        return $schema
            ->components([
                Section::make('Informasi Peminjaman')
                    ->components([
                        
Placeholder::make('status_progress')->visible(fn (string $operation) => $operation === 'edit' || $operation === 'view')
    ->label('Status Peminjaman')
    ->content(function ($record) {

        $status = $record->status;

        $steps = [
            'pending' => 'Menunggu Diproses',
            'confirmed_laboran' => 'Dikonfirmasi Laboran',
            'pending_kepala' => 'Menunggu Persetujuan Kepala',
            $record->status=='approved' ? 'approved' : 'rejected' => $record->status === 'approved' ? 'Disetujui' : 'Ditolak',
          
        ];

        $colors = [
            'done' => '#16a34a',   // hijau
            'current' => '#2563eb', // biru
            'pending' => '#d1d5db', // abu
            'rejected' => '#dc2626', // merah
        ];

        $statusOrder = array_keys($steps);
        $currentIndex = array_search($status, $statusOrder);

        $html = "<div style='display:flex;gap:20px;flex-wrap:wrap'>";

        foreach ($steps as $key => $label) {

            $index = array_search($key, $statusOrder);

            if ($status === 'rejected' && $key === 'rejected') {
                $color = $colors['rejected'];
            } elseif ($index < $currentIndex) {
                $color = $colors['done'];
            } elseif ($index === $currentIndex) {
                $color = $colors['current'];
            } else {
                $color = $colors['pending'];
            }

            $tanggal = match ($key) {
                'confirmed_laboran' => $record->confirmed_laboran_at,
                'pending'=> $record->created_at,
                'pending_kepala'=> $record->pending_kepala_at,
                'approved' => $record->approved_at,
                'rejected' => $record->rejected_at,
                default => null,
            };

            $tanggalText = $tanggal
                ? "<div style='font-size:11px;color:#6b7280'>" . Carbon::parse($tanggal)->format('d M Y H:i') . "</div>"
                : "";

            $html .= "
                <div style='text-align:center'>
                    <div style='width:40px;height:40px;border-radius:50%;background:$color;color:white;display:flex;align-items:center;justify-content:center;margin:auto'>
                        ✔
                    </div>
                    <div style='font-size:12px;margin-top:6px'>$label</div>
                    $tanggalText
                </div>
            ";
        }

        $html .= "</div>";

        // tambahan catatan jika ditolak
        if ($status === 'rejected') {
            $html .= "<div style='margin-top:10px;color:#dc2626'>
                        Catatan: " . ($record->catatan ?? '-') . "
                      </div>";
        }

        return new HtmlString($html);
    }),
                        // 1. Pilih Laboratorium
                    Select::make('lab_id')
    ->label('Laboratorium')
    ->placeholder('Pilih Laboratorium')
    ->relationship(
        'lab',
        'nama_labor',
        fn ($query) => auth()->user()->isLaboran() ? $query->where('laboran_id', auth()->id()) : $query
    )
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
Placeholder::make('preview_surat')
    ->label('Surat Peminjaman')
    ->content(function ($record) {
        if (!$record?->surat_peminjaman) {
            return 'Tidak ada file';
        }

        $url = Storage::url($record->surat_peminjaman);

        return new \Illuminate\Support\HtmlString("
            <iframe src='{$url}' width='100%' height='600px'></iframe>
        ");
    })
    ->visible(fn ($record) => filled($record?->surat_peminjaman)),
                        FileUpload::make('surat_peminjaman')
                            ->label('Surat Peminjaman (PDF)')
                            ->directory('surat-peminjaman')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5120)
                            ->disk('public')
                            ->downloadable()
    ->hidden(fn (string $operation, $record) => $operation =='view' || $operation === 'edit' && $record?->status != 'pending')
                            ,
                    ]),


            ]);
    }
}
