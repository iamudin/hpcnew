<div class="mt-6 space-y-4">
    <!-- Tombol Google dengan target="_self" dan tanpa wire:navigate -->
    <x-filament::button
        tag="a"
        :href="route('socialite.redirect', 'google')"
        color="info"
        icon="heroicon-o-globe-alt"
        full-width
        size="lg"
        target="_self"
        onclick="event.preventDefault(); window.location.href = this.href;"
    >
        Masuk dengan Google
    </x-filament::button>


</div>