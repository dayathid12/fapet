<!DOCTYPE html>
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
        .header { text-align: center; margin-bottom: 20px; }
        .header p { margin: 0; padding: 0; }
        .header .title-univ { font-size: 11pt; font-weight: bold; }
        .header .address { font-size: 10pt; font-weight: bold; border-bottom: 1px solid black; display: inline-block; width: 100%; padding-bottom: 2px; }
        .header .description { font-size: 8pt; margin-top: 5px; }

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
        .sign-space { height: 60px; }

        @page {
            size: A4 landscape;
            margin: 1cm;
        }
    </style>
</head>
<body>

    <div class="header">
        <p style="font-size: 11pt;">KEMENTERIAN PENDIDIKAN TINGGI, SAINS DAN TEKNOLOGI</p>
        <p class="title-univ">UNIVERSITAS PADJADJARAN</p>
        <p class="address">JALAN RAYA BANDUNG - SUMEDANG KM.21 JATINANGOR</p>
        <p class="description">Daftar Pembayaran Uang Saku Pengemudi dalam rangka melayani Kegiatan Civitas Akademika Unpad tgl {{ $sptjb->tanggal_range ?? \Carbon\Carbon::now()->locale('id_ID')->isoFormat('D MMMM YYYY') }}</p>
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
            @foreach($sptjb->details as $index => $detail)
                @php
                    // Pre-fetch data staf untuk performa lebih baik
                    $staf = \App\Models\Staf::where('nama_staf', $detail->nama)->first();
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $detail->nama }}</td>
                    <td class="text-center">{{ $detail->jabatan }}</td>
                    <td class="text-center">{{ $detail->tanggal_penugasan }}</td>
                    <td class="text-center">{{ $detail->jumlah_hari }}</td>
                    <td class="text-right">Rp. {{ number_format($detail->besaran_uang_per_hari, 0, ',', '.') }}</td>
                    <td class="text-right">Rp. {{ number_format($detail->jumlah_uang_diterima, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $staf ? $staf->rekening : '-' }}</td>
                    <td class="text-center">{{ $staf ? $staf->nama_bank : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold;">
                <td colspan="6" class="text-right">Jumlah</td>
                <td class="text-right">Rp{{ number_format($sptjb->details->sum('jumlah_uang_diterima'), 0, ',', '.') }}</td>
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
                Direktur Pengelolaan Aset
                <div class="sign-space"></div>
                <strong>{{ $penandatangan->direktur_nama ?? 'Edward Henry,S.IP.,MM.' }}</strong><br>
                NIP.{{ $penandatangan->direktur_nip ?? '196910232002121001' }}
            </td>
            <td>
                Jatinangor, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>
                Pembuat Daftar
                <div class="sign-space"></div>
                <strong>{{ $penandatangan->pembuat_nama ?? 'Agah Gunadi Ramdhan' }}</strong><br>
                NIP.{{ $penandatangan->pembuat_nip ?? '196812182001121001' }}
            </td>
        </tr>
    </table>

</body>
</html>
