<?php

namespace App\Policies;

use App\Models\UnitKerja;
use App\Models\User;

class UnitKerjaPolicy
{
    public function viewAny(User $user): bool { return $user->hasPermission('unit-kerja.view'); }
    public function view(User $user, UnitKerja $model): bool { return $user->hasPermission('unit-kerja.view'); }
    public function create(User $user): bool { return $user->hasPermission('unit-kerja.create'); }
    public function update(User $user, UnitKerja $model): bool { return $user->hasPermission('unit-kerja.edit'); }
    public function delete(User $user, UnitKerja $model): bool { return $user->hasPermission('unit-kerja.delete'); }
    public function restore(User $user, UnitKerja $model): bool { return $user->hasPermission('unit-kerja.delete'); }
    public function forceDelete(User $user, UnitKerja $model): bool { return $user->hasPermission('unit-kerja.delete'); }
}