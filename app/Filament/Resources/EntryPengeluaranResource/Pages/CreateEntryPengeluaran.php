<?php

namespace App\Filament\Resources\EntryPengeluaranResource\Pages;

use App\Filament\Resources\EntryPengeluaranResource;
use App\Models\EntryPengeluaran;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Carbon\Carbon;

class CreateEntryPengeluaran extends CreateRecord
{
    protected static string $resource = EntryPengeluaranResource::class;

}
