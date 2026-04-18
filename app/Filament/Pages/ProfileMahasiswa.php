<?php 

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProfileMahasiswa extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $title = 'Profil Saya';
    protected static ?string $slug = 'profile';
    protected string $view = 'filament.resources.mahasiswas.pages.profile-mahasiswa';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->isMahasiswa();
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

        if (!$user || !$user->mahasiswa) {
            abort(404, 'Data mahasiswa tidak ditemukan');
        }

        $this->record = $user->mahasiswa->load('user');
        $this->form->fill([
            'nama' => $this->record->nama,
            'nim' => $this->record->nim,
            'prodi' => $this->record->prodi,
            'semester' => $this->record->semester,
            'nohp' => $this->record->nohp,
            'email' => $this->record->user?->email,
             'avatar' => $this->record->user?->avatar, 
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->statePath('data')
            ->schema([

                Section::make('Informasi Pribadi')->schema([
Forms\Components\FileUpload::make('avatar')
    ->label('Foto Profil')
    ->image()
    ->directory('avatars')
    ->visibility('public')
    ->disk('public')
    ->imageEditor(),
                    Forms\Components\TextInput::make('nama')
                        ->required(),

                    Forms\Components\TextInput::make('nim')
                        ->required(),

                    Forms\Components\TextInput::make('prodi')
                        ->required(),

                    Forms\Components\TextInput::make('semester')
                        ->numeric()
                        ->required(),

                    Forms\Components\TextInput::make('nohp')
                        ->tel()
                        ->required(),

                ]),

                Section::make('Informasi Akun')->schema([

                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required(),

                    Forms\Components\TextInput::make('password')
                        ->label('Password Baru')
                        ->default('')
                        ->password()
                        ->revealable()
                        ->dehydrated(false), // hanya diproses manual

                ])->columns(2),

            ])->columns(2);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // ✅ Update mahasiswa
        $this->record->update([
            'nama' => $data['nama'],
            'nim' => $data['nim'],
            'prodi' => $data['prodi'],
            'semester' => $data['semester'],
            'nohp' => $data['nohp'],
        ]);

        // ✅ Update user
        $this->record->user->update([
            'email' => $data['email'],
            'name' => $data['nama'],
            'avatar' => $data['avatar'],
        ]);
if (!empty($data['avatar'])) {
    $this->record->user->update([
        'avatar' => $data['avatar'],
    ]);
}
        // ✅ Update password jika diisi
        if (!empty($data['password'])) {
            $this->record->user->update([
                'password' => bcrypt($data['password']),
            ]);
        }

        Notification::make()
            ->title('Berhasil')
            ->body('Profil berhasil diperbarui')
            ->success()
            ->send();
    }
}