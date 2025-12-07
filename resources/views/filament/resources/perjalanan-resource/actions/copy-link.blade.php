@props(['record'])

@php
    $nomor_perjalanan = $record->nomor_perjalanan;
    $url = $nomor_perjalanan ? url('/peminjaman/status/' . $nomor_perjalanan) : '';
@endphp

@if ($url)
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6 text-left shadow-sm animate-bounce-short" style="animation-delay: 0.1s;">
        <p class="text-xs text-yellow-700 mb-3 leading-relaxed">
            Gunakan informasi di bawah ini untuk melacak progres pengajuan Anda secara real-time.
        </p>

        <div class="bg-white border border-yellow-300 rounded-lg p-3 flex items-center justify-between shadow-inner group relative">
            <div class="overflow-hidden mr-2 w-full">
                <p class="text-[10px] text-gray-400 mb-0.5 uppercase tracking-wide">Link Tracking:</p>
                <code id="trackingUrl" class="text-gray-800 font-mono text-xs block truncate select-all">{{ $url }}</code>
            </div>
            <button onclick="copyTrackingLink()" class="flex-shrink-0 p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-md transition-colors relative" title="Salin Link" id="copyTrackingLinkBtn">
                <i id="copyTrackingLinkIcon" class="fa-regular fa-copy text-lg"></i>
                <span id="copyTrackingLinkTooltip" class="absolute -top-8 -left-2 bg-gray-800 text-white text-[10px] py-1 px-2 rounded opacity-0 transition-opacity duration-200 pointer-events-none">Disalin!</span>
            </button>
        </div>
    </div>

    <script>
        function copyTrackingLink() {
            const trackingUrl = document.getElementById('trackingUrl').textContent;
            const copyBtn = document.getElementById('copyTrackingLinkBtn');
            const copyIcon = document.getElementById('copyTrackingLinkIcon');
            const tooltip = document.getElementById('copyTrackingLinkTooltip');

            if (navigator.clipboard) {
                navigator.clipboard.writeText(trackingUrl).then(() => {
                    // Change icon to checkmark
                    copyIcon.className = 'fa-solid fa-check text-lg';
                    copyBtn.classList.add('text-green-600');
                    tooltip.classList.remove('opacity-0');
                    tooltip.textContent = 'Disalin!';

                    // Reset after 2 seconds
                    setTimeout(() => {
                        copyIcon.className = 'fa-regular fa-copy text-lg';
                        copyBtn.classList.remove('text-green-600');
                        tooltip.classList.add('opacity-0');
                    }, 2000);
                }).catch(err => {
                    console.error('Error copying to clipboard:', err);
                    alert('Gagal menyalin link. Silakan salin secara manual.');
                });
            } else {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = trackingUrl;
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    copyIcon.className = 'fa-solid fa-check text-lg';
                    copyBtn.classList.add('text-green-600');
                    tooltip.classList.remove('opacity-0');
                    tooltip.textContent = 'Disalin!';

                    setTimeout(() => {
                        copyIcon.className = 'fa-regular fa-copy text-lg';
                        copyBtn.classList.remove('text-green-600');
                        tooltip.classList.add('opacity-0');
                    }, 2000);
                } catch (err) {
                    console.error('Fallback copy failed:', err);
                    alert('Gagal menyalin link. Silakan salin secara manual.');
                }
                document.body.removeChild(textArea);
            }
        }
    </script>
@endif
