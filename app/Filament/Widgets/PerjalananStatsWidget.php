<?php

namespace App\Filament\Widgets;

use App\Models\Perjalanan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PerjalananStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        return [
            Stat::make('Menunggu Persetujuan', Perjalanan::where('status_perjalanan', 'Menunggu Persetujuan')->count())
                ->description('Perjalanan yang belum disetujui')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Terjadwal', Perjalanan::where('status_perjalanan', 'Terjadwal')->count())
                ->description('Perjalanan yang sudah disetujui')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Ditolak', Perjalanan::where('status_perjalanan', 'Ditolak')->count())
                ->description('Perjalanan yang ditolak')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
