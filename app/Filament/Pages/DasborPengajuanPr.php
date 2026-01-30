<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\PengajuanPrPerMonthChart;
use App\Filament\Widgets\PengajuanPrStatusDoughnutChart;
use App\Filament\Widgets\PengajuanPrStatusPieChart;
use App\Filament\Widgets\PengajuanPrStats;
use Filament\Pages\Page;

class DasborPengajuanPr extends Page
{
  protected static ?string $navigationGroup = 'Filament Shield';

    protected static string $view = 'filament.pages.dasbor-pengajuan-pr';

    protected function getHeaderWidgets(): array
    {
        return [
            PengajuanPrStats::class,
            PengajuanPrStatusDoughnutChart::class,
            PengajuanPrStatusPieChart::class,
            PengajuanPrPerMonthChart::class,
        ];
    }
}
