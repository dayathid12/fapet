<x-filament-panels::page>
    {{--
        Penting:
        Secara best practice, CSS kustom dan CDN Tailwind harus diintegrasikan
        melalui proses kompilasi aset (misalnya Vite/Webpack) atau ditambahkan
        ke layout utama Filament Anda (misalnya di `app/Providers/Filament/AdminPanelProvider.php`
        menggunakan `->viteTheme('resources/css/filament/admin/theme.css')` atau
        `->discoverStyles()`/`->discoverScripts()`).
        Namun, untuk mereplikasi tampilan persis seperti yang Anda berikan,
        saya menempatkannya di sini. Perlu diingat bahwa ini mungkin bukan
        pendekatan paling optimal untuk produksi.
    --}}
    <style>
        /* Modern Typography & Smoothness */
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        :root {
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(226, 232, 240, 0.8);
            --accent-primary: #14b8a6;
            --accent-secondary: #10b981;
        }

        .dark {
            --glass-bg: rgba(15, 23, 42, 0.7);
            --glass-border: rgba(30, 41, 59, 0.6);
        }

        .font-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .animate-shimmer {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            background-size: 200% 100%;
            animation: shimmer 2.5s infinite linear;
        }

        .filament-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            transition: all 0.3s ease;
        }

        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        .table-row:hover { background: rgba(20, 184, 166, 0.04); }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>

    <main class="w-full font-jakarta space-y-8 pb-12">
        {{-- Footer ini mungkin duplikat dari footer di bawah, sesuaikan jika perlu --}}
        <footer class="flex justify-between items-center px-2">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-[0.25em]">
                 {{ now()->format('H:i') }} WIB
            </div>
        </footer>
        {{-- Main Table Container --}}
        <div class="filament-card rounded-2xl overflow-hidden">
            {{-- Search & Filter Bar --}}
            <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-end items-center gap-4 bg-white dark:bg-slate-900/50">
                <div class="relative w-full sm:max-w-md group">
                    <x-heroicon-m-magnifying-glass class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-teal-500 transition-colors" />
                    <input
                        type="text"
                        wire:model.live.debounce.500ms="tableSearch"
                        placeholder="Cari data pengadaan..."
                        class="w-full pl-12 pr-4 py-3 text-base border border-slate-200 dark:border-slate-700 dark:bg-slate-900 rounded-xl focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 outline-none transition-all"
                    />
                </div>
            </div>

            {{-- Table Content --}}
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-[1000px]">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900/80 border-b border-slate-200 dark:border-slate-800">
                            <th class="px-8 py-5 text-sm font-bold text-slate-400 uppercase tracking-widest">Nomor Ajuan</th>
                            <th class="px-8 py-5 text-sm font-bold text-slate-400 uppercase tracking-widest">Informasi Pengajuan</th>
                            <th class="px-8 py-5 text-sm font-bold text-slate-400 uppercase tracking-widest text-right">Timeline Status</th>
                            <th class="px-8 py-5 text-sm font-bold text-slate-400 uppercase tracking-widest text-center">Nilai </th>
                            <th class="px-8 py-5 w-16"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                        @forelse ($this->getTableRecords() as $item)
                            @php
                                $hasAnggaran = (isset($item->total) && $item->total > 0);
                                $hasPR = !empty($item->nomor_pr);
                                $progress = $hasPR ? 100 : ($hasAnggaran ? 50 : 15);
                            @endphp
                            <tr class="table-row group">
                                <td class="px-8 py-7 align-top whitespace-nowrap">
                                    <span class="text-sm font-bold font-mono text-slate-400 bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded">#{{ str_pad($item->id ?? $loop->iteration, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>

                                <td class="px-8 py-7 align-top">
                                    <div class="flex flex-col gap-1.5">
                                        <span class="text-lg font-bold text-slate-900 dark:text-slate-100 group-hover:text-teal-600 transition-colors duration-300 leading-tight">
                                            {{ $item->nama_pekerjaan ?? $item->nama_perkerjaan ?? 'Untitled Project' }}
                                        </span>
                                        <div class="flex items-center gap-4 mt-1 text-sm font-semibold text-slate-400 uppercase tracking-tight">
                                            <span class="flex items-center gap-1.5">
                                                <x-heroicon-m-calendar class="w-4 h-4" />
                                                {{ $item->tanggal_usulan ? \Carbon\Carbon::parse($item->tanggal_usulan)->format('d M Y') : '-' }}
                                            </span>
                                            <span class="flex items-center gap-1.5">
                                                <x-heroicon-m-clock class="w-4 h-4" />
                                                {{ $item->created_at ? $item->created_at->format('H:i') : '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-8 py-7 align-top">
                                    <div class="flex flex-col gap-3 max-w-[320px] mx-auto">
                                        <div class="flex justify-between items-end">
                                            <span @class([
                                                'text-xs font-black uppercase tracking-[0.15em]',
                                                'text-emerald-600' => $progress === 100,
                                                'text-teal-600' => $progress === 50,
                                                'text-slate-400' => $progress < 50,
                                            ])>
                                                {{ $progress === 100 ? 'Verified' : ($progress === 50 ? 'Validating' : 'Drafting') }}
                                            </span>
                                            <span class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ $progress }}%</span>
                                        </div>

                                        <div class="h-2.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden relative border border-slate-200/50 dark:border-slate-700/50">
                                            <div
                                                @class([
                                                    'h-full rounded-full transition-all duration-1000 relative shadow-sm',
                                                    'bg-emerald-500 shadow-[0_0_12px_rgba(16,185,129,0.3)]' => $progress === 100,
                                                    'bg-gradient-to-r from-teal-400 to-teal-600' => $progress < 100,
                                                ])
                                                style="width: {{ $progress }}%"
                                            >
                                                <div class="absolute inset-0 animate-shimmer opacity-30"></div>
                                            </div>
                                        </div>

                                        <div class="flex gap-3 mt-1">
                                            <div @class([
                                                'flex-1 flex items-center justify-center gap-2 px-3 py-1.5 rounded-lg border text-[11px] font-bold transition-all',
                                                'bg-teal-50 border-teal-100 text-teal-700 dark:bg-teal-900/20 dark:border-teal-800' => $hasAnggaran,
                                                'bg-slate-50 border-slate-100 text-slate-300 opacity-60 dark:bg-slate-800/50 dark:border-slate-800' => !$hasAnggaran
                                            ])>
                                                <span>ANGGARAN</span>
                                                @if($hasAnggaran) <x-heroicon-m-check class="w-3.5 h-3.5" /> @endif
                                            </div>

                                            <div @class([
                                                'flex-1 flex items-center justify-center gap-2 px-3 py-1.5 rounded-lg border text-[11px] font-bold transition-all',
                                                'bg-emerald-50 border-emerald-100 text-emerald-700 dark:bg-emerald-900/20 dark:border-emerald-800' => $hasPR,
                                                'bg-white border-teal-200 text-teal-600 ring-4 ring-teal-500/5' => !$hasPR && $hasAnggaran,
                                                'bg-slate-50 border-slate-100 text-slate-300 opacity-60 dark:bg-slate-800/50 dark:border-slate-800' => !$hasPR && !$hasAnggaran
                                            ])>
                                                <span>PR TERBIT</span>
                                                @if($hasPR) <x-heroicon-m-shield-check class="w-3.5 h-3.5" /> @elseif($hasAnggaran) <x-heroicon-m-arrow-path class="w-3.5 h-3.5 animate-spin" /> @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-8 py-7 align-top text-right">
                                    <div class="flex flex-col items-end gap-2">
                                        <span @class([
                                            'text-xl font-extrabold tracking-tight',
                                            'text-slate-950 dark:text-white' => $hasAnggaran,
                                            'text-slate-300 italic font-bold' => !$hasAnggaran
                                        ])>
                                            {{ $hasAnggaran ? 'Rp ' . number_format($item->total, 0, ',', '.') : 'Belum Input' }}
                                        </span>
                                        @if($hasPR)
                                            <span class="text-xs font-black px-2.5 py-1 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 rounded-lg border border-emerald-200 dark:border-emerald-800 uppercase tracking-widest">
                                                {{ $item->nomor_pr }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-8 py-7 align-top text-right">
                                    <div class="flex gap-2">
                                        <button wire:click="openEditModal({{ $item->id }})" class="p-3 text-slate-400 hover:text-teal-600 hover:bg-teal-50 dark:hover:bg-teal-900/20 rounded-xl transition-all shadow-sm hover:shadow-md">
                                            <x-heroicon-m-rectangle-group class="w-6 h-6" />
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-32 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="p-6 bg-slate-50 dark:bg-slate-900 rounded-full mb-6">
                                            <x-heroicon-o-document-magnifying-glass class="w-16 h-16 text-slate-200 dark:text-slate-800" />
                                        </div>
                                        <p class="text-base font-bold text-slate-400 uppercase tracking-[0.3em]">No Data Intelligence Found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer Pagination --}}
            <footer class="p-6 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 flex justify-between items-center text-sm font-bold text-slate-500 uppercase tracking-widest">
                <div>Menampilkan {{ count($this->getTableRecords()) }} entri data</div>
                <div class="flex gap-3">
                    <button class="px-5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl opacity-50 cursor-not-allowed">Prev</button>
                    <button class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm hover:bg-slate-50 transition">Next</button>
                </div>
            </footer>
        </div>

        {{-- System Meta --}}
        <footer class="flex justify-between items-center px-2">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-[0.25em]">
                 {{ now()->format('H:i') }} WIB
            </div>
        </footer>
    </main>

    {{-- Edit Modal --}}
    @if($showEditModal && $selectedRecord)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Edit Pengajuan PR</h2>
                <button wire:click="closeEditModal" class="text-slate-400 hover:text-slate-600">
                    <x-heroicon-m-x-mark class="w-6 h-6" />
                </button>
            </div>

            <form wire:submit.prevent="saveEdit" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nama Pekerjaan</label>
                    <input type="text" readonly value="{{ $selectedRecord->nama_pekerjaan ?? $selectedRecord->nama_perkerjaan ?? 'Belum Input' }}" class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Total</label>
                    <input type="text" value="{{ $selectedRecord->total ? 'Rp ' . number_format($selectedRecord->total, 0, ',', '.') : 'Belum Input' }}" disabled class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Upload File</label>
                    <button type="button" wire:click="downloadFiles({{ $selectedRecord->id }})" class="w-full px-4 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-medium transition-colors">
                        <x-heroicon-m-arrow-down-tray class="w-5 h-5 inline mr-2" />
                        Download Semua File
                    </button>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nomor PR</label>
                    <input type="text" wire:model="nomor_pr" class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-slate-700 dark:text-slate-100">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Proses PR Screenshots</label>
                    <input type="file" wire:model="proses_pr_screenshots" multiple accept="image/*" class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-slate-700 dark:text-slate-100" id="screenshots-input">
                    <p class="text-xs text-slate-500 mt-1">Anda juga dapat paste gambar langsung (Ctrl+V)</p>
                </div>

                <div class="flex justify-end gap-4">
                    <button type="button" wire:click="closeEditModal" class="px-6 py-3 bg-slate-300 hover:bg-slate-400 text-slate-700 rounded-xl font-medium transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-medium transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <script>
        // Toast notification
        function showToast(message, type = 'success') {
            const toastId = 'toast-' + Date.now();
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';

            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = `fixed bottom-5 right-5 ${bgColor} text-white px-6 py-4 rounded-lg shadow-lg z-[9999] animate-fadeIn`;
            toast.textContent = message;

            const style = document.createElement('style');
            style.textContent = `
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(10px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                .animate-fadeIn { animation: fadeIn 0.3s ease-in-out; }
            `;
            if (!document.getElementById('fade-in-style')) {
                style.id = 'fade-in-style';
                document.head.appendChild(style);
            }

            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        // Handle file upload via Livewire
        function handleScreenshotFiles(files, inputElement) {
            if (!inputElement) return;

            // Create DataTransfer to set files
            const dataTransfer = new DataTransfer();

            // Add existing files if any
            if (inputElement.files.length > 0) {
                Array.from(inputElement.files).forEach(file => {
                    dataTransfer.items.add(file);
                });
            }

            // Add new files
            files.forEach(file => {
                dataTransfer.items.add(file);
            });

            inputElement.files = dataTransfer.files;

            // Trigger change event to notify Livewire
            inputElement.dispatchEvent(new Event('change', { bubbles: true }));

            // Show feedback to user
            showToast(`${files.length} gambar berhasil ditambahkan!`, 'success');
            console.log('[Screenshots] Files handled:', files.length);
        }

        // Attach handlers to a specific input element
        function attachHandlersToInput(screenshotsInput) {
            if (!screenshotsInput) return;

            // Drag and drop handlers
            screenshotsInput.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                screenshotsInput.style.borderColor = '#14b8a6';
                screenshotsInput.style.backgroundColor = 'rgba(20, 184, 166, 0.05)';
            }, false);

            screenshotsInput.addEventListener('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                screenshotsInput.style.borderColor = '';
                screenshotsInput.style.backgroundColor = '';
            }, false);

            screenshotsInput.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                screenshotsInput.style.borderColor = '';
                screenshotsInput.style.backgroundColor = '';

                const files = Array.from(e.dataTransfer.files).filter(file => file.type.indexOf('image') !== -1);
                if (files.length > 0) {
                    console.log('[Screenshots] Drop detected:', files.length, 'files');
                    handleScreenshotFiles(files, screenshotsInput);
                }
            }, false);

            console.log('[Screenshots] Handlers attached to input');
        }

        // Global paste handler with proper modal detection
        document.addEventListener('paste', function(e) {
            const screenshotsInput = document.getElementById('screenshots-input');

            if (!screenshotsInput) {
                console.log('[Paste] Input tidak ditemukan');
                return;
            }

            // Check if input is in the visible modal
            const modal = screenshotsInput.closest('.fixed');
            const isModalVisible = modal && window.getComputedStyle(modal).display !== 'none';

            console.log('[Paste] Detected - Modal visible:', isModalVisible);

            if (!isModalVisible) return;

            const items = e.clipboardData.items;
            const files = [];

            for (let i = 0; i < items.length; i++) {
                if (items[i].kind === 'file' && items[i].type.indexOf('image') !== -1) {
                    const file = items[i].getAsFile();
                    console.log('[Paste] Image file detected:', file.name, file.size);
                    files.push(file);
                }
            }

            if (files.length > 0) {
                console.log('[Paste] Total images found:', files.length);
                e.preventDefault();
                e.stopImmediatePropagation();
                handleScreenshotFiles(files, screenshotsInput);
            }
        }, false);

        // Watch for input element appearing in DOM
        function initializeWatcher() {
            // Try to attach handlers immediately if input exists
            const screenshotsInput = document.getElementById('screenshots-input');
            if (screenshotsInput) {
                console.log('[Screenshots] Input ditemukan - attaching handlers');
                attachHandlersToInput(screenshotsInput);
            }

            // Use MutationObserver to detect when input is added to DOM
            const observer = new MutationObserver(function(mutations) {
                const screenshotsInput = document.getElementById('screenshots-input');
                if (screenshotsInput && !screenshotsInput.dataset.handlerAttached) {
                    console.log('[Screenshots] Input muncul di DOM - attaching handlers');
                    screenshotsInput.dataset.handlerAttached = 'true';
                    attachHandlersToInput(screenshotsInput);
                }
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true,
                attributes: false,
                characterData: false
            });

            console.log('[Screenshots] Watcher initialized');
        }

        // Initialize on page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeWatcher);
        } else {
            initializeWatcher();
        }

        // Re-initialize after Livewire updates
        document.addEventListener('livewire:updated', initializeWatcher);
    </script>
</x-filament-panels::page>
