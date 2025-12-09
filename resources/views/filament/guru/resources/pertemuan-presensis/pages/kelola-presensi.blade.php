<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <x-filament::button type="submit" class="mt-4">
            Simpan Presensi
        </x-filament::button>
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
