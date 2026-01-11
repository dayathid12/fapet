
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
    </style>
</head>
<body>
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
        <br><br><br>
        <p class="font-bold underline">Nurhayati , SE.,M.Ak.</p>
        <p>NIP 197101111999032002</p>
    </div>

</body>
</html>
