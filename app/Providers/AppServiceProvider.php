<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        ini_set('max_execution_time', 300);

        Livewire::component('filament.pages.peminjaman-kendaraan-unpad', \App\Filament\Pages\PeminjamanKendaraanUnpad::class);
        Livewire::component('booking-kendaraan-calendar', \App\Livewire\BookingKendaraanCalendar::class);
    }
}
