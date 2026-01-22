<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/Favicon_Unpad.ico') }}" />
    <!-- Judul title diubah agar lebih dinamis jika memungkinkan -->
    <title>Surat Keterangan Perjalanan - {{ $perjalanan->nomor_perjalanan ?? 'NOMOR_SURAT' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0px 40px;
            line-height: 1.5;
            font-size: 12px; /* Ukuran font standar untuk dokumen */
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid black;
            padding-bottom: 15px;
            margin-bottom: 5px;
            border-collapse: collapse;
        }
        .logo-container {
            width: 140px;
            vertical-align: middle;
            text-align: left;
        }
        .logo-container img {
            width: 130px;
            height: auto;
        }
        .header-text-container {
            text-align: center;
            vertical-align: middle;
        }
        .header-text-container h1 {
            margin: 0;
            font-size: 16px; /* KEMENTERIAN */
            font-weight: bold;
        }
        .header-text-container h2 {
            margin: 0;
            font-size: 18px; /* UNIVERSITAS PADJADJARAN */
            font-weight: bold;
        }
        .header-text-container p {
            margin: 2px 0;
            font-size: 12px;
        }
        .title-section {
            text-align: center;
            margin-top: 0;
            margin-bottom: 30px;
        }
        .title-section h3 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
        }
        .title-section p {
            margin: 5px 0 0 0;
            font-size: 12px;
        }
        .content {
            margin-bottom: 20px;
        }
        .content-intro {
            margin-bottom: 20px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .details-table td {
            padding: 5px 10px;
            border: none; /* No borders for cleaner look */
            vertical-align: top;
        }
        .details-table .label {
            font-weight: normal;
            width: 180px;
        }
        .details-table .separator {
            width: 10px;
            text-align: center;
        }
        .details-table .value {
            /* No specific width, let it expand */
        }

        .clear {
            /* Untuk membersihkan float jika diperlukan */
            clear: both;
        }
    </style>
</head>
<body>
    <!-- 1. HEADER (Logo, Kop Surat) -->
    <table class="header-table">
        <tr>
            <td class="logo-container">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/pdf/logo-pdf 1.png'))) }}" alt="Logo Universitas Padjadjaran">
            </td>
            <td class="header-text-container">
                <h1>KEMENTERIAN PENDIDIKAN TINGGI, SAINS DAN TEKNOLOGI</h1>
                <h2>UNIVERSITAS PADJADJARAN</h2>
                <p>Jalan Dipati Ukur No. 35 Bandung 40132</p>
                <p>Jalan Ir. Soekarno Km. 21 Jatinangor, Sumedang 45363</p>
                <p>Telepon (022) 84288888 Laman: www.unpad.ac.id, Email: humas@unpad.ac.id</p>
            </td>
        </tr>
    </table>
    <div class="title-section">
        <h3>SURAT KETERANGAN PERJALANAN</h3>
        <p>Nomor Perjalanan: {{ $perjalanan->nomor_perjalanan ?? 'N/A' }}</p>
    </div>

    <!-- 3. ISI SURAT -->
    <div class="content">
        <p class="content-intro">Dengan surat ini diberikan keterangan perjalanan kepada:</p>

        <!-- Bagian detail menggunakan table untuk struktur yang lebih baik -->
        <table class="details-table">
            @forelse($perjalanan->pengemudi as $pengemudi)
                <tr>
                    <td class="label">Nama Pengemudi</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $pengemudi->nama_staf ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">NIP Pengemudi</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $pengemudi->nip_staf ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td class="label">Nama Pengemudi</td>
                    <td class="separator">:</td>
                    <td class="value">N/A</td>
                </tr>
                <tr>
                    <td class="label">NIP Pengemudi</td>
                    <td class="separator">:</td>
                    <td class="value">N/A</td>
                </tr>
            @endforelse

            @forelse($perjalanan->asisten as $asisten)
                @if($asisten->nama_staf)
                <tr>
                    <td class="label">Nama Asisten</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $asisten->nama_staf }}</td>
                </tr>
                @if($asisten->nip_staf)
                <tr>
                    <td class="label">NIP Asisten</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $asisten->nip_staf }}</td>
                </tr>
                @endif
                @endif
            @empty
            {{-- If no assistants, nothing will be rendered --}}
            @endforelse

            <tr>
                <td class="label">Jadwal Tugas</td>
                <td class="separator">:</td>
                <td class="value">
                    @php
                        $jadwalText = '';
                        $details = $perjalanan->details->first(); // Assuming single detail for simplicity
                        $tipePenugasan = $details ? $details->tipe_penugasan : null;

                        if ($tipePenugasan === 'Antar & Jemput') {
                            $startDateTime = $perjalanan->waktu_keberangkatan ? \Carbon\Carbon::parse($perjalanan->waktu_keberangkatan) : null;
                            $endDateTime = $perjalanan->waktu_kepulangan ? \Carbon\Carbon::parse($perjalanan->waktu_kepulangan) : null;
                        } elseif ($tipePenugasan === 'Antar (Keberangkatan)') {
                            $startDateTime = $perjalanan->waktu_keberangkatan ? \Carbon\Carbon::parse($perjalanan->waktu_keberangkatan) : null;
                            $endDateTime = $details && $details->waktu_selesai_penugasan ? \Carbon\Carbon::parse($details->waktu_selesai_penugasan) : null;
                        } elseif ($tipePenugasan === 'Jemput (Kepulangan)') {
                            $startDateTime = $details && $details->waktu_selesai_penugasan ? \Carbon\Carbon::parse($details->waktu_selesai_penugasan) : null;
                            $endDateTime = $perjalanan->waktu_kepulangan ? \Carbon\Carbon::parse($perjalanan->waktu_kepulangan) : null;
                        } else {
                            // Default to Antar & Jemput if no tipe_penugasan
                            $startDateTime = $perjalanan->waktu_keberangkatan ? \Carbon\Carbon::parse($perjalanan->waktu_keberangkatan) : null;
                            $endDateTime = $perjalanan->waktu_kepulangan ? \Carbon\Carbon::parse($perjalanan->waktu_kepulangan) : null;
                        }

                        if ($startDateTime && $endDateTime) {
                            $startDate = $startDateTime->toDateString();
                            $endDate = $endDateTime->toDateString();
                            $startTime = $startDateTime->format('H:i');
                            $endTime = $endDateTime->format('H:i');
                            if ($startDate === $endDate) {
                                $jadwalText = $startDateTime->format('j F Y') . ' ' . $startTime . ' - ' . $endTime;
                            } else {
                                $jadwalText = $startDateTime->format('j') . ' - ' . $endDateTime->format('j F Y') . ' ' . $startTime . ' - ' . $endTime;
                            }
                        } elseif ($startDateTime) {
                            $jadwalText = $startDateTime->format('j F Y') . ' ' . $startDateTime->format('H:i');
                        } elseif ($endDateTime) {
                            $jadwalText = $endDateTime->format('j F Y') . ' ' . $endDateTime->format('H:i');
                        } else {
                            $jadwalText = 'N/A';
                        }
                    @endphp
                    {{ $jadwalText }}
                </td>
            </tr>

            <tr>
                <td class="label">Nomor Kendaraan</td>
                <td class="separator">:</td>
                <td class="value">{{ $perjalanan->kendaraan->first()->nopol_kendaraan ?? 'N/A' }} ({{ $perjalanan->kendaraan->first()->merk_type ?? 'N/A' }})</td>
            </tr>
            <tr>
                <td class="label">Lokasi Keberangkatan</td>
                <td class="separator">:</td>
                <td class="value">{{ str_replace('06 January 2026', $perjalanan->waktu_keberangkatan ? \Carbon\Carbon::parse($perjalanan->waktu_keberangkatan)->format('H:i') : 'BERANGKAT', $perjalanan->lokasi_keberangkatan ?? 'N/A') }}</td>
            </tr>
            <tr>
                <td class="label">Alamat Tujuan</td>
                <td class="separator">:</td>
                <td class="value">{{ $perjalanan->alamat_tujuan ?? 'N/A' }}, {{ $perjalanan->wilayah->nama_wilayah ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Pengguna</td>
                <td class="separator">:</td>
                <td class="value">{{ $perjalanan->nama_pengguna ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Kontak Perwakilan</td>
                <td class="separator">:</td>
                <td class="value">{{ $perjalanan->kontak_pengguna_perwakilan ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- 4. TANGGAL DAN TANDA TANGAN -->
    <table style="width: 100%; margin-top: 40px;">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%; text-align: left; margin-left: auto;">
                @php
                    $signatureDate = $startDateTime ? $startDateTime->format('d F Y') : now()->format('d F Y');
                @endphp
                <p style="margin: 2px 0;">Jatinangor, {{ $signatureDate }}</p>
                <p style="margin: 2px 0;">A.n Direktur Pengelolaan Aset</p>
                <p style="margin: 2px 0;">dan Sarana, Prasarana</p>
                <img src="{{ public_path('images/pdf/ttdlewo.png') }}" alt="Tanda Tangan" style="width: 150px; height: auto; margin-top: 10px; margin-bottom: 10px;">
                <p style="font-weight: bold; text-decoration: underline; margin: 2px 0;">Gugun Gunawan, S.S.</p>
                <p style="margin: 2px 0;">NIP. 198211262016023001</p>
            </td>
        </tr>
    </table>

    <div class="clear"></div>

</body>
</html>

