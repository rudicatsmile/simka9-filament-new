<?php

namespace App\Policies;

use App\Models\DataPasangan;
use App\Models\User;

class DataPasanganPolicy
{
    public function viewAny(User $user): bool { return $user->hasPermission('data-pasangans.view'); }
    public function view(User $user, DataPasangan $model): bool { return $user->hasPermission('data-pasangans.view'); }
    public function create(User $user): bool { return $user->hasPermission('data-pasangans.create'); }
    public function update(User $user, DataPasangan $model): bool { return $user->hasPermission('data-pasangans.edit'); }
    public function delete(User $user, DataPasangan $model): bool { return $user->hasPermission('data-pasangans.delete'); }
    public function restore(User $user, DataPasangan $model): bool { return $user->hasPermission('data-pasangans.delete'); }
    public function forceDelete(User $user, DataPasangan $model): bool { return $user->hasPermission('data-pasangans.delete'); }
}