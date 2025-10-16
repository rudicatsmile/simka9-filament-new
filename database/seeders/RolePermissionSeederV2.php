<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CwspsPermission;
use App\Models\CwspsRole;

class RolePermissionSeederV2 extends Seeder
{
    /**
     * Run the database seeds (Versi 2).
     */
    public function run(): void
    {
        // Tambahkan permissions baru untuk Resource Jabatan Utama
        $newPermissions = [
            [
                'name' => 'Lihat Jabatan Utama',
                'identifier' => 'jabatan-utamas.view',
                'route' => 'filament.admin.resources.jabatan-utamas.index',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Buat Jabatan Utama',
                'identifier' => 'jabatan-utamas.create',
                'route' => 'filament.admin.resources.jabatan-utamas.create',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Edit Jabatan Utama',
                'identifier' => 'jabatan-utamas.edit',
                'route' => 'filament.admin.resources.jabatan-utamas.edit',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Hapus Jabatan Utama',
                'identifier' => 'jabatan-utamas.delete',
                'route' => 'filament.admin.resources.jabatan-utamas.delete',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
        ];

        // Tambahkan permissions untuk resource lain (lengkap & konsisten)
        $resources = [
            ['label' => 'Golongan Darah',     'prefix' => 'tabel-golongan-darahs',    'route' => 'filament.admin.resources.tabel-golongan-darahs.index'],
            ['label' => 'Hubungan Keluarga',  'prefix' => 'tabel-hubungan-keluargas', 'route' => 'filament.admin.resources.tabel-hubungan-keluargas.index'],
            ['label' => 'Jenis Pelatihan',    'prefix' => 'tabel-jenis-pelatihans',   'route' => 'filament.admin.resources.tabel-jenis-pelatihans.index'],
            ['label' => 'Pekerjaan',          'prefix' => 'tabel-pekerjaan',          'route' => 'filament.admin.resources.tabel-pekerjaans.index'],
            ['label' => 'Propinsi',           'prefix' => 'tabel-propinsis',          'route' => 'filament.admin.resources.tabel-propinsis.index'],
            ['label' => 'Unit Kerja',         'prefix' => 'unit-kerja',               'route' => 'filament.admin.resources.unit-kerja.index'],
            ['label' => 'Status Kepegawaian', 'prefix' => 'tabel-status-kepegawaians','route' => 'filament.admin.resources.tabel-status-kepegawaians.index'],
            ['label' => 'Status Nikah',       'prefix' => 'tabel-status-nikahs',      'route' => 'filament.admin.resources.tabel-status-nikahs.index'],
        ];

        foreach ($resources as $res) {
            foreach (['view' => 'Lihat', 'create' => 'Buat', 'edit' => 'Edit', 'delete' => 'Hapus'] as $action => $actionLabel) {
                $newPermissions[] = [
                    'name' => "{$actionLabel} {$res['label']}",
                    'identifier' => "{$res['prefix']}.{$action}",
                    'route' => $res['route'], // gunakan index karena create/edit via modal di sebagian resource
                    'panel_ids' => ['admin'],
                    'status' => true,
                ];
            }
        }

        foreach ($newPermissions as $permission) {
            CwspsPermission::firstOrCreate(
                ['identifier' => $permission['identifier']],
                $permission
            );
        }

        // Tidak membuat role baru. Pastikan "Admin" mendapat semua permission baru.
        $adminRole = CwspsRole::where('identifier', 'admin')->first();
        if ($adminRole) {
            $identifiersForAdmin = array_map(fn ($p) => $p['identifier'], $newPermissions);
            $adminPermissionIds = CwspsPermission::whereIn('identifier', $identifiersForAdmin)->pluck('id');
            if ($adminPermissionIds->isNotEmpty()) {
                $adminRole->permissions()->syncWithoutDetaching($adminPermissionIds);
            }
        }

        // Opsional: tetap pastikan "User" memiliki minimal permission view untuk Jabatan Utama agar menu muncul
        $userRole = CwspsRole::where('identifier', 'user')->first();
        $viewPermission = CwspsPermission::where('identifier', 'jabatan-utamas.view')->first();
        if ($userRole && $viewPermission) {
            $userRole->permissions()->syncWithoutDetaching([$viewPermission->id]);
        }

        $this->command?->info('RolePermissionSeederV2: permissions baru berhasil ditambahkan ke Admin.');
    }
}
