@php
    $record = $getRecord();

    // --- 1. DATA MAPPING & FORMATTING ---
    $departureTime = \Carbon\Carbon::parse($record->waktu_keberangkatan);
    $returnTime    = \Carbon\Carbon::parse($record->waktu_kepulangan);

    $day   = $departureTime->format('d');
    $month = strtoupper($departureTime->translatedFormat('M')); // e.g., DES
    $startTime = $departureTime->format('H:i');
    $returnTimeDisplay = $returnTime->format('H:i');

    $returnDay = $returnTime->format('d');
    $returnMonth = strtoupper($returnTime->translatedFormat('M'));
    
    // Calculate last updated time difference
    $updatedAtDiff = null;
    if ($record->updated_at) {
        $currentLocale = \Carbon\Carbon::getLocale();
        \Carbon\Carbon::setLocale('id');
        $updatedAtDiff = $record->updated_at->diffForHumans();
        \Carbon\Carbon::setLocale($currentLocale);
    }
    
    // Hitung Durasi
    $diffDays = $departureTime->diffInDays($returnTime);
    $isOvernight = $diffDays >= 1;
    
    $duration = '1 Hari'; 
    if ($isOvernight) {
        $days = $diffDays + 1;
        $nights = $diffDays;
        $duration = "$days Hari $nights Malam";
    }

    // --- 2. RELASI DATA (Driver & Kendaraan) ---
    $detail      = $record->details->first();
    $driverName  = $detail?->pengemudi?->nama_staf;
    $vehicleName = $detail?->kendaraan?->merk_type;
    $plateNumber = $detail?->kendaraan?->nopol_kendaraan;

    // Driver Initials
    $driverInitials = '-';
    if ($driverName) {
        $names = explode(' ', $driverName);
        $driverInitials = collect($names)->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->implode('');
    }

    // --- 3. STATUS COLOR THEME CONFIGURATION ---
    // Mapping status string ke key tema
    $statusKey = match ($record->dynamic_status) {
        'Terjadwal', 'Berjalan', 'Selesai' => 'active',
        'Menunggu Persetujuan' => 'pending',
        'Ditolak', 'Dibatalkan' => 'danger',
        default => 'pending',
    };

    // Konfigurasi warna berdasarkan tema (Soft UI Palette)
    $theme = match ($statusKey) {
        'active' => [
            'glow' => 'from-indigo-300 to-blue-300',
            'stripe' => 'from-indigo-500/10',
            'badge_bg' => 'bg-indigo-50',
            'badge_text' => 'text-indigo-600',
            'badge_ring' => 'ring-indigo-500/10',
            'dot_color' => 'bg-indigo-500',
            'icon_color' => 'text-indigo-600',
            'border_color' => 'border-indigo-500',
        ],
        'pending' => [
            'glow' => 'from-amber-200 to-orange-200',
            'stripe' => 'from-amber-500/10',
            'badge_bg' => 'bg-amber-50',
            'badge_text' => 'text-amber-700',
            'badge_ring' => 'ring-amber-500/20',
            'dot_color' => 'bg-amber-500',
            'icon_color' => 'text-amber-600',
            'border_color' => 'border-amber-500',
        ],
        'danger' => [
            'glow' => 'from-red-200 to-rose-200',
            'stripe' => 'from-red-500/10',
            'badge_bg' => 'bg-red-50',
            'badge_text' => 'text-red-700',
            'badge_ring' => 'ring-red-500/20',
            'dot_color' => 'bg-red-500',
            'icon_color' => 'text-red-600',
            'border_color' => 'border-red-500',
        ],
    };
@endphp

<!-- Main Wrapper -->
<!-- Note: Pastikan font 'Plus Jakarta Sans' atau font sans-serif pilihan Anda sudah dimuat di layout utama aplikasi -->
<div class="w-full font-sans antialiased">
    <div class="relative group transition-all duration-300 hover:-translate-y-1">
        
        {{-- 1. Ambient Glow Effect on Hover --}}
        <div class="absolute -inset-0.5 bg-gradient-to-r {{ $theme['glow'] }} rounded-[2rem] opacity-0 group-hover:opacity-30 blur transition duration-500"></div>

        {{-- 2. Card Container --}}
        <div class="relative bg-white rounded-[1.75rem] shadow-[0_2px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 p-1">
            
            {{-- Decorative Corner Stripe --}}
            <div class="absolute top-0 left-0 w-24 h-24 bg-gradient-to-br {{ $theme['stripe'] }} to-transparent rounded-tl-[1.75rem]"></div>

            {{-- Nomor Perjalanan --}}
            <span class="absolute top-3 left-5 text-xs font-bold text-slate-400 tracking-wider">
                #{{ str_pad($record->nomor_perjalanan, 6, '0', STR_PAD_LEFT) }}
            </span>

            {{-- Last Updated Text --}}
            @if($updatedAtDiff)
                <span class="absolute top-3 right-5 text-[10px] font-medium text-slate-400">
                    Terakhir diperbarui: {{ $updatedAtDiff }}
                </span>
            @endif

            <div class="flex flex-col lg:flex-row items-stretch p-5 lg:p-7 gap-6 lg:gap-8">

                {{-- SECTION A: DATE, TIME & STATUS --}}
                <div class="flex flex-col gap-5 lg:w-[32%] shrink-0 relative">
                    
                    {{-- Status Badge --}}
                    <div class="flex items-center gap-3">
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold uppercase tracking-wide {{ $theme['badge_bg'] }} {{ $theme['badge_text'] }} ring-1 {{ $theme['badge_ring'] }} whitespace-nowrap">                            <span class="relative flex h-2 w-2">
                                @if($statusKey === 'active')
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $theme['dot_color'] }}"></span>
                                @endif
                                <span class="relative inline-flex rounded-full h-2 w-2 {{ $theme['dot_color'] }}"></span>
                            </span>
                            {{ $record->dynamic_status ?? 'Draft' }}
                        </span>
                        
                        {{-- Nama Kegiatan (Truncated) --}}
                        <span class="text-xs font-semibold text-slate-500 whitespace-nowrap" title="{{ $record->nama_kegiatan }}">
                            {{ $record->nama_kegiatan ?? '-' }}
                        </span>
                    </div>

                    <div class="flex gap-5">
                        {{-- Calendar Box --}}
                        <div class="flex flex-col items-center justify-center w-[76px] h-[84px] bg-slate-50 rounded-2xl border border-slate-100 shadow-sm shrink-0">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ $month }}</span>
                            <span class="text-3xl font-extrabold text-slate-800 -mt-1">{{ $day }}</span>
                        </div>

                        {{-- Timeline Flow --}}
                        <div class="flex flex-col justify-between py-1 relative w-full">
                            {{-- Connector Line --}}
                            <div class="absolute left-[7px] top-3 bottom-3 w-0.5 bg-gradient-to-b from-indigo-200 via-slate-200 to-indigo-200 rounded-full"></div>

                            {{-- Departure --}}
                            <div class="relative pl-6">
                                <div class="absolute left-0 top-1.5 w-4 h-4 rounded-full bg-white border-[3px] {{ $theme['border_color'] }} z-10 shadow-sm"></div>
                                <div class="flex flex-col">
                                    <span class="text-lg font-bold text-slate-800 leading-none">{{ $startTime }}</span>
                                    <span class="text-[10px] font-medium text-slate-400 mt-0.5 uppercase tracking-wide">Berangkat</span>
                                </div>
                            </div>

                            {{-- Duration Pill --}}
                            <div class="relative pl-6 my-2">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border border-indigo-100 bg-indigo-50/50 shadow-sm text-[10px] font-bold text-indigo-600">
                                    {{-- Icon Clock/Time --}}
                                    <svg class="w-3 h-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                    </svg>
                                    {{ $duration }}
                                </div>
                            </div>

                            {{-- Return --}}
                            <div class="relative pl-6">
                                <div class="absolute left-0 top-1.5 w-4 h-4 rounded-full bg-slate-200 border-[3px] border-white ring-1 ring-slate-200 z-10"></div>
                                <div class="flex flex-col">
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-lg font-bold text-slate-600 leading-none">{{ $returnTimeDisplay }}</span>
                                        @if($isOvernight)
                                            <span class="text-[10px] font-semibold text-slate-400">+{{ $diffDays }} Hari</span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] font-medium text-slate-400 mt-0.5 uppercase tracking-wide">Pulang</span>
                                    @if($isOvernight)
                                        <span class="text-[10px] font-semibold text-slate-500 mt-1">{{ $returnDay }} {{ $returnMonth }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="w-full h-px lg:w-px lg:h-auto bg-slate-100"></div>

                {{-- SECTION B: ROUTE (Origin -> Destination) --}}
                <div class="flex flex-col justify-center gap-6 lg:w-[38%] py-2 relative">
                                        {{-- Dotted Line --}}
                                        <div class="absolute left-[9px] top-4 bottom-10 w-0.5 border-l-2 border-dashed border-slate-200"></div>
                    
                                        {{-- Origin --}}
                                        <div class="relative pl-8 group/loc">
                                            <div class="absolute left-0 top-1 w-5 h-5 rounded-full bg-indigo-50 flex items-center justify-center z-10 group-hover/loc:scale-110 transition-transform">
                                                <div class="w-2 h-2 rounded-full bg-indigo-500"></div>
                                            </div>
                                            <h4 class="text-sm font-semibold text-slate-900 leading-tight">
                                                {{ $record->lokasi_keberangkatan ?? 'Lokasi Awal' }}
                                            </h4>
                                            <p class="text-[11px] text-slate-400 mt-0.5">Asal Keberangkatan</p>
                                        </div>
                    
                                        {{-- Direction Arrow --}}
                    <div class="relative pl-8">
                        <div class="p-1 rounded bg-slate-50 w-fit text-slate-300">
                            {{-- Icon Arrow Down --}}
                            <svg class="w-3 h-3 rotate-90 lg:rotate-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        </div>
                    </div>

                    {{-- Destination --}}
                    <div class="relative pl-8 group/loc">
                        <div class="absolute left-0 top-1 w-5 h-5 rounded-full {{ $theme['badge_bg'] }} flex items-center justify-center z-10 shadow-sm shadow-indigo-200 group-hover/loc:scale-110 transition-transform">
                            {{-- Icon Map Pin --}}
                            <svg class="w-3 h-3 {{ $theme['icon_color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-slate-800 tracking-tight leading-tight">
                            {{ $record->wilayah?->nama_wilayah ?? 'Kota/Kabupaten Belum Ada' }}
                        </h4>
                        <p class="text-sm text-slate-500 mt-0.5">
                            {{ $record->alamat_tujuan ?? 'Alamat Tujuan Belum Ada' }}
                        </p>
                    </div>
                </div>

                {{-- SECTION C: RESOURCES (Driver & Vehicle) --}}
                <div class="flex flex-col justify-center gap-3 lg:w-[30%] bg-slate-50/80 rounded-2xl p-4 border border-slate-100/50">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 opacity-70">
                        Assigned Resources
                    </p>

                    {{-- Driver Card --}}
                    <div class="flex items-center gap-3 rounded-xl border transition-all
                        @if($driverName) bg-white p-2.5 border-slate-100 shadow-sm @else p-2 border-transparent opacity-60 @endif">
                        
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-[10px] font-bold shadow-sm shrink-0
                            @if($driverName) bg-slate-800 text-white @else bg-slate-100 text-slate-400 @endif">
                            @if($driverName)
                                {{ $driverInitials }}
                            @else
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            @endif
                        </div>
                        
                        <div class="flex flex-col min-w-0">
                            <p class="text-xs font-bold @if($driverName) text-slate-700 @else text-slate-400 italic @endif">
                                {{ $driverName ?? 'Belum ada driver' }}
                            </p>
                            <p class="text-[10px] text-slate-400">Pengemudi</p>
                        </div>
                    </div>

                    {{-- Vehicle Card --}}
                    <div class="flex items-center gap-3 rounded-xl border transition-all
                        @if($vehicleName) bg-white p-2.5 border-slate-100 shadow-sm @else p-2 border-transparent opacity-60 @endif">
                        
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0
                            @if($vehicleName) bg-indigo-50 text-indigo-600 @else bg-slate-100 text-slate-400 @endif">
                            {{-- Icon Truck/Car --}}
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>

                        <div class="flex flex-col min-w-0">
                            <p class="text-xs font-bold @if($vehicleName) text-slate-700 @else text-slate-400 italic @endif">
                                {{ $vehicleName ?? 'Menunggu Unit' }}
                            </p>
                            @if($plateNumber)
                                <span class="text-[10px] font-mono text-slate-500 bg-slate-100 px-1 rounded w-fit mt-0.5">
                                    {{ $plateNumber }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>