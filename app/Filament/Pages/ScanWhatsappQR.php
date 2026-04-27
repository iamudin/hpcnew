<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;

class ScanWhatsappQR extends Page
{
    protected string $view = 'filament.pages.scan-whatsapp-q-r';

    public ?string $qr = null;
    public ?string $status = null;

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