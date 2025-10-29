<?php

namespace App\Filament\Resources\KendaraanResource\Pages;

use App\Filament\Resources\KendaraanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateKendaraan extends CreateRecord
{
    protected static string $resource = KendaraanResource::class;

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
