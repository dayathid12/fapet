<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Peminjaman Kendaraan - Universitas Padjadjaran</title>

    <!-- Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Tom Select -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>


    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        unpad: {
                            orange: '#F7941E', // Unpad Orange nuance
                            blue: '#005b9f',   // Unpad Blue nuance
                            dark: '#0f172a',
                        }
                    },
                    animation: {
                        'fade-up': 'fadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                        'fade-in': 'fadeIn 0.3s ease-out forwards',
                        'scale-in': 'scaleIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    },
                    keyframes: {
                        fadeUp: {
                            '0%': { opacity: '0', transform: 'translateY(15px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        scaleIn: {
                            '0%': { opacity: '0', transform: 'scale(0.95)' },
                            '100%': { opacity: '1', transform: 'scale(1)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* NEW: Modern Animated Background */
        .bg-gradient-animate {
            background: linear-gradient(-45deg, #f8fafc, #eff6ff, #e0f2fe, #f0f9ff);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* NEW: Floating Blobs */
        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.6;
            animation: float 10s infinite ease-in-out;
        }
        .blob-1 {
            top: -10%;
            left: -10%;
            width: 600px;
            height: 600px;
            background: rgba(0, 91, 159, 0.4); /* Unpad Blue */
            animation-delay: 0s;
            border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
        }
        .blob-2 {
            bottom: -10%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(247, 148, 30, 0.3); /* Unpad Orange */
            animation-delay: -2s;
            border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
        }
        .blob-3 {
            top: 40%;
            left: 40%;
            width: 400px;
            height: 400px;
            background: rgba(99, 102, 241, 0.25); /* Indigo accent */
            animation-delay: -4s;
            border-radius: 40% 50% 30% 60%;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -50px) rotate(10deg); }
            66% { transform: translate(-20px, 20px) rotate(-5deg); }
        }

        /* NEW: Dot Pattern Overlay */
        .bg-dots {
            background-image: radial-gradient(#cbd5e1 1.5px, transparent 1.5px);
            background-size: 24px 24px;
            opacity: 0.4;
        }

        /* Glassmorphism Card (Updated for better contrast) */
        .tech-card {
            background: rgba(255, 255, 255, 0.9); /* Slightly more opaque */
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow:
                0 20px 25px -5px rgba(0, 0, 0, 0.05),
                0 8px 10px -6px rgba(0, 0, 0, 0.01),
                inset 0 0 0 1px rgba(255, 255, 255, 0.8);
        }

        /* Glassmorphism Card */
        .tech-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow:
                0 4px 6px -1px rgba(0, 0, 0, 0.02),
                0 2px 4px -1px rgba(0, 0, 0, 0.02),
                inset 0 0 0 1px rgba(255, 255, 255, 0.6);
        }

        /* Input Styling */
        .tech-input {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        .tech-input:focus {
            background-color: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            outline: none;
        }
        .tech-input.error {
            border-color: #ef4444;
            background-color: #fef2f2;
        }
        .tech-input:disabled {
            background-color: #e2e8f0;
            color: #94a3b8;
            cursor: not-allowed;
        }

        /* Nav Button Active State */
        .nav-btn.active {
            background-color: #eff6ff;
            color: #2563eb;
        }
        .nav-btn.active i {
            color: #2563eb;
            opacity: 1;
        }

        /* Step Panels */
        .step-panel { display: none; }
        .step-panel.active { display: block; animation: fadeUp 0.5s forwards; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen text-slate-800 font-sans pb-12 relative overflow-x-hidden selection:bg-blue-200 selection:text-blue-900 bg-gradient-animate">

    <!-- BACKGROUND: ANIMATED BLOBS & DOTS -->
    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <!-- Dot Pattern -->
        <div class="absolute inset-0 bg-dots"></div>

        <!-- Animated Blobs -->
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <div class="max-w-5xl mx-auto pt-8 px-4 sm:px-6 relative z-10">

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row items-center justify-between mb-8 animate-fade-up mt-8">
            <div class="flex items-start gap-4">
                <img src="{{ asset('images/Unpad_logo.png') }}" alt="Unpad" class="w-40 h-auto mt-2">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Peminjaman Kendaraan</h1>
                    <div class="flex items-center text-xs text-slate-500 font-semibold uppercase tracking-wider mt-0.5">
                        <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                        Universitas Padjadjaran
                    </div>
                </div>
            </div>

            <!-- Mobile Step Counter (Visual only) -->
            <div class="mt-4 md:mt-0 flex items-center bg-white/80 backdrop-blur px-5 py-2.5 rounded-full border border-slate-200/60 shadow-sm">
                <span class="text-[10px] font-bold text-slate-400 mr-3 uppercase tracking-wider">Progress</span>
                <div class="flex items-center gap-1.5">
                    <div class="step-dot w-2 h-2 rounded-full bg-blue-600 transition-all duration-300" data-step="1"></div>
                    <div class="step-dot w-2 h-2 rounded-full bg-slate-200 transition-all duration-300" data-step="2"></div>
                    <div class="step-dot w-2 h-2 rounded-full bg-slate-200 transition-all duration-300" data-step="3"></div>
                    <div class="step-dot w-2 h-2 rounded-full bg-slate-200 transition-all duration-300" data-step="4"></div>
                </div>
                <span class="text-sm font-bold text-slate-800 ml-3 w-20 text-right" id="stepLabel">Perjalanan</span>
            </div>
        </div>

        <!-- MAIN CONTENT LAYOUT -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- SIDEBAR NAVIGATION (Desktop) -->
            <div class="hidden lg:block lg:col-span-3 animate-fade-up" style="animation-delay: 0.1s;">
                <div class="sticky top-8">
                    <!-- Added Container Wrapper -->
                    <div class="tech-card rounded-3xl p-5 shadow-xl shadow-slate-200/50">
                        <nav class="space-y-2" id="sidebarNav">
                            <button class="w-full flex items-center group p-3.5 rounded-xl transition-all nav-btn active" data-target="1">
                                <i class="ph-bold ph-map-pin text-xl mr-3 text-slate-400 transition-colors"></i>
                                <div class="text-left">
                                    <p class="text-sm font-bold">Perjalanan</p>
                                    <p class="text-[10px] opacity-70 font-medium">Waktu & Lokasi</p>
                                </div>
                            </button>
                            <button class="w-full flex items-center group p-3.5 rounded-xl transition-all nav-btn text-slate-500 hover:bg-slate-50" data-target="2">
                                <i class="ph-bold ph-user text-xl mr-3 text-slate-400 transition-colors"></i>
                                <div class="text-left">
                                    <p class="text-sm font-bold">Pengguna</p>
                                    <p class="text-[10px] opacity-70 font-medium">Data Diri & Tim</p>
                                </div>
                            </button>
                            <button class="w-full flex items-center group p-3.5 rounded-xl transition-all nav-btn text-slate-500 hover:bg-slate-50" data-target="3">
                                <i class="ph-bold ph-clipboard-text text-xl mr-3 text-slate-400 transition-colors"></i>
                                <div class="text-left">
                                    <p class="text-sm font-bold">Detail</p>
                                    <p class="text-[10px] opacity-70 font-medium">Keterangan Lain</p>
                                </div>
                            </button>
                            <button class="w-full flex items-center group p-3.5 rounded-xl transition-all nav-btn text-slate-500 hover:bg-slate-50" data-target="4">
                                <i class="ph-bold ph-check-circle text-xl mr-3 text-slate-400 transition-colors"></i>
                                <div class="text-left">
                                    <p class="text-sm font-bold">Konfirmasi</p>
                                    <p class="text-[10px] opacity-70 font-medium">Finalisasi Data</p>
                                </div>
                            </button>
                        </nav>

                        <!-- Divider line -->
                        <div class="my-5 border-t border-slate-100"></div>

                        <!-- Help Widget inside Container -->
                        <div class="p-5 bg-slate-900 rounded-2xl text-white shadow-lg relative overflow-hidden group">
                            <div class="absolute -right-4 -top-4 text-slate-800 opacity-20 transform group-hover:scale-110 transition-transform duration-500">
                                <i class="ph-fill ph-car text-8xl"></i>
                            </div>
                            <p3 class="text-[10px] font-bold text-blue-400 mb-1 uppercase tracking-wider">Pusat Bantuan</p3>
                                                        <p class="text-xs text-slate-300 leading-relaxed mb-4">Informasi lebih lanjut hubungi kontak di bawah.</p>
                            <a href="https://api.whatsapp.com/send/?phone=62812121&text&type=phone_number&app_absent=0" target="_blank" class="inline-flex items-center text-xs font-bold bg-white/10 hover:bg-white/20 px-3 py-2 rounded-lg transition-colors">
                                <i class="ph-bold ph-whatsapp-logo mr-2"></i> Hubungi Admin
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FORM CARD -->
            <div class="lg:col-span-9 animate-fade-up" style="animation-delay: 0.2s;">
                <div class="tech-card rounded-3xl p-6 sm:p-10 min-h-[550px] flex flex-col relative shadow-xl shadow-slate-200/50">

                    <form id="mainForm" class="flex-1 flex flex-col h-full" onsubmit="event.preventDefault()">
                        <!-- Note: @csrf Removed for static HTML demo -->

                        <!-- STEP 1: PERJALANAN -->
                        <div class="step-panel active" data-step="1">
                            <div class="mb-8 pb-4 border-b border-slate-100">
                                <h2 class="text-2xl font-bold text-slate-900">Detail Perjalanan</h2>
                                <p class="text-sm text-slate-500 mt-1">Lengkapi informasi dasar jadwal penggunaan kendaraan.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Waktu Keberangkatan <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" name="waktu_keberangkatan" class="tech-input w-full px-4 py-3 rounded-xl text-sm font-semibold text-slate-700" required>
                                    <p class="text-red-500 text-[10px] mt-1 hidden error-msg flex items-center"><i class="ph-bold ph-warning mr-1"></i> Wajib diisi</p>
                                </div>
                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Perkiraan Pulang</label>
                                    <input type="datetime-local" name="waktu_kepulangan" class="tech-input w-full px-4 py-3 rounded-xl text-sm font-semibold text-slate-700">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Lokasi Jemput <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <i class="ph-bold ph-map-pin absolute left-4 top-3.5 text-slate-400 text-lg"></i>
                                        <input type="text" name="lokasi_keberangkatan" placeholder="Cth: Gedung Rektorat" class="tech-input w-full pl-11 pr-4 py-3 rounded-xl text-sm font-medium text-slate-700" required>
                                    </div>
                                    <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Wajib diisi</p>
                                </div>
                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Jumlah Penumpang <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <i class="ph-bold ph-users absolute left-4 top-3.5 text-slate-400 text-lg"></i>
                                        <input type="number" name="jumlah_rombongan" min="1" placeholder="0" class="tech-input w-full pl-11 pr-4 py-3 rounded-xl text-sm font-medium text-slate-700" required>
                                    </div>
                                    <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Min 1 orang</p>
                                </div>
                            </div>

                            <div class="mb-6 group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Alamat Tujuan Lengkap <span class="text-red-500">*</span></label>
                                <textarea name="alamat_tujuan" rows="2" class="tech-input w-full px-4 py-3 rounded-xl text-sm font-medium text-slate-700 resize-none" placeholder="Jalan, Nomor, Gedung..." required></textarea>
                                <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Wajib diisi</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Jenis Kegiatan <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <select name="nama_kegiatan" class="tech-input w-full px-4 py-3 rounded-xl text-sm font-medium text-slate-700 appearance-none cursor-pointer" required>
                                            <option value="">Pilih Jenis...</option>
                                            <option value="Perjalanan Dinas">Perjalanan Dinas</option>
                                            <option value="Kuliah Lapangan">Kuliah Lapangan</option>
                                            <option value="Kunjungan Industri">Kunjungan Industri</option>
                                            <option value="Kegiatan Perlombaan">Kegiatan Perlombaan</option>
                                            <option value="Kegiatan Kemahasiswaan">Kegiatan Kemahasiswaan</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                                    </div>
                                    <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Pilih satu</p>
                                </div>
                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Kota Tujuan <span class="text-red-500">*</span></label>
                                    <select id="select-tujuan" name="tujuan_wilayah_id" placeholder="Ketik untuk mencari kota..." required></select>
                                    <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Pilih satu</p>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 2: IDENTITAS -->
                        <div class="step-panel" data-step="2">
                            <div class="mb-8 pb-4 border-b border-slate-100">
                                <h2 class="text-2xl font-bold text-slate-900">Identitas Peminjam</h2>
                                <p class="text-sm text-slate-500 mt-1">Data penanggung jawab peminjaman.</p>
                            </div>

                            <div class="space-y-6">
                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Unit Kerja / Fakultas <span class="text-red-500">*</span></label>
                                    <select id="select-unit-kerja" name="unit_kerja_id" placeholder="Ketik untuk mencari unit kerja..." required></select>
                                    <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Pilih satu</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="group">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                        <input type="text" id="inputNama" name="nama_pengguna" class="tech-input w-full px-4 py-3 rounded-xl text-sm font-medium text-slate-700" required>
                                        <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Wajib diisi</p>
                                    </div>
                                    <div class="group">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">No. WhatsApp <span class="text-red-500">*</span></label>
                                        <input type="tel" id="inputKontak" name="kontak_pengguna" class="tech-input w-full px-4 py-3 rounded-xl text-sm font-medium text-slate-700" required>
                                        <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Wajib diisi</p>
                                    </div>
                                </div>

                                <!-- Box Perwakilan -->
                                <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 mt-4 transition-all">
                                    <div class="flex flex-col gap-2 mb-4">
                                                                        <label class="inline-flex items-center cursor-pointer group">
                                                                            <input type="checkbox" id="useSameInfo" class="sr-only peer">
                                                                            <div class="relative w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600 shadow-inner"></div>
                                                                            <span class="ms-3 text-xs font-bold text-slate-500 group-hover:text-blue-600 transition-colors">Sama dengan peminjam</span>
                                                                        </label>
                                                                        <h3 class="text-sm font-bold text-slate-800 flex items-center">
                                                                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-3">
                                                                                <i class="ph-bold ph-shield-check"></i>
                                                                            </div>
                                                                            Perwakilan Lapangan
                                                                        </h3>
                                                                    </div>                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="group">
                                            <input type="text" id="inputNamaWakil" name="nama_personil_perwakilan" placeholder="Nama Perwakilan" class="tech-input w-full px-4 py-3 rounded-xl text-sm" required>
                                            <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Wajib diisi</p>
                                        </div>
                                        <div class="group">
                                            <input type="tel" id="inputKontakWakil" name="kontak_pengguna_perwakilan" placeholder="Kontak Perwakilan" class="tech-input w-full px-4 py-3 rounded-xl text-sm" required>
                                            <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Wajib diisi</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">Status Pemohon <span class="text-red-500">*</span></label>
                                    <div class="flex flex-wrap gap-3">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="status_sebagai" value="Mahasiswa" class="peer sr-only" required>
                                            <div class="px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition-all hover:bg-slate-50">Mahasiswa</div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="status_sebagai" value="Dosen" class="peer sr-only">
                                            <div class="px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition-all hover:bg-slate-50">Dosen</div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="status_sebagai" value="Staf" class="peer sr-only">
                                            <div class="px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition-all hover:bg-slate-50">Staf</div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="status_sebagai" value="Lainnya" class="peer sr-only">
                                            <div class="px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition-all hover:bg-slate-50">Lainnya</div>
                                        </label>
                                    </div>
                                    <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Pilih satu</p>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 3: DETAIL & UPLOAD -->
                        <div class="step-panel" data-step="3">
                            <div class="mb-8 pb-4 border-b border-slate-100">
                                <h2 class="text-2xl font-bold text-slate-900">Detail Tambahan dan Upload File</h2>
                                <p class="text-sm text-slate-500 mt-1">Informasi pendukung dan upload berkas wajib.</p>
                            </div>

                            <div class="space-y-6">
                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Uraian Kegiatan</label>
                                    <textarea name="uraian_singkat_kegiatan" rows="4" class="tech-input w-full px-4 py-3 rounded-xl text-sm font-medium text-slate-700 resize-none" placeholder="Jelaskan secara singkat agenda kegiatan..."></textarea>
                                </div>
                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Catatan Khusus</label>
                                    <input type="text" name="catatan_keterangan_tambahan" class="tech-input w-full px-4 py-3 rounded-xl text-sm font-medium text-slate-700" placeholder="Opsional (cth: Membawa alat berat, butuh bagasi luas)">
                                </div>

                                <div class="p-4 bg-blue-50 border-l-4 border-blue-400 rounded-r-lg flex items-start">
                                    <i class="ph-fill ph-upload-simple text-blue-500 text-lg mr-3 mt-0.5"></i>
                                    <p class="text-xs text-blue-900 leading-relaxed">
                                        <span class="font-bold">Upload Berkas:</span> Harap siapkan file dalam format PDF, JPG, atau PNG. Ukuran maksimal 5MB.
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="group">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Surat Peminjaman Kendaraan</label>
                                        <input type="file" name="surat_peminjaman" class="tech-input block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer"/>
                                    </div>
                                    <div class="group">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Dokumen Pendukung</label>
                                        <input type="file" name="dokumen_pendukung" class="tech-input block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer"/>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- STEP 4: KONFIRMASI -->
                        <div class="step-panel" data-step="4">
                            <div class="mb-6 pb-4 border-b border-slate-100 text-center">
                                <h2 class="text-2xl font-bold text-slate-900">Konfirmasi Data</h2>
                                <p class="text-sm text-slate-500 mt-1">Pastikan seluruh data valid sebelum dikirim.</p>
                            </div>

                            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                                <div id="confirmationContent" class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 text-sm">
                                    <!-- Content Injected via JS -->
                                </div>
                            </div>

                            <div class="mt-8">
                                <label class="flex items-start gap-4 p-4 border border-slate-200 rounded-xl bg-white cursor-pointer hover:border-blue-400 hover:shadow-md transition-all group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" id="agreementCheck" class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-slate-300 transition-all checked:border-blue-600 checked:bg-blue-600">
                                        <i class="ph-bold ph-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 text-xs"></i>
                                    </div>
                                    <span class="text-xs text-slate-600 leading-relaxed select-none">
                                        Saya menyatakan data di atas benar dan bersedia mengikuti <span class="text-blue-600 font-bold hover:underline">SOP Peminjaman Kendaraan</span> yang berlaku di Universitas Padjadjaran.
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- FOOTER BUTTONS -->
                        <div class="mt-auto pt-8 flex items-center justify-between border-t border-slate-100">
                            <button type="button" id="btnPrev" class="hidden px-6 py-3 rounded-xl text-sm font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors flex items-center">
                                <i class="ph-bold ph-arrow-left mr-2"></i> Kembali
                            </button>

                            <div class="ml-auto">
                                <button type="button" id="btnNext" class="px-8 py-3.5 bg-slate-900 text-white text-sm font-bold rounded-xl shadow-lg shadow-slate-900/20 hover:bg-slate-800 hover:translate-y-[-2px] transition-all flex items-center gap-2 group">
                                    Lanjut <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </button>

                                <button type="button" onclick="submitForm()" id="btnSubmit" class="hidden px-8 py-3.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-600/30 hover:shadow-blue-600/40 hover:scale-[1.02] transition-all flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                                    <span id="btnText">Kirim Permohonan</span>
                                    <i id="btnIcon" class="ph-bold ph-paper-plane-right"></i>
                                    <i id="loadingIcon" class="ph-bold ph-spinner animate-spin hidden"></i>
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SUCCESS MODAL -->
    <!-- Container Utama Modal (Default: hidden/opacity-0, diaktifkan via JS) -->
    <div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0 pointer-events-none">

        <!-- Card Content -->
        <div id="modalContent" class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6 relative transform scale-90 opacity-0 transition-all duration-300 max-h-[95vh] overflow-y-auto mt-10">

            <!-- Tombol Close (X) -->
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
                <i class="fa-solid fa-times text-xl"></i>
            </button>

            <!-- Bagian Icon Premium (Floating) -->
            <div class="flex justify-center -mt-20 mb-6 relative z-20">
                <!-- Efek Glow Belakang -->
                <div class="absolute top-6 left-1/2 -translate-x-1/2 w-20 h-20 bg-green-400 rounded-full blur-2xl opacity-40"></div>

                <!-- Lingkaran Utama -->
                <div class="success-glow relative w-28 h-28 bg-gradient-to-tr from-green-500 to-emerald-400 rounded-full flex items-center justify-center border-[6px] border-white shadow-2xl animate-pop-in">
                    <i class="fa-solid fa-check text-white text-5xl drop-shadow-md transform transition-transform hover:scale-110 duration-200"></i>
                </div>
            </div>

            <!-- Konten Teks -->
            <div class="text-center px-2 pt-2">
                <h2 class="text-2xl font-bold text-gray-800 mb-2 tracking-tight">Berhasil!</h2>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Permohonan peminjaman kendaraan berhasil diajukan.
                </p>

                <!-- Box Notifikasi WhatsApp -->
                <div class="bg-green-50 border border-green-100 rounded-xl p-4 mb-4 flex items-start text-left animate-bounce-short shadow-sm">
                    <div class="flex-shrink-0 mr-3 mt-1">
                        <i class="fa-brands fa-whatsapp text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-green-800 mb-1">Langkah Selanjutnya</h3>
                        <p class="text-xs text-green-700 leading-tight">
                            Cek notifikasi pada nomor Whatsapp Anda untuk mendapatkan link tracking.
                        </p>
                    </div>
                </div>

                <!-- Box Link Tracking -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6 text-left shadow-sm animate-bounce-short" style="animation-delay: 0.1s;">
                    <div class="flex items-start mb-2">
                        <div class="flex-shrink-0 mr-2 mt-0.5">
                            <i class="fa-solid fa-circle-exclamation text-yellow-600 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-yellow-800">PENTING: Simpan Kode Ini</h3>
                        </div>
                    </div>

                    <p class="text-xs text-yellow-700 mb-3 leading-relaxed">
                        Gunakan informasi di bawah ini untuk melacak progres pengajuan Anda secara real-time.
                    </p>

                    <div class="bg-white border border-yellow-300 rounded-lg p-3 flex items-center justify-between shadow-inner group relative mb-4">
                        <div class="overflow-hidden mr-2 w-full">
                            <p class="text-[10px] text-gray-400 mb-0.5 uppercase tracking-wide">Kode Peminjaman (Token):</p>
                            <code id="rawToken" class="text-gray-800 font-mono text-xs block truncate select-all"></code>
                        </div>
                        <button onclick="copyRawToken()" class="flex-shrink-0 p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-md transition-colors relative" title="Salin Kode" id="copyTokenBtn">
                            <i id="copyTokenIcon" class="fa-regular fa-copy text-lg"></i>
                            <span id="copyTokenTooltip" class="absolute -top-8 -left-2 bg-gray-800 text-white text-[10px] py-1 px-2 rounded opacity-0 transition-opacity duration-200 pointer-events-none">Disalin!</span>
                        </button>
                    </div>

                    <div class="bg-white border border-yellow-300 rounded-lg p-3 flex items-center justify-between shadow-inner group relative">
                        <div class="overflow-hidden mr-2 w-full">
                            <p class="text-[10px] text-gray-400 mb-0.5 uppercase tracking-wide">Link Tracking:</p>
                            <code id="trackingUrl" class="text-gray-800 font-mono text-xs block truncate select-all"></code>
                        </div>
                        <button onclick="copyTrackingLink()" class="flex-shrink-0 p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-md transition-colors relative" title="Salin Link" id="copyTrackingLinkBtn">
                            <i id="copyTrackingLinkIcon" class="fa-regular fa-copy text-lg"></i>
                            <span id="copyTrackingLinkTooltip" class="absolute -top-8 -left-2 bg-gray-800 text-white text-[10px] py-1 px-2 rounded opacity-0 transition-opacity duration-200 pointer-events-none">Disalin!</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-2">
                <button onclick="closeModalAndReload()" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-medium py-3.5 rounded-xl transition-all duration-200 shadow-md transform active:scale-95 text-base">
                    OK, Mengerti
                </button>
            </div>
        </div>
    </div>

    <script>
        // --- MODAL LOGIC ---
        function openModal(trackingUrl) {
            const modal = document.getElementById('successModal');
            const content = document.getElementById('modalContent');
            document.getElementById('trackingUrl').innerText = trackingUrl;
            modal.classList.remove('opacity-0', 'pointer-events-none');
            setTimeout(() => {
                content.classList.remove('scale-90', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 50);
        }

        function closeModal() {
            const modal = document.getElementById('successModal');
            const content = document.getElementById('modalContent');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-90', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('opacity-0', 'pointer-events-none');
            }, 200);
        }

        function closeModalAndReload() {
            closeModal();
            // Optional: Add a small delay before reloading to see the closing animation
            setTimeout(() => {
                window.location.reload();
            }, 300);
        }

        function copyLink() {
            const urlText = document.getElementById('trackingUrl').innerText;
            const copyIcon = document.getElementById('copyIcon');
            const tooltip = document.getElementById('copyTooltip');

            navigator.clipboard.writeText(urlText).then(() => {
                // Feedback Visual
                const originalClass = copyIcon.className;
                copyIcon.className = "fa-solid fa-check text-green-500 text-lg";
                tooltip.classList.remove('opacity-0');

                setTimeout(() => {
                    copyIcon.className = originalClass;
                    tooltip.classList.add('opacity-0');
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }

        // --- FORM LOGIC ---
        const form = document.getElementById('mainForm');
        const btnPrev = document.getElementById('btnPrev');
        const btnNext = document.getElementById('btnNext');
        const btnSubmit = document.getElementById('btnSubmit');
        const stepLabel = document.getElementById('stepLabel');
        const agreementCheck = document.getElementById('agreementCheck');

        let currentStep = 1;
        const totalSteps = 4;
        const stepNames = {1: 'Perjalanan', 2: 'Pengguna', 3: 'Detail', 4: 'Konfirmasi'};

        // Definisi field yang wajib diisi per step
        const requiredFields = {
            1: ['waktu_keberangkatan', 'lokasi_keberangkatan', 'jumlah_rombongan', 'alamat_tujuan', 'nama_kegiatan', 'tujuan_wilayah_id'],
            2: ['unit_kerja_id', 'nama_pengguna', 'kontak_pengguna', 'nama_personil_perwakilan', 'kontak_pengguna_perwakilan', 'status_sebagai'],
            3: ['tujuan_wilayah_id_step3'],
            4: []
        };

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            updateUI();

            // Logic Checkbox "Sama dengan peminjam"
            const checkSameInfo = document.getElementById('useSameInfo');
            const inputNama = document.getElementById('inputNama');
            const inputKontak = document.getElementById('inputKontak');
            const inputNamaWakil = document.getElementById('inputNamaWakil');
            const inputKontakWakil = document.getElementById('inputKontakWakil');

            checkSameInfo.addEventListener('change', function() {
                if(this.checked) {
                    inputNamaWakil.value = inputNama.value;
                    inputKontakWakil.value = inputKontak.value;
                    inputNamaWakil.setAttribute('readonly', true);
                    inputKontakWakil.setAttribute('readonly', true);
                    inputNamaWakil.classList.add('bg-slate-100', 'text-slate-500');
                    inputKontakWakil.classList.add('bg-slate-100', 'text-slate-500');
                } else {
                    inputNamaWakil.value = '';
                    inputKontakWakil.value = '';
                    inputNamaWakil.removeAttribute('readonly');
                    inputKontakWakil.removeAttribute('readonly');
                    inputNamaWakil.classList.remove('bg-slate-100', 'text-slate-500');
                    inputKontakWakil.classList.remove('bg-slate-100', 'text-slate-500');
                }
            });

            // Update live jika checkbox checked dan input utama berubah
            [inputNama, inputKontak].forEach(input => {
                input.addEventListener('input', () => {
                    if(checkSameInfo.checked) {
                        checkSameInfo.dispatchEvent(new Event('change'));
                    }
                });
            });
        });

        // Navigation Logic
        btnNext.addEventListener('click', () => {
            if(validateStep(currentStep)) {
                if(currentStep < totalSteps) {
                    currentStep++;
                    updateUI();
                }
            }
        });

        btnPrev.addEventListener('click', () => {
            if(currentStep > 1) {
                currentStep--;
                updateUI();
            }
        });

        // Direct Sidebar Navigation
        document.querySelectorAll('.nav-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault(); // Prevent form submit
                const target = parseInt(btn.dataset.target);
                // Hanya boleh lompat ke step yang sudah dilewati atau next step
                if(target < currentStep || (target === currentStep + 1 && validateStep(currentStep))) {
                    currentStep = target;
                    updateUI();
                }
            });
        });

        function updateUI() {
            // 1. Show/Hide Panels
            document.querySelectorAll('.step-panel').forEach(el => el.classList.remove('active'));
            const activePanel = document.querySelector(`.step-panel[data-step="${currentStep}"]`);
            activePanel.classList.add('active');

            // 2. Update Header Steps
            stepLabel.innerText = stepNames[currentStep];
            document.querySelectorAll('.step-dot').forEach((el, idx) => {
                if((idx + 1) <= currentStep) {
                    el.classList.remove('bg-slate-200');
                    el.classList.add('bg-blue-600', 'scale-125', 'ring-4', 'ring-blue-100');
                } else {
                    el.classList.remove('bg-blue-600', 'scale-125', 'ring-4', 'ring-blue-100');
                    el.classList.add('bg-slate-200');
                }
            });

            // 3. Update Sidebar Active State
            document.querySelectorAll('.nav-btn').forEach(btn => {
                const step = parseInt(btn.dataset.target);
                if(step === currentStep) {
                    btn.classList.add('active', 'bg-blue-50', 'text-blue-600');
                    btn.querySelector('i').classList.remove('text-slate-400');
                    btn.querySelector('i').classList.add('text-blue-600');
                } else {
                    btn.classList.remove('active', 'bg-blue-50', 'text-blue-600');
                    btn.querySelector('i').classList.add('text-slate-400');
                    btn.querySelector('i').classList.remove('text-blue-600');
                }
            });

            // 4. Button Visibility
            if(currentStep === 1) {
                btnPrev.classList.add('hidden');
            } else {
                btnPrev.classList.remove('hidden');
            }

            if(currentStep === totalSteps) {
                btnNext.classList.add('hidden');
                btnSubmit.classList.remove('hidden');
                generateSummary(); // Generate summary when reaching step 4
            } else {
                btnNext.classList.remove('hidden');
                btnSubmit.classList.add('hidden');
            }

            // Scroll to top of card smoothly
            document.querySelector('.tech-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function validateStep(step) {
            let isValid = true;
            const fields = requiredFields[step];

            fields.forEach(fieldName => {
                // Handle Radio Buttons
                if(fieldName === 'status_sebagai') {
                    const radios = document.getElementsByName(fieldName);
                    let radioChecked = false;
                    radios.forEach(r => { if(r.checked) radioChecked = true; });
                    const container = radios[0].closest('.group').querySelector('.error-msg');

                    if(!radioChecked) {
                        isValid = false;
                        container.classList.remove('hidden');
                    } else {
                        container.classList.add('hidden');
                    }
                }
                // Handle Standard Inputs
                else {
                    const input = document.getElementsByName(fieldName)[0];
                    if(!input) return;

                    const errorMsg = input.parentElement.querySelector('.error-msg') || input.parentElement.parentElement.querySelector('.error-msg');

                    if(!input.value.trim()) {
                        isValid = false;
                        input.classList.add('error');
                        if(errorMsg) errorMsg.classList.remove('hidden');
                    } else {
                        input.classList.remove('error');
                        if(errorMsg) errorMsg.classList.add('hidden');
                    }
                }
            });

            return isValid;
        }

        function generateSummary() {
            const formData = new FormData(form);
            const summaryContainer = document.getElementById('confirmationContent');

            // Helper to get text from select options
            const getSelectText = (name) => {
                const el = document.getElementsByName(name)[0];
                return el.options[el.selectedIndex]?.text || '-';
            };

            const data = {
                'Waktu Berangkat': formData.get('waktu_keberangkatan').replace('T', ' '),
                'Lokasi Jemput': formData.get('lokasi_keberangkatan'),
                'Tujuan': formData.get('alamat_tujuan'),
                'Kota': getSelectText('tujuan_wilayah_id'),
                'Kegiatan': formData.get('nama_kegiatan'),
                'Penumpang': formData.get('jumlah_rombongan') + ' Orang',
                'Peminjam': formData.get('nama_pengguna'),
                'Kontak': formData.get('kontak_pengguna'),
                'Perwakilan': formData.get('nama_personil_perwakilan'),
                'Kontak Wakil': formData.get('kontak_pengguna_perwakilan')
            };

            let html = '';
            for (const [key, value] of Object.entries(data)) {
                html += `
                    <div class="border-b border-slate-100 pb-2">
                        <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-1">${key}</p>
                        <p class="font-semibold text-slate-800 break-words">${value || '-'}</p>
                    </div>
                `;
            }
            summaryContainer.innerHTML = html;
        }

        // Function to clear all error messages
        function clearErrors() {
            document.querySelectorAll('.tech-input.error').forEach(input => {
                input.classList.remove('error');
            });
            document.querySelectorAll('.error-msg').forEach(msg => {
                msg.classList.add('hidden');
                msg.innerText = ''; // Clear existing text
            });
        }

        async function submitForm() {
            if(!agreementCheck.checked) {
                alert('Mohon setujui pernyataan terlebih dahulu.');
                return;
            }

            clearErrors(); // Clear previous errors on new submission attempt

            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');
            const loadingIcon = document.getElementById('loadingIcon');
            const originalBtnText = btnText.innerText;

            btnSubmit.disabled = true;
            btnText.innerText = 'Mengirim...';
            btnIcon.classList.add('hidden');
            loadingIcon.classList.remove('hidden');

            const formData = new FormData(form);
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch('/PeminjamanKendaraanUnpad/submit', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ data: data }) // Wrap form data in 'data' key as expected by controller
                });

                const result = await response.json();

                if (result.success) {
                    openModal(result.tracking_url);
                } else {
                    alert('Terjadi kesalahan: ' + (result.message || 'Gagal menyimpan data.'));
                    if (result.errors) {
                        for (const fieldName in result.errors) {
                            const inputElement = document.querySelector(`[name="${fieldName}"]`);
                            if (inputElement) {
                                inputElement.classList.add('error');
                                const errorMsgElement = inputElement.closest('.group')?.querySelector('.error-msg'); // More robust targeting
                                if (errorMsgElement) {
                                    errorMsgElement.innerText = result.errors[fieldName][0];
                                    errorMsgElement.classList.remove('hidden');
                                }
                            }
                        }
                    }
                }
            } catch (error) {
                console.error('Network or server error:', error);
                alert('Terjadi kesalahan jaringan atau server. Mohon coba lagi.');
            } finally {
                btnSubmit.disabled = false;
                btnText.innerText = originalBtnText;
                btnIcon.classList.remove('hidden');
                loadingIcon.classList.add('hidden');
            }
        }
    </script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new TomSelect('#select-tujuan', {
            valueField: 'value',
            labelField: 'label',
            searchField: 'label',
            openOnFocus: true,
            load: function(query, callback) {
                var url = "{{ route('api.wilayah.search') }}?q=" + encodeURIComponent(query);
                fetch(url)
                    .then(response => response.json())
                    .then(json => {
                        callback(json.items);
                    }).catch(()=>{
                        callback();
                    });
            },
            render: {
                option: function(item, escape) {
                    return `<div>${escape(item.label)}</div>`;
                },
                item: function(item, escape) {
                    return `<div>${escape(item.label)}</div>`;
                }
            }
        });

        new TomSelect('#select-unit-kerja', {
            valueField: 'value',
            labelField: 'label',
            searchField: 'label',
            openOnFocus: true,
            load: function(query, callback) {
                var url = "{{ route('api.unit-kerja.search') }}?q=" + encodeURIComponent(query);
                fetch(url)
                    .then(response => response.json())
                    .then(json => {
                        callback(json.items);
                    }).catch(()=>{
                        callback();
                    });
            },
            render: {
                option: function(item, escape) {
                    return `<div>${escape(item.label)}</div>`;
                },
                item: function(item, escape) {
                    return `<div>${escape(item.label)}</div>`;
                }
            }
        });
    });
</script>
</body>
</html>