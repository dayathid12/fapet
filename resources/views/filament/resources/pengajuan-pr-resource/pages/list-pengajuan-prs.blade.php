<x-filament-panels::page>

    {{-- Custom Animations & Styles --}}
    <style>
        @keyframes pulse-glow {
            0% { box-shadow: 0 0 0 0 rgba(20, 184, 166, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(20, 184, 166, 0); }
            100% { box-shadow: 0 0 0 0 rgba(20, 184, 166, 0); }
        }
        .animate-pulse-glow { animation: pulse-glow 2s infinite; }

        @keyframes slide-shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .shimmer-line {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
            background-size: 200% 100%;
            animation: slide-shimmer 1.5s infinite linear;
        }

        .fi-section {
            border-radius: 3rem !important;
            border: 1px solid rgb(241 245 249 / 0.5) !important;
            box-shadow: 0 25px 50px -12px rgb(100 116 139 / 0.15) !important;
        }
    </style>



    {{-- Main Content: Table Container --}}
    <main class="w-full -mt-6">
        <div class="bg-white dark:bg-gray-800 rounded-[3rem] border border-gray-200/50 dark:border-gray-700/50 shadow-2xl shadow-gray-900/5 dark:shadow-black/10 overflow-hidden">

            {{-- Toolbar --}}
            <div class="p-8 border-b border-gray-100 dark:border-gray-700/50 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="relative flex-1 max-w-2xl group">
                    <x-heroicon-s-magnifying-glass class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-teal-600 transition-colors w-5 h-5" />
                    <input
                        type="search"
                        wire:model.live.debounce.500ms="tableSearch"
                        placeholder="Cari ID, Nama Pekerjaan, atau Nominal..."
                        class="w-full pl-14 pr-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-2 border-transparent rounded-2xl text-sm font-medium focus:bg-white dark:focus:bg-gray-800 focus:border-teal-500/20 focus:ring-4 focus:ring-teal-500/10 transition-all outline-none"
                    />
                </div>
                <div class="flex items-center gap-4">
                    {!! $this->table->getFiltersForm()->render() !!}
                </div>
            </div>

            {{-- Table Implementation --}}
            <div class="overflow-x-auto px-4">
                <table class="w-full text-left border-collapse min-w-[1200px]">
                    <thead>
                        <tr>
                            <th class="px-6 py-6 text-[11px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest w-24 text-center">Ref.</th>
                            <th class="px-8 py-6 text-[11px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Informasi Pekerjaan</th>
                            <th class="px-8 py-6 text-[11px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-center w-[500px]">Timeline Status</th>
                            <th class="px-8 py-6 text-[11px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-right">Nilai Anggaran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($this->getTableRecords() as $item)
                            @php
                                $step1 = $item->total > 0;
                                // Anggap step2 selesai jika ada nomor_pr
                                $step2 = !empty($item->nomor_pr);
                                $progress = $step2 ? 100 : ($step1 ? 50 : 5);
                            @endphp
                            <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-900/20 transition-all duration-300 cursor-pointer">
                                <td class="px-6 py-10 align-top text-center">
                                    <span class="font-mono text-xs font-black text-teal-600 bg-teal-50 dark:bg-teal-500/10 dark:text-teal-400 px-3 py-1.5 rounded-xl border border-teal-100 dark:border-teal-500/20 shadow-sm">
                                        #{{ $item->nomor_ajuan }}
                                    </span>
                                </td>
                                <td class="px-8 py-10 align-top">
                                    <div class="flex flex-col gap-3">
                                        <div class="flex items-center gap-3">
                                            <div class="p-3 bg-gray-50 dark:bg-gray-700 text-gray-400 dark:text-gray-400 rounded-2xl group-hover:bg-teal-600 group-hover:text-white transition-all duration-500 shadow-sm">
                                                <x-heroicon-o-document-text class="w-5 h-5" />
                                            </div>
                                            <p class="text-base font-bold text-gray-800 dark:text-gray-100 leading-tight group-hover:text-teal-900 dark:group-hover:text-teal-300 transition-colors">
                                                {{ $item->nama_perkerjaan }}
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-4 text-[11px] font-bold text-gray-400 dark:text-gray-500 ml-14">
                                            <span class="flex items-center gap-1.5"><x-heroicon-s-calendar class="w-3 h-3 text-teal-500" /> {{ \Carbon\Carbon::parse($item->tanggal_usulan)->format('d/m/Y') }}</span>
                                            <span class="w-1 h-1 rounded-full bg-gray-200 dark:bg-gray-700"></span>
                                            <span class="flex items-center gap-1.5"><x-heroicon-s-clock class="w-3 h-3" /> {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-10 align-top">
                                    <div class="flex flex-col gap-4 max-w-[420px] mx-auto">
                                        <div class="flex justify-between items-end px-1">
                                            <span class="text-[10px] font-black uppercase tracking-widest {{ $progress === 100 ? 'text-emerald-500' : 'text-teal-600' }}">
                                                {{ $progress === 100 ? 'Verified' : 'In Progress' }}
                                            </span>
                                            <span class="text-[13px] font-black text-gray-900 dark:text-white">{{ $progress }}%</span>
                                        </div>
                                        <div class="h-3 w-full bg-gray-100 dark:bg-gray-700 rounded-full p-1 relative overflow-hidden shadow-inner">
                                            <div class="h-full rounded-full transition-all duration-1000 ease-out relative shadow-sm {{ $progress === 100 ? 'bg-emerald-500' : 'bg-gradient-to-r from-teal-400 to-teal-600' }}" style="width: {{ $progress }}%;">
                                                <div class="absolute inset-0 shimmer-line"></div>
                                            </div>
                                        </div>
                                        <div class="flex gap-4">
                                            <div @class([
                                                'flex-1 p-3 rounded-2xl border-2 transition-all duration-500 flex flex-col gap-1.5',
                                                'bg-teal-50/40 dark:bg-teal-500/10 border-teal-100 dark:border-teal-500/20' => $step1,
                                                'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 opacity-60' => !$step1,
                                            ])>
                                                <div class="flex items-center justify-between">
                                                    <span class="text-[9px] font-black uppercase tracking-tighter {{ $step1 ? 'text-teal-600' : 'text-gray-400' }}">Anggaran</span>
                                                    @if ($step1)
                                                        <x-heroicon-s-check class="w-2.5 h-2.5 text-teal-600" stroke-width="4" />
                                                    @else
                                                        <x-heroicon-o-minus-circle class="w-2.5 h-2.5 text-gray-200 dark:text-gray-600" />
                                                    @endif
                                                </div>
                                                <span class="text-[11px] font-bold {{ $step1 ? 'text-gray-800 dark:text-gray-200' : 'text-gray-300 dark:text-gray-600 italic' }}">Tervalidasi</span>
                                            </div>
                                            <div @class([
                                                'flex-1 p-3 rounded-2xl border-2 transition-all duration-500 flex flex-col gap-1.5',
                                                'bg-emerald-50/40 dark:bg-emerald-500/10 border-emerald-100 dark:border-emerald-500/20' => $step2,
                                                'bg-white dark:bg-gray-800 border-teal-200 dark:border-teal-600 animate-pulse-glow' => $step1 && !$step2,
                                                'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 opacity-60' => !$step1,
                                            ])>
                                                <div class="flex items-center justify-between">
                                                    <span class="text-[9px] font-black uppercase tracking-tighter {{ $step2 ? 'text-emerald-600' : ($step1 ? 'text-teal-600' : 'text-gray-400') }}">PR</span>
                                                     @if ($step2)
                                                        <x-heroicon-s-shield-check class="w-2.5 h-2.5 text-emerald-600" stroke-width="3" />
                                                    @elseif ($step1)
                                                        <svg class="animate-spin h-2.5 w-2.5 text-teal-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                    @else
                                                        <x-heroicon-o-minus-circle class="w-2.5 h-2.5 text-gray-200 dark:text-gray-600" />
                                                    @endif
                                                </div>
                                                <span class="text-[11px] font-bold {{ $step2 ? 'text-gray-800 dark:text-gray-200' : ($step1 ? 'text-teal-700 dark:text-teal-400' : 'text-gray-300 dark:text-gray-600 italic') }}">Penerbitan</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-10 align-top text-right">
                                    <div class="flex flex-col items-end gap-1">
                                        <span @class([
                                            'text-xl font-black tracking-tight transition-all duration-300',
                                            'text-gray-900 dark:text-white group-hover:text-teal-600' => $item->total > 0,
                                            'text-gray-300 dark:text-gray-600 italic text-sm' => !($item->total > 0),
                                        ])>
                                            {{ $item->total > 0 ? 'Rp ' . number_format($item->total, 0, ',', '.') : 'N/A' }}
                                        </span>
                                        @if($item->total > 0)
                                            <div class="flex items-center gap-1.5 text-[9px] font-black text-emerald-500 uppercase tracking-widest bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 rounded-md border border-emerald-100 dark:border-emerald-500/20">
                                                <x-heroicon-s-shield-check class="w-2.5 h-2.5" /> Verified
                                            </div>
                                        @endif

                                        @php
                                            $editAction = $this->getTable()->getAction('edit');
                                            if ($editAction) {
                                                $editAction->record($item);
                                            }
                                        @endphp

                                        @if ($editAction && $editAction->isVisible())
                                            <a href="{{ $editAction->getUrl() }}" class="mt-6 flex items-center gap-2 px-4 py-2 bg-gray-50 dark:bg-gray-700 hover:bg-gray-900 dark:hover:bg-gray-600 hover:text-white dark:text-gray-300 rounded-xl text-[10px] font-black text-gray-600 uppercase tracking-widest transition-all shadow-sm">
                                                Detail <x-heroicon-s-chevron-right class="w-3 h-3" stroke-width="3" />
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="flex flex-col items-center justify-center text-center p-20">
                                        <div class="p-4 bg-gray-100 rounded-full mb-4">
                                            <x-heroicon-o-archive-box-x-mark class="w-10 h-10 text-gray-400" />
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-800">Data Tidak Ditemukan</h3>
                                        <p class="text-sm text-gray-500">Belum ada pengajuan PR yang dibuat.</p>
                                        <div class="mt-6">
                                            {{ $this->getCreateAction() }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer Pagination --}}
            <div class="p-8 border-t border-gray-100 dark:border-gray-800 bg-gray-50/20 dark:bg-gray-900/10 backdrop-blur-md flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-3 h-3 rounded-full bg-teal-500 animate-ping"></div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Sistem Online & Terhubung</p>
                </div>
            </div>
        </div>
    </main>

</x-filament-panels::page>
