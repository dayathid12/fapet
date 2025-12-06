@php
    $record = $getRecord();
    $status = $record->status_perjalanan;
    $start = \Carbon\Carbon::parse($record->waktu_keberangkatan);
    $end = $record->waktu_kepulangan ? \Carbon\Carbon::parse($record->waktu_kepulangan) : null;

    // Status Badge
    $badgeClasses = match ($status) {
        'Terjadwal' => 'bg-primary-500 text-white',
        'Menunggu Persetujuan' => 'bg-yellow-500 text-white animate-pulse',
        'Ditolak' => 'bg-danger-500 text-white',
        'Selesai' => 'bg-success-500 text-white',
        default => 'bg-gray-400 text-white',
    };
    $iconSvg = match ($status) {
        'Terjadwal', 'Selesai' => 'heroicon-o-check-circle',
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
@endphp

<div class="w-full p-4 space-y-4">
    {{-- HEADER --}}
    <div class="flex flex-wrap items-center justify-between gap-2">
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 text-sm font-bold text-gray-800 dark:text-gray-200">
                <x-filament::icon icon="heroicon-o-identification" class="w-5 h-5 text-gray-400" />
                <span>{{ $record->nomor_perjalanan ?? "ID: {$record->id}" }}</span>
            </div>
            <span
                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeClasses }}">
                <x-filament::icon :icon="$iconSvg" class="w-4 h-4" />
                {{ $status }}
            </span>
        </div>
        <div class="text-xs text-gray-500 dark:text-gray-400">
            Pembaruan: {{ $record->updated_at->diffForHumans() }}
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
        {{-- Main Info --}}
        <div class="space-y-4 md:col-span-8">
            {{-- Jadwal & Rute --}}
            <div class="p-4 border border-gray-200 rounded-xl dark:border-gray-700">
                <div class="flex items-center gap-3 mb-2">
                    <x-filament::icon icon="heroicon-o-calendar-days"
                        class="w-6 h-6 text-gray-500 dark:text-gray-400" />
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Jadwal & Rute</h3>
                </div>
                <div class="flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <x-filament::icon icon="heroicon-m-arrow-long-right"
                            class="w-5 h-5 text-success-500" />
                        <div>
                            <p class="font-semibold text-gray-700 dark:text-gray-300">
                                {{ $start->translatedFormat('d M Y, H:i') }}</p>
                            <p class="text-xs text-gray-500">{{ $record->lokasi_keberangkatan }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-filament::icon icon="heroicon-m-arrow-long-left" class="w-5 h-5 text-danger-500" />
                        <div>
                            <p class="font-semibold text-gray-700 dark:text-gray-300">{{ $endString }}</p>
                            <p class="text-xs text-gray-500">{{ $record->wilayah?->nama_wilayah ?? 'Tujuan' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-filament::icon icon="heroicon-o-clock" class="w-5 h-5 text-primary-500" />
                        <div>
                            <p class="font-semibold text-gray-700 dark:text-gray-300">{{ $duration }}</p>
                            <p class="text-xs text-gray-500">Durasi Perjalanan</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Kegiatan --}}
            <div class="p-4 border border-gray-200 rounded-xl dark:border-gray-700">
                <div class="flex items-center gap-3 mb-2">
                    <x-filament::icon icon="heroicon-o-user-group"
                        class="w-6 h-6 text-gray-500 dark:text-gray-400" />
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Detail Pengguna & Kegiatan</h3>
                </div>
                <div class="flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <x-filament::icon icon="heroicon-s-user" class="w-5 h-5 text-indigo-500" />
                        <div>
                            <p class="font-semibold text-gray-700 dark:text-gray-300">{{ $record->nama_pengguna }}
                            </p>
                            <p class="text-xs text-gray-500">PIC</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-filament::icon icon="heroicon-s-building-office-2" class="w-5 h-5 text-indigo-500" />
                        <div>
                            <p class="font-semibold text-gray-700 dark:text-gray-300">
                                {{ $record->unitKerja?->nama_unit_kerja ?? '-' }}</p>
                            <p class="text-xs text-gray-500">Unit Kerja</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-filament::icon icon="heroicon-s-clipboard-document-list"
                            class="w-5 h-5 text-indigo-500" />
                        <div>
                            <p class="font-semibold text-gray-700 dark:text-gray-300">
                                {{ $record->nama_kegiatan }}</p>
                            <p class="text-xs text-gray-500">Jenis Kegiatan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Side Info --}}
        <div class="space-y-4 md:col-span-4">
            {{-- Kendaraan --}}
            <div class="p-4 bg-gray-100 border border-gray-200 rounded-xl dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center gap-3 mb-2">
                    <x-filament::icon icon="heroicon-o-truck" class="w-6 h-6 text-primary-500" />
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Kendaraan</h3>
                </div>
                <div class="text-sm">
                    <p class="font-mono text-lg font-bold text-primary-600 dark:text-primary-400">
                        {{ $record->nopol_kendaraan ?? 'Belum Ditentukan' }}</p>
                    <p class="text-gray-600 dark:text-gray-300">{{ $record->kendaraan?->merk_type ?? '-' }}</p>
                </div>
            </div>

            {{-- Tim --}}
            <div class="p-4 border border-gray-200 rounded-xl dark:border-gray-700">
                <div class="flex items-center gap-3 mb-2">
                    <x-filament::icon icon="heroicon-o-user-circle"
                        class="w-6 h-6 text-gray-500 dark:text-gray-400" />
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Tim Bertugas</h3>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        @if ($record->pengemudi)
                            <div
                                class="flex items-center justify-center w-8 h-8 font-bold text-white bg-blue-500 rounded-full">
                                {{ strtoupper(substr($record->pengemudi->nama_staf, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-700 dark:text-gray-300">
                                    {{ $record->pengemudi->nama_staf }}</p>
                                <p class="text-xs text-gray-500">Pengemudi</p>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Pengemudi belum ditugaskan.</p>
                        @endif
                    </div>
                    @if ($record->asisten)
                        <div class="flex items-center gap-2">
                            <div
                                class="flex items-center justify-center w-8 h-8 font-bold text-white bg-orange-500 rounded-full">
                                {{ strtoupper(substr($record->asisten->nama_staf, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-700 dark:text-gray-300">
                                    {{ $record->asisten->nama_staf }}</p>
                                <p class="text-xs text-gray-500">Asisten</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
