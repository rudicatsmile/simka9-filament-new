<?php

namespace Database\Seeders;

use App\Models\CwspsRole;
use App\Models\CwspsPermission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat permissions default
        $permissions = [
            // User Management
            [
                'name' => 'Lihat Pengguna',
                'identifier' => 'users.view',
                'route' => 'filament.admin.resources.users.index',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Buat Pengguna',
                'identifier' => 'users.create',
                'route' => 'filament.admin.resources.users.create',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Edit Pengguna',
                'identifier' => 'users.edit',
                'route' => 'filament.admin.resources.users.edit',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Hapus Pengguna',
                'identifier' => 'users.delete',
                'route' => 'filament.admin.resources.users.delete',
                'panel_ids' => ['admin'],
                'status' => true,
            ],

            // Role Management
            [
                'name' => 'Lihat Role',
                'identifier' => 'roles.view',
                'route' => 'filament.admin.resources.cwsps-roles.index',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Buat Role',
                'identifier' => 'roles.create',
                'route' => 'filament.admin.resources.cwsps-roles.create',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Edit Role',
                'identifier' => 'roles.edit',
                'route' => 'filament.admin.resources.cwsps-roles.edit',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Hapus Role',
                'identifier' => 'roles.delete',
                'route' => 'filament.admin.resources.cwsps-roles.delete',
                'panel_ids' => ['admin'],
                'status' => true,
            ],

            // Permission Management
            [
                'name' => 'Lihat Permission',
                'identifier' => 'permissions.view',
                'route' => 'filament.admin.resources.cwsps-permissions.index',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Buat Permission',
                'identifier' => 'permissions.create',
                'route' => 'filament.admin.resources.cwsps-permissions.create',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Edit Permission',
                'identifier' => 'permissions.edit',
                'route' => 'filament.admin.resources.cwsps-permissions.edit',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Hapus Permission',
                'identifier' => 'permissions.delete',
                'route' => 'filament.admin.resources.cwsps-permissions.delete',
                'panel_ids' => ['admin'],
                'status' => true,
            ],

            // Dashboard
            [
                'name' => 'Akses Dashboard',
                'identifier' => 'dashboard.view',
                'route' => 'filament.admin.pages.dashboard',
                'panel_ids' => ['admin'],
                'status' => true,
            ],

            //Book
            [
                'name' => 'Lihat Buku',
                'identifier' => 'books.view',
                'route' => 'filament.admin.resources.books.index',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Buat Buku',
                'identifier' => 'books.create',
                'route' => 'filament.admin.resources.books.create',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Edit Buku',
                'identifier' => 'books.edit',
                'route' => 'filament.admin.resources.books.edit',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Hapus Buku',
                'identifier' => 'books.delete',
                'route' => 'filament.admin.resources.books.delete',
                'panel_ids' => ['admin'],
                'status' => true,
            ],

            //Agama
            [
                'name' => 'Lihat Agama',
                'identifier' => 'agamas.view',
                'route' => 'filament.admin.resources.agamas.index',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Buat Agama',
                'identifier' => 'agamas.create',
                'route' => 'filament.admin.resources.agamas.create',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Edit Agama',
                'identifier' => 'agamas.edit',
                'route' => 'filament.admin.resources.agamas.edit',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            [
                'name' => 'Hapus Agama',
                'identifier' => 'agamas.delete',
                'route' => 'filament.admin.resources.agamas.delete',
                'panel_ids' => ['admin'],
                'status' => true,
            ],
            //
        ];

        foreach ($permissions as $permission) {
            CwspsPermission::firstOrCreate(
                ['identifier' => $permission['identifier']],
                $permission
            );
        }

        // Buat roles default
        $roles = [
            [
                'name' => 'Super Admin',
                'identifier' => 'super-admin',
                'panel_ids' => ['admin'],
                'all_permission' => true,
                'status' => true,
            ],
            [
                'name' => 'Admin',
                'identifier' => 'admin',
                'panel_ids' => ['admin'],
                'all_permission' => false,
                'status' => true,
            ],
            [
                'name' => 'Manager',
                'identifier' => 'manager',
                'panel_ids' => ['admin'],
                'all_permission' => false,
                'status' => true,
            ],
            [
                'name' => 'User',
                'identifier' => 'user',
                'panel_ids' => ['admin'],
                'all_permission' => false,
                'status' => true,
            ],
        ];

        foreach ($roles as $roleData) {
            $role = CwspsRole::firstOrCreate(
                ['identifier' => $roleData['identifier']],
                $roleData
            );

            // Assign permissions berdasarkan role
            if (!$role->all_permission) {
                switch ($role->identifier) {
                    case 'admin':
                        // Admin dapat mengakses semua kecuali role dan permission management
                        $adminPermissions = CwspsPermission::whereNotIn('identifier', [
                            'roles.create',
                            'roles.edit',
                            'roles.delete',
                            'permissions.create',
                            'permissions.edit',
                            'permissions.delete'
                        ])->pluck('id');
                        $role->permissions()->sync($adminPermissions);
                        break;

                    case 'manager':
                        // Manager dapat mengakses user management dan dashboard
                        $managerPermissions = CwspsPermission::whereIn('identifier', [
                            'dashboard.view',
                            'users.view',
                            'users.create',
                            'users.edit',
                            'roles.view',
                            'permissions.view'
                        ])->pluck('id');
                        $role->permissions()->sync($managerPermissions);
                        break;

                    case 'user':
                        // User hanya dapat mengakses dashboard
                        $userPermissions = CwspsPermission::whereIn('identifier', [
                            'dashboard.view'
                        ])->pluck('id');
                        $role->permissions()->sync($userPermissions);
                        break;
                }
            }
        }

        $this->command->info('Role dan Permission berhasil dibuat!');
    }
}
