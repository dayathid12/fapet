<?php

namespace App\Filament\Resources\PerjalananResource\Pages;

use App\Filament\Resources\PerjalananResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\MaxWidth;
use Filament\Notifications\Notification;

class CreatePerjalanan extends CreateRecord
{
    protected static string $resource = PerjalananResource::class;

    public function getMaxContentWidth(): ?string
    {
        return MaxWidth::SevenExtraLarge->value;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Data Disimpan')
            ->body('Data perjalanan berhasil disimpan.')
            ->icon('heroicon-o-check-circle')
            ->duration(5000);
    }
}
