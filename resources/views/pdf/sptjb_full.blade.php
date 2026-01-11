<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pernyataan Tanggung Jawab Belanja</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            margin: 1.5cm;
            line-height: 1.3;
        }
        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .font-bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .mt-4 { margin-top: 1rem; }
        .mt-8 { margin-top: 2rem; }
        h3 { font-size: 14pt; margin-bottom: 5px; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .bordered th, .bordered td {
            border: 1px solid black;
            padding: 8px;
        }
        .no-border-table td {
            border: none;
            padding: 2px 0;
            vertical-align: top;
        }
        /* Tabel Khusus Tanda Tangan */
        .signature-table {
            width: 100%;
            border: none;
            margin-top: 2rem;
        }
        .signature-table td {
            border: none;
            text-align: center;
            vertical-align: top;
            width: 50%;
            padding-bottom: 10px;
        }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>

<?php

if (!function_exists('terbilang')) {

    function terbilang($angka) {

        $angka = abs((int)$angka);

        $bilangan = array(

            '',

            'satu',

            'dua',

            'tiga',

            'empat',

            'lima',

            'enam',

            'tujuh',

            'delapan',

            'sembilan',

            'sepuluh',

            'sebelas'

        );

        $temp = '';

        if ($angka < 12) {

            $temp = ' ' . $bilangan[$angka];

        } elseif ($angka < 20) {

            $temp = terbilang($angka - 10) . ' belas';

        } elseif ($angka < 100) {

            $temp = terbilang($angka / 10) . ' puluh' . terbilang($angka % 10);

        } elseif ($angka < 200) {

            $temp = ' seratus' . terbilang($angka - 100);

        } elseif ($angka < 1000) {

            $temp = terbilang($angka / 100) . ' ratus' . terbilang($angka % 100);

        } elseif ($angka < 2000) {

            $temp = ' seribu' . terbilang($angka - 1000);

        } elseif ($angka < 1000000) {

            $temp = terbilang($angka / 1000) . ' ribu' . terbilang($angka % 1000);

        } elseif ($angka < 2000000) { // Added for 'satu juta' vs 'satu ribu'

            $temp = ' satu juta' . terbilang($angka - 1000000);

        } elseif ($angka < 1000000000) {

            $temp = terbilang($angka / 1000000) . ' juta' . terbilang($angka % 1000000);

        } elseif ($angka < 1000000000000) {

            $temp = terbilang($angka / 1000000000) . ' milyar' . terbilang(fmod($angka, 1000000000));

        } elseif ($angka < 1000000000000000) {

            $temp = terbilang($angka / 1000000000000) . ' triliun' . terbilang(fmod($angka, 1000000000000));

        }

        return $temp;

    }

}

?>

    <div class="text-center">
        <h3 class="font-bold underline">SURAT PERNYATAAN TANGGUNG JAWAB BELANJA</h3>
        <span>Nomor: {{ $sptjb->no_sptjb }}</span>
    </div>

    <table class="no-border-table mt-8">
        <tr>
            <td style="width: 150px;">Satuan Kerja</td>
            <td>: Universitas Padjadjaran / Direktorat Pengelolaan Aset dan Sarana Prasarana</td>
        </tr>
        <tr>
            <td>Klasifikasi Anggaran</td>
            <td>: Beban Uang Saku Pengemudi / 528317</td>
        </tr>
    </table>

    <p class="text-justify mt-4">
        Yang bertanda tangan dibawah ini Kuasa Pengguna Anggaran Satuan Kerja Universitas Padjadjaran menyatakan bahwa
        saya bertanggung jawab secara formal dan material dan kebenaran perhitungan pemungutan pajak atas segala pembayaran
        tagihan yang telah kami perintahkan sebagai berikut:
    </p>

    <table class="bordered">
        <thead>
            <tr>
                <th class="text-center">No.</th>
                <th class="text-center">Akun</th>
                <th class="text-center">Penerima</th>
                <th class="text-center">Uraian</th>
                <th class="text-center">Nilai (Rupiah)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1.</td>
                <td class="text-center">528317</td>
                <td>{{ $sptjb->penerima ?? ($sptjb->details->first()->nama ?? 'N/A') }}</td>
                <td>{{ $sptjb->uraian ?? 'N/A' }}</td>
                <td style="text-align: right;">{{ number_format($sptjb->total_jumlah_uang_diterima, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

<table style="width: 100%; border: none; margin-top: 50px; border-collapse: collapse;">
        <tr>
            <td style="width: 60%; border: none;"></td>

            <td style="width: 40%; border: none; text-align: left; vertical-align: top;">
                <div style="line-height: 1.2;">
                    <p style="margin: 0;">Jatinangor, {{ \Carbon\Carbon::now()->locale('id_ID')->isoFormat('D MMMM YYYY') }}</p>
                    <p style="margin: 0;">Pembuat Komitmen,</p>

                    <div style="height: 80px;"></div>

                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">Nurhayati, SE., M.Ak.</p>
                    <p style="margin: 0;">NIP 197101111999032002</p>
                </div>
            </td>
        </tr>
    </table>

    <div class="page-break">
        <div style="text-align: right;">
            <div style="display: inline-block; text-align: left;">
                <p style="margin: 0;">T.A. 2025</p>
                <p style="margin: 0;">Nomor Bukti :</p>
                <p style="margin: 0;">M A K : 528317</p>
            </div>
        </div>

        <h3 class="font-bold underline text-center" style="font-size: 18pt; margin-top: 10px;">KUITANSI PEMBAYARAN</h3>

        <table class="no-border-table mt-8">
            <tr>
                <td style="width: 150px;">Sudah terima dari</td>
                <td>: Kuasa Pengguna Anggaran Universitas Padjadjaran</td>
            </tr>
            <tr>
                <td>Jumlah Uang</td>
                <td class="font-bold">: Rp{{ number_format($sptjb->total_jumlah_uang_diterima, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Terbilang</td>
                <td style="font-style: italic;">: {{ ucwords(trim(terbilang($sptjb->total_jumlah_uang_diterima))) }} Rupiah</td>
            </tr>
            <tr>
                <td>Untuk Pembayaran</td>
                <td>: {{ $sptjb->uraian }}</td>
            </tr>
            <tr>
                <td></td>
                <td>&nbsp;&nbsp;Beban Uang Saku Pengemudi (528317)</td>
            </tr>
        </table>

       <table style="width: 100%; border-collapse: collapse; margin-top: 50px; table-layout: fixed;">
            <tr>
                <td style="text-align: center; vertical-align: top; width: 50%;">
                    <div style="display: inline-block; text-align: center;">
                        <p style="margin: 0;">Mengetahui/Menyetujui:</p>
                        <p style="margin: 0;">Pembuat Komitmen,</p>

                        <div style="height: 80px;"></div>

                        <p style="margin: 0; font-weight: bold; text-decoration: underline;">Nurhayati, SE., M.Ak.</p>
                        <p style="margin: 0;">NIP 197101111999032002</p>
                    </div>
                </td>

                <td style="text-align: center; vertical-align: top; width: 50%;">
                    <div style="display: inline-block; text-align: center;">
                        <p style="margin: 0;">Jatinangor, {{ \Carbon\Carbon::now()->locale('id_ID')->isoFormat('D MMMM YYYY') }}</p>
                        <p style="margin: 0;">Koordinator Pool Kendaraan,</p>

                        <div style="height: 80px;"></div>

                        <p style="margin: 0; font-weight: bold; text-decoration: underline;">Gugun Gunawan, S.S.</p>
                        <p style="margin: 0;">NIP 198211262016023001</p>
                    </div>
                </td>
            </tr>
        </table>
    </div> </body>
</html>
