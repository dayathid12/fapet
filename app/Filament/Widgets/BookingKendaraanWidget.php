<?php

namespace App\Filament\Widgets;

use App\Models\Perjalanan;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class BookingKendaraanWidget extends Widget
{
    protected static string $view = 'filament.widgets.booking-kendaraan-widget';

    protected int | string | array $columnSpan = 'full';

    public function getCurrentMonth(): string
    {
        return request('month', now()->format('Y-m'));
    }

    public function getPerjalanans()
    {
        $currentMonth = $this->getCurrentMonth();
        $startOfMonth = Carbon::createFromFormat('Y-m', $currentMonth)->startOfMonth();
        $endOfMonth = Carbon::createFromFormat('Y-m', $currentMonth)->endOfMonth();

        return Perjalanan::where('status_perjalanan', 'Terjadwal')
            ->whereBetween('waktu_keberangkatan', [$startOfMonth, $endOfMonth])
            ->with(['kendaraan', 'pengemudi', 'asisten'])
            ->get();
    }
}
