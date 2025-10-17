<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CwspsRole;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Contoh user spesifik (opsional)
        $adminRole = CwspsRole::where('identifier', 'super-admin')->first();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => 'admin',
            'role_id' => $adminRole?->id, // assign UUID role jika ada
        ]);

        $userRole = CwspsRole::where('identifier', 'user')->first();

        User::factory()->create([
            'name' => 'rudi',
            'email' => 'rudi@user.com',
            'password' => 'rudi',
            'role_id' => $userRole?->id, // assign UUID role jika ada
        ]);

        User::factory()->create([
            'name' => 'apit',
            'email' => 'apit@user.com',
            'password' => 'apit',
            'role_id' => $userRole?->id, // assign UUID role jika ada
        ]);

        User::factory()->create([
            'name' => 'alip',
            'email' => 'alip@user.com',
            'password' => 'alip',
            'role_id' => $userRole?->id, // assign UUID role jika ada
        ]);

        User::factory()->create([
            'name' => 'kiki',
            'email' => 'kiki@user.com',
            'password' => 'kiki',
            'role_id' => $userRole?->id, // assign UUID role jika ada
        ]);

        User::factory()->create([
            'name' => 'adit',
            'email' => 'adit@user.com',
            'password' => 'adit',
            'role_id' => $userRole?->id, // assign UUID role jika ada
        ]);

        // Buat beberapa user dummy
        // User::factory()->count(5)->create();
    }
}
