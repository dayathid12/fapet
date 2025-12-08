@php
    $record = $getRecord();
    $detail = $record->details->first(); // Get the first detail record
    $originalStatus = $record->status_perjalanan;
    $waktuKepulangan = $record->waktu_kepulangan ? \Carbon\Carbon::parse($record->waktu_kepulangan) : null;

    $effectiveStatus = $originalStatus;
    if ($originalStatus === 'Terjadwal' && $waktuKepulangan && $waktuKepulangan->isPast()) {
        $effectiveStatus = 'Selesai';
    }

    $start = \Carbon\Carbon::parse($record->waktu_keberangkatan);
    $end = $waktuKepulangan;

    // Status Badge
    $badgeClasses = match ($effectiveStatus) {
        'Terjadwal' => 'bg-primary-500 text-white',
        'Menunggu Persetujuan' => 'bg-yellow-500 text-white animate-pulse',
        'Ditolak' => 'bg-danger-500 text-white',
        'Selesai' => 'bg-success-500 text-white',
        default => 'bg-gray-400 text-white',
    };
    $iconSvg = match ($effectiveStatus) {
        'Terjadwal' => 'heroicon-o-check-circle',
        'Selesai' => 'heroicon-o-check-badge',
        'Menunggu Persetujuan' => 'heroicon-o-clock',
        'Ditolak' => 'heroicon-o-x-circle',
        default => 'heroicon-o-question-mark-circle',
    };

    // Duration
    if ($end) {
        $diffInDays = $start->diffInDays($end);
        $totalDays = $diffInDays + 1;
        $nights = $diffInDays;
        $duration = ($nights <= 0) ? '1 Hari' : "{$totalDays} Hari {$nights} Malam";
        $endString = $end->translatedFormat('d M Y, H:i');
    } else {
        $duration = 'Belum ditentukan';
        $endString = 'Belum ditentukan';
    }

    $pengemudi = $detail?->pengemudi;
    $asisten = $detail?->asisten;
@endphp

<div class="w-full p-6 bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-800 hover:shadow-xl transition-all duration-300 ease-in-out transform hover:scale-[1.005] space-y-4">
    {{-- HEADER --}}
    <div class="flex flex-wrap items-center justify-between gap-2 pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 text-sm font-bold text-gray-800 dark:text-gray-200">
                <x-filament::icon icon="heroicon-o-identification" class="w-5 h-5 text-gray-400" />
                <span>{{ $record->nomor_perjalanan ?? "ID: {$record->id}" }}</span>
            </div>
            <span
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold {{ $badgeClasses }}">
                <x-filament::icon :icon="$iconSvg" class="w-4 h-4" />
                {{ $effectiveStatus }}
            </span>
        </div>
        <div class="text-sm text-gray-600 dark:text-gray-400">
            Pembaruan: {{ $record->updated_at->diffForHumans() }}
        </div>
    </div>

    {{-- Combined Card --}}
    <div class="p-4 border border-gray-200 rounded-xl dark:border-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Kolom 1: Jadwal & Rute --}}
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <x-filament::icon icon="heroicon-o-calendar-days" class="w-6 h-6 text-gray-500 dark:text-gray-400" />
                    <h3 class="text-xl font-extrabold text-gray-800 dark:text-gray-200">Jadwal & Rute</h3>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex items-start gap-3">
                        <div class="p-2 rounded-full bg-success-50 dark:bg-success-900/20">
                            <x-filament::icon icon="heroicon-m-arrow-long-right" class="w-6 h-6 text-success-600 dark:text-success-400" />
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 dark:text-gray-200">{{ $start->translatedFormat('d M Y, H:i') }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $record->lokasi_keberangkatan }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="p-2 rounded-full bg-danger-50 dark:bg-danger-900/20">
                            <x-filament::icon icon="heroicon-m-arrow-long-left" class="w-6 h-6 text-danger-600 dark:text-danger-400" />
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 dark:text-gray-200">{{ $endString }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $record->wilayah?->nama_wilayah ?? 'Tujuan' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="p-2 rounded-full bg-primary-50 dark:bg-primary-900/20">
                            <x-filament::icon icon="heroicon-o-clock" class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 dark:text-gray-200">{{ $duration }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Durasi Perjalanan</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom 2: Detail Pengguna & Kegiatan --}}
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <x-filament::icon icon="heroicon-o-user-group" class="w-6 h-6 text-gray-500 dark:text-gray-400" />
                    <h3 class="text-xl font-extrabold text-gray-800 dark:text-gray-200">Pengguna & Kegiatan</h3>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex items-start gap-3">
                        <div class="p-2 rounded-full bg-indigo-50 dark:bg-indigo-900/20">
                            <x-filament::icon icon="heroicon-s-user" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 dark:text-gray-200">{{ $record->nama_pengguna }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">PIC</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="p-2 rounded-full bg-indigo-50 dark:bg-indigo-900/20">
                            <x-filament::icon icon="heroicon-s-building-office-2" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 dark:text-gray-200">{{ $record->unitKerja?->nama_unit_kerja ?? '-' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Unit Kerja</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="p-2 rounded-full bg-indigo-50 dark:bg-indigo-900/20">
                            <x-filament::icon icon="heroicon-s-clipboard-document-list" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 dark:text-gray-200">{{ $record->nama_kegiatan }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Jenis Kegiatan</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom 3: Kendaraan & Tim Bertugas --}}
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <x-filament::icon icon="heroicon-o-truck" class="w-6 h-6 text-gray-500 dark:text-gray-400" />
                    <h3 class="text-xl font-extrabold text-gray-800 dark:text-gray-200">Kendaraan & Tim</h3>
                </div>
                <div class="space-y-3 text-sm">
                    {{-- Kendaraan --}}
                    <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 mb-3">
                        <div class="p-2 rounded-full bg-primary-50 dark:bg-primary-900/20">
                            <x-filament::icon icon="heroicon-o-truck" class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                        </div>
                        <div>
                            <p class="font-bold text-lg text-primary-600 dark:text-primary-400">{{ $detail?->kendaraan?->nopol_kendaraan ?? 'Belum Ditentukan' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $detail?->kendaraan?->merk_type ?? '-' }}</p>
                        </div>
                    </div>
                    {{-- Tim --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-3 p-2 rounded-lg bg-gray-50 dark:bg-gray-800 shadow-sm hover:shadow-md transition-shadow duration-200 ease-in-out">
                            @if ($pengemudi)
                                <div class="flex-shrink-0 w-9 h-9 font-bold text-white bg-blue-500 rounded-full flex items-center justify-center text-lg">
                                    {{ strtoupper(substr($pengemudi->nama_staf, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $pengemudi->nama_staf }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Pengemudi</p>
                                </div>
                            @else
                                <div class="flex-shrink-0 w-9 h-9 flex items-center justify-center text-gray-400 dark:text-gray-600">
                                    <x-filament::icon icon="heroicon-o-user-minus" class="w-7 h-7" />
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Pengemudi belum ditugaskan.</p>
                                </div>
                            @endif
                        </div>
                        @if ($asisten)
                            <div class="flex items-center gap-3 p-2 rounded-lg bg-gray-50 dark:bg-gray-800 shadow-sm hover:shadow-md transition-shadow duration-200 ease-in-out">
                                <div class="flex-shrink-0 w-9 h-9 font-bold text-white bg-orange-500 rounded-full flex items-center justify-center text-lg">
                                    {{ strtoupper(substr($asisten->nama_staf, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $asisten->nama_staf }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Asisten</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
