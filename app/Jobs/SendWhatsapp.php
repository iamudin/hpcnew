<?php

namespace App\Jobs;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;

class SendWhatsapp 
{
    use Dispatchable;


    protected string $phone;
    protected string $message;

    public function __construct(string $phone, string $message)
    {
        $this->phone = $phone;
        $this->message = $message;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Http::post(config('services.whatsapp.url').'/message/send-text', [
            'session' => config('services.whatsapp.session'),
            'to' => $this->phone,
            'text' => $this->message,
        ]);
    }
}
