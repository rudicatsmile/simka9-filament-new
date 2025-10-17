<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CwspsPermission;
use App\Models\CwspsRole;

/**
 * DataRiwayatPasanganRolePermissionSeeder
 * 
 * Seeder untuk menambahkan permissions khusus untuk resource DataRiwayatPasangan.
 * Mengikuti pola yang sama dengan RolePermissionSeederV2.
 * 
 * @package Database\Seeders
 * @author Laravel Filament SIMKA9
 * @version 1.0.0
 */
class DataRiwayatPasanganRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permissions untuk resource Data Riwayat Pasangan
        $newPermissions = [
            [
                'name' => 'Lihat Data Riwayat Pasangan',
                'identifier' => 'data-riwayat-pasangans.view',
                'route' => 'filament.admin.resources.data-riwayat-pasangans.index',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Buat Data Riwayat Pasangan',
                'identifier' => 'data-riwayat-pasangans.create',
                'route' => 'filament.admin.resources.data-riwayat-pasangans.create',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Edit Data Riwayat Pasangan',
                'identifier' => 'data-riwayat-pasangans.edit',
                'route' => 'filament.admin.resources.data-riwayat-pasangans.edit',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Hapus Data Riwayat Pasangan',
                'identifier' => 'data-riwayat-pasangans.delete',
                'route' => 'filament.admin.resources.data-riwayat-pasangans.delete',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
        ];

        // Tambahkan permissions ke database
        foreach ($newPermissions as $permission) {
            CwspsPermission::firstOrCreate(
                ['identifier' => $permission['identifier']],
                $permission
            );
        }

        // Berikan semua permissions ke role Admin
        $adminRole = CwspsRole::where('identifier', 'admin')->first();
        if ($adminRole) {
            $identifiersForAdmin = array_map(fn ($p) => $p['identifier'], $newPermissions);
            $adminPermissionIds = CwspsPermission::whereIn('identifier', $identifiersForAdmin)->pluck('id');
            if ($adminPermissionIds->isNotEmpty()) {
                $adminRole->permissions()->syncWithoutDetaching($adminPermissionIds);
            }
        }

        // Berikan permission view ke role User agar menu muncul
        $userRole = CwspsRole::where('identifier', 'user')->first();
        $viewPermission = CwspsPermission::where('identifier', 'data-riwayat-pasangans.view')->first();
        if ($userRole && $viewPermission) {
            $userRole->permissions()->syncWithoutDetaching([$viewPermission->id]);
        }

        $this->command?->info('DataRiwayatPasanganRolePermissionSeeder: permissions untuk Data Riwayat Pasangan berhasil ditambahkan.');
    }
}