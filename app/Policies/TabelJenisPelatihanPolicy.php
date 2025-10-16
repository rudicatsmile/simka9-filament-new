<?php

namespace App\Policies;

use App\Models\TabelJenisPelatihan;
use App\Models\User;

class TabelJenisPelatihanPolicy
{
    public function viewAny(User $user): bool { return $user->hasPermission('tabel-jenis-pelatihans.view'); }
    public function view(User $user, TabelJenisPelatihan $model): bool { return $user->hasPermission('tabel-jenis-pelatihans.view'); }
    public function create(User $user): bool { return $user->hasPermission('tabel-jenis-pelatihans.create'); }
    public function update(User $user, TabelJenisPelatihan $model): bool { return $user->hasPermission('tabel-jenis-pelatihans.edit'); }
    public function delete(User $user, TabelJenisPelatihan $model): bool { return $user->hasPermission('tabel-jenis-pelatihans.delete'); }
    public function restore(User $user, TabelJenisPelatihan $model): bool { return $user->hasPermission('tabel-jenis-pelatihans.delete'); }
    public function forceDelete(User $user, TabelJenisPelatihan $model): bool { return $user->hasPermission('tabel-jenis-pelatihans.delete'); }
}