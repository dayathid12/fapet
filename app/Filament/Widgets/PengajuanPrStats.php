<?php

namespace App\Filament\Widgets;

use App\Models\PengajuanPr;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class PengajuanPrStats extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static bool $isLazy = false;
    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $prDiprosesCount = DB::table('pengajuan_prs')->whereRaw('nomor_pr IS NOT NULL AND TRIM(nomor_pr) != ""')->count();
        $totalCount = DB::table('pengajuan_prs')->count();

        return [
            Stat::make('Total Pengajuan', $totalCount)
                ->description('Total semua pengajuan yang pernah dibuat')
                ->descriptionIcon('heroicon-m-list-bullet')
                ->color('info'),
            Stat::make('PR Diproses', $prDiprosesCount)
                ->description('Total ajuan yang sudah memiliki nomor PR')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}
