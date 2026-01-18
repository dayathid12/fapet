<x-filament-panels::page>
    <form wire:submit="save">
        <div class="flex justify-end mb-6">
            <x-filament-panels::form.actions
                :actions="$this->getFormActions()"
            />
        </div>

        {{ $this->form }}
    </form>
</x-filament-panels::page>
