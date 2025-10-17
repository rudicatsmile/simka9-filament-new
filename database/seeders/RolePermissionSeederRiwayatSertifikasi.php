<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * RolePermissionSeederRiwayatSertifikasi
 * 
 * Seeder untuk membuat role dan permission khusus untuk DataRiwayatSertifikasi
 * Mengatur hak akses berdasarkan role matrix yang telah ditentukan
 * 
 * @package Database\Seeders
 * @author Laravel Filament SIMKA9
 * @version 1.0.0
 */
class RolePermissionSeederRiwayatSertifikasi extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions for DataRiwayatSertifikasi
        $permissions = [
            'data-riwayat-sertifikasis.view',
            'data-riwayat-sertifikasis.create',
            'data-riwayat-sertifikasis.edit',
            'data-riwayat-sertifikasis.delete',
            'data-riwayat-sertifikasis.restore',
            'data-riwayat-sertifikasis.force-delete',
            'data-riwayat-sertifikasis.download',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $this->assignPermissionsToRoles();

        $this->command->info('DataRiwayatSertifikasi permissions created and assigned successfully.');
    }

    /**
     * Assign permissions to roles based on role matrix
     */
    private function assignPermissionsToRoles(): void
    {
        // Super Admin - Full access
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo([
            'data-riwayat-sertifikasis.view',
            'data-riwayat-sertifikasis.create',
            'data-riwayat-sertifikasis.edit',
            'data-riwayat-sertifikasis.delete',
            'data-riwayat-sertifikasis.restore',
            'data-riwayat-sertifikasis.force-delete',
            'data-riwayat-sertifikasis.download',
        ]);

        // Admin HR - Full CRUD access
        $adminHR = Role::firstOrCreate(['name' => 'Admin HR']);
        $adminHR->givePermissionTo([
            'data-riwayat-sertifikasis.view',
            'data-riwayat-sertifikasis.create',
            'data-riwayat-sertifikasis.edit',
            'data-riwayat-sertifikasis.delete',
            'data-riwayat-sertifikasis.restore',
            'data-riwayat-sertifikasis.download',
        ]);

        // Manager - View, Create, Edit (limited to unit kerja)
        $manager = Role::firstOrCreate(['name' => 'Manager']);
        $manager->givePermissionTo([
            'data-riwayat-sertifikasis.view',
            'data-riwayat-sertifikasis.create',
            'data-riwayat-sertifikasis.edit',
            'data-riwayat-sertifikasis.download',
        ]);

        // Supervisor - View, Create, Edit (limited to unit kerja)
        $supervisor = Role::firstOrCreate(['name' => 'Supervisor']);
        $supervisor->givePermissionTo([
            'data-riwayat-sertifikasis.view',
            'data-riwayat-sertifikasis.create',
            'data-riwayat-sertifikasis.edit',
            'data-riwayat-sertifikasis.download',
        ]);

        // Employee - View only (own data)
        $employee = Role::firstOrCreate(['name' => 'Employee']);
        $employee->givePermissionTo([
            'data-riwayat-sertifikasis.view',
            'data-riwayat-sertifikasis.download',
        ]);
    }
}
