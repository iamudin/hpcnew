<x-filament::page>
    <div class="flex flex-col items-center justify-center space-y-6">

        <h2 class="text-xl font-bold">
            Scan QR WhatsApp
        </h2>

        <div id="qrcode"></div>
        @if (!$qr)

            <p class="text-gray-500">QR belum tersedia...</p>
        @else
            <img id="qrImage" class="w-64 h-64 border rounded-lg shadow"
                src="{{ $qr ? 'https://api.qrserver.com/v1/create-qr-code/?size=256x256&data=' . urlencode($qr) : '' }}" />
        @endif


        <x-filament::button wire:click="refreshQr">
            Refresh QR
        </x-filament::button>

    </div>


</x-filament::page>