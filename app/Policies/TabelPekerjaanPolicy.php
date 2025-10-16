<?php

namespace App\Policies;

use App\Models\TabelPekerjaan;
use App\Models\User;

class TabelPekerjaanPolicy
{
    public function viewAny(User $user): bool { return $user->hasPermission('tabel-pekerjaan.view'); }
    public function view(User $user, TabelPekerjaan $model): bool { return $user->hasPermission('tabel-pekerjaan.view'); }
    public function create(User $user): bool { return $user->hasPermission('tabel-pekerjaan.create'); }
    public function update(User $user, TabelPekerjaan $model): bool { return $user->hasPermission('tabel-pekerjaan.edit'); }
    public function delete(User $user, TabelPekerjaan $model): bool { return $user->hasPermission('tabel-pekerjaan.delete'); }
    public function restore(User $user, TabelPekerjaan $model): bool { return $user->hasPermission('tabel-pekerjaan.delete'); }
    public function forceDelete(User $user, TabelPekerjaan $model): bool { return $user->hasPermission('tabel-pekerjaan.delete'); }
}