<?php

namespace App\Filament\Resources\BookingKendaraanResource\Pages;

use App\Filament\Resources\BookingKendaraanResource;
use Filament\Resources\Pages\Page;
use Filament\Tables\Table;
use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;

class ListPeminjam extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = BookingKendaraanResource::class;

    protected static string $view = 'filament.resources.booking-kendaraan-resource.pages.list-peminjam';

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()->whereHas('bookingKendaraan'))
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('booking_kendaraan_count')->counts('bookingKendaraan'),
            ])
            ->paginated(false);
    }
}
