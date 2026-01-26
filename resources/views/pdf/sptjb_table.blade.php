1<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pembayaran Uang Saku Pengemudi</title>
    <style>
        body {
            font-family: 'Arial', sans-serif; /* Gambar menggunakan font sans-serif bersih */
            font-size: 8.5pt;
            margin: 0.5cm;
            line-height: 1.2;
        }
        .header { text-align: left; margin-bottom: 20px; }
        .header p { margin: 0; padding: 0; }
        .header .title-univ { font-size: 11pt; font-weight: bold; }
        .header .address { font-size: 10pt; font-weight: bold; }
        .header .description { font-size: 8pt; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            vertical-align: middle;
        }
        th {
            background-color: #e0e0e0; /* Warna abu-abu pada header sesuai gambar */
            text-align: center;
            font-size: 8pt;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* Layout Tanda Tangan */
        .footer-sign {
            margin-top: 30px;
            width: 100%;
            border: none;
        }
        .footer-sign td {
            border: none;
            width: 25%;
            vertical-align: top;
            padding: 0 10px;
            font-size: 8pt;
        }
        .sign-space { height: 62px; }
        .sign-space-direktur { height: 45px; }



        @page {
            size: A4 landscape;
            margin: 1cm;
        }
    </style>
</head>
<body>

    <div class="header">
        <table style="width: 100%; border: none; margin-bottom: 20px;">
            <tr>
                <td style="width: 70%; text-align: left; border: none; padding: 0;">
                    <p style="font-size: 11pt; margin: 0; padding: 0;">KEMENTERIAN PENDIDIKAN TINGGI, SAINS DAN TEKNOLOGI</p>
                    <p class="title-univ" style="margin: 0; padding: 0;">UNIVERSITAS PADJADJARAN</p>
                    <p class="address" style="margin: 0; padding: 0;">JALAN RAYA BANDUNG - SUMEDANG KM.21 JATINANGOR</p>
                </td>
                <td style="width: 30%; text-align: right; border: none; padding: 0;">
                    <p class="description" style="font-size: 8pt; margin: 0; padding: 0;">Daftar Uang Saku Pengemudi dalam rangka melayani Kegiatan Civitas Akademika {{ $dateRangeString }}</p>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Tanggal Penugasan</th>
                <th>Jumlah Hari</th>
                <th>Besaran uang / Hari RP.</th>
                <th>Jumlah Uang Diterima</th>
                <th>Nomor Rekening</th>
                <th>Nama Bank</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupedDetails as $detail)
                <tr>
                    <td class="text-center">{{ $detail->no }}</td>
                    <td>{{ $detail->nama }}</td>
                    <td class="text-center">{{ $detail->jabatan }}</td>
                    <td class="text-center">{{ $detail->tanggal_penugasan }}</td>
                    <td class="text-center">{{ $detail->jumlah_hari }}</td>
                    <td class="text-right">Rp. {{ number_format($detail->besaran_uang_per_hari, 0, ',', '.') }}</td>
                    <td class="text-right">Rp. {{ number_format($detail->jumlah_uang_diterima, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $detail->nomor_rekening ?? '-' }}</td>
                    <td class="text-center">{{ \App\Models\Staf::where('nama_staf', $detail->nama)->first()->nama_bank ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold;">
                <td colspan="6" class="text-right">Jumlah</td>
                <td class="text-right">Rp{{ number_format($groupedDetails->sum('jumlah_uang_diterima'), 0, ',', '.') }}</td>
                <td colspan="2" style="background-color: #e0e0e0;"></td>
            </tr>
        </tfoot>
    </table>

    <table class="footer-sign">
        <tr>
            <td>
                Mengetahui/Menyetujui:<br>
                Pembuat Komitmen,
                <div class="sign-space"></div>
                <strong>{{ $penandatangan->ppk_nama ?? 'Nurhayati , SE.,M.Ak.' }}</strong><br>
                NIP.{{ $penandatangan->ppk_nip ?? '197101111999032002' }}
            </td>
            <td>
                Dibayar Lunas tgl ........<br>
                Bendahara Pengeluaran Pembantu,
                <div class="sign-space"></div>
                <strong>{{ $penandatangan->bpp_nama ?? 'Sri Rismayanti,S.Sos' }}</strong><br>
                NIP.{{ $penandatangan->bpp_nip ?? '197403191999032002' }}
            </td>
            <td>
                Mengetahui...<br>
                Direktur Pengelolaan Aset<br>dan Sarana Prasarana
                <div class="sign-space-direktur"></div>
                <strong>{{ $penandatangan->direktur_nama ?? 'Edward Henry,S.IP.,MM.' }}</strong><br>
                NIP.{{ $penandatangan->direktur_nip ?? '196910232002121001' }}
            </td>
            <td>
                Jatinangor,
                @php
                    $tanggalSurat = $sptjb->tanggal_surat;
                    $displayTanggalSurat = '';
                    if ($tanggalSurat && strlen($tanggalSurat) > 4) {
                        try {
                            $displayTanggalSurat = \Carbon\Carbon::parse($tanggalSurat)->locale('id_ID')->isoFormat('D MMMM Y');
                        } catch (\Exception $e) {
                            $displayTanggalSurat = $tanggalSurat; // Fallback to raw string if parsing fails
                        }
                    }
                @endphp
                {{ $displayTanggalSurat }}<br>
                Pembuat Daftar
                <div class="sign-space"></div>
                <strong>{{ $penandatangan->pembuat_nama ?? 'Agah Gunadi Ramdhan' }}</strong><br>
                NIP.{{ $penandatangan->pembuat_nip ?? '196812182001121001' }}
            </td>
        </tr>
    </table>

</body>
</html>
