<?php

namespace App\Filament\Resources\PerjalananResource\Pages;

use App\Filament\Resources\PerjalananResource;
use App\Filament\Widgets\PerjalananStatsWidget;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Card;


class ListPerjalanans extends ListRecords
{
    protected static string $resource = PerjalananResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
        ];
    }

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
            'hari-ini' => Tab::make('Hari Ini')
                ->icon('heroicon-o-calendar-days')
                ->badge(fn () => static::getResource()::getModel()::whereDate('waktu_keberangkatan', \Carbon\Carbon::today())->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn ($query) => $query->whereDate('waktu_keberangkatan', \Carbon\Carbon::today())),
            'selesai' => Tab::make('Selesai')
                ->icon('heroicon-o-check-badge')
                ->badge(fn () => static::getResource()::getModel()::where('status_perjalanan', 'Terjadwal')->where('waktu_kepulangan', '<', \Carbon\Carbon::today())->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn ($query) => $query->where('status_perjalanan', 'Terjadwal')->where('waktu_kepulangan', '<', \Carbon\Carbon::today())),
        ];
    }
}
