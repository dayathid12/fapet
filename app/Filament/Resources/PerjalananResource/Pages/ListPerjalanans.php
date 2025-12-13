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


use Illuminate\Support\Facades\Log;

class ListPerjalanans extends ListRecords
{
    protected static string $resource = PerjalananResource::class;

    protected static ?string $title = 'Pelayanan Perjalanan';

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

    protected function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        // Update status_perjalanan to 'Selesai' if waktu_kepulangan has passed and status is 'Terjadwal'
        $updatedRows = $query->getModel()::where('status_perjalanan', 'Terjadwal')
            ->where('waktu_kepulangan', '<=', \Carbon\Carbon::now())
            ->update(['status_perjalanan' => 'Selesai']);

        Log::info("Perjalanan status update: {$updatedRows} rows updated to 'Selesai'.");

        return $query;
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

                ->badge(fn () => static::getResource()::getModel()::where('status_perjalanan', 'Terjadwal')->where('waktu_kepulangan', '>=', \Carbon\Carbon::today())->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn ($query) => $query->where('status_perjalanan', 'Terjadwal')->where('waktu_kepulangan', '>=', \Carbon\Carbon::today())),
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
                ->badge(function () {
                    $query = static::getResource()::getModel()::query();
                    $query->where('status_perjalanan', 'Selesai')
                        ->orWhere(function ($q) {
                            $q->where('status_perjalanan', 'Terjadwal')
                                ->where('waktu_kepulangan', '<', \Carbon\Carbon::today());
                        });
                    return $query->count();
                })
                ->badgeColor('primary')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status_perjalanan', 'Selesai')
                        ->orWhere(function ($q) {
                            $q->where('status_perjalanan', 'Terjadwal')
                                ->where('waktu_kepulangan', '<', \Carbon\Carbon::today());
                        });
                }),
        ];
    }
}
