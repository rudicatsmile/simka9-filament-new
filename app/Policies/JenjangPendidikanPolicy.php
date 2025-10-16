<?php

namespace App\Policies;

use App\Models\JenjangPendidikan;
use App\Models\User;

class JenjangPendidikanPolicy
{
    public function viewAny(User $user): bool { return $user->hasPermission('jenjang-pendidikan.view'); }
    public function view(User $user, JenjangPendidikan $model): bool { return $user->hasPermission('jenjang-pendidikan.view'); }
    public function create(User $user): bool { return $user->hasPermission('jenjang-pendidikan.create'); }
    public function update(User $user, JenjangPendidikan $model): bool { return $user->hasPermission('jenjang-pendidikan.edit'); }
    public function delete(User $user, JenjangPendidikan $model): bool { return $user->hasPermission('jenjang-pendidikan.delete'); }
    public function restore(User $user, JenjangPendidikan $model): bool { return $user->hasPermission('jenjang-pendidikan.delete'); }
    public function forceDelete(User $user, JenjangPendidikan $model): bool { return $user->hasPermission('jenjang-pendidikan.delete'); }
}