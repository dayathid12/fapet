<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Berhasil - Peminjaman Kendaraan</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .header p {
            font-size: 15px;
            opacity: 0.95;
        }

        .content {
            padding: 40px 30px;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }

        .success-icon svg {
            width: 45px;
            height: 45px;
            color: white;
        }

        .message {
            text-align: center;
            margin-bottom: 30px;
        }

        .message h2 {
            font-size: 24px;
            color: #111827;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .message p {
            color: #6b7280;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .info-box {
            background: #f0fdf4;
            border: 2px solid #dcfce7;
            border-radius: 10px;
            padding: 20px;
            margin: 30px 0;
        }

        .info-box h3 {
            color: #15803d;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-box p {
            color: #166534;
            font-size: 14px;
            line-height: 1.6;
        }

        .tracking-box {
            background: #eff6ff;
            border: 2px solid #bfdbfe;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }

        .tracking-box h4 {
            color: #1e40af;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .tracking-link {
            color: #1e40af;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            word-break: break-all;
            display: inline-block;
        }

        .tracking-link:hover {
            text-decoration: underline;
            color: #1e3a8a;
        }

        .details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 30px 0;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
        }

        .detail-value {
            color: #111827;
            font-size: 14px;
            font-weight: 600;
        }

        .actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.3);
        }

        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #f8f5ff;
            transform: translateY(-2px);
        }

        .footer {
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        @media (max-width: 600px) {
            .container {
                border-radius: 10px;
            }

            .header {
                padding: 30px 20px;
            }

            .header h1 {
                font-size: 26px;
            }

            .content {
                padding: 25px 20px;
            }

            .message h2 {
                font-size: 20px;
            }

            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>✓ Pengajuan Berhasil!</h1>
            <p>Universitas Padjadjaran</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Success Icon -->
            <div class="success-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <!-- Main Message -->
            <div class="message">
                <h2>Permohonan Diterima</h2>
                <p>Terima kasih telah mengajukan permohonan peminjaman kendaraan. Permohonan Anda telah kami terima dan sedang menunggu persetujuan.</p>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <h3>📌 Nomor Referensi</h3>
                <p style="font-family: monospace; font-size: 13px; letter-spacing: 0.5px;">{{ $token }}</p>
            </div>

            <!-- Tracking Box -->
            <div class="tracking-box">
                <h4>🔗 Link Pelacakan Persetujuan</h4>
                <p>Gunakan link di bawah untuk melacak status permohonan Anda:</p>
                <br>
                <a href="{{ $tracking_url }}" class="tracking-link">{{ $tracking_url }}</a>
            </div>

            <!-- Details -->
            <div class="details">
                <div class="detail-item">
                    <span class="detail-label">Status Permohonan</span>
                    <span class="detail-value">⏳ Menunggu Persetujuan</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Tanggal Pengajuan</span>
                    <span class="detail-value">{{ date('d/m/Y H:i') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Jenis Permohonan</span>
                    <span class="detail-value">Peminjaman Kendaraan</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Nama Peminjam</span>
                    <span class="detail-value">{{ $perjalanan->nama_pengguna }}</span>
                </div>
            </div>

            <!-- Info -->
            <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; border-radius: 5px; margin: 25px 0;">
                <p style="color: #92400e; font-size: 13px; line-height: 1.6;">
                    <strong>ℹ️ Informasi Penting:</strong> Anda akan menerima notifikasi via WhatsApp tentang status permohonan Anda. Simpan link pelacakan di atas untuk mengecek perkembangan permohonan kapan saja.
                </p>
            </div>

            <!-- Actions -->
            <div class="actions">
                <a href="{{ $tracking_url }}" class="btn btn-primary">
                    Lihat Status Permohonan →
                </a>
                <a href="{{ route('peminjaman.form') }}" class="btn btn-secondary">
                    Kembali ke Form
                </a>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>Jika ada pertanyaan, silakan hubungi Direktorat Pengelolaan Aset dan Sarana Prasarana.</p>
            </div>
        </div>
    </div>
</body>
</html>
