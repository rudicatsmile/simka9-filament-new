<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CwspsPermission;
use App\Models\CwspsRole;

/**
 * DataRiwayatPelatihanRolePermissionSeeder
 * 
 * Seeder untuk membuat permissions khusus untuk DataRiwayatPelatihan
 * dan mengassign ke role yang sesuai
 */
class DataRiwayatPelatihanRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions for Data Riwayat Pelatihan
        $permissions = [
            [
                'name' => 'Lihat Data Pelatihan',
                'identifier' => 'data-riwayat-pelatihan.view',
                'route' => 'filament.admin.resources.data-riwayat-pelatihans.index',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Buat Data Pelatihan',
                'identifier' => 'data-riwayat-pelatihan.create',
                'route' => 'filament.admin.resources.data-riwayat-pelatihans.create',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Edit Data Pelatihan',
                'identifier' => 'data-riwayat-pelatihan.edit',
                'route' => 'filament.admin.resources.data-riwayat-pelatihans.edit',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Hapus Data Pelatihan',
                'identifier' => 'data-riwayat-pelatihan.delete',
                'route' => 'filament.admin.resources.data-riwayat-pelatihans.delete',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Download Sertifikat Pelatihan',
                'identifier' => 'data-riwayat-pelatihan.download',
                'route' => 'filament.admin.resources.data-riwayat-pelatihans.download',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Operasi Bulk Data Pelatihan',
                'identifier' => 'data-riwayat-pelatihan.bulk',
                'route' => 'filament.admin.resources.data-riwayat-pelatihans.bulk',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            CwspsPermission::firstOrCreate(
                ['identifier' => $permission['identifier']],
                $permission
            );
        }

        // Assign permissions to roles
        $this->assignPermissionsToRoles();

        $this->command?->info('DataRiwayatPelatihan permissions created and assigned successfully.');
    }

    /**
     * Assign permissions to roles based on role matrix
     */
    private function assignPermissionsToRoles(): void
    {
        // Super Admin - Full access (all_permission = true, so no need to assign individually)
        
        // Admin - Full CRUD access
        $adminRole = CwspsRole::where('identifier', 'admin')->first();
        if ($adminRole) {
            $adminPermissionIds = CwspsPermission::whereIn('identifier', [
                'data-riwayat-pelatihan.view',
                'data-riwayat-pelatihan.create',
                'data-riwayat-pelatihan.edit',
                'data-riwayat-pelatihan.delete',
                'data-riwayat-pelatihan.download',
                'data-riwayat-pelatihan.bulk',
            ])->pluck('id');
            
            if ($adminPermissionIds->isNotEmpty()) {
                $adminRole->permissions()->syncWithoutDetaching($adminPermissionIds);
            }
        }

        // Manager - View, Create, Edit, Download (limited to department)
        $managerRole = CwspsRole::where('identifier', 'manager')->first();
        if ($managerRole) {
            $managerPermissionIds = CwspsPermission::whereIn('identifier', [
                'data-riwayat-pelatihan.view',
                'data-riwayat-pelatihan.create',
                'data-riwayat-pelatihan.edit',
                'data-riwayat-pelatihan.download',
            ])->pluck('id');
            
            if ($managerPermissionIds->isNotEmpty()) {
                $managerRole->permissions()->syncWithoutDetaching($managerPermissionIds);
            }
        }

        // User/Employee - View only (personal data)
        $userRole = CwspsRole::where('identifier', 'user')->first();
        if ($userRole) {
            $userPermissionIds = CwspsPermission::whereIn('identifier', [
                'data-riwayat-pelatihan.view',
                'data-riwayat-pelatihan.download',
            ])->pluck('id');
            
            if ($userPermissionIds->isNotEmpty()) {
                $userRole->permissions()->syncWithoutDetaching($userPermissionIds);
            }
        }
    }
}