<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Policies\PermissionPolicy;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Contracts\Role;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Livewire\Livewire; // Add this line
use App\Filament\Pages\PeminjamanKendaraanUnpad; // Add this line

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Increase PHP execution time limit to prevent timeout errors in Filament
        ini_set('max_execution_time', 300); // Set to 5 minutes

        Gate::policy(Permission::class, PermissionPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        // Register the Livewire component for the public form
        Livewire::component('filament.pages.peminjaman-kendaraan-unpad', PeminjamanKendaraanUnpad::class);
        Livewire::component('booking-kendaraan-calendar', \App\Livewire\BookingKendaraanCalendar::class);
    }
}
