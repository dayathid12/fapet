<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Asset extends Page
{
 

    protected static string $view = 'filament.pages.asset';
    protected static ?string $navigationGroup = 'Asset';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('page_Asset');
    }
}
