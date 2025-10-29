<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Kendaraan</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f7f9;
            color: #333;
            margin: 20px;
        }
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 25px;
            overflow-x: auto;
        }
        h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .filter-form {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .filter-form label {
            font-weight: 500;
        }
        .filter-form select, .filter-form button {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        .filter-form button {
            background-color: #3498db;
            color: white;
            border-color: #3498db;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .filter-form button:hover {
            background-color: #2980b9;
        }
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .schedule-table th, .schedule-table td {
            border: 1px solid #e0e0e0;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            white-space: normal;
            word-wrap: break-word;
        }
        .schedule-table th {
            background-color: #f2f2f2;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 2;
        }
        .nopol-col {
            width: 120px;
            font-weight: 600;
            background-color: #f8f9fa;
            position: sticky;
            left: 0;
            z-index: 1;
        }
        .day-col {
            width: 60px;
        }
        .status-box {
            padding: 8px 4px;
            border-radius: 4px;
            color: #fff;
            font-weight: 500;
            display: flex;
            flex-direction: column;
            height: 100%;
            justify-content: center;
        }
        .status-Tersedia {
            background-color: #2ecc71; /* Green */
        }
        .status-Terjadwal {
            background-color: #e74c3c; /* Red */
        }
        .status-Menunggu {
            background-color: #f39c12; /* Yellow */
        }
        .kegiatan-text {
            font-size: 10px;
            margin-top: 4px;
            line-height: 1.2;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Jadwal Kendaraan - {{ $date->format('F Y') }}</h1>

    <form action="{{ url('/jadwal-kendaraan') }}" method="GET" class="filter-form">
        <label for="bulan">Pilih Bulan:</label>
        <select name="bulan" id="bulan">
            @foreach($bulanList as $value => $text)
                <option value="{{ $value }}" {{ $selectedMonth == $value ? 'selected' : '' }}>
                    {{ $text }}
                </option>
            @endforeach
        </select>
        <button type="submit">Tampilkan</button>
    </form>

    <div class="table-responsive">
        <table class="schedule-table">
            <thead>
                <tr>
                    <th class="nopol-col">Nomor Polisi</th>
                    @for ($day = 1; $day <= $daysInMonth; $day++)
                        <th class="day-col">{{ str_pad($day, 2, '0', STR_PAD_LEFT) }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwal as $nopol => $days)
                    <tr>
                        <td class="nopol-col">{{ $nopol }}</td>
                        @foreach ($days as $dayData)
                            <td>
                                <div class="status-box status-{{ $dayData['status'] }}">
                                    <span>{{ $dayData['status'] }}</span>
                                    @if($dayData['kegiatan'] !== '-')
                                        <span class="kegiatan-text">{{ $dayData['kegiatan'] }}</span>
                                    @endif
                                </div>
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $daysInMonth + 1 }}" style="padding: 20px; text-align: center;">
                            Tidak ada data perjalanan untuk bulan ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
