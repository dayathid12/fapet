<x-filament-panels::page class="min-h-screen bg-slate-50/50 font-jakarta antialiased">
    {{-- Inject Styles --}}
    @push('styles')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <style>
            .font-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }
            .timeline-dashed::before {
                content: '';
                position: absolute;
                left: 7px;
                top: 24px;
                bottom: 24px;
                border-left: 2px dashed #e2e8f0;
                z-index: 0;
            }
        </style>
    @endpush

    @php
        $totalTrip = isset($dataRecords) ? $dataRecords->total() : 0;
        $totalJam = isset($totalJam) ? $totalJam : 0;
        $onDutyCount = isset($dataRecords) ? $dataRecords->where('status_perjalanan', 'berangkat')->count() : 0;
    @endphp

    <div class="max-w-7xl mx-auto py-6 pb-20">

        {{-- 1. HEADER & STATS SECTION --}}
        {{-- PERBAIKAN: Ditambahkan 'mb-10' di sini untuk mendorong Filter Bar ke bawah --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="relative overflow-hidden bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 rounded-full bg-blue-50 blur-xl opacity-50"></div>
                <div class="relative flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">TOTAL TRIP</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalTrip }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-blue-100/50 flex items-center justify-center text-blue-600">
                        <i class="fas fa-route"></i>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 rounded-full bg-indigo-50 blur-xl opacity-50"></div>
                <div class="relative flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">TOTAL JAM</p>
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalJam }}<span class="text-sm text-slate-400 font-medium ml-1">Jam</span></h3>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-indigo-100/50 flex items-center justify-center text-indigo-600">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 rounded-full bg-amber-50 blur-xl opacity-50"></div>
                <div class="relative flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">RATING</p>
                        <h3 class="text-3xl font-extrabold text-slate-800 flex items-center gap-1">
                            4.9 <i class="fas fa-star text-amber-400 text-lg"></i>
                        </h3>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-100/50 flex items-center justify-center text-amber-600">
                        <i class="fas fa-award"></i>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 rounded-full bg-emerald-50 blur-xl opacity-50"></div>
                <div class="relative flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">STATUS</p>
                        <h3 class="text-xl font-extrabold text-emerald-600">{{ $userName ?? 'Unknown' }}</h3>
                        <p class="text-xs text-slate-500 mt-1">NIP: {{ $userNip ?? 'N/A' }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-100/50 flex items-center justify-center text-emerald-600">
                        <i class="fas fa-toggle-on"></i>
                    </div>
                </div>
            </div>
        </div>


        {{-- 3. CONTENT LIST SECTION --}}
        <div>
            @if ($dataRecords->count() > 0)
                <div class="flex flex-col gap-6">
                    @foreach ($dataRecords as $record)
                        @php
                            $start = \Carbon\Carbon::parse($record->waktu_keberangkatan);
                            $end = \Carbon\Carbon::parse($record->waktu_kepulangan);
                            $diffInDays = $start->diffInDays($end);
                            $days = $diffInDays + 1;
                            $nights = $diffInDays;
                            $durationText = $nights === 0 ? "$days Hari" : "$days Hari $nights Malam";
                            $totalHours = $start->diffInHours($end);
                            $kendaraan = $record->kendaraan?->first();
                            $statusLabel = match($record->status_perjalanan) {
                                'berangkat' => ['label' => 'ON DUTY', 'class' => 'bg-blue-50 text-blue-600 border-blue-200'],
                                'menunggu' => ['label' => 'STANDBY', 'class' => 'bg-slate-50 text-slate-600 border-slate-200'],
                                'selesai' => ['label' => 'FINISHED', 'class' => 'bg-emerald-50 text-emerald-600 border-emerald-200'],
                                default => ['label' => 'Status Perjalanan', 'class' => 'bg-red-50 text-red-600 border-red-200'],
                            };
                        @endphp

                        <div class="bg-white rounded-2xl shadow-[0_2px_8px_rgba(0,0,0,0.04)] border border-slate-100 hover:shadow-lg transition-all duration-300 p-6">
                            <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
                                {{-- Timeline --}}
                                <div class="lg:w-1/5 flex-shrink-0 flex flex-col justify-between timeline-dashed relative">
                                    <div class="relative z-10 pl-6 mb-8">
                                        <div class="absolute left-0 top-1.5 w-4 h-4 rounded-full border-[3px] border-blue-500 bg-white shadow-sm"></div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">BERANGKAT</span>
                                        <div class="font-extrabold text-slate-800 text-2xl leading-none mb-1">{{ $start->format('H:i') }}</div>
                                        <div class="text-xs text-slate-500 font-medium">{{ $start->translatedFormat('d M Y') }}</div>
                                    </div>

                                    <div class="relative z-10 pl-6 mb-4">
                                        <div class="absolute left-0 top-1.5 w-4 h-4 rounded-full border-[3px] border-slate-300 bg-white shadow-sm"></div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">PULANG</span>
                                        <div class="font-extrabold text-slate-800 text-2xl leading-none mb-1">{{ $end->format('H:i') }}</div>
                                        <div class="text-xs text-slate-500 font-medium">{{ $end->translatedFormat('d M Y') }}</div>
                                    </div>

                                    <div>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 text-[11px] font-bold text-indigo-600">
                                            <i class="fas fa-moon"></i> {{ $durationText }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Route --}}
                                <div class="lg:w-2/5 flex flex-col justify-center gap-8 py-2">
                                    <div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">
                                            <i class="fas fa-circle text-blue-200 text-[8px] mr-1"></i> LOKASI JEMPUT
                                        </span>
                                        <h4 class="font-bold text-slate-800 text-lg leading-tight">{{ $record->lokasi_keberangkatan }}</h4>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">
                                            <i class="fas fa-map-marker-alt text-slate-800 text-[10px] mr-1"></i> TUJUAN
                                        </span>
                                        <h4 class="font-bold text-slate-800 text-lg leading-tight">{{ $record->alamat_tujuan }}</h4>
                                        <span class="inline-block mt-2 bg-slate-100 text-slate-600 text-[11px] font-bold px-2 py-1 rounded border border-slate-200">
                                            {{ $record->wilayah?->nama_wilayah ?? 'Bandung' }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Vehicle & Actions --}}
                                <div class="lg:w-2/5 flex flex-row gap-4 h-full">
                                    <div class="flex-grow bg-slate-50 rounded-xl p-5 border border-slate-100">
                                        <div class="flex items-start gap-4 mb-5">
                                            <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-600 shadow-sm flex-shrink-0">
                                                <i class="fas fa-car-side"></i>
                                            </div>
                                            <div class="flex-grow">
                                                <div class="font-bold text-slate-800 text-sm leading-tight">
                                                    {{ $kendaraan->merk_type ?? 'Toyota Kijang Innova' }}
                                                </div>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="text-[10px] font-bold bg-slate-200 text-slate-700 px-1.5 py-0.5 rounded border border-slate-300">
                                                        {{ $kendaraan->nopol_kendaraan ?? 'D 1234 ABC' }}
                                                    </span>
                                                    <span class="text-[10px] text-slate-400">{{ $kendaraan->jenis_kendaraan ?? 'Minibus' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="h-px bg-slate-200 w-full border-t border-dashed border-slate-300 mb-5"></div>
                                        <div class="flex items-start gap-4">
                                            <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-600 shadow-sm flex-shrink-0">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 text-sm leading-tight">{{ $record->nama_personil_perwakilan }}</div>
                                                <div class="text-xs text-slate-500 mt-0.5">{{ $record->unitKerja->nama_unit_kerja ?? 'Staff' }}</div>
                                                <div class="text-xs text-slate-500 mt-0.5">
                                                    Kontak: <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $record->kontak_pengguna_perwakilan) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">{{ $record->kontak_pengguna_perwakilan }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-2 justify-start">
                                        <a href="#" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 hover:bg-white border border-slate-200 hover:border-blue-300 text-slate-500 hover:text-blue-600 transition-all shadow-sm">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $dataRecords->links() }}
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border border-dashed border-slate-300">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-wind text-3xl text-slate-300"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Tidak ada jadwal</h3>
                    <p class="text-slate-500 text-sm">Belum ada data perjalanan yang ditemukan.</p>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
