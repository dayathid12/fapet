@php
    $record = $getRecord();
    $status = $record->status_perjalanan;
    $waktuKepulangan = $record->waktu_kepulangan ? \Carbon\Carbon::parse($record->waktu_kepulangan) : null;

    $effectiveStatus = $status;
    if ($status === 'Terjadwal' && $waktuKepulangan && $waktuKepulangan->isPast()) {
        $effectiveStatus = 'Selesai';
    }

    // Enhanced color schemes with gradients and animations
    $statusConfig = match ($effectiveStatus) {
        'Terjadwal' => [
            'indicator' => 'bg-gradient-to-b from-emerald-400 via-green-500 to-emerald-600 shadow-emerald-500/30',
            'badge' => 'bg-emerald-50 border-emerald-200 text-emerald-700 shadow-emerald-100/50',
            'icon' => 'fa-calendar-check',
            'glow' => 'shadow-emerald-500/20'
        ],
        'Menunggu Persetujuan' => [
            'indicator' => 'bg-gradient-to-b from-amber-400 via-yellow-500 to-orange-500 shadow-amber-500/30',
            'badge' => 'bg-amber-50 border-amber-200 text-amber-700 shadow-amber-100/50',
            'icon' => 'fa-clock',
            'glow' => 'shadow-amber-500/20 animate-pulse'
        ],
        'Ditolak' => [
            'indicator' => 'bg-gradient-to-b from-red-400 via-rose-500 to-pink-600 shadow-red-500/30',
            'badge' => 'bg-red-50 border-red-200 text-red-700 shadow-red-100/50',
            'icon' => 'fa-times-circle',
            'glow' => 'shadow-red-500/20'
        ],
        'Selesai' => [
            'indicator' => 'bg-gradient-to-b from-blue-400 via-indigo-500 to-purple-600 shadow-blue-500/30',
            'badge' => 'bg-blue-50 border-blue-200 text-blue-700 shadow-blue-100/50',
            'icon' => 'fa-check-circle',
            'glow' => 'shadow-blue-500/20'
        ],
        default => [
            'indicator' => 'bg-gradient-to-b from-slate-400 to-slate-500 shadow-slate-500/30',
            'badge' => 'bg-slate-50 border-slate-200 text-slate-700 shadow-slate-100/50',
            'icon' => 'fa-question-circle',
            'glow' => 'shadow-slate-500/20'
        ],
    };

    $start = \Carbon\Carbon::parse($record->waktu_keberangkatan);
    $end = $waktuKepulangan;

    if ($end) {
        $arrivalTime = $end->format('H:i');
        $duration = $start->diffInHours($end) . ' jam';
    } else {
        $arrivalTime = 'TBD';
        $duration = 'TBD';
    }

    // Get vehicle and driver info
    $detail = $record->details->first();
    $vehicle = $detail?->kendaraan;
    $driver = $detail?->pengemudi;
@endphp

<div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 ease-out border border-slate-200/60 mb-6 overflow-hidden {{ $statusConfig['glow'] }}">

    <!-- Animated Background Gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-white via-slate-50/30 to-white opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

    <!-- Color Indicator with Glow -->
    <div class="absolute left-0 top-8 bottom-8 w-2 rounded-r-xl {{ $statusConfig['indicator'] }} shadow-lg"></div>

    <div class="relative flex flex-col lg:flex-row items-stretch min-h-[180px]">

        <!-- SECTION 1: Date & Status -->
        <div class="w-full lg:w-56 p-8 flex flex-col justify-center bg-gradient-to-br from-slate-50/80 to-white border-r border-slate-100/80 relative overflow-hidden">
            <!-- Decorative Background Pattern -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-slate-100/20 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-slate-100/10 to-transparent rounded-full translate-y-12 -translate-x-12"></div>

            <!-- Status Badge -->
            <div class="relative z-10 mb-4">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full {{ $statusConfig['badge'] }} text-xs font-bold uppercase border-2 backdrop-blur-sm">
                    <i class="fas {{ $statusConfig['icon'] }} w-3.5 h-3.5"></i>
                    @if($effectiveStatus === 'Menunggu Persetujuan')
                        Menunggu Persetujuan
                    @else
                        {{ $effectiveStatus }}
                    @endif
                </span>
            </div>

            <!-- Date Display -->
            <div class="relative z-10">
                <div class="text-5xl font-black text-slate-800 tracking-tighter mb-1">
                    {{ $start->format('d') }}
                </div>
                <div class="text-lg font-bold text-slate-600 uppercase tracking-wide mb-1">
                    {{ $start->translatedFormat('M') }}
                </div>
                <div class="text-sm font-medium text-slate-500">
                    {{ $start->format('Y') }}
                </div>
                <div class="mt-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                    {{ $start->format('l') }}
                </div>
            </div>
        </div>

        <!-- SECTION 2: Route Visualization -->
        <div class="flex-1 p-8 flex flex-col justify-center relative bg-white">
            <!-- Route Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-route text-slate-400 w-4 h-4"></i>
                    <span class="text-sm font-bold text-slate-600 uppercase tracking-wide">Rute Perjalanan</span>
                </div>
                <div class="text-xs text-slate-500 font-medium">
                    Durasi: {{ $duration }} • {{ $record->jumlah_rombongan ?? 1 }} orang
                </div>
            </div>

            <!-- Route Visualization -->
            <div class="flex items-center justify-between relative">
                <!-- Animated Dashed Line -->
                <div class="absolute left-12 right-12 top-1/2 -translate-y-1/2">
                    <div class="border-t-2 border-dashed border-slate-300 relative">
                        <div class="absolute inset-0 border-t-2 border-dashed border-indigo-400 animate-pulse opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>
                </div>

                <!-- Origin Point -->
                <div class="flex-1 text-left relative z-10">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-3 h-3 rounded-full bg-emerald-500 shadow-emerald-500/50 shadow-lg"></div>
                        <span class="text-xs font-bold text-emerald-600 uppercase">Keberangkatan</span>
                    </div>
                    <div class="text-lg font-bold text-slate-800 mb-1">
                        {{ Str::limit($record->lokasi_keberangkatan, 25) }}
                    </div>
                    <div class="text-sm font-semibold text-slate-500">
                        {{ $start->format('H:i') }}
                    </div>
                </div>

                <!-- Animated Arrow -->
                <div class="mx-4 relative z-20">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-xl transform group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-plane text-sm animate-bounce"></i>
                    </div>
                    <div class="absolute inset-0 rounded-full bg-indigo-400/20 animate-ping"></div>
                </div>

                <!-- Destination Point -->
                <div class="flex-1 text-right relative z-10">
                    <div class="flex items-center justify-end gap-2 mb-2">
                        <span class="text-xs font-bold text-blue-600 uppercase">Tujuan</span>
                        <div class="w-3 h-3 rounded-full bg-blue-500 shadow-blue-500/50 shadow-lg"></div>
                    </div>
                    <div class="text-lg font-bold text-slate-800 mb-1">
                        {{ Str::limit($record->wilayah?->nama_wilayah ?? 'Tujuan', 25) }}
                    </div>
                    <div class="text-sm font-semibold text-slate-500">
                        Est. {{ $arrivalTime }}
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: User & Vehicle Info -->
        <div class="w-full lg:w-80 p-6 bg-gradient-to-br from-slate-50 to-slate-100/50 border-l border-slate-200/60 flex flex-col justify-between relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-0 left-0 w-20 h-20 bg-gradient-to-br from-indigo-100/30 to-transparent rounded-full -translate-x-10 -translate-y-10"></div>
            <div class="absolute bottom-0 right-0 w-16 h-16 bg-gradient-to-tl from-purple-100/20 to-transparent rounded-full translate-x-8 translate-y-8"></div>

            <!-- User Info -->
            <div class="relative z-10 mb-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                        {{ strtoupper(substr($record->nama_pengguna, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-800">{{ Str::limit($record->nama_pengguna, 20) }}</div>
                        <div class="text-xs text-slate-600 font-medium">{{ $record->unitKerja?->nama_unit_kerja ?? 'Unit Kerja' }}</div>
                    </div>
                </div>

                <!-- Vehicle Info -->
                @if($vehicle)
                <div class="flex items-center gap-2 p-2 bg-white/60 rounded-lg border border-slate-200/50">
                    <i class="fas fa-car text-slate-500 w-4 h-4"></i>
                    <div class="flex-1">
                        <div class="text-xs font-bold text-slate-700">{{ $vehicle->nopol_kendaraan }}</div>
                        <div class="text-xs text-slate-500">{{ $vehicle->merk_kendaraan ?? 'Kendaraan' }}</div>
                    </div>
                </div>
                @endif

                <!-- Driver Info -->
                @if($driver)
                <div class="flex items-center gap-2 p-2 bg-white/60 rounded-lg border border-slate-200/50 mt-2">
                    <i class="fas fa-user-tie text-slate-500 w-4 h-4"></i>
                    <div class="flex-1">
                        <div class="text-xs font-bold text-slate-700">{{ Str::limit($driver->nama_staf, 18) }}</div>
                        <div class="text-xs text-slate-500">Pengemudi</div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="relative z-10 flex gap-3">
                <a href="{{ \App\Filament\Resources\PerjalananResource::getUrl('view', ['record' => $record]) }}"
                   class="flex-1 text-center text-sm font-bold text-indigo-600 bg-white/80 hover:bg-indigo-50 px-4 py-2.5 rounded-xl border border-indigo-200 hover:border-indigo-300 transition-all duration-200 shadow-sm hover:shadow-md backdrop-blur-sm">
                   <i class="fas fa-eye mr-1.5"></i>Detail
                </a>
                <a href="{{ \App\Filament\Resources\PerjalananResource::getUrl('edit', ['record' => $record]) }}"
                   class="flex-1 text-center text-sm font-bold text-amber-600 bg-white/80 hover:bg-amber-50 px-4 py-2.5 rounded-xl border border-amber-200 hover:border-amber-300 transition-all duration-200 shadow-sm hover:shadow-md backdrop-blur-sm">
                   <i class="fas fa-edit mr-1.5"></i>Edit
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Accent Line -->
    <div class="h-1 bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
</div>
