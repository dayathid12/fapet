<?php

namespace App\Filament\Resources\PerjalananResource\Pages;

use App\Filament\Resources\PerjalananResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\HtmlString;


class ListPerjalanans extends ListRecords
{
    protected static string $resource = PerjalananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getMaxContentWidth(): ?string
    {
        return MaxWidth::Screen->value;
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua')
                ->icon('heroicon-o-queue-list'),
            'menunggu-persetujuan' => Tab::make('Menunggu Persetujuan')
                ->icon('heroicon-o-clock')
                ->badge(fn () => static::getResource()::getModel()::where('status_perjalanan', 'Menunggu Persetujuan')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('status_perjalanan', 'Menunggu Persetujuan')),
            'terjadwal' => Tab::make('Terjadwal')
                ->icon('heroicon-o-check-circle')
               
                ->badge(fn () => static::getResource()::getModel()::where('status_perjalanan', 'Terjadwal')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn ($query) => $query->where('status_perjalanan', 'Terjadwal')),
            'ditolak' => Tab::make('Ditolak')
                ->icon('heroicon-o-x-circle')
                ->badge(fn () => static::getResource()::getModel()::where('status_perjalanan', 'Ditolak')->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn ($query) => $query->where('status_perjalanan', 'Ditolak')),
        ];
    }
}
