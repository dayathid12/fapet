<div class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-600 dark:text-slate-300 font-sans relative overflow-hidden transition-colors duration-300">

    {{-- Decorative Background Blobs (Lebih Berwarna / Colorful) --}}
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        {{-- Menggunakan warna Indigo, Fuchsia, dan Orange untuk kesan ceria --}}
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-400/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-fuchsia-400/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute top-[20%] right-[20%] w-[30%] h-[30%] bg-orange-300/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 4s;"></div>
    </div>

    <div class="relative z-10 w-full p-6 lg:p-10 max-w-[1920px] mx-auto">

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-6 animate-fade-in-up">
            <div>

                <p class="text-lg text-slate-500 dark:text-slate-400 font-medium max-w-2xl leading-relaxed text-center md:text-left">
                    Platform manajemen mobilitas staf. Kelola rute dan waktu secara efisien.
                </p>
            </div>
        </div>

        {{-- Main Glass Card --}}
        <div class="backdrop-blur-xl bg-white/90 dark:bg-slate-900/80 rounded-[2rem] shadow-2xl shadow-indigo-200/50 dark:shadow-none border border-white/50 dark:border-slate-700 overflow-hidden flex flex-col min-h-[700px] w-full ring-1 ring-slate-900/5">

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
                        type="search"
                        placeholder="Cari nama atau NIP..."
                        class="block w-full pl-10 pr-4 py-3 text-sm font-medium text-slate-700 dark:text-white bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-2xl placeholder-slate-400 focus:ring-4 focus:ring-fuchsia-500/20 focus:border-fuchsia-500 transition-all duration-200 ease-in-out"
                    >

                </div>
            </div>

            {{-- Table Wrapper --}}
            <div
                x-data="{
                    stafOrder: @entangle('manualSortOrder').defer,
                    init() {
                        new Sortable(this.$refs.stafTableBody, {
                            animation: 200,
                            ghostClass: 'bg-teal-50/50', // Ghost color teal
                            handle: '.drag-handle',
                            onEnd: (evt) => {
                                this.stafOrder = Array.from(this.$refs.stafTableBody.children).map(row => row.dataset.stafId);
                                $wire.dispatch('update-staf-sort', { newOrder: this.stafOrder });
                            }
                        });
                    }
                }"
                class="flex-grow custom-scrollbar"
            >
                <div class="max-h-[400px] overflow-y-auto">
                    <table class="w-max min-w-full border-separate border-spacing-0">
                        <thead class="sticky top-0 z-40 shadow-sm">
                        <tr>
                            {{-- Sticky Driver Header --}}
                            <th class="sticky left-0 z-50 p-4 min-w-[320px] bg-slate-100 dark:bg-slate-800 border-b border-r border-slate-300 dark:border-slate-600 text-left">
                                <div class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-slate-600 dark:text-slate-300">
                                    <div class="p-1.5 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg text-indigo-600 dark:text-indigo-400">
                                        <x-heroicon-o-users class="w-4 h-4" />
                                    </div>
                                    <span>Personil Pengemudi</span>
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

                    <tbody x-ref="stafTableBody">
                        @forelse ($drivers as $driver)
                            <tr class="group" data-staf-id="{{ $driver['staf_id'] }}">

                                {{-- Sticky Driver Info Cell --}}
                                <td class="sticky left-0 z-30 p-4 bg-white dark:bg-slate-900 border-r border-b border-slate-200 dark:border-slate-700 transition-all group-hover:bg-slate-50 dark:group-hover:bg-slate-800 min-h-[160px]">
                                    <div class="flex items-center gap-4">
                                        <button type="button" class="drag-handle p-2 text-slate-300 hover:text-indigo-500 cursor-move rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all">
                                            <x-heroicon-o-bars-3 class="w-5 h-5" />
                                        </button>

                                        <div class="flex flex-col min-w-0">
                                            <div class="text-sm font-bold text-slate-800 dark:text-slate-100 whitespace-nowrap group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                                {{ $driver['nama_staf'] }}
                                            </div>
                                            <div class="text-xs font-mono text-slate-500 dark:text-slate-400 whitespace-nowrap mt-0.5 px-2 py-0.5 bg-slate-100 dark:bg-slate-800 rounded w-fit">
                                                {{ $driver['nip'] ?? '-' }}
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
                                        $cellPerjalanans = $perjalanansByDriverAndDate[$driver['staf_id']][$dateString] ?? [];
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
                                                    </div>

                                                    <div class="space-y-1">
                                                        <p class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-tight">
                                                            {{ $perjalanan['kota_kabupaten'] }}
                                                        </p>
                                                        <p class="text-[10px] text-slate-500 dark:text-slate-400 flex items-center gap-1.5 font-medium">
                                                            <x-heroicon-m-truck class="w-3 h-3 {{ $isToday ? 'text-blue-500' : 'text-slate-400' }}" />
                                                            <span>{{ $perjalanan['nopol_kendaraan'] }}</span>
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
                                        <h3 class="text-lg font-bold text-slate-700 dark:text-white mb-1">Jadwal Kosong</h3>
                                        <p class="text-slate-500 max-w-sm text-sm">Tidak ada data perjalanan ditemukan untuk periode ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-900/80 flex justify-between items-center text-xs font-medium text-slate-500">
                <span class="flex items-center gap-2">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                    </span>
                    Menampilkan {{ count($drivers) }} personil aktif
                </span>
                <span class="opacity-60 font-mono">v2.1 Colorful Edition</span>
            </div>
        </div>
    </div>

    {{-- Styles for clean scrollbar --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar { height: 10px; width: 10px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 100vh;
            border: 3px solid transparent;
            background-clip: content-box;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fade-in-up 0.6s ease-out forwards; }
    </style>
</div>
