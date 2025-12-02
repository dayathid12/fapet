<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popup Peminjaman Berhasil</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Plus Jakarta Sans (Modern & Geometric) -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* --- Animations --- */
        
        /* Modal Entrance: Pop & Fade */
        @keyframes modalEnter {
            0% {
                opacity: 0;
                transform: scale(0.95) translateY(10px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        
        .animate-modal-enter {
            animation: modalEnter 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Checkmark Drawing Animation */
        .checkmark-path {
            stroke-dasharray: 60;
            stroke-dashoffset: 60;
            animation: drawCheck 0.6s 0.3s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }

        @keyframes drawCheck {
            100% {
                stroke-dashoffset: 0;
            }
        }

        /* Circle Scale Animation */
        .circle-scale {
            animation: circlePop 0.4s ease-out forwards;
        }

        @keyframes circlePop {
            0% { transform: scale(0); opacity: 0; }
            80% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Background Decoration (Optional) -->
    <div class="absolute inset-0 z-0 opacity-40 pointer-events-none">
        <div class="absolute top-0 left-0 w-64 h-64 bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    </div>

    <!-- Trigger Button -->
    <button onclick="openModal()" class="z-10 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 font-medium py-3 px-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-0.5 flex items-center gap-2">
        <span>Tampilkan Popup</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    </button>

    <!-- Modal Backdrop -->
    <div id="backdrop" class="fixed inset-0 bg-gray-900/40 backdrop-blur-[2px] z-40 transition-opacity duration-300 opacity-0 pointer-events-none" aria-hidden="true" onclick="closeModal()"></div>

    <!-- Modal Card -->
    <div id="modal" class="fixed z-50 w-full max-w-[360px] p-4 opacity-0 pointer-events-none transition-all duration-300 transform scale-95" role="dialog" aria-modal="true">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden relative">
            
            <!-- Top Decoration Line -->
            <div class="h-1.5 w-full bg-gradient-to-r from-green-400 to-emerald-500"></div>

            <div class="p-6 pt-8 text-center">
                <!-- Animated Success Icon -->
                <div class="circle-scale mx-auto w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mb-6 relative">
                    <!-- Outer Ring Effect -->
                    <div class="absolute inset-0 rounded-full border-4 border-green-100 opacity-50"></div>
                    
                    <svg class="w-10 h-10 text-green-600 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path class="checkmark-path" stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <!-- Main Heading -->
                <h2 class="text-2xl font-bold text-gray-900 mb-2 tracking-tight">Berhasil!</h2>
                
                <!-- Main Message -->
                <p class="text-gray-500 text-[15px] leading-relaxed mb-6">
                    Permohonan peminjaman kendaraan berhasil diajukan.
                </p>

                <!-- WhatsApp Notification Box -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50/50 border border-green-100 rounded-2xl p-4 mb-6 text-left flex items-start gap-3.5 shadow-sm">
                    <!-- WA Icon Container -->
                    <div class="flex-shrink-0 bg-white p-1.5 rounded-lg shadow-sm border border-green-100">
                        <svg class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </div>
                    <!-- WA Text -->
                    <div class="flex-1">
                        <p class="text-[11px] font-bold text-green-800 uppercase tracking-wide mb-0.5">Notifikasi Dikirim</p>
                        <p class="text-sm text-gray-700 leading-snug">Cek nomor <span class="font-semibold text-green-700">Whatsapp</span> untuk melihat link tracking.</p>
                    </div>
                </div>

                <!-- Action Button -->
                <button onclick="closeModal()" class="w-full bg-gray-900 hover:bg-black text-white font-semibold py-3.5 px-4 rounded-xl shadow-lg shadow-gray-200 transition-all duration-200 transform hover:-translate-y-0.5 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                    Oke, Saya Mengerti
                </button>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modal');
        const backdrop = document.getElementById('backdrop');
        const checkPath = document.querySelector('.checkmark-path');
        const circleIcon = document.querySelector('.circle-scale');

        function openModal() {
            // Show Backdrop
            backdrop.classList.remove('opacity-0', 'pointer-events-none');
            
            // Show Modal Container
            modal.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
            
            // Trigger Animations
            const card = modal.querySelector('div'); // The inner card
            card.classList.add('animate-modal-enter');
            
            // Reset & Replay Checkmark Animation
            checkPath.style.animation = 'none';
            checkPath.offsetHeight; /* trigger reflow */
            checkPath.style.animation = 'drawCheck 0.6s 0.3s cubic-bezier(0.65, 0, 0.45, 1) forwards';

            // Reset & Replay Circle Animation
            circleIcon.style.animation = 'none';
            circleIcon.offsetHeight;
            circleIcon.style.animation = 'circlePop 0.4s ease-out forwards';
        }

        function closeModal() {
            // Hide Backdrop
            backdrop.classList.add('opacity-0', 'pointer-events-none');
            
            // Hide Modal
            modal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
            
            // Cleanup animation classes
            const card = modal.querySelector('div');
            card.classList.remove('animate-modal-enter');
        }

        // Auto open on load for demo purposes
        window.addEventListener('load', () => {
            setTimeout(openModal, 600);
        });
    </script>
</body>
</html>
