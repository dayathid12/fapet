<div class="container mx-auto p-6">
    {{-- UNPAD Logo --}}
    <div class="flex justify-center mb-8">
        <div class="unpad-logo text-center">
            <div class="text-4xl font-bold text-blue-800">UNPAD</div>
            <div class="text-sm text-gray-600">Universitas Padjadjaran</div>
        </div>
    </div>

    <h2 class="text-2xl font-bold text-center mb-8 text-gray-800 dark:text-gray-200">{{ static::$title }}</h2>

    <form wire:submit.prevent="submit" class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{ $this->form }}
        </div>
        <div class="mt-8 flex justify-end">
            <button type="submit" class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:ring-offset-primary-600">
                Kirim Formulir
            </button>
        </div>
    </form>