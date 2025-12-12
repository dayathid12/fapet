<div class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-600 dark:text-slate-300 font-sans relative overflow-hidden transition-colors duration-300">

    {{-- Decorative Background Blobs (Lebih Berwarna / Colorful) --}}
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        {{-- Menggunakan warna Indigo, Fuchsia, dan Orange untuk kesan ceria --}}
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-400/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-fuchsia-400/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute top-[20%] right-[20%] w-[30%] h-[30%] bg-orange-300/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 4s;"></div>
    </div>

    <div class="relative z-10 w-full p-6 lg:p-10 max-w-[1920px] mx-auto">

        {{-- Header Section for Combined Calendar --}}
        <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-6 animate-fade-in-up">
            <div>
                <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white mb-3 text-center md:text-left">
                    Manajemen Jadwal
                </h1>
                <p class="text-lg text-slate-500 dark:text-slate-400 font-medium max-w-2xl leading-relaxed text-center md:text-left">
                    Kelola jadwal kendaraan dan pengemudi dalam satu tampilan terpadu.
                </p>
            </div>
        </div>

        {{-- Main Glass Card --}}
        <div class="backdrop-blur-xl bg-white/90 dark:bg-slate-900/80 rounded-[2rem] shadow-2xl shadow-indigo-200/50 dark:shadow-none border border-white/50 dark:border-slate-700 overflow-hidden flex flex-col min-h-[700px] w-full ring-1 ring-slate-900/5">

            {{-- Tabs --}}
            <div x-data="{ activeTab: 'kendaraan' }" class="p-6 border-b border-slate-200 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-800/40">
                <div class="flex space-x-4">
                    <button
                        @click="activeTab = 'kendaraan'"
                        :class="{ 'bg-indigo-600 text-white': activeTab === 'kendaraan', 'bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-slate-600': activeTab !== 'kendaraan' }"
                        class="px-5 py-2 rounded-xl font-semibold transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
                    >
                        Jadwal Kendaraan
                    </button>
                    <button
                        @click="activeTab = 'pengemudi'"
                        :class="{ 'bg-indigo-600 text-white': activeTab === 'pengemudi', 'bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-slate-600': activeTab !== 'pengemudi' }"
                        class="px-5 py-2 rounded-xl font-semibold transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
                    >
                        Jadwal Pengemudi
                    </button>
                </div>
            </div>

            {{-- Tab Content --}}
            <div class="flex-grow p-0">
                <div x-show="activeTab === 'kendaraan'" x-cloak>
                    @livewire('booking-kendaraan-calendar')
                </div>
                <div x-show="activeTab === 'pengemudi'" x-cloak>
                    @livewire('jadwal-pengemudi-calendar')
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-900/80 flex justify-between items-center text-xs font-medium text-slate-50">
                <span class="flex items-center gap-2">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-50"></span>
                    </span>
                    Manajemen terpadu dengan Livewire & Alpine.js
                </span>
                <span class="opacity-60 font-mono">v2.1 Colorful Edition</span>
            </div>
        </div>
    </div>

    {{-- Styles for clean scrollbar --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar { height: 10px; width: 10px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 100vh;
            border: 3px solid transparent;
            background-clip: content-box;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fade-in-up 0.6s ease-out forwards; }
    </style>
</div>