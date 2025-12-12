<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class ScheduleOverview extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.schedule-overview';

    protected static ?string $title = 'Jadwal Kendaraan dan Pengemudi';

    protected static ?string $navigationGroup = 'Manajemen Jadwal';

    protected static ?int $navigationSort = 1;

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
