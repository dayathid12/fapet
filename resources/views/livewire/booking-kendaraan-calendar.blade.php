<div>
    {{-- Toolbar --}}
    <div class="p-6 border-b border-slate-200 dark:border-slate-700/60 flex flex-col lg:flex-row justify-between items-center gap-5 bg-slate-50/50 dark:bg-slate-800/40">

        {{-- Date Filters --}}
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
            <div class="relative w-full sm:w-48 group">
                <select wire:model.live="selectedMonth" class="appearance-none w-full pl-4 pr-10 py-3 bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-2xl text-sm font-bold text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm cursor-pointer hover:border-indigo-300">
                    @foreach (range(1, 12) as $month)
                        <option value="{{ $month }}">{{ Carbon\Carbon::create()->month($month)->locale('id')->translatedFormat('F') }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400 group-hover:text-indigo-500 transition-colors">
                    <x-heroicon-m-chevron-down class="w-4 h-4" />
                </div>
            </div>

            <div class="relative w-full sm:w-32 group">
                <select wire:model.live="selectedYear" class="appearance-none w-full pl-4 pr-10 py-3 bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-2xl text-sm font-bold text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm cursor-pointer hover:border-indigo-300">
                    @foreach ($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400 group-hover:text-indigo-500 transition-colors">
                    <x-heroicon-m-chevron-down class="w-4 h-4" />
                </div>
            </div>
        </div>

        {{-- Search --}}
        <div class="w-full lg:w-80 relative group">
            <input
                wire:model.live.debounce.300ms="search"
                type="search"
                placeholder="Cari plat nomor atau merk..."
                class="block w-full pl-10 pr-4 py-3 text-sm font-medium text-slate-700 dark:text-white bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-2xl placeholder-slate-400 focus:ring-4 focus:ring-fuchsia-500/20 focus:border-fuchsia-500 transition-all duration-200 ease-in-out"
            >
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                <x-heroicon-m-magnifying-glass class="w-5 h-5" />
            </div>
        </div>
    </div>

    {{-- Table Wrapper --}}
    <div
        x-data="{
            kendaraanOrder: @entangle('manualSortOrder').defer,
            init() {
                new Sortable(this.$refs.kendaraanTableBody, {
                    animation: 200,
                    ghostClass: 'bg-teal-50/50',
                    handle: '.drag-handle',
                    onEnd: (evt) => {
                        this.kendaraanOrder = Array.from(this.$refs.kendaraanTableBody.children).map(row => row.dataset.nopolKendaraan);
                        $wire.dispatch('update-kendaraan-sort', { newOrder: this.kendaraanOrder });
                    }
                });
            }
        }"
        class="flex-grow custom-scrollbar"
    >
        <div class="max-h-[calc(100vh-250px)] overflow-y-auto"> {{-- Adjusted max-height --}}
            <table class="w-max min-w-full border-separate border-spacing-0">
                <thead class="sticky top-0 z-40 shadow-sm">
                <tr>
                    {{-- Sticky Vehicle Header --}}
                    <th class="sticky left-0 z-50 p-4 min-w-[320px] bg-slate-100 dark:bg-slate-800 border-b border-r border-slate-300 dark:border-slate-600 text-left">
                        <div class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-slate-600 dark:text-slate-300">
                            <div class="p-1.5 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg text-indigo-600 dark:text-indigo-400">
                                <x-heroicon-o-truck class="w-4 h-4" />
                            </div>
                            <span>Daftar Kendaraan</span>
                        </div>
                    </th>

                    {{-- Dates Header --}}
                    @foreach ($dates as $dateString)
                        @php
                            $date = Carbon\Carbon::parse($dateString);
                            $isWeekend = $date->isWeekend();
                            $isToday = $date->isToday();
                        @endphp
                        <th @class([
                            'p-2 w-40 text-center border-b border-r border-slate-200 dark:border-slate-700 transition-colors',
                            'bg-red-100 dark:bg-red-900/40 border-red-200 dark:border-red-800' => $isWeekend && !$isToday, // Warna Header Weekend (Merah)
                            'bg-blue-600 text-white border-blue-700 shadow-md transform scale-y-105 origin-top z-50' => $isToday, // Warna Header Hari Ini (Biru Kuat)
                            'bg-slate-50 dark:bg-slate-800' => !$isWeekend && !$isToday,
                        ])>
                            <div class="flex flex-col items-center justify-center py-1">
                                {{-- Nama Hari --}}
                                <span class="text-[10px] font-bold uppercase tracking-widest mb-1
                                    {{ $isToday ? 'text-blue-100' : ($isWeekend ? 'text-red-600 dark:text-red-400' : 'text-slate-500') }}">
                                    {{ $date->locale('id')->translatedFormat('D') }}
                                </span>

                                {{-- Tanggal Angka --}}
                                <div class="text-xl font-black leading-none mb-1
                                    {{ $isToday ? 'text-white' : ($isWeekend ? 'text-red-700 dark:text-red-300' : 'text-slate-700 dark:text-slate-200') }}">
                                    {{ $date->day }}
                                </div>

                                {{-- Bulan --}}
                                <span class="text-[10px] font-medium
                                    {{ $isToday ? 'text-blue-200' : ($isWeekend ? 'text-red-500/80 dark:text-red-400/80' : 'text-slate-400') }}">
                                    {{ $date->locale('id')->translatedFormat('M') }}
                                </span>
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody x-ref="kendaraanTableBody">
                @forelse ($vehicles as $vehicle)
                    <tr class="group" data-nopol-kendaraan="{{ $vehicle['nopol_kendaraan'] }}">

                        {{-- Sticky Vehicle Info Cell --}}
                        <td class="sticky left-0 z-30 p-4 bg-white dark:bg-slate-900 border-r border-b border-slate-200 dark:border-slate-700 transition-all group-hover:bg-slate-50 dark:group-hover:bg-slate-800 min-h-[160px]">
                            <div class="flex items-center gap-4">
                                <button type="button" class="drag-handle p-2 text-slate-300 hover:text-indigo-500 cursor-move rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all">
                                    <x-heroicon-o-bars-3 class="w-5 h-5" />
                                </button>

                                <div class="flex flex-col min-w-0">
                                    <div class="text-sm font-bold text-slate-800 dark:text-slate-100 whitespace-nowrap group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                        {{ $vehicle['merk_type'] }}
                                    </div>
                                    <div class="text-xs font-mono text-slate-500 dark:text-slate-400 whitespace-nowrap mt-0.5 px-2 py-0.5 bg-slate-100 dark:bg-slate-800 rounded w-fit">
                                        {{ $vehicle['nopol_kendaraan'] ?? '-' }}
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap mt-0.5">
                                        {{ $vehicle['jenis_kendaraan'] ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Schedule Cells --}}
                        @foreach ($dates as $dateString)
                            @php
                                $date = Carbon\Carbon::parse($dateString);
                                $isWeekend = $date->isWeekend();
                                $isToday = $date->isToday();
                                $cellPerjalanans = $perjalanansByVehicleAndDate[$vehicle['nopol_kendaraan']][$dateString] ?? [];
                            @endphp
                            <td @class([
                                'p-2 min-h-[160px] align-top border-b border-r border-slate-200 dark:border-slate-700 transition-colors',
                                // LOGIKA WARNA KOLOM
                                'bg-red-50/80 dark:bg-red-900/10' => $isWeekend && !$isToday, // Warna soft merah untuk kolom weekend
                                'bg-blue-50/70 dark:bg-blue-900/20 border-blue-100 dark:border-blue-800' => $isToday, // Warna soft biru untuk kolom hari ini
                                'bg-white dark:bg-slate-900' => !$isWeekend && !$isToday, // Warna default putih
                                // Hover effect untuk row
                                'group-hover:brightness-95 dark:group-hover:brightness-110'
                            ])>
                                <div class="flex flex-col gap-2 h-full">
                                    @forelse ($cellPerjalanans as $perjalanan)
                                        {{-- CARD ITEM --}}
                                        <div
                                            class="relative w-full p-3 rounded-xl border shadow-sm hover:shadow-lg hover:-translate-y-1 cursor-pointer transition-all duration-200 group/card
                                            {{-- Card Styling based on column context --}}
                                            {{ $isToday
                                                ? 'bg-white border-blue-200 dark:bg-slate-800 dark:border-blue-700'
                                                : ($isWeekend
                                                    ? 'bg-white border-red-200 dark:bg-slate-800 dark:border-red-800'
                                                    : 'bg-slate-50 border-slate-200 dark:bg-slate-800 dark:border-slate-700')
                                            }}"
                                        >
                                            <div class="flex justify-between items-start mb-2">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold border
                                                    {{ $isToday ? 'bg-blue-100 text-blue-700 border-blue-200' : 'bg-slate-200 text-slate-600 border-slate-300' }}">
                                                    #{{ $perjalanan['nomor_perjalanan'] }}
                                                </span>
                                                @if($perjalanan['status_perjalanan'] === 'Menunggu Persetujuan')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                                        Pending
                                                    </span>
                                                @elseif($perjalanan['status_perjalanan'] === 'Disetujui')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-green-100 text-green-700 border border-green-200">
                                                        Approved
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="space-y-1">
                                                <p class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-tight">
                                                    {{ $perjalanan['kota_kabupaten'] }}
                                                </p>
                                                <p class="text-[10px] text-slate-500 dark:text-slate-400 flex items-center gap-1.5 font-medium">
                                                    <x-heroicon-m-truck class="w-3 h-3 {{ $isToday ? 'text-blue-500' : 'text-slate-400' }}" />
                                                    <span>{{ $perjalanan['merk_type'] }} ({{ $perjalanan['nopol_kendaraan'] }})</span>
                                                </p>
                                            </div>
                                        </div>
                                    @empty
                                        {{-- Empty State (Dot subtle) --}}
                                        <div class="h-full w-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <div class="w-1.5 h-1.5 rounded-full {{ $isWeekend ? 'bg-red-200' : 'bg-slate-200' }}"></div>
                                        </div>
                                    @endforelse
                                </div>
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($dates) + 1 }}" class="p-20">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="w-24 h-24 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-slate-800 dark:to-slate-800 rounded-full flex items-center justify-center mb-4 ring-4 ring-white dark:ring-slate-700 shadow-xl">
                                    <x-heroicon-o-calendar-days class="w-10 h-10 text-indigo-400" />
                                </div>
                                <h3 class="text-lg font-bold text-slate-700 dark:text-white mb-1">Booking Kosong</h3>
                                <p class="text-slate-500 max-w-sm text-sm">Tidak ada data booking kendaraan ditemukan untuk periode ini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
