<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->accessible_resources = [
            'App\Filament\Resources\UserResource',
            'App\Filament\Resources\RoleResource',
            'App\Filament\Resources\PermissionResource',
            'App\Filament\Resources\KendaraanResource',
            'App\Filament\Resources\BookingKendaraanResource',
        ];
        $admin->save();

        $user = Role::firstOrCreate(['name' => 'User']);
        $user->accessible_resources = [
            'App\Filament\Resources\UserResource',
            'App\Filament\Resources\KendaraanResource',
            'App\Filament\Resources\BookingKendaraanResource',
        ];
        $user->save();
    }
}
