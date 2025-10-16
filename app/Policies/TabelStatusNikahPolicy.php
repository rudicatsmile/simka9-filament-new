<?php

namespace App\Policies;

use App\Models\TabelStatusNikah;
use App\Models\User;

class TabelStatusNikahPolicy
{
    public function viewAny(User $user): bool { return $user->hasPermission('tabel-status-nikahs.view'); }
    public function view(User $user, TabelStatusNikah $model): bool { return $user->hasPermission('tabel-status-nikahs.view'); }
    public function create(User $user): bool { return $user->hasPermission('tabel-status-nikahs.create'); }
    public function update(User $user, TabelStatusNikah $model): bool { return $user->hasPermission('tabel-status-nikahs.edit'); }
    public function delete(User $user, TabelStatusNikah $model): bool { return $user->hasPermission('tabel-status-nikahs.delete'); }
    public function restore(User $user, TabelStatusNikah $model): bool { return $user->hasPermission('tabel-status-nikahs.delete'); }
    public function forceDelete(User $user, TabelStatusNikah $model): bool { return $user->hasPermission('tabel-status-nikahs.delete'); }
}