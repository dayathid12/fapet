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
        background-image: linear-gradient(
            45deg,
            #f8f8f8 25%, /* Sangat terang, hampir putih */
            transparent 25%,
            transparent 50%,
            #f8f8f8 50%,
            #f8f8f8 75%,
            transparent 75%,
            transparent
        );
        background-size: 20px 20px; /* Ukuran pola */
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
        color: #333;
    }

    /* Container Kartu Utama */
    .card {
        background-color: #ffffff;
        width: 100%;
        max-width: 1100px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        padding: 40px;
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
        <div class="logo-container">
            <img src="{{ asset('images/Unpad_logo.png') }}" alt="Unpad Logo" class="h-16 mx-auto mb-4">
        </div>
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
                    <button type="button" wire:click="nextStep" class="btn btn-next">
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
</div>
