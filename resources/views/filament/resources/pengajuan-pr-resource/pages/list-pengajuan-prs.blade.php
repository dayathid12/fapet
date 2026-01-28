<x-filament-panels::page>
    {{-- Custom Enhanced Styles --}}
    <style>
        /* Smooth Gradient Background for Row Hover */
        .premium-row {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .premium-row:hover {
            background: linear-gradient(90deg, rgba(20, 184, 166, 0.04) 0%, transparent 100%);
            transform: translateX(4px);
        }

        /* Sophisticated Glassmorphism */
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .dark .glass-card {
            background: rgba(17, 24, 39, 0.8);
        }

        /* Animated Progress Bar Shimmer */
        @keyframes shimmer-fast {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .shimmer-fast {
            animation: shimmer-fast 2.5s infinite ease-in-out;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        }

        /* Connecting Line for Workflow */
        .workflow-line {
            position: relative;
        }
        .workflow-line::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: #e5e7eb;
            z-index: 0;
            transform: translateY(-50%);
        }
        .dark .workflow-line::before {
            background: #374151;
        }
    </style>

    <main class="w-full max-w-none -mt-8">
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-white">Daftar Pengajuan</h1>
                <p class="text-sm text-gray-500 font-medium italic">Manajemen alur kerja pengadaan secara real-time</p>
            </div>
        </div>

        <div class="glass-card rounded-[3rem] border border-gray-200/60 dark:border-gray-700/40 shadow-2xl overflow-hidden">

            {{-- Enhanced Toolbar --}}
            <div class="p-8 border-b border-gray-100/50 dark:border-gray-800/50 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="relative flex-1 group">
                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                        <x-heroicon-m-magnifying-glass class="w-5 h-5 text-gray-400 group-focus-within:text-teal-500 transition-colors" />
                    </div>
                    <input
                        type="search"
                        wire:model.live.debounce.500ms="tableSearch"
                        placeholder="Cari referensi atau nama pekerjaan..."
                        class="block w-full pl-14 pr-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-none rounded-[1.5rem] text-sm font-semibold placeholder-gray-400 focus:ring-2 focus:ring-teal-500/20 focus:bg-white dark:focus:bg-gray-800 transition-all outline-none"
                    />
                </div>

                <div class="flex items-center gap-3">
                    {{-- Pastikan method filtersForm ada atau ganti dengan standar Filament --}}
                    @if(method_exists($this, 'table'))
                        {{ $this->table->getFiltersForm() }}
                    @endif
                </div>
            </div>

            {{-- Table Content --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-0 min-w-[1200px]">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-900/30">
                            <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 dark:border-gray-800">Ref.</th>
                            <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 dark:border-gray-800">Detail Pekerjaan</th>
                            <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] text-center border-b border-gray-100 dark:border-gray-800 w-[450px]">Status Alur Kerja</th>
                            <th class="px-8 py-6 text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] text-right border-b border-gray-100 dark:border-gray-800">Estimasi Biaya</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100/50 dark:divide-gray-800/50">
                        @forelse ($this->getTableRecords() as $item)
                            @php
                                $step1 = (isset($item->total) && $item->total > 0);
                                $step2 = !empty($item->nomor_pr);
                                $progress = $step2 ? 100 : ($step1 ? 55 : 15);
                                $themeColor = $step2 ? 'emerald' : ($step1 ? 'teal' : 'slate');
                            @endphp
                            <tr class="premium-row group" wire:key="row-{{ $item->id ?? $loop->index }}">
                                {{-- ID --}}
                                <td class="px-8 py-10 align-top">
                                    <span class="font-mono text-[11px] font-black text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-500/10 px-3 py-1.5 rounded-full border border-teal-100 dark:border-teal-500/20">
                                        #{{ $item->nomor_ajuan ?? '-' }}
                                    </span>
                                </td>

                                {{-- Job Info --}}
                                <td class="px-8 py-10">
                                    <div class="space-y-2">
                                        <h3 class="text-base font-extrabold text-gray-900 dark:text-white group-hover:text-teal-600 transition-colors">
                                            {{ $item->nama_perkerjaan ?? 'Tanpa Nama' }}
                                        </h3>
                                        <div class="flex items-center gap-4 text-[11px] font-bold text-gray-400 dark:text-gray-500">
                                            <span class="flex items-center gap-1.5 bg-gray-50 dark:bg-gray-800 px-2 py-1 rounded-md">
                                                <x-heroicon-s-calendar class="w-3 h-3 text-teal-500" />
                                                {{ $item->tanggal_usulan ? \Carbon\Carbon::parse($item->tanggal_usulan)->translatedFormat('d M Y') : '-' }}
                                            </span>
                                            <span class="flex items-center gap-1.5">
                                                <x-heroicon-s-clock class="w-3 h-3" />
                                                {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('H:i') : '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Workflow Status --}}
                                <td class="px-8 py-10">
                                    <div class="flex flex-col gap-5">
                                        <div class="flex justify-between items-center px-1">
                                            <div @class([
                                                'flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-black tracking-widest transition-all',
                                                'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' => $step2,
                                                'bg-teal-100 text-teal-700 dark:bg-teal-500/20 dark:text-teal-400' => $step1 && !$step2,
                                                'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400' => !$step1,
                                            ])>
                                                <span class="relative flex h-2 w-2">
                                                    <span @class([
                                                        'animate-ping absolute inline-flex h-full w-full rounded-full opacity-75',
                                                        'bg-emerald-400' => $step2,
                                                        'bg-teal-400' => $step1 && !$step2,
                                                        'bg-slate-400' => !$step1,
                                                    ])></span>
                                                    <span @class([
                                                        'relative inline-flex rounded-full h-2 w-2',
                                                        'bg-emerald-500' => $step2,
                                                        'bg-teal-500' => $step1 && !$step2,
                                                        'bg-slate-500' => !$step1,
                                                    ])></span>
                                                </span>
                                                {{ $step2 ? 'TERBIT (COMPLETED)' : ($step1 ? 'PROSES VALIDASI' : 'DRAFT PENGAJUAN') }}
                                            </div>
                                            <span class="text-sm font-black text-gray-900 dark:text-white">{{ $progress }}%</span>
                                        </div>

                                        <div class="h-1.5 w-full bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden relative">
                                            <div @class([
                                                'h-full rounded-full transition-all duration-1000 ease-in-out relative',
                                                'bg-emerald-500' => $step2,
                                                'bg-gradient-to-r from-teal-400 to-teal-600' => !$step2,
                                            ]) style="width: {{ $progress }}%;">
                                                <div class="absolute inset-0 shimmer-fast"></div>
                                            </div>
                                        </div>

                                        <div class="flex justify-between items-center relative px-2">
                                            <div class="absolute top-1/2 left-0 right-0 h-0.5 bg-gray-100 dark:bg-gray-800 -translate-y-1/2 z-0"></div>
                                            <div class="absolute top-1/2 left-0 h-0.5 bg-teal-500 -translate-y-1/2 z-0 transition-all duration-1000" style="width: {{ $step2 ? '100%' : ($step1 ? '50%' : '0%') }}"></div>

                                            <div class="relative z-10 flex flex-col items-center gap-2">
                                                <div @class([
                                                    'w-8 h-8 rounded-full flex items-center justify-center border-4 transition-all duration-500 shadow-sm',
                                                    'bg-teal-500 border-teal-100 dark:border-teal-900 text-white' => $step1,
                                                    'bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-800 text-gray-300' => !$step1,
                                                ])>
                                                    <x-heroicon-s-banknotes class="w-3.5 h-3.5" />
                                                </div>
                                                <span class="text-[9px] font-black uppercase text-gray-400 dark:text-gray-500 tracking-tighter">Anggaran</span>
                                            </div>

                                            <div class="relative z-10 flex flex-col items-center gap-2">
                                                <div @class([
                                                    'w-8 h-8 rounded-full flex items-center justify-center border-4 transition-all duration-500 shadow-sm',
                                                    'bg-emerald-500 border-emerald-100 dark:border-emerald-900 text-white' => $step2,
                                                    'bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-800 text-gray-300' => !$step2,
                                                    'ring-4 ring-teal-500/20 animate-pulse' => $step1 && !$step2,
                                                ])>
                                                    @if($step2)
                                                        <x-heroicon-s-check-circle class="w-4 h-4" />
                                                    @else
                                                        <x-heroicon-s-document-text class="w-3.5 h-3.5" />
                                                    @endif
                                                </div>
                                                <span class="text-[9px] font-black uppercase text-gray-400 dark:text-gray-500 tracking-tighter">Nomor PR</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Value --}}
                                <td class="px-8 py-10 text-right">
                                    <div class="flex flex-col items-end gap-3">
                                        <div class="space-y-1">
                                            <p class="text-xl font-black text-gray-900 dark:text-white tracking-tight">
                                                {{ (isset($item->total) && $item->total > 0) ? 'Rp' . number_format($item->total, 0, ',', '.') : '-' }}
                                            </p>
                                            @if(isset($item->total) && $item->total > 0)
                                                <div class="flex items-center justify-end gap-1.5 text-[9px] font-bold text-emerald-500 uppercase">
                                                    <x-heroicon-m-shield-check class="w-3 h-3" /> Validated
                                                </div>
                                            @endif
                                        </div>

                                        @php
                                            $editAction = $this->getTable()->getAction('edit');
                                            if ($editAction) { $editAction->record($item); }
                                        @endphp

                                        @if ($editAction && $editAction->isVisible())
                                            <a href="{{ $editAction->getUrl() }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-teal-600 hover:bg-teal-600 dark:hover:bg-teal-500 text-white rounded-full text-[10px] font-black uppercase tracking-widest transition-all shadow-lg hover:shadow-teal-500/20">
                                                Lihat Detail <x-heroicon-m-arrow-right class="w-3 h-3" />
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="flex flex-col items-center justify-center py-32 opacity-60">
                                        <x-heroicon-o-inbox class="w-16 h-16 text-gray-300 mb-4" />
                                        <p class="text-gray-500 font-bold">Data tidak ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="p-8 bg-gray-50/30 dark:bg-gray-900/40 border-t border-gray-100/50 dark:border-gray-800/50 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2 px-3 py-1 bg-white dark:bg-gray-800 rounded-full border border-gray-100 dark:border-gray-700 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Cloud Synchronized</span>
                    </div>
                </div>
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
            </div>
        </div>
    </main>
</x-filament-panels::page>
