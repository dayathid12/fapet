<div class="w-full">
    <div class="font-sans w-full">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate-fade-in">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Jadwal Perjalanan Pengemudi</h2>
                <p class="text-slate-500 dark:text-gray-400 mt-1 text-sm">Lihat dan kelola jadwal perjalanan pengemudi.</p>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden flex flex-col min-h-[500px] w-full shadow-md rounded-lg">

            {{-- Toolbar for Filters --}}
            <div class="p-4 md:p-5 border-b border-slate-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white dark:bg-gray-800 z-20">
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Month Dropdown --}}
                    <select wire:model.live="selectedMonth" class="bg-slate-50 dark:bg-gray-700/50 border border-slate-200 dark:border-gray-600 rounded-lg text-sm px-3 py-2 focus:ring-teal-500 focus:border-teal-500">
                        @foreach (range(1, 12) as $month)
                            <option value="{{ $month }}">{{ Carbon\Carbon::create()->month($month)->locale('id')->translatedFormat('F') }}</option>
                        @endforeach
                    </select>

                    {{-- Year Dropdown --}}
                    <select wire:model.live="selectedYear" class="bg-slate-50 dark:bg-gray-700/50 border border-slate-200 dark:border-gray-600 rounded-lg text-sm px-3 py-2 focus:ring-teal-500 focus:border-teal-500">
                        @foreach ($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>


                </div>
            </div>

            {{-- Table Container --}}
            <div class="flex-grow overflow-x-auto relative">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 z-10 bg-white dark:bg-gray-800 shadow-sm">
                        <tr>
                            {{-- Sticky Driver Column Header --}}
                            <th class="sticky left-0 z-20 bg-white dark:bg-gray-800 p-4 min-w-[220px] border-r border-slate-100 dark:border-gray-700 shadow-[4px_0_12px_-4px_rgba(0,0,0,0.05)] border-b border-slate-200 dark:border-gray-700">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pengemudi</span>
                            </th>

                            {{-- Dates Header --}}
                            @foreach ($dates as $dateString)
                                @php
                                    $date = Carbon\Carbon::parse($dateString);
                                    $dayName = $date->locale('id')->translatedFormat('D');
                                    $isWeekend = $date->isWeekend();
                                    $isToday = $date->isToday();
                                @endphp
                                <th class="p-2 min-w-[150px] text-center border-r border-slate-50 dark:border-gray-700/50 border-b border-slate-200 dark:border-gray-700 last:border-r-0 bg-white dark:bg-gray-800 {{ $isWeekend ? 'bg-slate-50/50 dark:bg-gray-700/30' : '' }}">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-[10px] font-medium text-slate-400 uppercase">{{ $dayName }}</span>
                                        <span @class([
                                            "text-sm font-bold w-8 h-8 flex items-center justify-center rounded-full transition-colors",
                                            "text-red-500 bg-red-50 dark:bg-red-500/10 dark:text-red-400" => $isWeekend,
                                            "text-slate-700 dark:text-gray-300" => !$isWeekend,
                                            "bg-teal-600 !text-white" => $isToday,
                                        ])>
                                            {{ $date->day }}
                                        </span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-gray-700">
                        @forelse ($drivers as $driver)
                            <tr class="group hover:bg-slate-50/80 dark:hover:bg-gray-700/50 transition-colors">
                                {{-- Sticky Driver Column Body --}}
                                <td class="sticky left-0 z-10 bg-white dark:bg-gray-800 group-hover:bg-slate-50/80 dark:group-hover:bg-gray-700/50 p-3 px-4 border-r border-slate-100 dark:border-gray-700 shadow-[4px_0_12px_-4px_rgba(0,0,0,0.05)] transition-colors">
                                    <div class="flex items-center gap-2">
                                        <div class="flex flex-col">
                                            <div class="text-sm font-semibold text-slate-700 dark:text-gray-200 leading-tight whitespace-nowrap overflow-hidden text-ellipsis">{{ $driver['nama_staf'] }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Perjalanan Cells --}}
                                @foreach ($dates as $dateString)
                                    @php
                                        $cellPerjalanans = $perjalanansByDriverAndDate[$driver['staf_id']][$dateString] ?? [];
                                        $isWeekend = Carbon\Carbon::parse($dateString)->isWeekend();
                                    @endphp
                                    <td class="p-1 border-r border-slate-50 dark:border-gray-700/50 last:border-r-0 h-24 relative transition-colors align-top {{ $isWeekend ? 'bg-slate-50/30 dark:bg-gray-900/30' : '' }}">
                                        @forelse ($cellPerjalanans as $perjalanan)
                                            <div
                                                class="absolute top-0.5 bottom-0.5 left-0.5 right-0.5 bg-blue-100/50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-md flex flex-col justify-center cursor-pointer hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-colors p-1 text-xs mb-1"
                                                title="Nomor: {{ $perjalanan['nomor_perjalanan'] }} | {{ $perjalanan['merk_type'] }} ({{ $perjalanan['nopol_kendaraan'] }}) | {{ $perjalanan['kota_kabupaten'] }}"
                                            >
                                                <p class="font-bold text-blue-800 dark:text-blue-200 truncate leading-tight">#{{ $perjalanan['nomor_perjalanan'] }}</p>
                                                <p class="text-blue-700 dark:text-blue-300 truncate leading-tight">{{ $perjalanan['merk_type'] }}</p>
                                                <p class="text-blue-600 dark:text-blue-400 truncate leading-tight">({{ $perjalanan['nopol_kendaraan'] }})</p>
                                                <p class="text-blue-500 dark:text-blue-500 truncate leading-tight">{{ $perjalanan['kota_kabupaten'] }}</p>
                                            </div>
                                        @empty
                                            <div class="text-center text-gray-400 dark:text-gray-600 italic text-xs h-full flex items-center justify-center">
                                                Kosong
                                            </div>
                                        @endforelse
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($dates) + 1 }}" class="text-center p-12 text-gray-500 dark:text-gray-400">
                                    <x-heroicon-o-inbox class="w-12 h-12 mx-auto mb-4"/>
                                    Tidak ada perjalanan yang ditemukan untuk filter ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="bg-slate-50 dark:bg-gray-800/50 p-3 border-t border-slate-200 dark:border-gray-700 text-xs text-slate-500 dark:text-gray-400 flex justify-between items-center">
                <span>Menampilkan {{ count($drivers) }} pengemudi dengan perjalanan</span>
            </div>
        </div>
    </div>
</div>
