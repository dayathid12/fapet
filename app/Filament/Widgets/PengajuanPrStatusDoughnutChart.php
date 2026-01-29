<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasPengajuanPrStatusData;
use Filament\Widgets\ChartWidget;

class PengajuanPrStatusDoughnutChart extends ChartWidget
{
    use HasPengajuanPrStatusData;

    protected static ?string $heading = 'Status Pengajuan PR (Doughnut)';

    protected static ?string $maxHeight = '200px';
    protected int|string|array $columnSpan = 4;

    protected function getType(): string
    {
        return 'doughnut';
    }
}
