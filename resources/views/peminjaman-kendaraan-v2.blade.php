<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Peminjaman Kendaraan - Universitas Padjadjaran</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .progress-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 30px 20px;
            background: #f8f9fa;
        }

        .progress-item {
            text-align: center;
            flex: 1;
            position: relative;
        }

        .progress-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 20px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: #ddd;
            z-index: 0;
        }

        .progress-item.active::after {
            background: #667eea;
        }

        .progress-number {
            width: 40px;
            height: 40px;
            background: #ddd;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #666;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .progress-item.active .progress-number {
            background: #667eea;
            color: white;
        }

        .progress-item.completed .progress-number {
            background: #28a745;
            color: white;
        }

        .progress-label {
            font-size: 12px;
            color: #666;
            font-weight: 500;
        }

        .progress-item.active .progress-label {
            color: #667eea;
            font-weight: 600;
        }

        .form-container {
            padding: 30px 20px;
        }

        .step-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
            font-size: 14px;
        }

        .required {
            color: #e74c3c;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-row.full {
            grid-template-columns: 1fr;
        }

        .error {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .error.show {
            display: block;
        }

        .form-group input.invalid,
        .form-group select.invalid,
        .form-group textarea.invalid {
            border-color: #e74c3c;
        }

        .button-group {
            display: flex;
            gap: 10px;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        button {
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }

        button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-prev {
            background: #6c757d;
            color: white;
            display: none;
        }

        .btn-prev:hover:not(:disabled) {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-next {
            background: #667eea;
            color: white;
            margin-left: auto;
        }

        .btn-next:hover:not(:disabled) {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-submit {
            background: #28a745;
            color: white;
            display: none;
            margin-left: auto;
        }

        .btn-submit:hover:not(:disabled) {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none;
            animation: slideDown 0.3s ease-in;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert.show {
            display: block;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .confirmation-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .confirmation-label {
            font-weight: 600;
            color: #666;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .confirmation-value {
            color: #333;
            font-size: 14px;
        }

        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 18px;
            }

            .form-container {
                padding: 20px;
            }

            .progress-label {
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Formulir Peminjaman Kendaraan</h1>
            <p>Universitas Padjadjaran</p>
        </div>

        <!-- Progress -->
        <div class="progress-container">
            <div class="progress-item active" data-step="1">
                <div class="progress-number">1</div>
                <div class="progress-label">Perjalanan</div>
            </div>
            <div class="progress-item" data-step="2">
                <div class="progress-number">2</div>
                <div class="progress-label">Pengguna</div>
            </div>
            <div class="progress-item" data-step="3">
                <div class="progress-number">3</div>
                <div class="progress-label">Detail</div>
            </div>
            <div class="progress-item" data-step="4">
                <div class="progress-number">4</div>
                <div class="progress-label">Konfirmasi</div>
            </div>
        </div>

        <!-- Form -->
        <div class="form-container">
            <!-- Alert Messages -->
            <div id="alertSuccess" class="alert alert-success"></div>
            <div id="alertError" class="alert alert-error"></div>

            <form id="mainForm">
                @csrf

                <!-- STEP 1: Informasi Perjalanan -->
                <div class="step active" data-step="1">
                    <div class="step-title">Informasi Perjalanan</div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Waktu Keberangkatan <span class="required">*</span></label>
                            <input type="datetime-local" name="waktu_keberangkatan" required>
                            <div class="error">Field ini diperlukan</div>
                        </div>
                        <div class="form-group">
                            <label>Waktu Kepulangan</label>
                            <input type="datetime-local" name="waktu_kepulangan">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Lokasi Keberangkatan <span class="required">*</span></label>
                            <input type="text" name="lokasi_keberangkatan" placeholder="Contoh: Kampus Jatinangor" required>
                            <div class="error">Field ini diperlukan</div>
                        </div>
                        <div class="form-group">
                            <label>Jumlah Rombongan <span class="required">*</span></label>
                            <input type="number" name="jumlah_rombongan" min="1" placeholder="Jumlah orang" required>
                            <div class="error">Field ini diperlukan</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Alamat Tujuan <span class="required">*</span></label>
                        <textarea name="alamat_tujuan" placeholder="Masukkan alamat lengkap tujuan perjalanan" required></textarea>
                        <div class="error">Field ini diperlukan</div>
                    </div>

                    <div class="form-row">
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
                            <div class="error">Pilih salah satu</div>
                        </div>
                        <div class="form-group">
                            <label>Kota Kabupaten <span class="required">*</span></label>
                            <select name="tujuan_wilayah_id" required>
                                <option value="">-- Pilih Kota Kabupaten --</option>
                                @foreach ($wilayahs as $wilayah)
                                    <option value="{{ $wilayah->wilayah_id }}">{{ $wilayah->nama_wilayah }}</option>
                                @endforeach
                            </select>
                            <div class="error">Pilih salah satu</div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: Informasi Pengguna -->
                <div class="step" data-step="2">
                    <div class="step-title">Informasi Pengguna</div>

                    <div class="form-group">
                        <label>Unit Kerja/Fakultas/UKM <span class="required">*</span></label>
                        <select name="unit_kerja_id" required>
                            <option value="">-- Pilih Unit Kerja --</option>
                            @foreach ($unitKerjas as $unit)
                                <option value="{{ $unit->unit_kerja_id }}">{{ $unit->nama_unit_kerja }}</option>
                            @endforeach
                        </select>
                        <div class="error">Pilih salah satu</div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Pengguna <span class="required">*</span></label>
                            <input type="text" name="nama_pengguna" placeholder="Masukkan nama lengkap" required>
                            <div class="error">Field ini diperlukan</div>
                        </div>
                        <div class="form-group">
                            <label>Kontak Pengguna (HP/WA) <span class="required">*</span></label>
                            <input type="tel" name="kontak_pengguna" placeholder="Contoh: 081234567890" required>
                            <div class="error">Field ini diperlukan</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="useSameInfo" id="useSameInfo" style="width: auto; margin-right: 8px;">
                            Gunakan info yang sama untuk perwakilan
                        </label>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Personil Perwakilan <span class="required">*</span></label>
                            <input type="text" name="nama_personil_perwakilan" placeholder="Nama perwakilan" required>
                            <div class="error">Field ini diperlukan</div>
                        </div>
                        <div class="form-group">
                            <label>Kontak Perwakilan (HP/WA) <span class="required">*</span></label>
                            <input type="tel" name="kontak_pengguna_perwakilan" placeholder="Kontaknya" required>
                            <div class="error">Field ini diperlukan</div>
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
                        <div class="error">Pilih salah satu</div>
                    </div>
                </div>

                <!-- STEP 3: Detail Perjalanan -->
                <div class="step" data-step="3">
                    <div class="step-title">Detail Perjalanan</div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Kota Kabupaten <span class="required">*</span></label>
                            <select name="tujuan_wilayah_id_step3" required>
                                <option value="">-- Pilih Kota Kabupaten --</option>
                                @foreach ($wilayahs as $wilayah)
                                    <option value="{{ $wilayah->wilayah_id }}">{{ $wilayah->nama_wilayah }}</option>
                                @endforeach
                            </select>
                            <div class="error">Pilih salah satu</div>
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

                <!-- STEP 4: Konfirmasi -->
                <div class="step" data-step="4">
                    <div class="step-title">Konfirmasi Data</div>
                    <div id="confirmationContent"></div>
                </div>

                <!-- Buttons -->
                <div class="button-group">
                    <button type="button" class="btn-prev" id="btnPrev">← Sebelumnya</button>
                    <button type="button" class="btn-next" id="btnNext">Selanjutnya →</button>
                    <button type="submit" class="btn-submit" id="btnSubmit">✓ Ajukan Permohonan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('mainForm');
        const btnPrev = document.getElementById('btnPrev');
        const btnNext = document.getElementById('btnNext');
        const btnSubmit = document.getElementById('btnSubmit');
        const alertSuccess = document.getElementById('alertSuccess');
        const alertError = document.getElementById('alertError');

        let currentStep = 1;
        const totalSteps = 4;

        const requiredFields = {
            1: ['waktu_keberangkatan', 'lokasi_keberangkatan', 'jumlah_rombongan', 'alamat_tujuan', 'nama_kegiatan', 'tujuan_wilayah_id'],
            2: ['unit_kerja_id', 'nama_pengguna', 'kontak_pengguna', 'nama_personil_perwakilan', 'kontak_pengguna_perwakilan', 'status_sebagai'],
            3: ['tujuan_wilayah_id_step3'],
            4: []
        };

        function showStep(step) {
            // Hide all steps
            document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.progress-item').forEach(el => {
                el.classList.remove('active', 'completed');
            });

            // Show current step
            document.querySelector(`.step[data-step="${step}"]`).classList.add('active');
            document.querySelector(`.progress-item[data-step="${step}"]`).classList.add('active');

            // Mark completed steps
            for (let i = 1; i < step; i++) {
                document.querySelector(`.progress-item[data-step="${i}"]`).classList.add('completed');
            }

            // Update buttons
            btnPrev.style.display = step > 1 ? 'block' : 'none';
            btnNext.style.display = step < totalSteps ? 'block' : 'none';
            btnSubmit.style.display = step === totalSteps ? 'block' : 'none';

            // Generate confirmation on step 4
            if (step === totalSteps) {
                generateConfirmation();
            }

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function validateStep(step) {
            const fields = requiredFields[step];
            let isValid = true;

            fields.forEach(fieldName => {
                const field = form.querySelector(`[name="${fieldName}"]`);
                const errorMsg = field.parentElement.querySelector('.error');

                if (!field || !field.value) {
                    field.classList.add('invalid');
                    if (errorMsg) errorMsg.classList.add('show');
                    isValid = false;
                } else {
                    field.classList.remove('invalid');
                    if (errorMsg) errorMsg.classList.remove('show');
                }
            });

            return isValid;
        }

        function generateConfirmation() {
            const formData = new FormData(form);
            const content = document.getElementById('confirmationContent');

            let html = `
                <div class="confirmation-item">
                    <div class="confirmation-label">Waktu Keberangkatan</div>
                    <div class="confirmation-value">${formData.get('waktu_keberangkatan') || '-'}</div>
                </div>
                <div class="confirmation-item">
                    <div class="confirmation-label">Lokasi Keberangkatan</div>
                    <div class="confirmation-value">${formData.get('lokasi_keberangkatan') || '-'}</div>
                </div>
                <div class="confirmation-item">
                    <div class="confirmation-label">Jumlah Rombongan</div>
                    <div class="confirmation-value">${formData.get('jumlah_rombongan') || '-'} orang</div>
                </div>
                <div class="confirmation-item">
                    <div class="confirmation-label">Alamat Tujuan</div>
                    <div class="confirmation-value">${formData.get('alamat_tujuan') || '-'}</div>
                </div>
                <div class="confirmation-item">
                    <div class="confirmation-label">Nama Kegiatan</div>
                    <div class="confirmation-value">${formData.get('nama_kegiatan') || '-'}</div>
                </div>
                <div class="confirmation-item">
                    <div class="confirmation-label">Unit Kerja</div>
                    <div class="confirmation-value">${document.querySelector('[name="unit_kerja_id"]').options[document.querySelector('[name="unit_kerja_id"]').selectedIndex].text}</div>
                </div>
                <div class="confirmation-item">
                    <div class="confirmation-label">Nama Pengguna</div>
                    <div class="confirmation-value">${formData.get('nama_pengguna') || '-'}</div>
                </div>
                <div class="confirmation-item">
                    <div class="confirmation-label">Kontak Pengguna</div>
                    <div class="confirmation-value">${formData.get('kontak_pengguna') || '-'}</div>
                </div>
                <div class="confirmation-item">
                    <div class="confirmation-label">Status Sebagai</div>
                    <div class="confirmation-value">${formData.get('status_sebagai') || '-'}</div>
                </div>
            `;

            content.innerHTML = html;
        }

        btnNext.addEventListener('click', (e) => {
            e.preventDefault();
            if (validateStep(currentStep)) {
                currentStep++;
                if (currentStep > totalSteps) currentStep = totalSteps;
                showStep(currentStep);
            } else {
                showAlert('error', 'Mohon lengkapi semua field yang diperlukan');
            }
        });

        btnPrev.addEventListener('click', (e) => {
            e.preventDefault();
            currentStep--;
            if (currentStep < 1) currentStep = 1;
            showStep(currentStep);
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '⏳ Memproses...';

            const formData = new FormData(form);
            const data = Object.fromEntries(formData);
            data.tujuan_wilayah_id = data.tujuan_wilayah_id_step3 || data.tujuan_wilayah_id;

            try {
                const response = await fetch('{{ route("peminjaman.submit") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
                    },
                    body: JSON.stringify({ data })
                });

                const result = await response.json();

                if (result.success) {
                    // Show success popup
                    showSuccessPopup(result.token, result.tracking_url);
                    // Redirect after 3 seconds
                    setTimeout(() => {
                        window.location.href = '{{ url("/peminjaman/sukses") }}/' + result.token;
                    }, 3000);
                } else {
                    showAlert('error', result.message || 'Terjadi kesalahan');
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = '✓ Ajukan Permohonan';
                }
            } catch (error) {
                showAlert('error', 'Error: ' + error.message);
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '✓ Ajukan Permohonan';
            }
        });

        // Clear error on input
        form.addEventListener('input', (e) => {
            if (e.target.name) {
                e.target.classList.remove('invalid');
                const errorMsg = e.target.parentElement.querySelector('.error');
                if (errorMsg) errorMsg.classList.remove('show');
            }
        });

        // Copy info checkbox
        document.getElementById('useSameInfo').addEventListener('change', (e) => {
            const nama = form.querySelector('[name="nama_pengguna"]').value;
            const kontak = form.querySelector('[name="kontak_pengguna"]').value;

            if (e.target.checked) {
                form.querySelector('[name="nama_personil_perwakilan"]').value = nama;
                form.querySelector('[name="kontak_pengguna_perwakilan"]').value = kontak;
            } else {
                form.querySelector('[name="nama_personil_perwakilan"]').value = '';
                form.querySelector('[name="kontak_pengguna_perwakilan"]').value = '';
            }
        });

        function showAlert(type, message) {
            if (type === 'success') {
                alertSuccess.textContent = message;
                alertSuccess.classList.add('show');
                alertError.classList.remove('show');
            } else {
                alertError.textContent = message;
                alertError.classList.add('show');
                alertSuccess.classList.remove('show');
            }

            setTimeout(() => {
                alertSuccess.classList.remove('show');
                alertError.classList.remove('show');
            }, 5000);
        }

        function showSuccessPopup(token, trackingUrl) {
            const modal = document.getElementById('successModal');
            const backdrop = document.getElementById('successBackdrop');
            
            if (!modal) return;
            
            // Update tracking URL in popup
            document.getElementById('trackingLink').textContent = trackingUrl;
            document.getElementById('trackingLink').href = trackingUrl;
            
            // Show backdrop and modal
            backdrop.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            
            const card = modal.querySelector('div');
            card.classList.add('animate-modal-enter');
        }

        function closeSuccessModal() {
            const modal = document.getElementById('successModal');
            const backdrop = document.getElementById('successBackdrop');
            
            backdrop.classList.add('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-0', 'pointer-events-none');
        }

        // Initialize
        showStep(1);
    </script>

    <!-- Success Modal -->
    <div id="successBackdrop" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-40 transition-opacity duration-300 opacity-0 pointer-events-none" onclick="closeSuccessModal()"></div>
    
    <div id="successModal" class="fixed z-50 w-full max-w-[420px] p-4 opacity-0 pointer-events-none transition-all duration-300 transform scale-95 left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Top Decoration Line -->
            <div class="h-1.5 w-full bg-gradient-to-r from-green-400 to-emerald-500"></div>

            <div class="p-8 text-center">
                <!-- Animated Success Icon -->
                <div class="mx-auto w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mb-6 relative" style="animation: circlePop 0.4s ease-out forwards;">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path style="stroke-dasharray: 60; stroke-dashoffset: 60; animation: drawCheck 0.6s 0.3s cubic-bezier(0.65, 0, 0.45, 1) forwards;" stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <!-- Main Heading -->
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Berhasil!</h2>
                
                <!-- Main Message -->
                <p class="text-gray-600 text-sm leading-relaxed mb-6">
                    Permohonan peminjaman kendaraan Anda berhasil diajukan dan sedang menunggu persetujuan.
                </p>

                <!-- WhatsApp Notification Box -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-4 mb-6 text-left flex items-start gap-3">
                    <div class="flex-shrink-0 bg-white p-1.5 rounded-lg border border-green-200">
                        <svg class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-bold text-green-800 uppercase tracking-wide mb-1">Notifikasi Dikirim</p>
                        <p class="text-sm text-gray-700">Cek <span class="font-semibold text-green-700">Whatsapp</span> untuk melihat link tracking persetujuan Anda.</p>
                    </div>
                </div>

                <!-- Tracking Link Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6 text-left">
                    <p class="text-xs font-bold text-blue-800 uppercase tracking-wide mb-2">Link Pelacakan</p>
                    <a id="trackingLink" href="#" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 font-medium break-all underline">Loading...</a>
                </div>

                <!-- Action Button -->
                <button onclick="closeSuccessModal()" class="w-full bg-gray-900 hover:bg-black text-white font-semibold py-3 px-4 rounded-xl shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 active:scale-[0.98]">
                    Oke, Saya Mengerti
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes circlePop {
            0% { transform: scale(0); opacity: 0; }
            80% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }

        @keyframes drawCheck {
            100% { stroke-dashoffset: 0; }
        }

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
    </style>
</body>
</html>
