<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class Account extends Page
{
    protected string $view = 'filament.pages.account';
    
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        return !auth()->user()->isMahasiswa();
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Update')
                ->size('sm')
                ->icon('heroicon-o-check')
                ->color('primary')
                ->action('save'),
        ];
    }

    public $record;

    public ?array $data = [];

    public function mount(): void
    {
        $user = auth()->user();
        $this->record = $user;
        if (!$user) {
            abort(404, 'Data mahasiswa tidak ditemukan');
        }

        $this->form->fill([
            'email' => $user?->email,
            'avatar' => $user?->avatar, 
            'nohp' => $user?->nohp, 
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->statePath('data')
            ->schema([

                Section::make('Akun')->schema([
FileUpload::make('avatar')
    ->label('Foto Profil')
    ->image()
    ->directory('avatars')
    ->visibility('public')
    ->disk('public')
    ->imageEditor(),
     TextInput::make('nohp')
                        ->label('No Whatsapp (aktif)')
                        ->placeholder("62812345678")
                        ->visible(fn()=>auth()->user()->isLaboran()),


                   TextInput::make('email')
                        ->label('Email')
                        ->email()
           ->rules([
        Rule::unique('users', 'email')->ignore($this->record->id),
    ])
                        ->required(),

               TextInput::make('password')
                        ->label('Password Baru')
                        ->default('')
                        ->password()
                        ->revealable()
                        ->dehydrated(false), // hanya diproses manual

                ])

            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();



        // ✅ Update user
        $this->record->update([
            'email' => $data['email'],
            'avatar' => $data['avatar'],
            'nohp' => $data['nohp'] ?? null,
        ]);
if (!empty($data['avatar'])) {
    $this->record->update([
        'avatar' => $data['avatar'],
    ]);
}
        // ✅ Update password jika diisi
        if (!empty($data['password'])) {
            $this->record->update([
                'password' => bcrypt($data['password']),
            ]);
        }

        Notification::make()
            ->title('Berhasil')
            ->body('Akun berhasil diperbarui')
            ->success()
            ->send();
    }
}
