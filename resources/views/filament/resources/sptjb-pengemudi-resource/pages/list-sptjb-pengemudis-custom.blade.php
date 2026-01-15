@props([
    'records' => [],
])

<div class="space-y-4">
    @if (count($records) === 0)
        <div class="flex items-center justify-center p-8 text-gray-500 dark:text-gray-400">
            <span>Tidak ada data SPTJB yang tersedia.</span>
        </div>
    @else
        @foreach ($records as $record)
            @php
                $isSelesai = $record->status === 'SELESAI';
                $isAjukan = $record->status === 'AJUKAN';

                // =================================================================
                // PALET WARNA STATUS-DRIVEN (SESUAI PERMINTAAN)
                // =================================================================
                $colors = [
                    'icon_name' => $isSelesai ? 'check-circle' : 'clock',
                    'badgeBg' => $isSelesai ? 'bg-emerald-500' : 'bg-amber-500',
                    'badgeText' => 'text-white',
                    'border' => $isSelesai ? 'border-emerald-200' : 'border-amber-200',
                    'borderHover' => $isSelesai ? 'hover:border-emerald-400' : 'hover:border-amber-400',
                    'textHeader' => $isSelesai ? 'text-emerald-700' : 'text-amber-700',
                    'textSubtle' => $isSelesai ? 'text-emerald-600/70' : 'text-amber-600/70',
                    'textNormal' => $isSelesai ? 'text-emerald-800' : 'text-amber-800',
                    'lightBgContainer' => $isSelesai ? 'bg-emerald-50' : 'bg-amber-50',

                    'dark' => [
                        'badgeBg' => $isSelesai ? 'bg-emerald-600' : 'bg-amber-600',
                        'border' => $isSelesai ? 'border-emerald-900' : 'border-amber-900',
                        'borderHover' => $isSelesai ? 'hover:border-emerald-700' : 'hover:border-amber-700',
                        'textHeader' => $isSelesai ? 'text-emerald-400' : 'text-amber-400',
                        'textSubtle' => $isSelesai ? 'text-emerald-400/60' : 'text-amber-400/60',
                        'textNormal' => $isSelesai ? 'text-emerald-300' : 'text-amber-300',
                        'lightBgContainer' => $isSelesai ? 'bg-emerald-950/50' : 'bg-amber-950/50',
                    ],
                ];

                // Data convenience
                $perjalanan = $record->perjalanan;
                $pengemudi = $perjalanan->pengemudi;
                $asisten = $perjalanan->asisten;
            @endphp

            {{-- Card Wrapper --}}
            <div
                class="
                    flex overflow-hidden rounded-xl border-2 bg-white
                    {{ $colors['border'] }} {{ $colors['borderHover'] }}
                    dark:bg-gray-800/50 dark:{{ $colors['dark']['border'] }} dark:{{ $colors['dark']['borderHover'] }}
                    transition-all duration-200 hover:scale-[1.005] cursor-pointer
                "
            >
                {{-- 1. Status Sidebar (Desktop Only) --}}
                <div class="hidden lg:flex flex-shrink-0 items-center justify-center w-16 {{ $colors['badgeBg'] }} dark:{{ $colors['dark']['badgeBg'] }}">
                    @svg('lucide::' . $colors['icon_name'], 'w-8 h-8 text-white stroke-[3]')
                </div>

                {{-- Card Body --}}
                <div class="flex-grow p-3 sm:p-4">
                    {{-- 2. Body Grid (Mobile: 1-col, Desktop: 3-col) --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-5">

                        {{-- Kolom 1: Identitas --}}
                        <div class="flex flex-col space-y-2 lg:border-r lg:pr-5 {{ $colors['border'] }} dark:{{ $colors['dark']['border'] }}">
                            <div class="flex items-center gap-2">
                                @svg('lucide::hash', 'w-3.5 h-3.5 ' . $colors['textHeader'] . ' dark:' . $colors['dark']['textHeader'])
                                <span class="font-bold text-xs uppercase {{ $colors['textHeader'] }} dark:{{ $colors['dark']['textHeader'] }}">
                                    {{ $perjalanan->nomor_perjalanan ?? 'NO-REF' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                @svg('lucide::file-text', 'w-3.5 h-3.5 ' . $colors['textSubtle'] . ' dark:' . $colors['dark']['textSubtle'])
                                <span class="font-medium text-[11px] {{ $colors['textSubtle'] }} dark:{{ $colors['dark']['textSubtle'] }}">
                                    {{ $record->suratTugas?->nomor_surat_tugas ?? 'Tidak Ada Surat Tugas' }}
                                </span>
                            </div>
                        </div>

                        {{-- Kolom 2: Operasional --}}
                        <div class="flex flex-col justify-between space-y-3">
                             <div class="space-y-1.5">
                                 <div class="flex items-center gap-2">
                                                                         @svg('lucide::user', 'w-3 h-3 ' . $colors['textNormal'] . ' dark:' . $colors['dark']['textNormal'])                                    <span class="font-bold text-[10px] uppercase tracking-wider {{ $colors['textNormal'] }} dark:{{ $colors['dark']['textNormal'] }}">
                                        {{ $pengemudi->nama ?? 'N/A' }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                                                         @svg('lucide::users', 'w-3 h-3 opacity-70 ' . $colors['textNormal'] . ' dark:' . $colors['dark']['textNormal'])                                    <span class="font-medium text-[10px] uppercase opacity-70 {{ $colors['textNormal'] }} dark:{{ $colors['dark']['textNormal'] }}">
                                        {{ $asisten->nama ?? 'Tanpa Asisten' }}
                                    </span>
                                </div>
                             </div>
                             <div class="flex items-center gap-3">
                                 <span class="px-2 py-0.5 rounded-md text-xs font-mono {{ $colors['badgeBg'] }} {{ $colors['badgeText'] }} dark:{{ $colors['dark']['badgeBg'] }}">
                                     {{ $perjalanan->kendaraan->nopol ?? 'N/A' }}
                                 </span>
                                 <div class="flex items-center gap-1.5">
                                                                         @svg('lucide::briefcase', 'w-3 h-3 ' . $colors['textSubtle'] . ' dark:' . $colors['dark']['textSubtle'])                                    <span class="font-medium text-[11px] {{ $colors['textSubtle'] }} dark:{{ $colors['dark']['textSubtle'] }}">
                                        {{ $perjalanan->jenis_kegiatan ?? 'Lainnya' }}
                                    </span>
                                 </div>
                             </div>
                        </div>

                        {{-- Kolom 3: Logistik / Waktu --}}
                        <div class="relative h-full rounded-lg p-3 {{ $colors['lightBgContainer'] }} dark:{{ $colors['dark']['lightBgContainer'] }}">
                            <span class="absolute top-1 left-1.5 px-1.5 py-0.5 rounded font-black text-[9px] uppercase {{ $colors['badgeBg'] }} {{ $colors['badgeText'] }} dark:{{ $colors['dark']['badgeBg'] }}">
                                {{ $perjalanan->tipe_perjalanan ?? 'DINAS' }}
                            </span>
                             <div class="flex flex-col justify-center h-full space-y-2 pt-4">
                                <div class="flex items-center gap-2">
                                    @svg('lucide::calendar', 'w-3.5 h-3.5 ' . $colors['textNormal'] . ' dark:' . $colors['dark']['textNormal'])
                                    <span class="font-semibold text-xs {{ $colors['textNormal'] }} dark:{{ $colors['dark']['textNormal'] }}">
                                       {{ \Carbon\Carbon::parse($perjalanan->waktu_berangkat)->isoFormat('D MMM Y') }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    @svg('lucide::map-pin', 'w-3.5 h-3.5 ' . $colors['textNormal'] . ' dark:' . $colors['dark']['textNormal'])
                                    <span class="font-semibold text-xs {{ $colors['textNormal'] }} dark:{{ $colors['dark']['textNormal'] }}">
                                        {{ \Carbon\Carbon::parse($perjalanan->waktu_pulang)->isoFormat('D MMM Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. Action Indicator (Desktop Only) --}}
                <div class="hidden lg:flex flex-shrink-0 items-center justify-center px-2 text-gray-300 dark:text-gray-600">
                    @svg('lucide::chevron-right', 'w-5 h-5')
                </div>
            </div>
        @endforeach
    @endif
</div>
