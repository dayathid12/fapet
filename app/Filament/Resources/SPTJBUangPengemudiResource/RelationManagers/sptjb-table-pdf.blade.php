<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPTJB Uang Pengemudi - Tabel</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.5;
        }

        .container {
            padding: 0 30px;
        }

        .header,
        .footer {
            text-align: center;
        }

        .header h3,
        .header p {
            margin: 0;
        }

        .content {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 10pt;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .signature-section {
            margin-top: 40px;
            width: 100%;
            font-size: 11pt;
        }

        .signature {
            width: 30%;
            float: right;
            text-align: left;
        }

        .signature p {
            margin-bottom: 60px;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h3>DAFTAR PENERIMAAN UANG HARIAN KEGIATAN DALAM KOTA</h3>
            <h3>{{ strtoupper($sptjb->nama_kegiatan) }}</h3>
            <p>Nomor: {{ $sptjb->no_sptjb }}</p>
        </div>

        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Nama</th>
                        <th rowspan="2">Jabatan</th>
                        <th colspan="3">Uang Harian</th>
                        <th rowspan="2">Jumlah Diterima (Rp)</th>
                        <th rowspan="2">Tanda Tangan</th>
                    </tr>
                    <tr>
                        <th>Tanggal Penugasan</th>
                        <th>Jumlah Hari</th>
                        <th>Besaran (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalDiterima = 0;
                    @endphp
                    @foreach ($groupedDetails as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-left">{{ $detail->nama }}</td>
                            <td>{{ $detail->jabatan }}</td>
                            <td>{{ $detail->tanggal_penugasan }}</td>
                            <td>{{ $detail->jumlah_hari }}</td>
                            <td class="text-right">{{ number_format($detail->besaran_uang_per_hari, 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($detail->jumlah_uang_diterima, 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                        @php
                            $totalDiterima += $detail->jumlah_uang_diterima;
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-right">Jumlah</th>
                        <th class="text-right">{{ number_format($totalDiterima, 0, ',', '.') }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>

            <div class="signature-section clearfix">
                <div class="signature">
                    <p>
                        Jatinangor, {{ \Carbon\Carbon::parse($sptjb->tanggal_surat)->translatedFormat('d F Y') }}<br>
                        Pejabat Pembuat Komitmen
                    </p>
                    <p>
                        <strong>{{ $sptjb->pejabat_pembuat_komitmen }}</strong><br>
                        NIP. {{ $sptjb->nip_pejabat }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
