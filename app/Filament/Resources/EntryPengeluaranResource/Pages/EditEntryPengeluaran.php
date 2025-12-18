<?php

namespace App\Filament\Resources\EntryPengeluaranResource\Pages;

use App\Filament\Resources\EntryPengeluaranResource;
use App\Models\Kendaraan;
use App\Models\Perjalanan;
use App\Models\Staf; // Tambahkan ini
use App\Models\PerjalananKendaraan;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class EditEntryPengeluaran extends EditRecord
{
    protected static string $resource = EntryPengeluaranResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
