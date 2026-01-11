<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class HalamanUtama extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.halaman-utama';

    protected static ?string $title = 'Halaman Utama';

    protected static ?string $navigationLabel = 'Halaman Utama';

    // This will make it appear at the top of the navigation
    protected static ?int $navigationSort = -3;
}
