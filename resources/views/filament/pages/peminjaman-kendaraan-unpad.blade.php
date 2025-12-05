<style>
    /* Reset & Base Styles */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #ffffff; /* Ganti ke putih */
        background-image: url('{{ asset('images/Unpad_logo.png') }}'), linear-gradient(
            45deg,
            #f8f8f8 25%, /* Sangat terang, hampir putih */
            transparent 25%,
            transparent 50%,
            #f8f8f8 50%,
            #f8f8f8 75%,
            transparent 75%,
            transparent
        );
        background-repeat: no-repeat, repeat; /* Logo tidak berulang, pola berulang */
        background-position: center center, 0 0; /* Logo di tengah, pola mulai dari kiri atas */
        background-size: 300px auto, 20px 20px; /* Ukuran logo, ukuran pola */
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
        color: #333;
        position: relative; /* Diperlukan untuk z-index jika ada elemen lain */
    }

    body::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.7); /* Lapisan putih transparan untuk memudarkan logo */
        z-index: 1; /* Di atas logo, di bawah konten kartu */
    }

    /* Container Kartu Utama */
    .card {
        background-color: #ffffff;
        width: 100%;
        max-width: 1100px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        padding: 40px;
        position: relative; /* Untuk memastikan kartu di atas lapisan transparan */
        z-index: 2; /* Di atas lapisan transparan */
    }

    /* Header (Logo & Judul) */
    .header {
        text-align: center;
        margin-bottom: 50px;
    }

    h2 {
        font-size: 22px;
        font-weight: 700;
        color: #222;
        margin-bottom: 8px;
    }

    h4 {
        font-size: 15px;
        font-weight: 400;
        color: #555;
    }

    /* Stepper (Indikator Langkah) */
    .stepper-container {
        margin-bottom: 35px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden; /* Agar border-radius bekerja pada child elements */
    }

    .stepper {
        display: flex;
        justify-content: space-between;
        width: 100%;
    }

    .step-item {
        flex: 1;
        padding: 18px 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 500;
        color: #999;
        background-color: #fff;
        position: relative;
        white-space: nowrap;
        transition: all 0.3s ease;
    }

    /* Separator antar step */
    .step-item:not(:last-child) {
        border-right: 1px solid #e0e0e0;
    }

    .step-item i {
        font-size: 16px;
        margin-right: 10px;
        border: 1px solid #ccc;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    /* Active Step Style */
    .step-item.active {
        color: #ffffff;
        background-color: #87ceeb; /* Warna Biru Soft */
        font-weight: 600;
    }

    .step-item.active i {
        color: #87ceeb;
        background-color: #fff;
        border-color: #87ceeb;
    }

    .step-item.active:not(:last-child)::after {
        border-left-color: #87ceeb;
    }


    /* Form Layout - Dihapus karena Filament akan menanganinya */

    /* Footer Button */
    .form-footer {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
    }

    .btn {
        background-color: #6c757d;
        color: #ffffff;
        border: none;
        padding: 12px 28px;
        border-radius: 5px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Poppins', sans-serif;
    }

    .btn-next {
        background-color: #007bff;
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2); /* Subtle blue shadow */
    }

    .btn-next:hover {
        background-color: #0069d9; /* Slightly darker blue on hover */
        box-shadow: 0 6px 12px rgba(0, 123, 255, 0.3); /* Slightly larger shadow on hover */
        transform: translateY(-2px); /* A bit more lift */
    }

    .btn-submit {
        background-color: #28a745;
    }

    .btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .btn[disabled] {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
        pointer-events: none;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .step-item span {
            display: none;
        }
        .step-item i {
            margin-right: 0;
        }
        .step-item {
            justify-content: center;
        }
    }

    @media (max-width: 768px) {
        .card {
            padding: 20px;
        }
        .header {
            margin-bottom: 20px;
        }
        .header h2 {
            font-size: 18px;
        }
    }
</style>

<div class="card">
    <!-- Header Section -->
    <div class="header">
        <h2>Formulir Peminjaman Kendaraan Universitas Padjadjaran</h2>
        <h4>Direktorat Pengelolaan Aset dan Sarana Prasarana</h4>
    </div>

    <!-- Stepper Section -->
    <div class="stepper-container">
        <div class="stepper">
            <div @class(['step-item', 'active' => $this->currentStep >= 1])>
                <i class="fa-solid fa-map-location-dot"></i>
                <span>Informasi Perjalanan</span>
            </div>
            <div @class(['step-item', 'active' => $this->currentStep >= 2])>
                <i class="fa-solid fa-user-group"></i>
                <span>Informasi Pengguna</span>
            </div>
            <div @class(['step-item', 'active' => $this->currentStep >= 3])>
                <i class="fa-solid fa-list-check"></i>
                <span>Detail Perjalanan</span>
            </div>
            <div @class(['step-item', 'active' => $this->currentStep >= 4])>
                <i class="fa-solid fa-file-alt"></i>
                <span>Dokumen & Berkas</span>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <form wire:submit.prevent="submit">
        {{ $this->form }}

        <!-- Footer Buttons -->
        <div class="form-footer">
            <div>
                @if ($this->currentStep > 1)
                    <button type="button" wire:click="previousStep" class="btn">
                        Sebelumnya
                    </button>
                @endif
            </div>
            <div>
                @if ($this->currentStep < 4)
                    <button type="button" wire:click="nextStep" class="btn btn-next" @disabled(!$this->isCurrentStepValid())>
                        Selanjutnya
                    </button>
                @else
                    <button type="submit" class="btn btn-submit">
                        Ajukan Permohonan
                    </button>
                @endif
            </div>
        </div>
    </form>

    <!-- Success Modal -->
    @if($showSuccessModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
            <div class="bg-white rounded-lg shadow-lg p-8 max-w-lg w-full mx-4">
                <div class="text-center">
                    <div class="mb-6">
                        <i class="fas fa-check-circle text-green-500 text-7xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-3">Berhasil!</h2>
                    <p class="text-gray-600 mb-6">Permohonan peminjaman kendaraan berhasil diajukan.</p>

                    <!-- Checklist Icon and Items -->
                    <div class="mb-6 bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-center justify-center mb-4">
                            <i class="fas fa-list-check text-blue-500 text-3xl mr-3"></i>
                            <span class="text-lg font-semibold text-gray-700">Status Pengajuan</span>
                        </div>
                        <ul class="text-left text-sm text-gray-600 space-y-2">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3 text-lg"></i> Data telah diterima
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-clock text-yellow-500 mr-3 text-lg"></i> Menunggu verifikasi admin
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-clock text-yellow-500 mr-3 text-lg"></i> Proses persetujuan
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-clock text-yellow-500 mr-3 text-lg"></i> Penugasan kendaraan
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-clock text-yellow-500 mr-3 text-lg"></i> Kendaraan siap digunakan
                            </li>
                        </ul>
                    </div>

                    <!-- Tracking Link -->
                    <div class="bg-gray-100 p-4 rounded-lg mb-6">
                        <p class="text-sm text-gray-500 mb-2">Link Tracking:</p>
                        <a href="{{ $trackingUrl }}" target="_blank" class="text-blue-600 hover:text-blue-800 break-all text-base font-medium">{{ $trackingUrl }}</a>
                    </div>

                    <button wire:click="$set('showSuccessModal', false)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg text-lg">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
