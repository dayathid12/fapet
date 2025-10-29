<?php

namespace App\Filament\Resources\BookingKendaraanResource\Pages;

use App\Filament\Resources\BookingKendaraanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateBookingKendaraan extends CreateRecord
{
    protected static string $resource = BookingKendaraanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Data Disimpan')
            ->body('Data kendaraan berhasil disimpan.')
            ->icon('heroicon-o-check-circle')
            ->duration(5000);
    }
}
