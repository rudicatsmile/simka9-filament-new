<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * DataRiwayatKepegawaianRolePermissionSeeder
 * 
 * Seeder untuk membuat role dan permission khusus untuk DataRiwayatKepegawaian
 */
class DataRiwayatKepegawaianRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions for DataRiwayatKepegawaian
        $permissions = [
            'data-riwayat-kepegawaians.view',
            'data-riwayat-kepegawaians.create',
            'data-riwayat-kepegawaians.edit',
            'data-riwayat-kepegawaians.delete',
            'data-riwayat-kepegawaians.restore',
            'data-riwayat-kepegawaians.force-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $this->assignPermissionsToRoles();

        $this->command->info('DataRiwayatKepegawaian permissions created and assigned successfully.');
    }

    /**
     * Assign permissions to roles based on role matrix
     */
    private function assignPermissionsToRoles(): void
    {
        // Super Admin - Full access
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo([
            'data-riwayat-kepegawaians.view',
            'data-riwayat-kepegawaians.create',
            'data-riwayat-kepegawaians.edit',
            'data-riwayat-kepegawaians.delete',
            'data-riwayat-kepegawaians.restore',
            'data-riwayat-kepegawaians.force-delete',
        ]);

        // Admin HR - Full CRUD access
        $adminHR = Role::firstOrCreate(['name' => 'Admin HR']);
        $adminHR->givePermissionTo([
            'data-riwayat-kepegawaians.view',
            'data-riwayat-kepegawaians.create',
            'data-riwayat-kepegawaians.edit',
            'data-riwayat-kepegawaians.delete',
            'data-riwayat-kepegawaians.restore',
        ]);

        // Manager - View, Create, Edit (limited to unit kerja)
        $manager = Role::firstOrCreate(['name' => 'Manager']);
        $manager->givePermissionTo([
            'data-riwayat-kepegawaians.view',
            'data-riwayat-kepegawaians.create',
            'data-riwayat-kepegawaians.edit',
        ]);

        // Supervisor - View, Create, Edit (limited to unit kerja)
        $supervisor = Role::firstOrCreate(['name' => 'Supervisor']);
        $supervisor->givePermissionTo([
            'data-riwayat-kepegawaians.view',
            'data-riwayat-kepegawaians.create',
            'data-riwayat-kepegawaians.edit',
        ]);

        // Employee - View only (own data)
        $employee = Role::firstOrCreate(['name' => 'Employee']);
        $employee->givePermissionTo([
            'data-riwayat-kepegawaians.view',
        ]);
    }
}