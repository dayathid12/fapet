<?php

namespace App\Filament\Resources\BookingKendaraanResource\Pages;

use App\Filament\Resources\BookingKendaraanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBookingKendaraan extends ViewRecord
{
    protected static string $resource = BookingKendaraanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
