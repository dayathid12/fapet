<x-filament-panels::page>
    <style>
        .premium-row { transition: all 0.2s ease-in-out; border-left: 3px solid transparent; }
        .premium-row:hover { background: rgba(20, 184, 166, 0.03); border-left: 3px solid #14b8a6; }
        .custom-scrollbar::-webkit-scrollbar { height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #374151; }

        @keyframes shimmer { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }
        .shimmer-fast { animation: shimmer 2.5s infinite linear; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent); }
    </style>

    <main class="w-full -mt-8 space-y-4">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-2">
            <div>
                <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase italic">Pengadaan Dashboard</h1>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest opacity-70">Sistem Pemantauan Alur Kerja</p>
            </div>
            <div class="flex items-center gap-3">
                @if(method_exists($this, 'table'))
                    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-1">
                        {{ $this->table->getFiltersForm() }}
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
            {{-- Toolbar --}}
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/30 dark:bg-gray-900/50">
                <div class="relative max-w-xl group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <x-heroicon-m-magnifying-glass class="w-4 h-4 text-gray-400 group-focus-within:text-teal-500 transition-colors" />
                    </span>
                    <input type="search" wire:model.live.debounce.500ms="tableSearch" placeholder="Cari data pengajuan..." class="block w-full pl-10 pr-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-teal-500/20 outline-none transition-all shadow-sm" />
                </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-gray-800/50">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800 w-32">Ref.</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800">Nama Pekerjaan</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800">Estimasi Biaya</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800 text-center">Status Alur</th>
                            <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 w-24"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($this->getTableRecords() as $item)
                            @php
                                $step1 = (isset($item->total) && $item->total > 0);
                                $step2 = !empty($item->nomor_pr);
                            @endphp
                            <tr class="premium-row group" wire:key="row-{{ $item->id ?? $loop->index }}">
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <span class="text-[11px] font-black font-mono px-2 py-1 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded">#{{ $item->nomor_ajuan ?? '-' }}</span>
                                </td>

                                <td class="px-6 py-6">
                                    <div class="flex flex-col">
                                        <h3 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-teal-600 transition-colors">{{ $item->nama_perkerjaan ?? 'N/A' }}</h3>
                                        <span class="text-[10px] font-bold text-gray-400 mt-1 uppercase">{{ $item->tanggal_usulan ? \Carbon\Carbon::parse($item->tanggal_usulan)->translatedFormat('d M Y') : '-' }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-6 whitespace-nowrap">
                                    <span class="text-sm font-black text-gray-900 dark:text-white">
                                        {{ $step1 ? 'Rp ' . number_format($item->total, 0, ',', '.') : '-' }}
                                    </span>
                                </td>

                                {{-- Status Alur dengan 2 Checklist --}}
                                <td class="px-6 py-6">
                                    <div class="flex items-center justify-center gap-8">
                                        {{-- Step 1: Anggaran --}}
                                        <div class="flex flex-col items-center gap-1">
                                            <div @class([
                                                'w-7 h-7 rounded-full flex items-center justify-center border-2 transition-all shadow-sm',
                                                'bg-emerald-500 border-emerald-200 text-white shadow-emerald-200' => $step1,
                                                'bg-gray-50 border-gray-200 text-gray-300 dark:bg-gray-800 dark:border-gray-700' => !$step1,
                                            ])>
                                                <x-heroicon-m-check class="w-4 h-4" />
                                            </div>
                                            <span class="text-[9px] font-black uppercase tracking-tighter {{ $step1 ? 'text-emerald-600' : 'text-gray-400' }}">Anggaran</span>
                                        </div>

                                        {{-- Connector --}}
                                        <div class="w-8 h-0.5 {{ $step2 ? 'bg-emerald-500' : 'bg-gray-200 dark:bg-gray-700' }} -mt-4"></div>

                                        {{-- Step 2: Nomor PR --}}
                                        <div class="flex flex-col items-center gap-1">
                                            <div @class([
                                                'w-7 h-7 rounded-full flex items-center justify-center border-2 transition-all shadow-sm',
                                                'bg-emerald-500 border-emerald-200 text-white shadow-emerald-200' => $step2,
                                                'bg-gray-50 border-gray-200 text-gray-300 dark:bg-gray-800 dark:border-gray-700' => !$step2,
                                            ])>
                                                <x-heroicon-m-check class="w-4 h-4" />
                                            </div>
                                            <span class="text-[9px] font-black uppercase tracking-tighter {{ $step2 ? 'text-emerald-600' : 'text-gray-400' }}">Nomor PR</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-6 text-right whitespace-nowrap">
                                    @php
                                        $editAction = $this->getTable()->getAction('edit');
                                        if ($editAction) { $editAction->record($item); }
                                    @endphp
                                    @if ($editAction && $editAction->isVisible())
                                        <a href="{{ $editAction->getUrl() }}" class="inline-flex items-center gap-1 px-4 py-2 bg-gray-900 dark:bg-teal-600 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-teal-600 transition-all shadow-sm">
                                            Detail <x-heroicon-m-chevron-right class="w-3 h-3" />
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-24 text-center opacity-30 text-sm font-black uppercase tracking-widest">Tiada Data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center text-[10px] font-black text-gray-400 uppercase tracking-widest">
                <div class="flex items-center gap-2"><span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>System Active</div>
                <div>{{ now()->translatedFormat('l, d F Y') }}</div>
            </div>
        </div>
    </main>
</x-filament-panels::page>
