<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Http;

class ScanWhatsappQR extends Page
{
    protected string $view = 'filament.pages.scan-whatsapp-q-r';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::QrCode;
    public ?string $qr = null;
    public ?string $status = null;
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function canAccess(): bool
    {
        return auth()->user()->isAdmin();
    }
    public function mount(): void
    {
        $this->loadQr();
    }

    public function loadQr(): void
    {
        $response = Http::post(config('services.whatsapp.url').'/session/start',[
            'session'=>config('services.whatsapp.session')
        ]);
        
        if ($response->successful()) {
            $data = $response->json();
            $this->qr = $data['qr'] ?? null;
        }
    }

    public function refreshQr(): void
    {
    Http::post(config('services.whatsapp.url') . '/session/logout', [
            'session' => config('services.whatsapp.session')
        ]);
        $this->loadQr();
    }
}