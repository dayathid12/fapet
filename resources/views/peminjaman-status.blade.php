<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Permohonan - Peminjaman Kendaraan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
            padding: 20px;
        }

        .navbar {
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 15px 0;
            margin-bottom: 30px;
        }

        .navbar-content {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .navbar h1 {
            font-size: 18px;
            color: #111827;
            font-weight: 600;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 20px;
        }

        .status-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }

        .status-icon {
            width: 60px;
            height: 60px;
            background: #fef3c7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
        }

        .status-text h2 {
            color: #111827;
            font-size: 20px;
            margin-bottom: 5px;
        }

        .status-text p {
            color: #6b7280;
            font-size: 14px;
        }

        .timeline {
            margin: 30px 0;
        }

        .timeline-item {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            position: relative;
        }

        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 29px;
            top: 60px;
            width: 2px;
            height: calc(100% + 5px);
            background: #e5e7eb;
        }

        .timeline-dot {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
        }

        .timeline-dot.completed {
            background: #10b981;
        }

        .timeline-dot.current {
            background: #f59e0b;
            font-size: 20px;
        }

        .timeline-dot.pending {
            background: #d1d5db;
        }

        .timeline-content h3 {
            color: #111827;
            font-size: 16px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .timeline-content p {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 3px;
        }

        .timeline-date {
            color: #9ca3af;
            font-size: 12px;
            margin-top: 5px;
        }

        .details-section {
            margin-top: 30px;
        }

        .details-section h3 {
            color: #111827;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .detail-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
        }

        .detail-label {
            color: #6b7280;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            color: #111827;
            font-size: 14px;
            font-weight: 500;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.2);
        }

        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #f8f5ff;
        }

        .info-box {
            background: #e0f2fe;
            border-left: 4px solid #0284c7;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .info-box p {
            color: #0c4a6e;
            font-size: 13px;
            line-height: 1.6;
        }

        @media (max-width: 600px) {
            .card {
                padding: 20px;
            }

            .status-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-content">
            <h1>Status Permohonan Peminjaman Kendaraan</h1>
        </div>
    </div>

    <!-- Content -->
    <div class="container">
        <!-- Main Card -->
        <div class="card">
            <!-- Status Header -->
            <div class="status-header">
                <div class="status-icon">⏳</div>
                <div class="status-text">
                    <h2>{{ ucfirst($perjalanan->status_perjalanan) }}</h2>
                    <p>Referensi: <code style="background: #f3f4f6; padding: 2px 6px; border-radius: 3px;">{{ $token }}</code></p>
                </div>
            </div>

            <!-- Timeline -->
            <div class="timeline">
                <!-- Step 1: Submitted -->
                <div class="timeline-item">
                    <div class="timeline-dot completed">✓</div>
                    <div class="timeline-content">
                        <h3>Permohonan Diterima</h3>
                        <p>Permohonan Anda telah kami terima dan sedang diproses.</p>
                        <div class="timeline-date">{{ $perjalanan->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>

                <!-- Step 2: Under Review -->
                <div class="timeline-item">
                    <div class="timeline-dot {{ $perjalanan->status_perjalanan === 'Disetujui' ? 'completed' : 'current' }}">
                        {{ $perjalanan->status_perjalanan === 'Disetujui' ? '✓' : '...' }}
                    </div>
                    <div class="timeline-content">
                        <h3>Sedang Diproses</h3>
                        <p>Tim kami sedang meninjau permohonan Anda.</p>
                        <div class="timeline-date">Estimasi: 1-3 hari kerja</div>
                    </div>
                </div>

                <!-- Step 3: Approved -->
                <div class="timeline-item">
                    <div class="timeline-dot {{ $perjalanan->status_perjalanan === 'Disetujui' ? 'completed' : 'pending' }}">
                        {{ $perjalanan->status_perjalanan === 'Disetujui' ? '✓' : '' }}
                    </div>
                    <div class="timeline-content">
                        <h3>Disetujui/Ditolak</h3>
                        <p>Anda akan menerima notifikasi via WhatsApp tentang keputusan kami.</p>
                        <div class="timeline-date">Menunggu...</div>
                    </div>
                </div>

                <!-- Step 4: Completed -->
                <div class="timeline-item">
                    <div class="timeline-dot pending"></div>
                    <div class="timeline-content">
                        <h3>Selesai</h3>
                        <p>Proses permohonan telah selesai dan siap untuk pengambilan kendaraan.</p>
                        <div class="timeline-date">Belum</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="card">
            <div class="details-section">
                <h3>📋 Detail Permohonan</h3>
                <div class="details-grid">
                    <div class="detail-card">
                        <div class="detail-label">Nama Peminjam</div>
                        <div class="detail-value">{{ $perjalanan->nama_pengguna }}</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Unit Kerja</div>
                        <div class="detail-value">{{ $perjalanan->unitKerja->nama_unit_kerja ?? 'N/A' }}</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Kontak</div>
                        <div class="detail-value">{{ $perjalanan->kontak_pengguna }}</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Status Pemohon</div>
                        <div class="detail-value">{{ $perjalanan->status_sebagai }}</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Jenis Kegiatan</div>
                        <div class="detail-value">{{ $perjalanan->nama_kegiatan }}</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Tanggal Keberangkatan</div>
                        <div class="detail-value">{{ \Carbon\Carbon::parse($perjalanan->waktu_keberangkatan)->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Lokasi Keberangkatan</div>
                        <div class="detail-value">{{ $perjalanan->lokasi_keberangkatan }}</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Jumlah Rombongan</div>
                        <div class="detail-value">{{ $perjalanan->jumlah_rombongan }} orang</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="card">
            <div class="info-box">
                <p><strong>💡 Tips:</strong> Refresh halaman ini untuk melihat update status terbaru. Anda juga akan menerima notifikasi via WhatsApp ketika ada perubahan status permohonan.</p>
            </div>

            <div class="action-buttons">
                <a href="{{ route('peminjaman.form') }}" class="btn btn-secondary">← Kembali ke Form</a>
                <button onclick="location.reload()" class="btn btn-primary">🔄 Refresh Status</button>
            </div>
        </div>
    </div>
</body>
</html>
