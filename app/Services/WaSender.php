<?php

namespace App\Services;

use App\Jobs\SendWhatsapp;

class WaSender
{
    public  function send(string $phone, string $message): void
    {
        SendWhatsapp::dispatchAfterResponse($this->format_wa($phone), $message);
    }

    public  function sendBulk(array $numbers, string $message): void
    {
        foreach ($numbers as $phone) {
            SendWhatsapp::dispatchAfterResponse($phone, $message);
        }
    }

   public  function format_wa($phone)
    {
        // hapus selain angka
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // ubah 08 jadi 628
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        // kalau sudah pakai 62 biarkan
        return $phone;
    }
}