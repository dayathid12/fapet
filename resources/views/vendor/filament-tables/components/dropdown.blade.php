@props([
    'trigger' => null,
    'placement' => 'bottom-start',
])

<x-filament::dropdown.list
    {{ $attributes->merge([
        'x-data' => '{
            open: false,
            placement: $wire.entangle(\'tableColumnSearchColumn\').live ? \'bottom-start\' : \'' . $placement . '\',
        }',
        'x-on:keydown.escape.window' => 'open = false',
        'x-on:click.outside' => 'open = false',
    ]) }}
>
    <x-slot name="trigger">
        {{ $trigger }}
    </x-slot>

    {{ $slot }}
</x-filament::dropdown.list>
