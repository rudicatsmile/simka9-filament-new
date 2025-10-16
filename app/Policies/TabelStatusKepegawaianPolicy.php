<?php

namespace App\Policies;

use App\Models\TabelStatusKepegawaian;
use App\Models\User;

class TabelStatusKepegawaianPolicy
{
    public function viewAny(User $user): bool { return $user->hasPermission('tabel-status-kepegawaians.view'); }
    public function view(User $user, TabelStatusKepegawaian $model): bool { return $user->hasPermission('tabel-status-kepegawaians.view'); }
    public function create(User $user): bool { return $user->hasPermission('tabel-status-kepegawaians.create'); }
    public function update(User $user, TabelStatusKepegawaian $model): bool { return $user->hasPermission('tabel-status-kepegawaians.edit'); }
    public function delete(User $user, TabelStatusKepegawaian $model): bool { return $user->hasPermission('tabel-status-kepegawaians.delete'); }
    public function restore(User $user, TabelStatusKepegawaian $model): bool { return $user->hasPermission('tabel-status-kepegawaians.delete'); }
    public function forceDelete(User $user, TabelStatusKepegawaian $model): bool { return $user->hasPermission('tabel-status-kepegawaians.delete'); }
}