<?php

namespace App\Filament\Widgets;

use App\Models\PengajuanPr;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class PengajuanPrPerMonthChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Pengajuan PR per Bulan';

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = PengajuanPr::query()
            ->selectRaw('COUNT(*) as count, MONTH(created_at) as month')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = $data->map(function ($item) {
            return Carbon::create()->month($item->month)->format('F');
        });

        $datasetData = $data->pluck('count')->all();

        return [
            'datasets' => [
                [
                    'label' => 'Pengajuan Dibuat',
                    'data' => $datasetData,
                    'backgroundColor' => '#36A2EB',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
