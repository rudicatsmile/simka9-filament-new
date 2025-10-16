<?php

namespace App\Policies;

use App\Models\DataAnak;
use App\Models\User;

class DataAnakPolicy
{
    public function viewAny(User $user): bool { return $user->hasPermission('data-anaks.view'); }
    public function view(User $user, DataAnak $model): bool { return $user->hasPermission('data-anaks.view'); }
    public function create(User $user): bool { return $user->hasPermission('data-anaks.create'); }
    public function update(User $user, DataAnak $model): bool { return $user->hasPermission('data-anaks.edit'); }
    public function delete(User $user, DataAnak $model): bool { return $user->hasPermission('data-anaks.delete'); }
    public function restore(User $user, DataAnak $model): bool { return $user->hasPermission('data-anaks.delete'); }
    public function forceDelete(User $user, DataAnak $model): bool { return $user->hasPermission('data-anaks.delete'); }
}