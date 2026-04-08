<?php

namespace App\Filament\Pages;

use App\Models\Lab;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class Pengaturan extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Pengaturan';
    protected static ?string $title = 'Pengaturan Sistem Laboratorium';
    protected string $view = 'filament.pages.pengaturan';

    // Data untuk tiap tab
    public ?array $labData = [];
    public ?array $kalabData = [];
    public ?array $jadwalData = [];

    public function mount(): void
    {
        $this->fillForms();
    }

    protected function fillForms(): void
    {
        $this->labData = Lab::first()?->toArray() ?? [];
        $this->kalabData = User::where('level', 'kepala_laboran')->first()?->toArray() ?? [];
        // $this->jadwalData = LabAvailableSlot::first()?->toArray() ?? [];
    }

    public function form(Schema $form)
    {
        return $form
            ->components([
                Tabs::make('Pengaturan')
                    ->tabs([
                        Tab::make('Laboratorium')
                            ->icon('heroicon-o-beaker')
                            ->schema($this->getLabFormSchema()),

                       Tab::make('Kepala Laboran')
                            ->icon('heroicon-o-user')
                            ->schema($this->getKalabFormSchema()),

                        Tab::make('Jadwal Peminjaman')
                            ->icon('heroicon-o-calendar')
                            ->schema($this->getJadwalFormSchema()),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('');   // penting untuk multiple form state
    }

    // ==================== FORM SCHEMA ====================

    protected function getLabFormSchema(): array
    {
        return [
            Section::make('Data Laboratorium')
                ->description('Informasi umum laboratorium')
                ->schema([
                    Forms\Components\TextInput::make('labData.nama_labor')
                        ->label('Nama Laboratorium')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Textarea::make('labData.deskripsi')
                        ->label('Deskripsi')
                        ->rows(5),
                ]),
        ];
    }

    protected function getKalabFormSchema(): array
    {
        return [
            Section::make('Data Kepala Laboran')
                ->schema([
                    Forms\Components\TextInput::make('kalabData.name')
                        ->label('Nama Lengkap')
                        ->required(),

                    Forms\Components\TextInput::make('kalabData.email')
                        ->label('Email')
                        ->email()
                        ->required(),

                    Forms\Components\Select::make('kalabData.lab_id')
                        ->label('Laboratorium')
                        ->options(Lab::pluck('nama_labor', 'id'))
                        ->required()
                        ->native(false),
                ])
                ->columns(2),
        ];
    }

    protected function getJadwalFormSchema(): array
    {
        return [
           Section::make('Jadwal yang Boleh Dipinjam')
                ->schema([
                    Forms\Components\Select::make('jadwalData.hari')
                        ->label('Hari')
                        ->options([
                            'Senin' => 'Senin',
                            'Selasa' => 'Selasa',
                            'Rabu' => 'Rabu',
                            'Kamis' => 'Kamis',
                            'Jumat' => 'Jumat',
                            'Sabtu' => 'Sabtu',
                        ])
                        ->required(),

                    Forms\Components\TimePicker::make('jadwalData.jam_mulai')
                        ->label('Jam Mulai')
                        ->required(),

                    Forms\Components\TimePicker::make('jadwalData.jam_selesai')
                        ->label('Jam Selesai')
                        ->required(),

                    Forms\Components\Textarea::make('jadwalData.keterangan')
                        ->label('Keterangan'),

                    Forms\Components\Toggle::make('jadwalData.is_active')
                        ->label('Aktif')
                        ->default(true),
                ])
                ->columns(2),
        ];
    }

    // ==================== SAVE METHOD ====================

    public function save(): void
    {
        $this->validate([
            'labData.nama_labor' => 'required|string|max:255',
            'kalabData.name' => 'required|string|max:255',
            'kalabData.email' => 'required|email',
            'jadwalData.hari' => 'required',
            'jadwalData.jam_mulai' => 'required',
            'jadwalData.jam_selesai' => 'required',
        ]);

        // Simpan Lab
        $lab = Lab::firstOrNew(['id' => $this->labData['id'] ?? null]);
        $lab->fill($this->labData);
        $lab->save();

        // Simpan Kepala Laboran
        $kalab = User::firstOrNew([
            'id' => $this->kalabData['id'] ?? null,
            'role' => 'kepala_laboran',
        ]);
        $kalab->fill($this->kalabData);
        $kalab->save();

        // Simpan Jadwal
        // $jadwal = LabAvailableSlot::firstOrNew(['id' => $this->jadwalData['id'] ?? null]);
        // $jadwal->fill($this->jadwalData);
        // $jadwal->save();

        $this->notify('success', 'Semua data berhasil disimpan.');

        $this->fillForms(); // refresh data setelah simpan
    }

    protected function getFormActions()
    {
    }
}