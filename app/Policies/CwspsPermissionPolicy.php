<?php

namespace App\Policies;

use App\Models\CwspsPermission;
use App\Models\User;

class CwspsPermissionPolicy
{
    public function viewAny(User $user): bool { return $user->hasPermission('permissions.view'); }
    public function view(User $user, CwspsPermission $model): bool { return $user->hasPermission('permissions.view'); }
    public function create(User $user): bool { return $user->hasPermission('permissions.create'); }
    public function update(User $user, CwspsPermission $model): bool { return $user->hasPermission('permissions.edit'); }
    public function delete(User $user, CwspsPermission $model): bool { return $user->hasPermission('permissions.delete'); }
    public function restore(User $user, CwspsPermission $model): bool { return $user->hasPermission('permissions.delete'); }
    public function forceDelete(User $user, CwspsPermission $model): bool { return $user->hasPermission('permissions.delete'); }
}