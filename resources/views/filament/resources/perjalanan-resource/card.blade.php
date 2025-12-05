<x-filament-panels::page.card>
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Perjalanan</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Total</p>
                        <p class="text-2xl font-bold text-blue-600">{{ \App\Models\Perjalanan::count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Menunggu</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ \App\Models\Perjalanan::where('status_perjalanan', 'Menunggu Persetujuan')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Terjadwal</p>
                        <p class="text-2xl font-bold text-green-600">{{ \App\Models\Perjalanan::where('status_perjalanan', 'Terjadwal')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Ditolak</p>
                        <p class="text-2xl font-bold text-red-600">{{ \App\Models\Perjalanan::where('status_perjalanan', 'Ditolak')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page.card>
