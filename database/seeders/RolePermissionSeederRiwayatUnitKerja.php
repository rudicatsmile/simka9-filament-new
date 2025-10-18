<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CwspsPermission as Permission;
use App\Models\CwspsRole as Role;

/**
 * RolePermissionSeederRiwayatUnitKerja
 * 
 * Seeder untuk membuat role dan permission khusus untuk DataRiwayatUnitKerjaLain
 * Mengatur hak akses berdasarkan role matrix yang telah ditentukan
 * 
 * @package Database\Seeders
 * @author Laravel Filament SIMKA9
 * @version 1.0.0
 */
class RolePermissionSeederRiwayatUnitKerja extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            [
                'name' => 'Lihat Data Unit Kerja Lain',
                'identifier' => 'data-riwayat-unit-kerja-lains.view',
                'route' => 'filament.admin.resources.data-riwayat-unit-kerja-lains.index',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Buat Data Unit Kerja Lain',
                'identifier' => 'data-riwayat-unit-kerja-lains.create',
                'route' => 'filament.admin.resources.data-riwayat-unit-kerja-lains.create',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Edit Data Unit Kerja Lain',
                'identifier' => 'data-riwayat-unit-kerja-lains.edit',
                'route' => 'filament.admin.resources.data-riwayat-unit-kerja-lains.edit',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Hapus Data Unit Kerja Lain',
                'identifier' => 'data-riwayat-unit-kerja-lains.delete',
                'route' => null,
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Restore Data Unit Kerja Lain',
                'identifier' => 'data-riwayat-unit-kerja-lains.restore',
                'route' => null,
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Force Delete Data Unit Kerja Lain',
                'identifier' => 'data-riwayat-unit-kerja-lains.force-delete',
                'route' => null,
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Download Data Unit Kerja Lain',
                'identifier' => 'data-riwayat-unit-kerja-lains.download',
                'route' => null,
                'panel_ids' => ['admin'],
                'status' => true,
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['identifier' => $permission['identifier']],
                $permission
            );
        }

        // Assign permissions to roles
        $this->assignPermissionsToRoles();

        $this->command->info('DataRiwayatUnitKerjaLain permissions created and assigned successfully.');
    }

    /**
     * Assign permissions to roles
     */
    private function assignPermissionsToRoles(): void
    {
        // Get all permissions for this feature
        $permissions = Permission::whereIn('identifier', [
            'data-riwayat-unit-kerja-lains.view',
            'data-riwayat-unit-kerja-lains.create',
            'data-riwayat-unit-kerja-lains.edit',
            'data-riwayat-unit-kerja-lains.delete',
            'data-riwayat-unit-kerja-lains.restore',
            'data-riwayat-unit-kerja-lains.force-delete',
            'data-riwayat-unit-kerja-lains.download',
        ])->get();

        // Super Admin - Full access
        $superAdmin = Role::where('identifier', 'super-admin')->first();
        if ($superAdmin) {
            $superAdmin->permissions()->syncWithoutDetaching($permissions->pluck('id'));
        }

        // Admin HR - Full access
        $adminHR = Role::where('identifier', 'admin-hr')->first();
        if ($adminHR) {
            $adminHR->permissions()->syncWithoutDetaching($permissions->pluck('id'));
        }

        // Manager - View, Create, Edit
        $manager = Role::where('identifier', 'manager')->first();
        if ($manager) {
            $managerPermissions = $permissions->whereIn('identifier', [
                'data-riwayat-unit-kerja-lains.view',
                'data-riwayat-unit-kerja-lains.create',
                'data-riwayat-unit-kerja-lains.edit',
            ]);
            $manager->permissions()->syncWithoutDetaching($managerPermissions->pluck('id'));
        }

        // Supervisor - View, Create, Edit
        $supervisor = Role::where('identifier', 'supervisor')->first();
        if ($supervisor) {
            $supervisorPermissions = $permissions->whereIn('identifier', [
                'data-riwayat-unit-kerja-lains.view',
                'data-riwayat-unit-kerja-lains.create',
                'data-riwayat-unit-kerja-lains.edit',
            ]);
            $supervisor->permissions()->syncWithoutDetaching($supervisorPermissions->pluck('id'));
        }

        // Employee - View only
        $employee = Role::where('identifier', 'employee')->first();
        if ($employee) {
            $employeePermissions = $permissions->whereIn('identifier', [
                'data-riwayat-unit-kerja-lains.view',
            ]);
            $employee->permissions()->syncWithoutDetaching($employeePermissions->pluck('id'));
        }
    }
}