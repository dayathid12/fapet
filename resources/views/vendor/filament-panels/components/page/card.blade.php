@props([
    'class' => '',
])

<div {{ $attributes->class(['bg-white rounded-lg shadow-sm border border-gray-200', $class]) }}>
    {{ $slot }}
</div>
