<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * DataRiwayatAnakRolePermissionSeeder
 * 
 * Seeder untuk membuat role dan permission khusus untuk modul Data Riwayat Anak
 * Mengatur hak akses berdasarkan role matrix yang telah ditentukan
 * 
 * @package Database\Seeders
 * @author SIMKA9 Development Team
 * @version 1.0.0
 */
class DataRiwayatAnakRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar permissions untuk Data Riwayat Anak
        $permissions = [
            // View permissions
            'data-anak.view-all' => 'Melihat semua data anak pegawai',
            'data-anak.view-department' => 'Melihat data anak pegawai di departemen',
            'data-anak.view-own' => 'Melihat data anak sendiri',
            
            // Create permissions
            'data-anak.create' => 'Membuat data anak pegawai',
            'data-anak.create-own' => 'Membuat data anak sendiri',
            
            // Edit permissions
            'data-anak.edit' => 'Mengedit semua data anak pegawai',
            'data-anak.edit-department' => 'Mengedit data anak pegawai di departemen',
            'data-anak.edit-own' => 'Mengedit data anak sendiri',
            
            // Delete permissions
            'data-anak.delete' => 'Menghapus data anak pegawai',
            'data-anak.delete-department' => 'Menghapus data anak pegawai di departemen',
            
            // Advanced permissions
            'data-anak.restore' => 'Restore data anak pegawai yang dihapus',
            'data-anak.force-delete' => 'Menghapus permanen data anak pegawai',
            'data-anak.export' => 'Export data anak pegawai',
            'data-anak.import' => 'Import data anak pegawai',
            'data-anak.statistics' => 'Melihat statistik data anak pegawai',
        ];

        // Buat permissions
        foreach ($permissions as $name => $description) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['description' => $description]
            );
        }

        // Ambil semua roles yang ada
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $adminHrRole = Role::firstOrCreate(['name' => 'admin-hr', 'guard_name' => 'web']);
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $supervisorRole = Role::firstOrCreate(['name' => 'supervisor', 'guard_name' => 'web']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);

        // Assign permissions ke roles

        // Super Admin - Full access
        $superAdminRole->givePermissionTo([
            'data-anak.view-all',
            'data-anak.create',
            'data-anak.edit',
            'data-anak.delete',
            'data-anak.restore',
            'data-anak.force-delete',
            'data-anak.export',
            'data-anak.import',
            'data-anak.statistics',
        ]);

        // Admin HR - Full access kecuali force delete
        $adminHrRole->givePermissionTo([
            'data-anak.view-all',
            'data-anak.create',
            'data-anak.edit',
            'data-anak.delete',
            'data-anak.restore',
            'data-anak.export',
            'data-anak.import',
            'data-anak.statistics',
        ]);

        // Manager - Department level access
        $managerRole->givePermissionTo([
            'data-anak.view-department',
            'data-anak.view-own',
            'data-anak.create',
            'data-anak.edit-department',
            'data-anak.edit-own',
            'data-anak.delete-department',
            'data-anak.export',
            'data-anak.statistics',
        ]);

        // Supervisor - Department level access (limited)
        $supervisorRole->givePermissionTo([
            'data-anak.view-department',
            'data-anak.view-own',
            'data-anak.create-own',
            'data-anak.edit-department',
            'data-anak.edit-own',
            'data-anak.export',
        ]);

        // Employee - Own data only
        $employeeRole->givePermissionTo([
            'data-anak.view-own',
            'data-anak.create-own',
            'data-anak.edit-own',
        ]);

        $this->command->info('Data Riwayat Anak permissions and role assignments created successfully!');
        
        // Display summary
        $this->command->table(
            ['Role', 'Permissions Count', 'Key Permissions'],
            [
                [
                    'super-admin', 
                    $superAdminRole->permissions()->where('name', 'like', 'data-anak.%')->count(),
                    'Full Access'
                ],
                [
                    'admin-hr', 
                    $adminHrRole->permissions()->where('name', 'like', 'data-anak.%')->count(),
                    'All except force-delete'
                ],
                [
                    'manager', 
                    $managerRole->permissions()->where('name', 'like', 'data-anak.%')->count(),
                    'Department + Own'
                ],
                [
                    'supervisor', 
                    $supervisorRole->permissions()->where('name', 'like', 'data-anak.%')->count(),
                    'Department + Own (Limited)'
                ],
                [
                    'employee', 
                    $employeeRole->permissions()->where('name', 'like', 'data-anak.%')->count(),
                    'Own Data Only'
                ],
            ]
        );
    }
}
