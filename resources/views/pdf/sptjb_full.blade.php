
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
            }
            .text-center {
                text-align: center;
            }
            .text-justify {
                text-align: justify;
            }
            .font-bold {
                font-weight: bold;
            }
            .underline {
                text-decoration: underline;
            }
            .mt-4 {
                margin-top: 1rem;
            }
            .mt-8 {
                margin-top: 2rem;
            }
            h3 {
                font-size: 14pt;
            }        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        .no-border-table td {
            border: none;
            padding: 2px 0;
        }
        .signature-block {
            margin-top: 4rem;
            width: 40%;
            margin-left: 60%;
        }
        .signature-block p {
            margin: 0;
            padding: 0;
        }
        .receipt-details-table td {
            padding: 5px 0; /* Adjust as needed */
            word-break: break-word; /* Ensure long words break and wrap */
        }
    </style>
</head>
<body>
<?php
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
    } elseif ($angka < 2000000) {
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
        tagihan yang telah kami perintahkan sebagai berikut :
    </p>

    <table>
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
                <td style="text-align: right;">
                    <?php
                        $value = $sptjb->total_jumlah_uang_diterima;
                        if ($value == floor($value)) {
                            echo number_format($value, 0, ',', '.');
                        } else {
                            echo number_format($value, 2, ',', '.');
                        }
                    ?>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="font-bold" style="text-align: right;">TOTAL</td>
                <td class="font-bold" style="text-align: right;">
                    <?php
                        $value = $sptjb->total_jumlah_uang_diterima;
                        if ($value == floor($value)) {
                            echo number_format($value, 0, ',', '.');
                        } else {
                            echo number_format($value, 2, ',', '.');
                        }
                    ?>
                </td>
            </tr>
        </tfoot>
    </table>

    <p class="text-justify">
        Bukti-bukti pengeluaran anggaran dan asli setoran pajak (SSP/BPN) tersebut diatas disimpan oleh Pengguna
        anggaran / Kuasa Pengguna Anggaran untuk kelengkapan administrasi dan pemeriksaan aparat pengawasan
        fungsional.
    </p>
    <p class="text-justify mt-4">
        Demikian surat pernyataan ini kami buat dengan sesungguhnya.
    </p>

    <div class="signature-block">
        <p>Jatinangor, {{ $tanggal }}</p>
        <p>Pembuat Komitmen,</p>
        <br><br><br><br><br>
        <p class="font-bold underline">Nurhayati , SE.,M.Ak.</p>
        <p>NIP 197101111999032002</p>
    </div>

    <div style="page-break-before: always;">
        <div style="float: right; width: 200px; text-align: left; margin-right: -10px;">
            <p style="margin: 0; padding: 0;">T.A.2025</p>
            <p style="margin: 0; padding: 0;">Nomor Bukti :</p>
            <p style="margin: 0; padding: 0;">M A K : 528317</p>
        </div>
        <div style="clear: both;"></div>
        <h3 class="font-bold underline text-center" style="font-size: 18pt;">KUITANSI PEMBAYARAN</h3>

        <table class="no-border-table mt-4 receipt-details-table">
            <tr>
                <td style="width: 150px;">Sudah terima dari</td>
                <td>: Kuasa Pengguna Anggaran Universitas Padjadjaran</td>
            </tr>
            <tr>
                <td>Jumlah Uang</td>
                <td>: Rp{{ number_format($sptjb->total_jumlah_uang_diterima, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Terbilang</td>
                <td>: {{ ucwords(trim(terbilang($sptjb->total_jumlah_uang_diterima))) }}</td>
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

        <div class="mt-8" style="overflow: hidden;">
            <p style="float: left;">Mengetahui/Menyetujui:</p>
            <p style="float: right;">Jatinangor, 22 September 2025</p>
        </div>
        <div style="overflow: hidden; margin-top: 2rem;">
            <div style="width: 49%; float: left; text-align: center;">
                <p>Pembuat Komitmen,</p>
                <br><br><br>
                <p class="font-bold underline">Nurhayati , SE.,M.Ak.</p>
                <p>NIP 197101111999032002</p>
            </div>
            <div style="width: 49%; float: right; text-align: center;">
                <p>Koordinator Pool Kendaraan,</p>
                <br><br><br>
                <p class="font-bold underline">Gugun Gunawan, S.S.</p>
                <p>NIP.198211262016023001</p>
            </div>
        </div>
    </div>
</body>
</html>
