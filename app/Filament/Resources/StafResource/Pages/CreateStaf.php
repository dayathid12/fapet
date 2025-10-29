<?php

namespace App\Filament\Resources\StafResource\Pages;

use App\Filament\Resources\StafResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateStaf extends CreateRecord
{
    protected static string $resource = StafResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Data Disimpan')
            ->body('Data staf berhasil disimpan.')
            ->icon('heroicon-o-check-circle')
            ->duration(5000);
    }
}
