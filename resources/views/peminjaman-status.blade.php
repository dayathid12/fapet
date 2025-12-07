<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pelacakan Permohonan - Peminjaman Kendaraan</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            /* Colors */
            --primary: #2563eb; /* Blue 600 */
            --primary-bg: #eff6ff; /* Blue 50 */

            --success: #059669; /* Emerald 600 */
            --success-bg: #ecfdf5;

            --danger: #e11d48; /* Rose 600 */
            --danger-bg: #fff1f2;

            --slate-50: #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-400: #94a3b8;
            --slate-600: #475569;
            --slate-800: #1e293b;
            --slate-900: #0f172a;

            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }

        body {
            background-color: var(--slate-50);
            color: var(--slate-800);
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        /* --- Layout Utils --- */
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .header-section {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        @media(min-width: 768px) {
            .header-section {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }

        .badge-corp {
            background-color: var(--slate-200);
            color: var(--slate-600);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: inline-block;
            margin-bottom: 0.5rem;
        }

        .page-title { font-size: 1.5rem; font-weight: 700; color: var(--slate-900); }
        .page-subtitle { font-size: 0.875rem; color: var(--slate-600); margin-top: 0.25rem; }
        .ref-code { font-family: monospace; font-weight: 600; color: var(--slate-800); }

        .btn-print {
            background: white;
            border: 1px solid var(--slate-200);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--slate-600);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }
        .btn-print:hover { background: var(--slate-100); }

        /* --- Dashboard Grid --- */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media(min-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 2fr 1fr;
            }
        }

        /* --- Cards --- */
        .card {
            background: white;
            border-radius: 12px;
            border: 1px solid var(--slate-200);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .card:last-child { margin-bottom: 0; }

        .card-body { padding: 1.5rem; }

        /* --- Status Banner --- */
        .status-banner {
            border-radius: 12px;
            border: 1px solid;
            padding: 1.5rem;
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        /* Status Themes */
        .status-banner.pending {
            background-color: var(--primary-bg);
            border-color: #bfdbfe; /* Blue 200 */
        }
        .status-banner.pending .status-icon { background: #dbeafe; color: var(--primary); }
        .status-banner.pending .status-text h2 { color: #1e40af; }
        .status-banner.pending .status-text p { color: #1e40af; }

        .status-banner.approved {
            background-color: var(--success-bg);
            border-color: #a7f3d0;
        }
        .status-banner.approved .status-icon { background: #d1fae5; color: var(--success); }
        .status-banner.approved .status-text h2 { color: #065f46; }

        .status-banner.completed {
            background-color: var(--success-bg);
            border-color: #a7f3d0;
        }
        .status-banner.completed .status-icon { background: #d1fae5; color: var(--success); }
        .status-banner.completed .status-text h2 { color: #065f46; }

        .status-banner.rejected {
            background-color: var(--danger-bg);
            border-color: #fecdd3;
        }
        .status-banner.rejected .status-icon { background: #ffe4e6; color: var(--danger); }
        .status-banner.rejected .status-text h2 { color: #9f1239; }

        .status-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.25rem;
        }

        .status-text h2 { font-size: 1.125rem; font-weight: 700; margin-bottom: 0.25rem; }
        .status-text p { font-size: 0.875rem; opacity: 0.9; line-height: 1.5; }

        /* --- Stepper / Timeline --- */
        .section-title {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--slate-400);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1.5rem;
        }

        .stepper {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 1rem;
        }

        /* Horizontal Line */
        .stepper::before {
            content: '';
            position: absolute;
            top: 16px; /* Half of circle height */
            left: 0;
            right: 0;
            height: 2px;
            background-color: var(--slate-100);
            z-index: 1;
        }

        .step-item {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            z-index: 2;
            flex: 1;
            text-align: center;
        }

        .step-circle {
            width: 32px;
            height: 32px;
            background: white;
            border: 2px solid var(--slate-200);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--slate-400);
            flex-shrink: 0;
            transition: all 0.3s;
        }

        /* Active/Completed Step Styles */
        .step-item.completed .step-circle {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .step-item.active .step-circle {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
            box-shadow: 0 0 0 4px var(--primary-bg);
        }

        /* If Rejected */
        .step-item.error .step-circle {
            background: var(--danger);
            border-color: var(--danger);
            color: white;
        }

        .step-content h4 { font-size: 0.875rem; font-weight: 700; color: var(--slate-800); }
        .step-content p { font-size: 0.75rem; margin-top: 0.25rem; }

        .pulse-text { color: var(--primary); font-weight: 600; animation: pulse 2s infinite; }
        .final-text { color: var(--danger); font-weight: 700; }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.6; }
            100% { opacity: 1; }
        }

        /* --- Route Visualization --- */
        .route-visual {
            position: relative;
            padding-left: 1.5rem;
            border-left: 2px solid var(--slate-100);
            margin-left: 0.75rem;
        }

        .route-point { position: relative; margin-bottom: 2rem; }
        .route-point:last-child { margin-bottom: 0; }

        .route-dot {
            position: absolute;
            left: -1.95rem; /* Adjust based on border and dot size */
            top: 0;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 0 0 1px var(--slate-200);
            background: var(--slate-200);
        }

        .route-dot.start { background: var(--slate-400); }
        .route-dot.end { background: var(--primary); box-shadow: 0 0 0 1px var(--primary-bg); }

        .route-label { font-size: 0.75rem; font-weight: 700; color: var(--slate-400); text-transform: uppercase; margin-bottom: 0.25rem; }
        .route-val { font-size: 1rem; font-weight: 600; color: var(--slate-800); }

        /* --- Info List (Sidebar) --- */
        .info-list { list-style: none; }
        .info-list li {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }
        .info-list li:last-child { margin-bottom: 0; }

        .info-icon {
            width: 36px;
            height: 36px;
            background: var(--slate-100);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--slate-600);
            flex-shrink: 0;
        }

        .info-text label { display: block; font-size: 0.75rem; color: var(--slate-500); margin-bottom: 0.125rem; }
        .info-text div { font-size: 0.875rem; font-weight: 600; color: var(--slate-800); }

        /* --- Passenger Card --- */
        .passenger-card {
            background: #4338ca; /* Indigo */
            color: white;
            border: none;
            position: relative;
        }
        .passenger-bg-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 4rem;
            opacity: 0.1;
            color: white;
        }

        .pax-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; opacity: 0.8; margin-bottom: 0.25rem; }
        .pax-count { font-size: 2rem; font-weight: 700; }
        .pax-unit { font-size: 1rem; font-weight: 500; opacity: 0.8; }

    </style>
</head>
<body>

    <div class="container">

        <!-- Header -->
        <div class="header-section">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <img src="{{ asset('images/Unpad_logo.png') }}" alt="Logo Universitas Padjadjaran" style="height: 60px; width: auto; object-fit: contain;">
                <div>
                    <span class="badge-corp">Universitas Padjadjaran</span>
                    <h1 class="page-title">Pelacakan Permohonan</h1>
                    <p class="page-subtitle">ID Referensi: <span class="ref-code">{{ $perjalanan->nomor_perjalanan }}</span></p>
                </div>
            </div>
            <div>
                <button class="btn-print" onclick="window.print()">
                    <i class="fas fa-print"></i> Cetak Bukti
                </button>
            </div>
        </div>

        <div class="dashboard-grid">

            <!-- LEFT COLUMN -->
            <div class="main-content">

                <!-- Status Banner -->
                @php
                    $perjalanan = $perjalanan; // Ensure $perjalanan is defined
                    $detail = $perjalanan->details->first();
                    $originalStatus = $perjalanan->status_perjalanan;
                    $waktuKepulangan = $perjalanan->waktu_kepulangan ? \Carbon\Carbon::parse($perjalanan->waktu_kepulangan) : null;

                    $effectiveStatus = $originalStatus;
                    if ($originalStatus === 'Terjadwal' && $waktuKepulangan && $waktuKepulangan->isPast()) {
                        $effectiveStatus = 'Selesai';
                    }

                    // Default values
                    $statusClass = 'pending';
                    $statusIcon = 'fa-clock';
                    $statusTitle = 'Menunggu Persetujuan';
                    $statusDesc = 'Permohonan Anda sedang ditinjau oleh Poll Kendaraan Unpad.';

                    if ($effectiveStatus === 'Terjadwal') {
                        $statusClass = 'approved';
                        $statusIcon = 'fa-check';
                        $statusTitle = 'Terjadwal';
                        $statusDesc = 'Permohonan Anda telah disetujui dan untuk selanjutnya silahkan koordinasi dengan pengemudi pada halaman ini.';
                    } elseif ($effectiveStatus === 'Ditolak') {
                        $statusClass = 'rejected';
                        $statusIcon = 'fa-times';
                        $statusTitle = 'Permohonan Ditolak';
                        $statusDesc = 'Permohonan Anda ditolak. Silakan hubungi bagian Poll Kendaraan Unpad.';
                    } elseif ($effectiveStatus === 'Selesai') {
                        $statusClass = 'completed';
                        $statusIcon = 'fa-check-double';
                        $statusTitle = 'Selesai';
                        $statusDesc = 'Pelayanan perjalanan telah selesai. Terima kasih telah menggunakan layanan kami.';
                    }
                @endphp
                <div class="status-banner {{ $statusClass }}">
                    <div class="status-icon">
                        <i class="fas {{ $statusIcon }}"></i>
                    </div>
                    <div class="status-text">
                        <h2>{{ $statusTitle }}</h2>
                        <p>{{ $statusDesc }}</p>
                    </div>
                </div>

                <!-- Progress Tracker -->
                <div class="card" style="margin-top: 1.5rem;">
                    <div class="card-body">
                        <h3 class="section-title">Tahapan Proses</h3>

                        <div class="stepper">
                            @php
                                $step1Class = 'completed';
                                $step1Icon = '<i class="fas fa-check"></i>';
                                $step1Text = '';

                                $step2Class = '';
                                $step2Icon = '2';
                                $step2Text = '';

                                $step3Class = '';
                                $step3Icon = '3';
                                $step3Text = '';

                                $today = \Carbon\Carbon::today();

                                if ($perjalanan->status_perjalanan === 'Menunggu Persetujuan') {
                                    $step1Text = '<p class="pulse-text">Permohonan sedang diproses</p>';
                                    $step2Class = 'active';
                                    $step2Text = '<p class="pulse-text">Sedang diproses...</p>';
                                } elseif ($perjalanan->status_perjalanan === 'Terjadwal') {
                                    $step2Class = 'completed';
                                    $step2Icon = '<i class="fas fa-check"></i>';
                                    $step2Text = '<p style="color: var(--success); font-weight: 600;">Permohonan Disetujui</p>';

                                    // Check for Penugasan logic
                                    $departureDate = \Carbon\Carbon::parse($perjalanan->waktu_keberangkatan)->toDateString();
                                    $returnDate = $perjalanan->waktu_kepulangan ? \Carbon\Carbon::parse($perjalanan->waktu_kepulangan)->toDateString() : null;

                                    if ($departureDate === $today->toDateString()) {
                                        $step3Class = 'completed';
                                        $step3Icon = '<i class="fas fa-check" style="color: var(--success);"></i>';
                                        $step3Text = '<p style="color: var(--success); font-weight: 600;">Sedang melakukan pelayanan</p>';
                                    } elseif ($returnDate && $returnDate >= $today->toDateString()) {
                                        $step3Class = 'completed';
                                        $step3Icon = '<i class="fas fa-clipboard-check" style="color: var(--primary);"></i>';
                                        $step3Text = '<p style="color: var(--primary); font-weight: 600;">Pelayanan selesai</p>';
                                    } else {
                                        $step3Class = 'active';
                                        $step3Text = '<p class="pulse-text">Sedang diproses...</p>';
                                    }
                                } elseif ($perjalanan->status_perjalanan === 'Ditolak') {
                                    $step2Class = 'error';
                                    $step2Icon = '<i class="fas fa-times" style="color: var(--danger);"></i>';
                                    $step2Text = '<p class="final-text">Permohonan ditolak</p>';
                                }
                            @endphp

                            <!-- Step 1: Pengajuan - Always completed -->
                            <div class="step-item {{ $step1Class }}">
                                <div class="step-circle">{!! $step1Icon !!}</div>
                                <div class="step-content">
                                    <h4>Pengajuan</h4>
                                    {!! $step1Text !!}
                                </div>
                            </div>

                            <!-- Step 2: Keputusan -->
                            <div class="step-item {{ $step2Class }}">
                                <div class="step-circle">{!! $step2Icon !!}</div>
                                <div class="step-content">
                                    <h4>Keputusan</h4>
                                    {!! $step2Text !!}
                                </div>
                            </div>

                            <!-- Step 3: Penugasan -->
                            <div class="step-item {{ $step3Class }}">
                                <div class="step-circle">{!! $step3Icon !!}</div>
                                <div class="step-content">
                                    <h4>Penugasan</h4>
                                    {!! $step3Text !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trip Details -->
                <div class="card">
                    <div class="card-body">
                        <div style="display:flex; justify-content:space-between; margin-bottom:1.5rem;">
                            <h3 class="section-title" style="margin:0;">Detail Perjalanan</h3>
                            <span style="font-size:0.75rem; font-weight:600; background:var(--slate-100); padding:2px 8px; border-radius:4px;">
                                @if($perjalanan->details->isNotEmpty())
                                    {{ $perjalanan->details->count() }} Kendaraan
                                @else
                                    Kendaraan Belum Ditentukan
                                @endif
                            </span>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                            <!-- Route -->
                            <div class="route-visual">
                                <div class="route-point">
                                    <div class="route-dot start"></div>
                                    <div class="route-label">Dari</div>
                                    <div class="route-val">{{ $perjalanan->lokasi_keberangkatan }}</div>
                                </div>
                                <div class="route-point">
                                    <div class="route-dot end"></div>
                                    <div class="route-label" style="color:var(--primary);">Ke</div>
                                    <div class="route-val">{{ $perjalanan->alamat_tujuan }}</div>
                                </div>
                            </div>

                            <!-- Schedule Info -->
                            <div style="background:var(--slate-50); padding:1rem; border-radius:8px; align-self:start;">
                                <ul class="info-list">
                                    <li style="margin-bottom:1rem;">
                                        <div class="info-icon" style="width:28px; height:28px;"><i class="fas fa-calendar-alt" style="font-size:0.8rem;"></i></div>
                                        <div class="info-text">
                                            <label>Jadwal</label>
                                            <div>
                                                @php
                                                    $departure = \Carbon\Carbon::parse($perjalanan->waktu_keberangkatan);
                                                    $return = $perjalanan->waktu_kepulangan ? \Carbon\Carbon::parse($perjalanan->waktu_kepulangan) : null;
                                                    $daysDiff = $return ? $departure->diffInDays($return) : null;
                                                @endphp

                                                @if($return && $daysDiff <= 1)
                                                    {{ $departure->locale('id')->isoFormat('dddd, D MMM YYYY') }}<br>
                                                    {{ $departure->format('H:i') }} WIB - {{ $return->format('H:i') }} WIB
                                                @else
                                                    {{ $departure->locale('id')->isoFormat('dddd, D MMM YYYY') }}<br>
                                                    {{ $departure->format('H:i') }} WIB
                                                    @if($return)
                                                        <br><span style="font-size: 0.75rem; font-weight: 400; color: var(--slate-600); opacity: 0.75;">
                                                            {{ $return->locale('id')->isoFormat('dddd, D MMM YYYY') }}<br>
                                                            {{ $return->format('H:i') }} WIB
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="info-icon" style="width:28px; height:28px;"><i class="fas fa-clipboard-list" style="font-size:0.8rem;"></i></div>
                                        <div class="info-text">
                                            <label>Keperluan</label>
                                            <div>{{ $perjalanan->nama_kegiatan }}</div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                </div>

                <!-- RIGHT COLUMN (Sidebar) -->
                <div class="sidebar">

                <!-- User Info -->
                <div class="card">
                    <div class="card-body">
                        <h3 class="section-title" style="border-bottom:1px solid var(--slate-100); padding-bottom:0.5rem;">Informasi Pemohon</h3>
                        <ul class="info-list" style="margin-top:1rem;">
                            <li>
                                <div class="info-icon"><i class="fas fa-user"></i></div>
                                <div class="info-text">
                                    <label>Nama Lengkap</label>
                                    <div>{{ $perjalanan->nama_pengguna }}</div>
                                </div>
                            </li>
                            <li>
                                <div class="info-icon"><i class="fas fa-building"></i></div>
                                <div class="info-text">
                                    <label>Unit Kerja</label>
                                    <div>{{ $perjalanan->unitKerja ? $perjalanan->unitKerja->nama_unit_kerja : 'Unit Kerja Tidak Ditemukan' }}</div>
                                </div>
                            </li>
                            <li>
                                <div class="info-icon"><i class="fas fa-phone"></i></div>
                                <div class="info-text">
                                    <label>Kontak</label>
                                    <div>{{ $perjalanan->kontak_pengguna }}</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Vehicle Info (only show when status is Terjadwal or Selesai) -->
                @if(in_array($perjalanan->status_perjalanan, ['Terjadwal', 'Selesai']) && $perjalanan->details->isNotEmpty())
                    @foreach($perjalanan->details as $detail)
                    <div class="card">
                        <div class="card-body">
                            <h3 class="section-title" style="border-bottom:1px solid var(--slate-100); padding-bottom:0.5rem;">Informasi Kendaraan {{ $loop->iteration }}</h3>
                            <ul class="info-list" style="margin-top:1rem;">
                                <li>
                                    <div class="info-icon"><i class="fas fa-car"></i></div>
                                    <div class="info-text">
                                        <label>Nomor Polisi Kendaraan</label>
                                        <div>{{ $detail->kendaraan->nopol_kendaraan ?? 'Tidak tersedia' }}</div>
                                    </div>
                                </li>
                                <li>
                                    <div class="info-icon"><i class="fas fa-user-tie"></i></div>
                                    <div class="info-text">
                                        <label>Nama Pengemudi</label>
                                        <div>{{ $detail->pengemudi->nama_staf ?? 'Tidak tersedia' }}</div>
                                    </div>
                                </li>
                                <li>
                                    <div class="info-icon"><i class="fas fa-phone"></i></div>
                                    <div class="info-text">
                                        <label>Kontak Pengemudi</label>
                                        <div>{{ $detail->pengemudi->no_telepon ?? 'Tidak tersedia' }}</div>
                                    </div>
                                </li>
                                @if($detail->asisten)
                                <li>
                                    <div class="info-icon"><i class="fas fa-user-friends"></i></div>
                                    <div class="info-text">
                                        <label>Nama Asisten Pengemudi</label>
                                        <div>{{ $detail->asisten->nama_staf ?? 'Tidak tersedia' }}</div>
                                    </div>
                                </li>
                                <li>
                                    <div class="info-icon"><i class="fas fa-phone"></i></div>
                                    <div class="info-text">
                                        <label>Kontak Asisten Pengemudi</label>
                                        <div>{{ $detail->asisten->no_telepon ?? 'Tidak tersedia' }}</div>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    @endforeach
                @endif

                <!-- Passenger Count -->
                <div class="card passenger-card">
                    <div class="card-body">
                        <i class="fas fa-car passenger-bg-icon"></i>
                        <div class="pax-label">Total Penumpang</div>
                        <div class="pax-count">{{ $perjalanan->jumlah_rombongan }} <span class="pax-unit">Orang</span></div>
                    </div>
                </div>

                <!-- Help -->
                <div style="text-align:center; font-size:0.75rem; color:var(--slate-400); margin-top:1rem;">
                    Butuh bantuan? Hubungi logistik di <br>
                    <a href="#" style="color:var(--primary); text-decoration:none; font-weight:600;">logistik@unpad.ac.id</a>
                </div>

                </div>

                </div>
                </div>

                </body>
                </html>

