<?php

namespace App\Filament\Resources\PerjalananResource\Pages;

use App\Filament\Resources\PerjalananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\MaxWidth;

class EditPerjalanan extends EditRecord
{
    protected static string $resource = PerjalananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getMaxContentWidth(): ?string
    {
        return MaxWidth::SevenExtraLarge->value;
    }
}
