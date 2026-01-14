<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/Favicon_Unpad.ico') }}" />
    <title>Surat Tugas - {{ $perjalanan->nomor_perjalanan ?? 'NOMOR_SURAT' }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif; /* Mengubah ke Serif agar mirip surat resmi di gambar */
            margin: 0px 40px;
            line-height: 1.3;
            font-size: 12pt;
            color: #000;
        }

        /* HEADER STYLES */
        .header-table {
            width: 100%;
            border-bottom: 3px solid black; /* Garis tebal bawah kop */
            padding-bottom: 10px;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .logo-container {
            width: 120px;
            vertical-align: top;
            text-align: left;
        }
        .logo-container img {
            width: 110px;
            height: auto;
        }
        .header-text-container {
            text-align: center;
            vertical-align: top;
            padding-left: 10px;
            padding-right: 10px;
        }
        .header-text-container h1 {
            margin: 0;
            font-size: 14pt;
            font-weight: normal; /* Di gambar terlihat regular/semi-bold */
            text-transform: uppercase;
        }
        .header-text-container h2 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header-text-container .address {
            margin: 2px 0;
            font-size: 10pt;
        }

        /* TITLE SECTION */
        .title-section {
            text-align: center;
            margin-bottom: 20px;
        }
        .title-section h3 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
        }
        .title-section p {
            margin: 2px 0 0 0;
            font-size: 12pt;
        }

        /* CONTENT SECTION */
        .content-intro {
            margin-bottom: 15px;
            text-align: justify;
        }

        /* ASSIGNEE TABLE (Tabel Nama/NIP) */
        .assignee-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .assignee-table th, .assignee-table td {
            border: 1px solid black;
            padding: 5px 10px;
            text-align: center;
        }
        .assignee-table th {
            background-color: #fff; /* Bisa diubah jika perlu background */
            font-weight: normal;
        }

        /* DETAILS LIST (Unit Kerja, Kegiatan, dll) */
        .details-list-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 20px;
        }
        .details-list-table td {
            padding: 3px 5px;
            vertical-align: top;
            border: none;
        }
        .details-list-table .label {
            width: 140px;
        }
        .details-list-table .separator {
            width: 10px;
            text-align: center;
        }

        /* CLOSING SECTION */
        .closing-text {
            margin-bottom: 10px;
            text-align: justify;
        }

        /* SIGNATURE SECTION */
        .signature-table {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .signature-content {
            text-align: left;
            margin-left: auto; /* Push to the right */
            width: 55%; /* Width of the signature area */
        }
        .qr-code {
            width: 80px;
            height: 80px;
            margin: 10px 0;
        }

    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="logo-container">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/pdf/logo-pdf 1.png'))) }}" alt="Logo Universitas Padjadjaran">
            </td>
            <td class="header-text-container">
                <h1>KEMENTERIAN PENDIDIKAN TINGGI, SAINS,</h1>
                <h1>DAN TEKNOLOGI</h1>
                <h2>UNIVERSITAS PADJADJARAN</h2>
                <div class="address">
                    Jalan Dipati Ukur No. 35 Bandung 40132<br>
                    Jalan Ir. Soekarno Km. 21 Jatinangor, Sumedang 45363<br>
                    Telepon (022) 84288888 Laman: www.unpad.ac.id</u>, Email: humas@unpad.ac.id</u>
                </div>
            </td>
        </tr>
    </table>

    <div class="title-section">
        <h3>Surat Tugas</h3>
        <p>Nomor : {{ ($perjalanan->no_surat_tugas ?? '555') . '/UN6.4.2.1/KP.00/2026' }}</p>
    </div>

    <div class="content">
        <p class="content-intro">
            Direktur Pengelolaan Aset dan Sarana Prasarana Universitas Padjadjaran memberi tugas kepada:
        </p>

        <table class="assignee-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($perjalanan->pengemudi as $pengemudi)
                <tr>
                    <td>{{ $pengemudi->nama_staf ?? 'N/A' }}</td>
                    <td>{{ $pengemudi->nip_staf ?? 'N/A' }}</td>
                </tr>
                @empty
                <tr>
                    <td>N/A</td>
                    <td>N/A</td>
                </tr>
                @endforelse
                @forelse($perjalanan->asisten as $asisten)
                <tr>
                    <td>{{ $asisten->nama_staf ?? 'N/A' }}</td>
                    <td>{{ $asisten->nip_staf ?? 'N/A' }}</td>
                </tr>
                @empty
                {{-- No additional row for empty assistants --}}
                @endforelse
            </tbody>
        </table>

        <p style="margin-top: 15px; margin-bottom: 5px;">
            untuk melayani kegiatan/keberangkatan:
        </p>

        <table class="details-list-table">
            <tr>
                <td class="label">Unit</td>
                <td class="separator">:</td>
                <td class="value">{{ $perjalanan->unit_kerja_fakultas_ukm ?? $perjalanan->unitKerja->nama_unit_kerja ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Kegiatan</td>
                <td class="separator">:</td>
                <td class="value">{{ $perjalanan->nama_kegiatan ?? $perjalanan->jenis_kegiatan ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">Tujuan</td>
                <td class="separator">:</td>
                <td class="value">{{ $perjalanan->alamat_tujuan ?? '' }} {{ $perjalanan->entryPengeluaran?->rincianPengeluarans?->first()?->kota_kabupaten ?? $perjalanan->wilayah->nama_wilayah ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">Keberangkatan</td>
                <td class="separator">:</td>
                <td class="value">{{ $perjalanan->lokasi_keberangkatan ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal</td>
                <td class="separator">:</td>
                <td class="value">
                    {{ $perjalanan->waktu_keberangkatan ? \Carbon\Carbon::parse($perjalanan->waktu_keberangkatan)->translatedFormat('d F Y') : 'N/A' }}
                </td>
            </tr>
        </table>

        <p class="closing-text">
            Segera setelah melaksanakan tugas ini wajib menyampaikan laporan penyelesaian tugas dan penggunaan biaya operasional.
        </p>
        <p class="closing-text">
            Demikian surat tugas ini diterbitkan untuk dilaksanakan dengan sebaik - baiknya dan penuh rasa tanggung jawab.
        </p>
    </div>

    <table class="signature-table">
        <tr>
            <td style="width: 30%;"></td>

            <td style="width: 90%; vertical-align: top;">
                <div class="signature-content">
                    <p style="margin: 0;">Jatinangor, {{ now()->translatedFormat('d F Y') }}</p>
                    <p style="margin: 0;">a.n. Direktur</p>
                    <p style="margin: 0;">Sekretaris Direktorat Pengelolaan</p>
                    <p style="margin: 0;">Aset dan Sarana Prasarana,</p>
                    @if($perjalanan->upload_tte)
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/' . $perjalanan->upload_tte))) }}" alt="TTE Signature" style="max-width: 300px; max-height: 150px; margin: 10px 0;">
                    @endif
                    <p style="margin: 0;">Arief Irmansyah, S.Sos., M.Si.</p>
                    <p style="margin: 0;">NIP. 197809252005021008</p>
                </div>
            </td>
        </tr>
    </table>x

</body>
</html>
