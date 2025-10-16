<?php

namespace App\Policies;

use App\Models\DataPendidikan;
use App\Models\User;

class DataPendidikanPolicy
{
    public function viewAny(User $user): bool { return $user->hasPermission('data-pendidikans.view'); }
    public function view(User $user, DataPendidikan $model): bool { return $user->hasPermission('data-pendidikans.view'); }
    public function create(User $user): bool { return $user->hasPermission('data-pendidikans.create'); }
    public function update(User $user, DataPendidikan $model): bool { return $user->hasPermission('data-pendidikans.edit'); }
    public function delete(User $user, DataPendidikan $model): bool { return $user->hasPermission('data-pendidikans.delete'); }
    public function restore(User $user, DataPendidikan $model): bool { return $user->hasPermission('data-pendidikans.delete'); }
    public function forceDelete(User $user, DataPendidikan $model): bool { return $user->hasPermission('data-pendidikans.delete'); }
}