<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::firstOrCreate([
            'email' => 'dayat.hidayat@unpad.ac.id',
        ], [
            'name' => 'Dayat Hidayat',
            'password' => bcrypt('password'),
        ]);

        $adminRole = \Spatie\Permission\Models\Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $user->assignRole($adminRole);
        }
    }
}
