<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Peminjaman Kendaraan - Universitas Padjadjaran</title>
    <style>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #e0f2fe 0%, #f3e8ff 100%);
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .max-w-2xl {
            max-width: 50rem;
            margin: 0 auto;
        }

        .bg-white {
            background-color: white;
        }

        .rounded-lg {
            border-radius: 0.5rem;
        }

        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .p-8 {
            padding: 2rem;
        }

        .mb-8 {
            margin-bottom: 2rem;
        }

        .mb-6 {
            margin-bottom: 1.5rem;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .text-center {
            text-align: center;
        }

        .h-20 {
            height: 5rem;
            max-width: 100px;
            margin: 0 auto 1rem;
        }

        .text-3xl {
            font-size: 2rem;
            font-weight: bold;
            color: #111827;
        }

        .text-gray-600 {
            color: #4b5563;
        }

        .text-gray-500 {
            color: #6b7280;
        }

        .text-sm {
            font-size: 0.875rem;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #6b7280;
            transition: all 0.3s ease;
        }

        .step.active .step-number {
            background-color: #3b82f6;
            color: white;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
        }

        .step.completed .step-number {
            background-color: #10b981;
            color: white;
        }

        .step-label {
            font-size: 0.875rem;
            margin-top: 0.5rem;
            color: #6b7280;
            text-align: center;
            font-weight: 500;
        }

        .step.active .step-label {
            color: #3b82f6;
        }

        .step-line {
            position: absolute;
            top: 20px;
            left: 50%;
            right: -50%;
            height: 2px;
            background-color: #e5e7eb;
            z-index: -1;
        }

        .step.completed .step-line {
            background-color: #10b981;
        }

        .step-content {
            display: none;
            animation: fadeIn 0.3s ease-in;
        }

        .step-content.active {
            display: block !important;
            visibility: visible !important;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .form-group {
            margin-bottom: 1.5rem;
            display: block !important;
            visibility: visible !important;
            width: 100%;
        }

        .form-group label {
            display: block !important;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
            visibility: visible !important;
        }

        .required {
            color: #ef4444;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100% !important;
            padding: 0.75rem !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            font-size: 1rem !important;
            font-family: inherit !important;
            transition: all 0.3s ease !important;
            box-sizing: border-box !important;
            display: block !important;
            visibility: visible !important;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
            font-family: inherit;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }

        .form-group input.is-invalid,
        .form-group select.is-invalid,
        .form-group textarea.is-invalid {
            border-color: #ef4444;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-size: 1rem;
        }

        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }

        .btn-primary:hover:not(:disabled) {
            background-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .btn-primary:disabled {
            background-color: #9ca3af;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
            transform: translateY(-2px);
        }

        .btn-success {
            background-color: #10b981;
            color: white;
        }

        .btn-success:hover:not(:disabled) {
            background-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: space-between;
            margin-top: 2rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            display: none;
        }

        .alert.show {
            display: block;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .grid-2 {
            display: grid !important;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            visibility: visible !important;
        }

        .space-y-6 > * + * {
            margin-top: 1.5rem;
        }

        .bg-gray-50 {
            background-color: #f9fafb;
        }

        .p-6 {
            padding: 1.5rem;
        }

        .p-4 {
            padding: 1rem;
        }

        .space-y-2 > * + * {
            margin-top: 0.5rem;
        }

        .text-xl {
            font-size: 1.25rem;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-gray-900 {
            color: #111827;
        }

        .bg-blue-50 {
            background-color: #eff6ff;
        }

        .border {
            border: 1px solid #e5e7eb;
        }

        .border-blue-200 {
            border-color: #bfdbfe;
        }

        .text-blue-900 {
            color: #111d3d;
        }

        .mr-2 {
            margin-right: 0.5rem;
        }

        .ml-2 {
            margin-left: 0.5rem;
        }

        .grid-2.full {
            grid-template-columns: 1fr;
        }

        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }

            .step-label {
                font-size: 0.75rem;
            }

            .p-8 {
                padding: 1rem;
            }

            .text-3xl {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="max-w-2xl">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <div class="text-center mb-6" style="display: block; visibility: visible;">
                @if (file_exists(public_path('images/Unpad_logo.png')))
                    <img src="{{ asset('images/Unpad_logo.png') }}" alt="Unpad Logo" class="h-20 mx-auto mb-4">
                @endif
                <h1 class="text-3xl font-bold text-gray-900">Formulir Peminjaman Kendaraan</h1>
                <p class="text-gray-600 mt-2">Universitas Padjadjaran</p>
                <p class="text-sm text-gray-500">Direktorat Pengelolaan Aset dan Sarana Prasarana</p>
            </div>

            <!-- Step Indicator -->
            <div class="step-indicator" style="display: flex; visibility: visible;">
                <div class="step" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-label">Informasi Perjalanan</div>
                </div>
                <div class="step-line"></div>
                <div class="step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-label">Informasi Pengguna</div>
                </div>
                <div class="step-line"></div>
                <div class="step" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-label">Detail Perjalanan</div>
                </div>
                <div class="step-line"></div>
                <div class="step" data-step="4">
                    <div class="step-number">4</div>
                    <div class="step-label">Konfirmasi</div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-lg p-8" style="display: block; visibility: visible; width: 100%;">
            <!-- Alert Messages -->
            <div id="successAlert" class="alert alert-success" style="display: none;">
                ✓ <span id="successMessage"></span>
            </div>
            <div id="errorAlert" class="alert alert-error" style="display: none;">
                ⚠ <span id="errorMessage"></span>
            </div>

            <!-- STEP 1: INFORMASI PERJALANAN -->
            <form id="wizardForm" method="POST" style="display: block; visibility: visible;">
                @csrf

                <div class="step-content active" data-step="1" style="display: block !important; visibility: visible !important;">
                    <h2 class="text-xl font-bold text-gray-900 mb-6" style="display: block; visibility: visible;">Informasi Perjalanan</h2>

                    <div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; visibility: visible;">
                        <div class="form-group">
                            <label>Waktu Keberangkatan <span class="required">*</span></label>
                            <input type="datetime-local" name="waktu_keberangkatan" required>
                            <span class="error-message"></span>
                        </div>
                        <div class="form-group">
                            <label>Waktu Kepulangan</label>
                            <input type="datetime-local" name="waktu_kepulangan">
                        </div>
                    </div>

                    <div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; visibility: visible;">
                        <div class="form-group">
                            <label>Lokasi Keberangkatan <span class="required">*</span></label>
                            <input type="text" name="lokasi_keberangkatan" placeholder="Contoh: Kampus Jatinangor" required>
                            <span class="error-message"></span>
                        </div>
                        <div class="form-group">
                            <label>Jumlah Rombongan <span class="required">*</span></label>
                            <input type="number" name="jumlah_rombongan" min="1" placeholder="Jumlah orang" required>
                            <span class="error-message"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Alamat Tujuan <span class="required">*</span></label>
                        <textarea name="alamat_tujuan" placeholder="Masukkan alamat lengkap tujuan perjalanan" required></textarea>
                        <span class="error-message"></span>
                    </div>

                    <div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; visibility: visible;">
                        <div class="form-group">
                            <label>Nama Kegiatan <span class="required">*</span></label>
                            <select name="nama_kegiatan" required>
                                <option value="">-- Pilih Kegiatan --</option>
                                <option value="Perjalanan Dinas">Perjalanan Dinas</option>
                                <option value="Kuliah Lapangan">Kuliah Lapangan</option>
                                <option value="Kunjungan Industri">Kunjungan Industri</option>
                                <option value="Kegiatan Perlombaan">Kegiatan Perlombaan</option>
                                <option value="Kegiatan Kemahasiswaan">Kegiatan Kemahasiswaan</option>
                                <option value="Kegiatan Perkuliahan">Kegiatan Perkuliahan</option>
                                <option value="Kegiatan Lainnya">Kegiatan Lainnya</option>
                            </select>
                            <span class="error-message"></span>
                        </div>
                        <div class="form-group">
                            <label>Kota Kabupaten <span class="required">*</span></label>
                            <select name="tujuan_wilayah_id" required>
                                <option value="">-- Pilih Kota Kabupaten --</option>
                                @foreach ($wilayahs as $wilayah)
                                    <option value="{{ $wilayah->wilayah_id }}">{{ $wilayah->nama_wilayah }}</option>
                                @endforeach
                            </select>
                            <span class="error-message"></span>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: INFORMASI PENGGUNA -->
                <div class="step-content" data-step="2" style="display: none; visibility: visible;">
                    <h2 class="text-xl font-bold text-gray-900 mb-6" style="display: block; visibility: visible;">Informasi Pengguna</h2>

                    <div class="form-group">
                        <label>Unit Kerja/Fakultas/UKM <span class="required">*</span></label>
                        <select name="unit_kerja_id" required>
                            <option value="">-- Pilih Unit Kerja --</option>
                            @foreach ($unitKerjas as $unit)
                                <option value="{{ $unit->unit_kerja_id }}">{{ $unit->nama_unit_kerja }}</option>
                            @endforeach
                        </select>
                        <span class="error-message"></span>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label>Nama Pengguna <span class="required">*</span></label>
                            <input type="text" name="nama_pengguna" placeholder="Masukkan nama lengkap" required>
                            <span class="error-message"></span>
                        </div>
                        <div class="form-group">
                            <label>Kontak Pengguna (HP/WA) <span class="required">*</span></label>
                            <input type="tel" name="kontak_pengguna" placeholder="Contoh: 081234567890" required>
                            <span class="error-message"></span>
                        </div>
                    </div>

                    <div class="form-group bg-blue-50 p-4 rounded-lg mb-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" id="useSameInfo" class="mr-3">
                            <span>Gunakan informasi yang sama untuk Personil Perwakilan</span>
                        </label>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label>Nama Personil Perwakilan <span class="required">*</span></label>
                            <input type="text" name="nama_personil_perwakilan" placeholder="Masukkan nama lengkap" required>
                            <span class="error-message"></span>
                        </div>
                        <div class="form-group">
                            <label>Kontak Personil Perwakilan (HP/WA) <span class="required">*</span></label>
                            <input type="tel" name="kontak_pengguna_perwakilan" placeholder="Contoh: 081234567890" required>
                            <span class="error-message"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Status Sebagai <span class="required">*</span></label>
                        <select name="status_sebagai" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Mahasiswa">Mahasiswa</option>
                            <option value="Dosen">Dosen</option>
                            <option value="Staf">Staf</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <span class="error-message"></span>
                    </div>
                </div>

                <!-- STEP 3: DETAIL PERJALANAN -->
                <div class="step-content" data-step="3" style="display: none; visibility: visible;">
                    <h2 class="text-xl font-bold text-gray-900 mb-6" style="display: block; visibility: visible;">Detail Perjalanan</h2>

                    <div class="grid-2">
                        <div class="form-group">
                            <label>Kota Kabupaten <span class="required">*</span></label>
                            <select name="tujuan_wilayah_id_step3" required>
                                <option value="">-- Pilih Kota Kabupaten --</option>
                                @foreach ($wilayahs as $wilayah)
                                    <option value="{{ $wilayah->wilayah_id }}">{{ $wilayah->nama_wilayah }}</option>
                                @endforeach
                            </select>
                            <span class="error-message"></span>
                        </div>
                        <div class="form-group">
                            <label>Provinsi</label>
                            <input type="text" name="provinsi" placeholder="Provinsi akan terisi otomatis" disabled>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Uraian Singkat Kegiatan</label>
                        <textarea name="uraian_singkat_kegiatan" placeholder="Jelaskan tujuan dan rencana kegiatan"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Catatan/Keterangan Tambahan</label>
                        <textarea name="catatan_keterangan_tambahan" placeholder="Informasi tambahan jika ada"></textarea>
                    </div>
                </div>

                <!-- STEP 4: KONFIRMASI -->
                <div class="step-content" data-step="4" style="display: none; visibility: visible;">
                    <h2 class="text-xl font-bold text-gray-900 mb-6" style="display: block; visibility: visible;">Konfirmasi Data</h2>
                    <div id="confirmationSummary" class="bg-gray-50 p-6 rounded-lg" style="display: block; visibility: visible;">
                        <p class="text-gray-600">Loading data konfirmasi...</p>
                    </div>
                </div>

                <!-- Button Group -->
                <div class="btn-group">
                    <button type="button" id="prevBtn" class="btn btn-secondary" style="display:none;">
                        ← Sebelumnya
                    </button>
                    <div></div>
                    <button type="button" id="nextBtn" class="btn btn-primary">
                        Selanjutnya →
                    </button>
                    <button type="submit" id="submitBtn" class="btn btn-success" style="display:none;">
                        ✈ Ajukan Permohonan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('wizardForm');
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const submitBtn = document.getElementById('submitBtn');
        const successAlert = document.getElementById('successAlert');
        const errorAlert = document.getElementById('errorAlert');
        let currentStep = 1;
        const totalSteps = 4;

        const stepRules = {
            1: ['waktu_keberangkatan', 'lokasi_keberangkatan', 'jumlah_rombongan', 'alamat_tujuan', 'nama_kegiatan', 'tujuan_wilayah_id'],
            2: ['unit_kerja_id', 'nama_pengguna', 'kontak_pengguna', 'nama_personil_perwakilan', 'kontak_pengguna_perwakilan', 'status_sebagai'],
            3: ['tujuan_wilayah_id_step3'],
            4: []
        };

        // Show/hide step
        function showStep(step) {
            document.querySelectorAll('.step-content').forEach(el => {
                el.classList.remove('active');
                el.style.display = 'none';
            });
            const activeStep = document.querySelector(`[data-step="${step}"]`);
            activeStep.classList.add('active');
            activeStep.style.display = 'block';
            activeStep.style.visibility = 'visible';

            document.querySelectorAll('.step').forEach((el, idx) => {
                const stepNum = parseInt(el.dataset.step);
                el.classList.remove('active', 'completed');
                if (stepNum < step) {
                    el.classList.add('completed');
                } else if (stepNum === step) {
                    el.classList.add('active');
                }
            });

            prevBtn.style.display = step > 1 ? 'inline-block' : 'none';
            nextBtn.style.display = step < totalSteps ? 'inline-block' : 'none';
            submitBtn.style.display = step === totalSteps ? 'inline-block' : 'none';

            if (step === totalSteps) {
                generateConfirmationSummary();
            }
        }

        // Get form data
        function getFormData() {
            const data = {};
            const formElements = form.elements;
            for (let el of formElements) {
                if (el.name && el.value && el.type !== 'submit') {
                    data[el.name] = el.value;
                }
            }
            return data;
        }

        // Validate step
        function isStepValid() {
            const fields = stepRules[currentStep];
            let isValid = true;

            fields.forEach(fieldName => {
                const field = form.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    const errorEl = field.parentElement.querySelector('.error-message');
                    if (!field.value) {
                        field.classList.add('is-invalid');
                        if (errorEl) errorEl.textContent = 'Field ini diperlukan';
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                        if (errorEl) errorEl.textContent = '';
                    }
                }
            });

            return isValid;
        }

        // Generate confirmation summary
        function generateConfirmationSummary() {
            const data = getFormData();
            const wilayahSelect = form.querySelector('[name="tujuan_wilayah_id"]');
            const wilayahStep3Select = form.querySelector('[name="tujuan_wilayah_id_step3"]');
            const unitSelect = form.querySelector('[name="unit_kerja_id"]');

            const getSelectedText = (select) => {
                return select.options[select.selectedIndex]?.text || 'Tidak dipilih';
            };

            const html = `
                <div class="space-y-6">
                    <div>
                        <h3 class="font-bold text-gray-900 mb-3">Informasi Perjalanan</h3>
                        <div class="bg-white p-4 rounded space-y-2 text-sm">
                            <p><strong>Waktu Keberangkatan:</strong> ${data.waktu_keberangkatan || '-'}</p>
                            <p><strong>Lokasi Keberangkatan:</strong> ${data.lokasi_keberangkatan || '-'}</p>
                            <p><strong>Jumlah Rombongan:</strong> ${data.jumlah_rombongan || '-'}</p>
                            <p><strong>Alamat Tujuan:</strong> ${data.alamat_tujuan || '-'}</p>
                            <p><strong>Nama Kegiatan:</strong> ${data.nama_kegiatan || '-'}</p>
                            <p><strong>Kota Kabupaten:</strong> ${getSelectedText(wilayahSelect)}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 mb-3">Informasi Pengguna</h3>
                        <div class="bg-white p-4 rounded space-y-2 text-sm">
                            <p><strong>Unit Kerja:</strong> ${getSelectedText(unitSelect)}</p>
                            <p><strong>Nama Pengguna:</strong> ${data.nama_pengguna || '-'}</p>
                            <p><strong>Kontak Pengguna:</strong> ${data.kontak_pengguna || '-'}</p>
                            <p><strong>Nama Personil Perwakilan:</strong> ${data.nama_personil_perwakilan || '-'}</p>
                            <p><strong>Kontak Perwakilan:</strong> ${data.kontak_pengguna_perwakilan || '-'}</p>
                            <p><strong>Status Sebagai:</strong> ${data.status_sebagai || '-'}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 mb-3">Detail Perjalanan</h3>
                        <div class="bg-white p-4 rounded space-y-2 text-sm">
                            <p><strong>Kota Kabupaten:</strong> ${getSelectedText(wilayahStep3Select)}</p>
                            <p><strong>Uraian Kegiatan:</strong> ${data.uraian_singkat_kegiatan || '-'}</p>
                            <p><strong>Catatan:</strong> ${data.catatan_keterangan_tambahan || '-'}</p>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 p-4 rounded text-sm text-blue-900">
                        ℹ Silakan periksa kembali data Anda sebelum mengajukan permohonan.
                    </div>
                </div>
            `;

            document.getElementById('confirmationSummary').innerHTML = html;
        }

        // Copy data checkbox
        document.getElementById('useSameInfo').addEventListener('change', function() {
            const namaPengguna = form.querySelector('[name="nama_pengguna"]').value;
            const kontakPengguna = form.querySelector('[name="kontak_pengguna"]').value;

            if (this.checked) {
                form.querySelector('[name="nama_personil_perwakilan"]').value = namaPengguna;
                form.querySelector('[name="kontak_pengguna_perwakilan"]').value = kontakPengguna;
            } else {
                form.querySelector('[name="nama_personil_perwakilan"]').value = '';
                form.querySelector('[name="kontak_pengguna_perwakilan"]').value = '';
            }
        });

        // Next button
        nextBtn.addEventListener('click', function() {
            if (isStepValid()) {
                currentStep++;
                if (currentStep > totalSteps) currentStep = totalSteps;
                showStep(currentStep);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                errorAlert.classList.add('show');
                document.getElementById('errorMessage').textContent = 'Mohon lengkapi semua field yang diperlukan';
                setTimeout(() => errorAlert.classList.remove('show'), 4000);
            }
        });

        // Previous button
        prevBtn.addEventListener('click', function() {
            currentStep--;
            if (currentStep < 1) currentStep = 1;
            showStep(currentStep);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Submit button
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            submitBtn.disabled = true;
            submitBtn.innerHTML = '⏳ Memproses...';

            const data = getFormData();

            fetch('{{ route("peminjaman.submit") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
                },
                body: JSON.stringify({ data })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    successAlert.classList.add('show');
                    document.getElementById('successMessage').textContent = result.message;
                    form.reset();
                    currentStep = 1;
                    showStep(1);
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    errorAlert.classList.add('show');
                    document.getElementById('errorMessage').textContent = result.message;
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '✈ Ajukan Permohonan';
                }
            })
            .catch(error => {
                errorAlert.classList.add('show');
                document.getElementById('errorMessage').textContent = 'Terjadi kesalahan: ' + error.message;
                submitBtn.disabled = false;
                submitBtn.innerHTML = '✈ Ajukan Permohonan';
            });
        });

        // Clear errors on input
        form.addEventListener('input', function(e) {
            if (e.target.name) {
                e.target.classList.remove('is-invalid');
                const errorEl = e.target.parentElement.querySelector('.error-message');
                if (errorEl) errorEl.textContent = '';
            }
        });

        // Initialize
        showStep(1);
    </script>
</body>
</html>
