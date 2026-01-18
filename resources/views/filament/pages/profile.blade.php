<x-filament-panels::page>
    <form wire:submit="save">
        <div class="flex items-center justify-between mb-6">
            @if ($this->data['tanggal_lahir'])
                <p class="text-xl font-bold text-gray-700 dark:text-gray-300">
                    Masa Pensiun Anda adalah {{ $this->calculateRetirementCountdown($this->data['tanggal_lahir']) }}
                </p>
            @endif
            <x-filament-panels::form.actions
                :actions="$this->getFormActions()"
            />
        </div>

        {{ $this->form }}
    </form>
</x-filament-panels::page>
