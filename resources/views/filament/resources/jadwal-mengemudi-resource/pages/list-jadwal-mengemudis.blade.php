<x-filament-panels::page class="relative min-h-screen overflow-x-hidden bg-gray-50 dark:bg-gray-950 font-jakarta antialiased">
    {{-- Inject Styles & Fonts --}}
    @push('styles')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            .font-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }
            .border-spacing-y-4 { border-spacing: 0 1rem; }

            /* Custom Scrollbar */
            ::-webkit-scrollbar { width: 8px; height: 8px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
            ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

            /* Blob Animation */
            @keyframes float {
                0% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
                100% { transform: translate(0px, 0px) scale(1); }
            }
            .animate-blob { animation: float 7s infinite; }
            .animation-delay-2000 { animation-delay: 2s; }
            .animation-delay-4000 { animation-delay: 4s; }
        </style>
    @endpush

    {{-- Premium Background (Fixed) --}}
    <div class="fixed inset-0 -z-10 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-gray-900 dark:via-blue-900/20 dark:to-purple-900/20 pointer-events-none">
        <div class="absolute top-0 left-0 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob dark:bg-blue-900/20"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000 dark:bg-indigo-900/20"></div>
        <div class="absolute -bottom-32 left-20 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000 dark:bg-purple-900/20"></div>
    </div>

    @php
        // Data comes from the $dataRecords public property on the component
        $totalTrip = $dataRecords->total();
        $totalJam = $totalTrip * 4; // Dummy logic
        $onDutyCount = $dataRecords->where('status_perjalanan', 'berangkat')->count();
    @endphp

    <div class="w-full text-slate-800 dark:text-slate-200 -mt-8 px-4 sm:px-6 lg:px-8">



        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <!-- Card 1 -->
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-md p-5 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-200 dark:border-gray-700 group">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Total Trip</div>
                        <div class="text-3xl font-bold text-gray-800 dark:text-white">{{ $totalTrip }}</div>
                    </div>
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="fas fa-route"></i>
                    </div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-md p-5 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-200 dark:border-gray-700 group">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Jam Kerja</div>
                        <div class="text-3xl font-bold text-gray-800 dark:text-white">{{ $totalJam }}<span class="text-lg text-gray-400 font-normal">h</span></div>
                    </div>
                    <div class="p-2 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-lg group-hover:bg-purple-600 group-hover:text-white transition-colors">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-md p-5 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-200 dark:border-gray-700 group">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Rating</div>
                        <div class="text-3xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            4.9 <span class="text-sm text-yellow-500"><i class="fas fa-star"></i></span>
                        </div>
                    </div>
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 rounded-lg group-hover:bg-yellow-500 group-hover:text-white transition-colors">
                        <i class="fas fa-award"></i>
                    </div>
                </div>
            </div>
            <!-- Card 4 -->
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-md p-5 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-200 dark:border-gray-700 group">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Status</div>
                        <div class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $onDutyCount > 0 ? 'On Duty' : 'Standby' }}</div>
                    </div>
                    <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-lg group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <i class="fas fa-toggle-on"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter / Search Bar --}}
        <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4 bg-white/70 dark:bg-gray-800/70 p-2 rounded-2xl backdrop-blur-md border border-gray-200 dark:border-gray-700 shadow-sm">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white pl-4 w-full sm:w-auto flex items-center gap-2">
                <i class="fas fa-calendar-alt text-blue-600"></i> Jadwal Penugasan
            </h2>
            <div class="flex gap-2 w-full sm:w-auto p-1">
                <div class="relative w-full sm:w-72 group">
                    <input type="text" placeholder="Cari rute, unit, atau kontak..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border-0 bg-white dark:bg-gray-900 ring-1 ring-gray-300 dark:ring-gray-600 focus:ring-2 focus:ring-blue-500 transition-all text-sm shadow-sm group-hover:shadow-md dark:text-white">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <button class="bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 ring-1 ring-gray-300 dark:ring-gray-600 px-5 py-2.5 rounded-xl text-sm font-medium transition-all shadow-sm hover:shadow-md active:scale-95">
                    <i class="fas fa-sliders-h mr-2"></i> Filter
                </button>
            </div>
        </div>

        @if ($dataRecords->count() > 0)
            {{-- Desktop Table View (Separated Rows) --}}
            <div class="hidden md:block overflow-x-auto pb-4">
                <table class="min-w-full border-separate border-spacing-y-3 px-1">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider w-[40%] sticky top-0 z-10 bg-white/70 dark:bg-gray-800/70 backdrop-blur-md rounded-tl-xl rounded-bl-xl">Jadwal & Rute</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider sticky top-0 z-10 bg-white/70 dark:bg-gray-800/70 backdrop-blur-md">Pengemudi & Armada</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider sticky top-0 z-10 bg-white/70 dark:bg-gray-800/70 backdrop-blur-md">Perwakilan, Unit & Kontak</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider sticky top-0 z-10 bg-white/70 dark:bg-gray-800/70 backdrop-blur-md">Status</th>
                            <th scope="col" class="relative px-6 py-3 sticky top-0 z-10 bg-white/70 dark:bg-gray-800/70 backdrop-blur-md rounded-tr-xl rounded-br-xl"><span class="sr-only">Detail</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataRecords as $record)
                            @php
                                $namaDriver = $record->pengemudi?->first()?->nama_staf ?? 'N/A';
                                $nipDriver = $record->pengemudi?->first()?->nip ?? '-';

                                $kendaraan = $record->kendaraan?->first();
                                $nopol = $kendaraan?->nopol_kendaraan ?? '-';
                                $namaKendaraan = $kendaraan?->nama_kendaraan ?? 'Kendaraan';
                                $jenisKendaraan = $kendaraan?->jenis_kendaraan ?? 'Operasional';

                                $start = \Carbon\Carbon::parse($record->waktu_keberangkatan);
                                $end = \Carbon\Carbon::parse($record->waktu_kepulangan);
                                $diffInDays = $start->diffInDays($end);
                                $days = $diffInDays + 1;
                                $nights = $diffInDays;
                                $durationText = $nights === 0 ? "$days Hari" : "$days Hari $nights Malam";

                                // Format Phone to WhatsApp
                                $phone = $record->no_telepon_perwakilan;
                                $waLink = '#';
                                if($phone) {
                                    $clean = preg_replace('/\\D/', '', $phone);
                                    if(str_starts_with($clean, '0')) $clean = '62' . substr($clean, 1);
                                    $waLink = "https://wa.me/$clean";
                                }
                            @endphp

                            <tr class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-md shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 group rounded-xl relative border border-gray-200 dark:border-gray-700">

                                {{-- 1. Jadwal & Rute --}}
                                <td class="px-6 py-4 align-top first:rounded-l-xl border-y first:border-l border-gray-200/50 dark:border-gray-700">
                                    <div class="flex gap-8">
                                        {{-- Waktu --}}
                                        <div class="w-44 flex-shrink-0 border-r border-gray-200 dark:border-gray-700 pr-6 relative">
                                            <div class="absolute right-0 top-0 bottom-0 w-[1px] bg-gradient-to-b from-transparent via-gray-200 dark:via-gray-700 to-transparent"></div>
                                            <div class="flex flex-col gap-4">
                                                <div>
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-emerald-50 dark:ring-emerald-900/30"></div>
                                                        <span class="text-[10px] font-bold text-gray-400 tracking-wider">BERANGKAT</span>
                                                    </div>
                                                    <div class="pl-5">
                                                        <div class="text-base font-bold text-gray-800 dark:text-white">{{ $start->format('H:i') }}</div>
                                                        <div class="text-xs font-medium text-gray-500">{{ $start->isoFormat('D MMM YYYY') }}</div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <div class="w-2.5 h-2.5 rounded-full bg-rose-500 ring-4 ring-rose-50 dark:ring-rose-900/30"></div>
                                                        <span class="text-[10px] font-bold text-gray-400 tracking-wider">PULANG</span>
                                                    </div>
                                                    <div class="pl-5">
                                                        <div class="text-base font-bold text-gray-800 dark:text-white">{{ $end->format('H:i') }}</div>
                                                        <div class="text-xs font-medium text-gray-500">{{ $end->isoFormat('D MMM YYYY') }}</div>
                                                    </div>
                                                </div>
                                                <div class="pl-5 pt-1">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-sm dark:bg-indigo-900/30 dark:text-indigo-300 dark:border-indigo-800">
                                                        <i class="fas fa-moon mr-1.5 text-indigo-500"></i> {{ $durationText }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Rute --}}
                                        <div class="flex-grow pt-1">
                                            <div class="flex flex-col gap-5 relative">
                                                <div class="absolute left-[8px] top-3 bottom-3 w-[2px] border-l-2 border-dashed border-gray-200 dark:border-gray-700"></div>

                                                <div class="flex gap-4 relative">
                                                    <div class="w-4 mt-1 flex flex-col items-center z-10">
                                                        <div class="w-4 h-4 rounded-full bg-white dark:bg-gray-800 border-[3px] border-blue-500 shadow-md"></div>
                                                    </div>
                                                    <div>
                                                        <span class="text-[10px] uppercase text-gray-400 font-bold tracking-wider block mb-0.5">Lokasi Jemput</span>
                                                        <div class="text-sm text-gray-800 dark:text-white font-semibold leading-tight hover:text-blue-600 transition-colors">{{ $record->lokasi_keberangkatan }}</div>
                                                    </div>
                                                </div>
                                                <div class="flex gap-4 relative">
                                                    <div class="w-4 mt-1 flex flex-col items-center z-10">
                                                        <div class="w-4 h-4 rounded-full bg-white dark:bg-gray-800 border-[3px] border-rose-500 shadow-md"></div>
                                                    </div>
                                                    <div>
                                                        <span class="text-[10px] uppercase text-gray-400 font-bold tracking-wider block mb-0.5">Tujuan</span>
                                                        <div class="text-sm text-gray-800 dark:text-white font-semibold leading-tight hover:text-rose-600 transition-colors">{{ $record->alamat_tujuan }}</div>
                                                        <div class="mt-2 inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-gray-100 text-gray-600 border border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700">
                                                            <i class="fas fa-map-marker-alt mr-1.5 text-gray-400"></i> {{ $record->wilayah?->nama_wilayah ?? 'Luar Kota' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- 2. Pengemudi & Armada --}}
                                <td class="px-6 py-4 whitespace-nowrap align-top border-y border-gray-200/50 dark:border-gray-700">
                                    <div class="flex flex-col gap-4">
                                        <div class="flex items-center gap-3 pl-1">
                                            <div class="flex-shrink-0 h-8 w-8 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg flex items-center justify-center shadow-sm border border-blue-100 dark:border-blue-800">
                                                <i class="fas fa-car text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-800 dark:text-white">{{ $kendaraan->merk_type ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $kendaraan->nopol_kendaraan ?? 'N/A' }}</div>
                                                <div class="text-[10px] font-bold text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 px-1.5 py-0.5 rounded inline-block mt-0.5 border border-gray-200 dark:border-gray-700">{{ $kendaraan->jenis_kendaraan ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- 3. Perwakilan & Kontak --}}
                                <td class="px-6 py-4 align-top border-y border-gray-200/50 dark:border-gray-700">
                                    <div class="flex flex-col gap-1">
                                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Perwakilan</div>
                                        <div class="text-sm font-bold text-gray-800 dark:text-white">{{ $record->nama_personil_perwakilan }}</div>
                                    </div>
                                    <div class="flex flex-col gap-1 mt-3">
                                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Unit Kerja/Fakultas/UKM</div>
                                        <div class="text-sm text-gray-800 dark:text-white font-semibold">{{ $record->unitKerja->nama_unit_kerja ?? 'N/A' }}</div>
                                    </div>
                                    <div class="flex flex-col gap-1 mt-3">
                                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kontak Perwakilan</div>
                                        @if($record->no_telepon_perwakilan)
                                            <a href="{{ $waLink }}" target="_blank" class="group/btn inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 rounded-lg text-xs font-bold border border-emerald-100 dark:border-emerald-800 hover:bg-emerald-500 hover:text-white hover:border-emerald-600 transition-all shadow-sm max-w-min">
                                                <i class="fab fa-whatsapp text-sm group-hover/btn:scale-110 transition-transform"></i> <span>{{ $record->no_telepon_perwakilan }}</span>
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-500 italic">Tidak ada kontak</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- 4. Status --}}
                                <td class="px-6 py-4 whitespace-nowrap align-top border-y border-gray-200/50 dark:border-gray-700">
                                    @php
                                        $statusClass = match($record->status_perjalanan) {
                                            'berangkat' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'menunggu' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'selesai' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'dibatalkan' => 'bg-red-100 text-red-700 border-red-200',
                                            default => 'bg-gray-100 text-gray-700 border-gray-200',
                                        };
                                        $statusIcon = match($record->status_perjalanan) {
                                            'berangkat' => 'fa-steering-wheel',
                                            'menunggu' => 'fa-clock',
                                            'selesai' => 'fa-check-circle',
                                            'dibatalkan' => 'fa-times-circle',
                                            default => 'fa-question-circle',
                                        };
                                        $statusLabel = match($record->status_perjalanan) {
                                            'berangkat' => 'Jalan',
                                            'menunggu' => 'Menunggu',
                                            'selesai' => 'Selesai',
                                            'dibatalkan' => 'Batal',
                                            default => $record->status_perjalanan,
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 inline-flex items-center text-xs leading-5 font-bold uppercase tracking-wide rounded-full border shadow-sm {{ $statusClass }}">
                                        <i class="fas {{ $statusIcon }} mr-1.5"></i> {{ $statusLabel }}
                                    </span>
                                </td>

                                {{-- 5. Action --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium align-top last:rounded-r-xl border-y last:border-r border-gray-200/50 dark:border-gray-700">
                                    <button class="text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 w-8 h-8 rounded-full transition-all flex items-center justify-center">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card View --}}
            <div class="md:hidden space-y-5 pb-8">
                 @foreach ($dataRecords as $record)
                    @php
                        $start = \Carbon\Carbon::parse($record->waktu_keberangkatan);
                        $end = \Carbon\Carbon::parse($record->waktu_kepulangan);
                        $namaDriverMobile = $record->pengemudi?->first()?->nama_staf ?? 'N/A';
                        $nipDriverMobile = $record->pengemudi?->first()?->nip ?? '-';
                        $unitMobile = $record->kendaraan?->first()?->nama_kendaraan ?? 'N/A';
                        $typeMobile = $record->kendaraan?->first()?->jenis_kendaraan ?? 'N/A';

                        $statusLabelMobile = match($record->status_perjalanan) {
                            'berangkat' => 'Jalan',
                            'menunggu' => 'Menunggu',
                            'selesai' => 'Selesai',
                            'dibatalkan' => 'Batal',
                            default => $record->status_perjalanan,
                        };
                    @endphp
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-md p-5 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 relative overflow-hidden group">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-purple-400"></div>

                        <div class="flex justify-between items-start mb-4 border-b border-gray-200 dark:border-gray-700 pb-3">
                             <div class="flex flex-col gap-3 w-full">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-emerald-500 ring-2 ring-emerald-100"></div>
                                        <span class="text-xs font-bold text-emerald-700">BERANGKAT</span>
                                    </div>
                                    <span class="text-xs font-bold text-gray-800 dark:text-white bg-gray-50 dark:bg-gray-800 px-2 py-0.5 rounded border border-gray-200 dark:border-gray-700">{{ $start->format('d M • H:i') }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-rose-500 ring-2 ring-rose-100"></div>
                                        <span class="text-xs font-bold text-rose-700">PULANG</span>
                                    </div>
                                    <span class="text-xs font-bold text-gray-800 dark:text-white bg-gray-50 dark:bg-gray-800 px-2 py-0.5 rounded border border-gray-200 dark:border-gray-700">{{ $end->format('d M • H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="absolute top-5 right-5">
                             <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-full bg-gray-100 text-gray-700 border border-gray-200">
                                {{ $statusLabelMobile }}
                             </span>
                        </div>

                        <div class="space-y-4 pt-1">
                             <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center"><i class="fas fa-car"></i></div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $kendaraan->merk_type ?? 'N/A' }}</div>
                                        <div class="text-[10px] text-gray-500 mb-1">{{ $kendaraan->nopol_kendaraan ?? 'N/A' }}</div>
                                        <div class="text-[10px] text-gray-500">{{ $kendaraan->jenis_kendaraan ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-xl border border-gray-200 dark:border-gray-700 mt-3">
                                <div class="flex flex-col gap-1 mb-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Perwakilan</div>
                                    <div class="text-sm font-bold text-gray-800 dark:text-white">{{ $record->nama_personil_perwakilan }}</div>
                                </div>
                                <div class="flex flex-col gap-1 mb-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Unit Kerja/Fakultas/UKM</div>
                                    <div class="text-sm text-gray-800 dark:text-white font-semibold">{{ $record->unitKerja->nama_unit_kerja ?? 'N/A' }}</div>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kontak Perwakilan</div>
                                    @if($record->no_telepon_perwakilan)
                                        <a href="{{ $waLink }}" target="_blank" class="group/btn inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 rounded-lg text-xs font-bold border border-emerald-100 dark:border-emerald-800 hover:bg-emerald-500 hover:text-white hover:border-emerald-600 transition-all shadow-sm max-w-min">
                                            <i class="fab fa-whatsapp text-sm group-hover/btn:scale-110 transition-transform"></i> <span>{{ $record->no_telepon_perwakilan }}</span>
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-500 italic">Tidak ada kontak</span>
                                    @endif
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
            <div class="flex flex-col items-center justify-center px-6 py-16 text-center bg-white/70 dark:bg-gray-800/70 border border-gray-200 border-dashed shadow-sm dark:border-gray-700 rounded-2xl">
                 <div class="flex items-center justify-center w-16 h-16 mb-6 rounded-2xl bg-gray-100 dark:bg-gray-700 text-gray-500">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Jadwal Kosong</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-sm">Belum ada jadwal perjalanan yang terdaftar.</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>
