<x-filament-panels::page>
    {{ $this->infolist }}

    @php
        // Data Query & Helpers
        $rincianBiayas = $this->rincianPengeluaran->rincianBiayas;
        $bbm = $rincianBiayas->where('tipe', 'bbm');
        $toll = $rincianBiayas->where('tipe', 'toll');
        $parkir = $rincianBiayas->where('tipe', 'parkir');
        $rp = fn($v) => 'Rp' . number_format($v, 0, ',', '.');
    @endphp

    <div class="mt-8 mb-10 grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- BBM --}}
    <div class="group relative rounded-3xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-1 transition-all hover:shadow-lg">
        <div class="relative h-full overflow-hidden rounded-[1.2rem] bg-gray-50/50 dark:bg-gray-800/50 p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div class="h-10 w-10 rounded-full bg-white dark:bg-gray-700 flex items-center justify-center shadow-sm text-rose-500">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" /></svg>
                </div>
                <span class="text-xs font-medium px-2 py-1 rounded bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">BBM</span>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1 tracking-tight">
                    {{ $rp($bbm->sum('biaya')) }}
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Pengeluaran</p>
            </div>
        </div>
    </div>

    {{-- TOLL --}}
    <div class="group relative rounded-3xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-1 transition-all hover:shadow-lg">
        <div class="relative h-full overflow-hidden rounded-[1.2rem] bg-gray-50/50 dark:bg-gray-800/50 p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div class="h-10 w-10 rounded-full bg-white dark:bg-gray-700 flex items-center justify-center shadow-sm text-violet-500">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>
                </div>
                <span class="text-xs font-medium px-2 py-1 rounded bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300">Toll</span>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1 tracking-tight">
                     {{ $rp($toll->sum('biaya')) }}
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Pengeluaran</p>
            </div>
        </div>
    </div>

    {{-- PARKIR --}}
    <div class="group relative rounded-3xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-1 transition-all hover:shadow-lg">
        <div class="relative h-full overflow-hidden rounded-[1.2rem] bg-gray-50/50 dark:bg-gray-800/50 p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div class="h-10 w-10 rounded-full bg-white dark:bg-gray-700 flex items-center justify-center shadow-sm text-emerald-500">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                </div>
                <span class="text-xs font-medium px-2 py-1 rounded bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">Parkir</span>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1 tracking-tight">
                     {{ $rp($parkir->sum('biaya')) }}
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Pengeluaran</p>
            </div>
        </div>
    </div>
</div>

    {{-- ================= BOTTOM SECTION: CLEAN DETAIL LISTS ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">

        {{-- WRAPPER STYLE FOR ALL LISTS --}}
        @php
            $listWrapperClass = "rounded-[2rem] p-6 shadow-sm border border-gray-100/80 dark:border-gray-800/80 bg-white/80 dark:bg-gray-900/60 backdrop-blur-lg flex flex-col h-full transition-all hover:shadow-md";
            $headerBorderClass = "pb-4 mb-4 border-b border-gray-100 dark:border-gray-800 flex items-center gap-3";
        @endphp

        {{-- KOLOM 1: BBM --}}
        <div class="{{ $listWrapperClass }}">
            <div class="{{ $headerBorderClass }}">
                <div class="w-8 h-8 rounded-xl bg-rose-100 dark:bg-rose-900/50 flex items-center justify-center text-rose-600 dark:text-rose-400 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h3 class="font-bold text-lg text-gray-800 dark:text-white">Rincian BBM</h3>
            </div>
            <div class="space-y-3">
                @forelse($bbm as $item)
                    <div class="group flex flex-col p-3 rounded-2xl bg-gray-50/50 dark:bg-gray-800/40 border border-gray-100/50 dark:border-gray-700/30 hover:bg-rose-50/50 dark:hover:bg-rose-900/10 transition-colors">
                        <div class="flex items-center gap-2">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-700 dark:text-gray-200 text-sm">{{ $item->jenis_bbm }}</h4>
                                <span class="text-xs text-gray-400">{{ $item->volume }} Liter</span>
                            </div>
                            <span class="font-bold text-sm text-rose-600 dark:text-rose-400">{{ $rp($item->biaya) }}</span>
                        </div>
                        @if($item->deskripsi)
                            <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-100/50 dark:border-gray-700/30">
                                <p class="text-[11px] text-gray-400 italic line-clamp-1 group-hover:text-gray-500 dark:group-hover:text-gray-300 transition-colors">{{ $item->deskripsi }}</p>
                                <form action="{{ route('biaya.delete', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus item ini?')" class="inline opacity-50">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @empty
                     <div class="py-6 text-center text-sm text-gray-400 italic">Tidak ada data BBM.</div>
                @endforelse
            </div>
        </div>

        {{-- KOLOM 2: TOLL --}}
        <div class="{{ $listWrapperClass }}">
             <div class="{{ $headerBorderClass }}">
                <div class="w-8 h-8 rounded-xl bg-violet-100 dark:bg-violet-900/50 flex items-center justify-center text-violet-600 dark:text-violet-400 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                </div>
                <h3 class="font-bold text-lg text-gray-800 dark:text-white">Rincian Toll</h3>
            </div>
            <div class="space-y-3">
                @forelse($toll as $item)
                    <div class="flex items-center gap-2 p-3 rounded-2xl bg-gray-50/50 dark:bg-gray-800/40 border border-gray-100/50 dark:border-gray-700/30 hover:bg-violet-50/50 dark:hover:bg-violet-900/10 transition-colors">
                        <form action="{{ route('biaya.delete', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus item ini?')" class="inline opacity-50">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                        <h4 class="font-bold text-gray-700 dark:text-gray-200 text-sm leading-tight flex-1">
                            {{ $item->deskripsi ?? 'Gerbang Toll' }}
                        </h4>
                        <span class="font-bold text-sm text-violet-600 dark:text-violet-400">{{ $rp($item->biaya) }}</span>
                    </div>
                @empty
                     <div class="py-6 text-center text-sm text-gray-400 italic">Tidak ada data Toll.</div>
                @endforelse
            </div>
        </div>

        {{-- KOLOM 3: PARKIR --}}
        <div class="{{ $listWrapperClass }}">
             <div class="{{ $headerBorderClass }}">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                </div>
                <h3 class="font-bold text-lg text-gray-800 dark:text-white">Rincian Parkir</h3>
            </div>
            <div class="space-y-3">
                @forelse($parkir as $item)
                    <div class="flex items-center gap-2 p-3 rounded-2xl bg-gray-50/50 dark:bg-gray-800/40 border border-gray-100/50 dark:border-gray-700/30 hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10 transition-colors">
                        <form action="{{ route('biaya.delete', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus item ini?')" class="inline opacity-50">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                        <h4 class="font-bold text-gray-700 dark:text-gray-200 text-sm leading-tight flex-1">
                            {{ $item->deskripsi ?? 'Lokasi Parkir' }}
                        </h4>
                        <span class="font-bold text-sm text-emerald-600 dark:text-emerald-400">{{ $rp($item->biaya) }}</span>
                    </div>
                @empty
                     <div class="py-6 text-center text-sm text-gray-400 italic">Tidak ada data Parkir.</div>
                @endforelse
            </div>
        </div>

    </div>
</x-filament-panels::page>

@push('scripts')
    <script src='https://unpkg.com/tesseract.js@5.0.3/dist/tesseract.min.js'></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('ocrHandler', () => ({
                init() {
                    console.log('OCR Handler initialized.');
                },
                async processImage(event, livewireComponent) { // Added livewireComponent argument
                    console.log('Processing image...');
                    const file = event.target.files[0];
                    if (!file) {
                        return;
                    }

                    // TODO: Add OCR logic here
                    console.log('File selected:', file.name);

                    // Example: try to read the file
                    const reader = new FileReader();
                    reader.onload = async (e) => {
                        const image = e.target.result;
                        console.log('Image loaded, starting OCR...');
                        try {
                            const { data: { text } } = await Tesseract.recognize(
                                image,
                                'eng', // Language
                                { logger: m => console.log(m) }
                            );
                            console.log('OCR Result:', text);

                            const ocrText = text.toLowerCase();
                            let biaya = 0;
                            let deskripsi = '';
                            let jenis_bbm = null;
                            let volume = 0;
                            let tipe = null; // New variable to determine the type (bbm, toll, parkir)

                            // --- Robust Parsing Logic ---
                            // Identify type first
                            if (ocrText.includes('bbm') || ocrText.includes('bensin') || ocrText.includes('pertamax') || ocrText.includes('dekslite') || ocrText.includes('liter')) {
                                tipe = 'bbm';
                            } else if (ocrText.includes('toll') || ocrText.includes('tol') || ocrText.includes('gerbang')) {
                                tipe = 'toll';
                            } else if (ocrText.includes('parkir') || ocrText.includes('parking') || ocrText.includes('lokasi')) {
                                tipe = 'parkir';
                            }

                            // Extract 'biaya' (amount) - looking for Rp or currency symbols followed by numbers
                            const biayaMatch = ocrText.match(/(?:rp|idr|\$|€|£)\s*(\d{1,3}(?:[.,]\d{3})*(?:[.,]\d{2})?)/);
                            if (biayaMatch && biayaMatch[1]) {
                                biaya = parseFloat(biayaMatch[1].replace(/\./g, '').replace(/,/g, '.'));
                            } else {
                                // Fallback: look for numbers that look like amounts, perhaps near 'total' or 'jumlah'
                                const totalMatch = ocrText.match(/(?:total|jumlah|harga)\s*(\d{1,3}(?:[.,]\d{3})*(?:[.,]\d{2})?)/);
                                if (totalMatch && totalMatch[1]) {
                                    biaya = parseFloat(totalMatch[1].replace(/\./g, '').replace(/,/g, '.'));
                                }
                            }

                            // Extract 'jenis_bbm'
                            if (tipe === 'bbm') {
                                if (ocrText.includes('pertamax')) {
                                    jenis_bbm = 'Pertamax';
                                } else if (ocrText.includes('dexlite') || ocrText.includes('dekslite')) {
                                    jenis_bbm = 'Dexlite';
                                } else if (ocrText.includes('solar')) {
                                    jenis_bbm = 'Lainnya'; // Assuming 'Solar'
                                } else {
                                    jenis_bbm = 'Lainnya'; // Default for other BBM types
                                }
                            }

                            // Extract 'volume'
                            if (tipe === 'bbm') {
                                const volumeMatch = ocrText.match(/(\d+(?:[.,]\d+)?)\s*(liter|lt)/);
                                if (volumeMatch && volumeMatch[1]) {
                                    volume = parseFloat(volumeMatch[1].replace(/,/g, '.'));
                                }
                            }

                            // Extract 'deskripsi'
                            // This is highly dependent on the document. For now, a generic approach.
                            // For BBM: try to find pump/station name or transaction ID
                            // For Toll: try to find gate name or card ID
                            // For Parkir: try to find location
                            if (tipe === 'bbm') {
                                 // Look for common keywords near numbers that could be transaction IDs or locations
                                 const bbmDescMatch = ocrText.match(/(spbu|pom bensin|station|transaksi|lokasi)\s*([a-zA-Z0-9\s#\/\-]+)/);
                                 deskripsi = bbmDescMatch ? bbmDescMatch[2].trim() : ocrText.substring(0, 100);
                            } else if (tipe === 'toll') {
                                 const tollDescMatch = ocrText.match(/(gerbang|gate|card|kartu)\s*([a-zA-Z0-9\s#\/\-]+)/);
                                 deskripsi = tollDescMatch ? tollDescMatch[2].trim() : ocrText.substring(0, 100);
                            } else if (tipe === 'parkir') {
                                const parkirDescMatch = ocrText.match(/(lokasi|area|tempat)\s*parkir\s*([a-zA-Z0-9\s#\/\-]+)/);
                                deskripsi = parkirDescMatch ? parkirDescMatch[2].trim() : ocrText.substring(0, 100);
                            } else {
                                deskripsi = ocrText.substring(0, 100); // Generic fallback
                            }
                            if (deskripsi.length > 255) { // Ensure description fits typical database column
                                deskripsi = deskripsi.substring(0, 255);
                            }


                            // --- Populate fields using Livewire component ---
                            if (livewireComponent) {
                                livewireComponent.set('data.tipe', tipe);
                                livewireComponent.set('data.biaya', biaya);
                                livewireComponent.set('data.deskripsi', deskripsi);
                                if (tipe === 'bbm') {
                                    livewireComponent.set('data.jenis_bbm', jenis_bbm);
                                    livewireComponent.set('data.volume', volume);
                                }
                            }

                            console.log('Form fields updated:');
                            console.log('Tipe:', tipe);
                            console.log('Biaya:', biaya);
                            console.log('Deskripsi:', deskripsi);
                            if (tipe === 'bbm') {
                                console.log('Jenis BBM:', jenis_bbm);
                                console.log('Volume:', volume);
                            }

                        } catch (error) {
                            console.error('OCR Error:', error);
                        }
                    };
                    reader.readAsDataURL(file);
                }
            }));
        });
    </script>
@endpush
