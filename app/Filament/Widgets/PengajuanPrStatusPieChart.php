<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasPengajuanPrStatusData;
use Filament\Widgets\ChartWidget;

class PengajuanPrStatusPieChart extends ChartWidget
{
    use HasPengajuanPrStatusData;

    protected static ?string $heading = 'Status Pengajuan PR (Pie)';

    protected static ?string $maxHeight = '300px';

    protected function getType(): string
    {
        return 'pie';
    }
}
