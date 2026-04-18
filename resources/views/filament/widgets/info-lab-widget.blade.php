<x-filament-widgets::widget>
    <x-filament::section>
    <div class="p-6 bg-white rounded-xl shadow-sm">

        <h1 class="text-lg font-bold text-gray-800 mb-2">
            Sebagai Kepala Laboratorium
        </h1>

        @if($lab)
            <div class="text-gray-700">
                <p class="text-xl font-semibold" style="font-width: bold;font-size:18px;">
                    {{ $lab->nama_labor }} - Laboran : {{ $lab->laboran?->name ?? 'Belum ada laboran' }}
                </p>

            </div>
        @else
            <div class="text-red-500">
                Tidak ada lab yang terhubung dengan akun ini.
            </div>
        @endif

    </div>
    </x-filament::section>
</x-filament-widgets::widget>
