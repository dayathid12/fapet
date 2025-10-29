<?php

namespace App\Filament\Resources\BookingKendaraanResource\Pages;

use App\Filament\Resources\BookingKendaraanResource;
use App\Filament\Widgets\BookingKendaraanWidget;
use App\Models\Perjalanan;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Carbon\Carbon;

class ListBookingKendaraans extends ListRecords
{
    protected static string $resource = BookingKendaraanResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }


}
