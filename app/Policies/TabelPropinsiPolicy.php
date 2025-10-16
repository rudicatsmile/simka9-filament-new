<?php

namespace App\Policies;

use App\Models\TabelPropinsi;
use App\Models\User;

class TabelPropinsiPolicy
{
    public function viewAny(User $user): bool { return $user->hasPermission('tabel-propinsis.view'); }
    public function view(User $user, TabelPropinsi $model): bool { return $user->hasPermission('tabel-propinsis.view'); }
    public function create(User $user): bool { return $user->hasPermission('tabel-propinsis.create'); }
    public function update(User $user, TabelPropinsi $model): bool { return $user->hasPermission('tabel-propinsis.edit'); }
    public function delete(User $user, TabelPropinsi $model): bool { return $user->hasPermission('tabel-propinsis.delete'); }
    public function restore(User $user, TabelPropinsi $model): bool { return $user->hasPermission('tabel-propinsis.delete'); }
    public function forceDelete(User $user, TabelPropinsi $model): bool { return $user->hasPermission('tabel-propinsis.delete'); }
}