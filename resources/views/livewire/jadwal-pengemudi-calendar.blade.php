<div class="w-full" style="background-image: url(&quot;data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E&quot;);">
    <div class="font-sans w-full p-4 md:p-8">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate-fade-in">
            <div>
                <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white leading-tight drop-shadow-sm">Jadwal Perjalanan Pengemudi</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 mt-2">Pantau dan kelola jadwal perjalanan staf pengemudi secara efisien.</p>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden flex flex-col min-h-[600px] w-full shadow-2xl rounded-2xl border border-gray-100 dark:border-gray-700">

            {{-- Toolbar for Filters and Search --}}
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50 dark:bg-gray-700/50 z-20 rounded-t-xl">
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    {{-- Month Dropdown --}}
                    <select wire:model.live="selectedMonth" class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md shadow-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 transition ease-in-out duration-150">
                        @foreach (range(1, 12) as $month)
                            <option value="{{ $month }}">{{ Carbon\Carbon::create()->month($month)->locale('id')->translatedFormat('F') }}</option>
                        @endforeach
                    </select>

                    {{-- Year Dropdown --}}
                    <select wire:model.live="selectedYear" class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md shadow-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 transition ease-in-out duration-150">
                        @foreach ($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Search Input --}}
                <div class="w-full sm:w-64 relative">
                    <input
                        type="search"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari pengemudi..."
                        class="block w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out placeholder-gray-500 dark:placeholder-gray-400"
                    >
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <x-heroicon-o-magnifying-glass class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                    </div>
                </div>
            </div>

            {{-- Table Container --}}
            <div
                x-data="{
                    stafOrder: @entangle('manualSortOrder').defer,
                    init() {
                        new Sortable(this.$refs.stafTableBody, {
                            animation: 150,
                            handle: '.drag-handle',
                            onEnd: (evt) => {
                                this.stafOrder = Array.from(this.$refs.stafTableBody.children).map(row => row.dataset.stafId);
                                $wire.dispatch('update-staf-sort', { newOrder: this.stafOrder });
                            }
                        });
                    }
                }"
                class="flex-grow overflow-x-auto relative custom-scrollbar"
            >
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 z-30 bg-gray-100 dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            {{-- Sticky Driver Column Header --}}
                            <th class="sticky left-0 z-40 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 p-4 min-w-[250px] border-r border-gray-200 dark:border-gray-700 shadow-xl">
                                <span class="text-sm font-bold uppercase tracking-wider text-gray-700 dark:text-gray-200">Pengemudi</span>
                            </th>

                            {{-- Dates Header --}}
                            @foreach ($dates as $dateString)
                                @php
                                    $date = Carbon\Carbon::parse($dateString);
                                    $dayName = $date->locale('id')->translatedFormat('D');
                                    $isWeekend = $date->isWeekend();
                                    $isToday = $date->isToday();
                                @endphp
                                <th class="p-3 min-w-[160px] text-center border-r border-gray-200 dark:border-gray-700 last:border-r-0 {{ $isWeekend ? 'bg-red-50 dark:bg-red-950/20' : 'bg-gray-50 dark:bg-gray-800/50' }}">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">{{ $dayName }}</span>
                                        <span @class([
                                            "text-lg font-extrabold w-10 h-10 flex items-center justify-center rounded-full transition-all duration-200 ease-in-out",
                                            "text-red-700 bg-red-100 dark:bg-red-900/50 dark:text-red-300" => $isWeekend,
                                            "text-gray-800 dark:text-gray-100" => !$isWeekend && !$isToday,
                                            "bg-blue-600 text-white shadow-lg ring-2 ring-blue-400" => $isToday,
                                        ])>
                                            {{ $date->day }}
                                        </span>
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ $date->translatedFormat('M') }}</span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody x-ref="stafTableBody" class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($drivers as $driver)
                            <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150 ease-in-out" data-staf-id="{{ $driver['staf_id'] }}">
                                {{-- Sticky Driver Column Body --}}
                                <td class="sticky left-0 z-20 bg-white dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-700/30 p-3 px-4 border-r border-gray-200 dark:border-gray-700 shadow-[4px_0_15px_-4px_rgba(0,0,0,0.15)]">
                                    <div class="flex items-center gap-3">
                                        <button type="button" class="drag-handle p-1 text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 cursor-grab rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <x-heroicon-o-bars-3 class="w-5 h-5" />
                                        </button>
                                        <div class="flex-shrink-0">
                                            {{-- You can add an avatar component here if available --}}
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 dark:from-blue-600 dark:to-blue-800 flex items-center justify-center text-white font-bold text-base shadow-md">
                                                {{ Str::limit($driver['nama_staf'], 1, '') }}
                                            </div>
                                        </div>
                                        <div class="flex flex-col">
                                            <div class="text-base font-bold text-gray-800 dark:text-gray-100 leading-snug whitespace-nowrap overflow-hidden text-ellipsis">{{ $driver['nama_staf'] }}</div>
                                            {{-- <p class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $driver['staf_id'] }}</p> --}}
                                        </div>
                                    </div>
                                </td>

                                {{-- Perjalanan Cells --}}
                                @foreach ($dates as $dateString)
                                    @php
                                        $cellPerjalanans = $perjalanansByDriverAndDate[$driver['staf_id']][$dateString] ?? [];
                                        $isWeekend = Carbon\Carbon::parse($dateString)->isWeekend();
                                    @endphp
                                    <td class="p-2 border-r border-gray-100 dark:border-gray-700/50 last:border-r-0 h-28 relative align-top {{ $isWeekend ? 'bg-red-50 dark:bg-red-950/20' : '' }}">
                                        @forelse ($cellPerjalanans as $perjalanan)
                                            <div
                                                class="group-card absolute inset-1 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-800/60 dark:to-blue-900/60 border border-blue-300 dark:border-blue-700 rounded-lg flex flex-col justify-center cursor-pointer p-2 text-sm shadow-md
                                                hover:from-blue-200 hover:to-blue-300 dark:hover:from-blue-700/70 dark:hover:to-blue-800/70 hover:shadow-lg transition-all duration-200 ease-in-out transform hover:-translate-y-1"
                                                title="Nomor: {{ $perjalanan['nomor_perjalanan'] }} | {{ $perjalanan['merk_type'] }} ({{ $perjalanan['nopol_kendaraan'] }}) | {{ $perjalanan['kota_kabupaten'] }}"
                                            >
                                                <p class="font-bold text-blue-800 dark:text-blue-200 truncate leading-tight text-sm">#{{ $perjalanan['nomor_perjalanan'] }}</p>
                                                <p class="text-blue-700 dark:text-blue-300 truncate leading-tight text-xs">{{ $perjalanan['merk_type'] }}</p>
                                                <p class="text-blue-600 dark:text-blue-400 truncate leading-tight text-xs">({{ $perjalanan['nopol_kendaraan'] }})</p>
                                                <p class="text-blue-500 dark:text-blue-500 truncate leading-tight text-xs">{{ $perjalanan['kota_kabupaten'] }}</p>
                                            </div>
                                        @empty
                                            <div class="flex items-center justify-center h-full text-gray-400 dark:text-gray-600 italic text-xs">
                                                <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">Kosong</span>
                                            </div>
                                        @endforelse
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($dates) + 2 }}" class="text-center p-16 text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20">
                                    <x-heroicon-o-calendar-days class="w-20 h-20 mx-auto mb-6 text-blue-400 dark:text-blue-600"/>
                                    <p class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-2">Tidak ada jadwal perjalanan untuk bulan ini.</p>
                                    <p class="text-base text-gray-500 dark:text-gray-400">Coba pilih bulan atau tahun lain, atau tambahkan jadwal baru untuk pengemudi.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 text-sm text-gray-600 dark:text-gray-400 flex justify-between items-center rounded-b-2xl">
                <span class="text-gray-700 dark:text-gray-300 font-medium">Menampilkan {{ count($drivers) }} pengemudi dengan perjalanan</span>
                {{-- Pagination or other footer elements can go here --}}
            </div>
        </div>
    </div>
    <style>
/* Custom scrollbar for better aesthetics */
.custom-scrollbar::-webkit-scrollbar {
    height: 8px;
    width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #555;
}

@media (prefers-color-scheme: dark) {
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #374151; /* dark gray */
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #6b7280; /* medium dark gray */
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af; /* light dark gray */
    }
}
</style>
</div>