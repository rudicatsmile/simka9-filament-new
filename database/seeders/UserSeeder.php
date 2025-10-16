<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CwspsRole;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat beberapa user dummy
        User::factory()->count(10)->create();

        // Contoh user spesifik (opsional)
        $adminRole = CwspsRole::where('identifier', 'super-admin')->first();

        User::factory()->create([
            'name' => 'Admin Demo',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role_id' => $adminRole?->id, // assign UUID role jika ada
        ]);
    }
}