<?php

namespace App\Policies;

use App\Models\CwspsRole;
use App\Models\User;

class CwspsRolePolicy
{
    public function viewAny(User $user): bool { return $user->hasPermission('roles.view'); }
    public function view(User $user, CwspsRole $model): bool { return $user->hasPermission('roles.view'); }
    public function create(User $user): bool { return $user->hasPermission('roles.create'); }
    public function update(User $user, CwspsRole $model): bool { return $user->hasPermission('roles.edit'); }
    public function delete(User $user, CwspsRole $model): bool { return $user->hasPermission('roles.delete'); }
    public function restore(User $user, CwspsRole $model): bool { return $user->hasPermission('roles.delete'); }
    public function forceDelete(User $user, CwspsRole $model): bool { return $user->hasPermission('roles.delete'); }
}