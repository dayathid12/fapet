<div class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-600 dark:text-slate-300 font-sans relative overflow-hidden transition-colors duration-300">

    {{-- Decorative Background Blobs (Soft & Pastel) --}}
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        {{-- Menggunakan Teal dan Rose yang lebih soft --}}
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-teal-400/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-rose-400/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative z-10 w-full p-6 lg:p-10 max-w-[1920px] mx-auto">

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-6 animate-fade-in-up">
            <div>
               
                <p class="text-lg text-slate-500 dark:text-slate-400 font-medium max-w-2xl leading-relaxed">
                    Platform manajemen mobilitas staf. Kelola rute dan waktu secara efisien.
                </p>
            </div>
        </div>

        {{-- Main Glass Card --}}
        <div class="backdrop-blur-xl bg-white/80 dark:bg-slate-900/60 rounded-[2rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-white/50 dark:border-slate-700 overflow-hidden flex flex-col min-h-[700px] w-full ring-1 ring-slate-900/5">

            {{-- Toolbar --}}
            <div class="p-6 border-b border-slate-100 dark:border-slate-700/60 flex flex-col lg:flex-row justify-between items-center gap-5 bg-white/50 dark:bg-slate-800/40">

                {{-- Date Filters --}}
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                    <div class="relative w-full sm:w-48 group">
                        {{-- Focus ring diganti ke Teal --}}
                        <select wire:model.live="selectedMonth" class="appearance-none w-full pl-4 pr-10 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:border-teal-500 transition-all shadow-sm hover:border-teal-300 cursor-pointer">
                            @foreach (range(1, 12) as $month)
                                <option value="{{ $month }}">{{ Carbon\Carbon::create()->month($month)->locale('id')->translatedFormat('F') }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400 group-hover:text-teal-500 transition-colors">
                            <x-heroicon-m-chevron-down class="w-4 h-4" />
                        </div>
                    </div>

                    <div class="relative w-full sm:w-32 group">
                        <select wire:model.live="selectedYear" class="appearance-none w-full pl-4 pr-10 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:border-teal-500 transition-all shadow-sm hover:border-teal-300 cursor-pointer">
                            @foreach ($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400 group-hover:text-teal-500 transition-colors">
                            <x-heroicon-m-chevron-down class="w-4 h-4" />
                        </div>
                    </div>
                </div>

                {{-- Search --}}
                <div class="w-full lg:w-80 relative group">
                    <input
                        type="search"
                        placeholder="Cari nama atau NIP..."
                        class="block w-full pl-10 pr-4 py-3 text-sm text-slate-700 dark:text-white bg-slate-50/50 dark:bg-slate-800/50 border-0 ring-1 ring-slate-200 dark:ring-slate-700 rounded-2xl placeholder-slate-400 focus:ring-2 focus:ring-teal-500 focus:bg-white dark:focus:bg-slate-800 transition-all duration-200 ease-in-out"
                    >
                    <div class="absolute inset-y-0 left-0 flex items-center px-3 pointer-events-none text-slate-400 group-focus-within:text-teal-500 transition-colors">
                         <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                    </div>
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
                class="flex-grow overflow-auto custom-scrollbar"
            >
                <table class="w-max min-w-full border-separate border-spacing-0">
                    <thead class="sticky top-0 z-40">
                        <tr>
                            {{-- Sticky Driver Header --}}
                            <th class="sticky left-0 z-50 p-4 min-w-[320px] bg-slate-50 dark:bg-slate-800/50 backdrop-blur-md border-b border-r border-slate-200 dark:border-slate-700 text-left">
                                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    <x-heroicon-o-users class="w-4 h-4 text-teal-500" />
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
                                    'p-2 w-40 text-center border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 backdrop-blur-md',
                                    'bg-rose-50/30 dark:bg-rose-900/10' => $isWeekend,
                                    'z-20' => $isToday,
                                ])>
                                    <div @class([
                                        "flex flex-col items-center justify-center py-2 px-3 rounded-2xl transition-all duration-300",
                                        "ring-1 ring-inset ring-rose-200/50 bg-rose-50 dark:bg-rose-900/20 dark:ring-rose-800/50" => $isWeekend,
                                        "bg-teal-500 text-white shadow-lg shadow-teal-500/30 scale-105 ring-0" => $isToday,
                                        "hover:bg-white dark:hover:bg-slate-700" => !$isWeekend && !$isToday
                                    ])>
                                        <span class="text-[10px] font-bold uppercase tracking-widest {{ $isToday ? 'text-teal-100' : 'text-slate-400' }}">
                                            {{ $date->locale('id')->translatedFormat('D') }}
                                        </span>
                                        <span class="text-xl font-black {{ $isToday ? 'text-white' : ($isWeekend ? 'text-rose-600 dark:text-rose-400' : 'text-slate-700 dark:text-slate-200') }}">
                                            {{ $date->day }}
                                        </span>
                                        <span class="text-[10px] font-medium {{ $isToday ? 'text-teal-100' : 'text-slate-400' }}">
                                            {{ $date->locale('id')->translatedFormat('M') }}
                                        </span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody x-ref="stafTableBody">
                        @forelse ($drivers as $driver)
                            <tr class="group even:bg-slate-50 dark:even:bg-slate-800/20" data-staf-id="{{ $driver['staf_id'] }}">

                                {{-- Sticky Driver Info Cell --}}
                                <td class="sticky left-0 z-30 p-4 bg-white dark:bg-slate-900 border-r border-b border-slate-200 dark:border-slate-700 shadow-[4px_0_24px_-12px_rgba(0,0,0,0.05)] transition-colors group-hover:bg-slate-100 dark:group-hover:bg-slate-800 min-h-[160px] even:bg-slate-50 dark:even:bg-slate-800/20">
                                    <div class="flex items-center gap-4">
                                        <button type="button" class="drag-handle p-2 text-slate-400 hover:text-teal-500 cursor-move rounded-xl hover:bg-teal-100/50 dark:hover:bg-teal-900/30 transition-all">
                                            <x-heroicon-o-bars-3 class="w-5 h-5" />
                                        </button>

                                        <div class="flex flex-col min-w-0">
                                            <div class="text-sm font-bold text-slate-800 dark:text-slate-100 whitespace-nowrap group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">
                                                {{ $driver['nama_staf'] }}
                                            </div>
                                            <div class="text-xs font-mono text-slate-500 dark:text-slate-400 whitespace-nowrap mt-0.5">
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
                                        'p-2 min-h-[160px] align-top border-b border-slate-200 dark:border-slate-700 transition-colors',
                                        'relative',
                                        'bg-teal-50/40 dark:bg-teal-900/10' => $isToday,
                                        'border-x border-teal-200/50 dark:border-teal-800/30' => $isToday,
                                        'border-r' => !$isToday,
                                        'bg-rose-50/40 dark:bg-rose-900/10' => $isWeekend && !$isToday,
                                        'bg-white dark:bg-slate-900 group-hover:bg-slate-100 dark:group-hover:bg-slate-800' => !$isWeekend && !$isToday,
                                        'even:bg-slate-50 dark:even:bg-slate-800/20'
                                    ])>
                                        <div class="flex flex-col gap-2 h-full">
                                            @forelse ($cellPerjalanans as $perjalanan)
                                                {{-- CARD ITEM (Soft Teal Style) --}}
                                                <div
                                                    class="relative w-full p-3 rounded-xl border border-teal-200/80 dark:border-teal-800/50 bg-white/70 dark:bg-slate-800/50 shadow-sm hover:shadow-lg hover:border-teal-300 dark:hover:border-teal-600 cursor-pointer transition-all duration-200 group/card transform hover:-translate-y-0.5"
                                                >
                                                    <div class="flex justify-between items-start mb-1.5">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-teal-100/80 border border-teal-200/80 text-teal-800 dark:bg-teal-900/70 dark:text-teal-200 dark:border-teal-800/70">
                                                            #{{ $perjalanan['nomor_perjalanan'] }}
                                                        </span>
                                                    </div>

                                                    <div class="space-y-1">
                                                        <p class="text-xs font-bold text-slate-800 dark:text-slate-100 whitespace-nowrap group-hover/card:text-teal-600 dark:group-hover/card:text-teal-400">
                                                            {{ $perjalanan['kota_kabupaten'] }}
                                                        </p>
                                                        <p class="text-[10px] text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                                                            <x-heroicon-m-truck class="w-3 h-3 text-teal-500" />
                                                            <span class="whitespace-nowrap font-medium">{{ $perjalanan['nopol_kendaraan'] }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            @empty
                                                {{-- Empty State (Dot subtle) --}}
                                                <div class="h-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-700"></div>
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
                                        <div class="w-24 h-24 bg-slate-100 dark:bg-slate-800/50 rounded-full flex items-center justify-center mb-4 ring-1 ring-slate-200/50">
                                            <x-heroicon-o-calendar-days class="w-10 h-10 text-slate-400" />
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

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/80 flex justify-between items-center text-xs font-medium text-slate-500">
                <span class="flex items-center gap-2">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-teal-500"></span>
                    </span>
                    Menampilkan {{ count($drivers) }} personil aktif
                </span>
                <span class="opacity-60 font-mono">v2.0</span>
            </div>
        </div>
    </div>

    {{-- Styles for clean scrollbar (Tweak colors slightly) --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar { height: 8px; width: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 100vh;
            border: 2px solid transparent;
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
