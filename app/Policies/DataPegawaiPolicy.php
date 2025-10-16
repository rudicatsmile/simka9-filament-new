<?php

namespace App\Policies;

use App\Models\DataPegawai;
use App\Models\User;

class DataPegawaiPolicy
{
    public function viewAny(User $user): bool { return $user->hasPermission('data-pegawais.view'); }
    public function view(User $user, DataPegawai $model): bool { return $user->hasPermission('data-pegawais.view'); }
    public function create(User $user): bool { return $user->hasPermission('data-pegawais.create'); }
    public function update(User $user, DataPegawai $model): bool { return $user->hasPermission('data-pegawais.edit'); }
    public function delete(User $user, DataPegawai $model): bool { return $user->hasPermission('data-pegawais.delete'); }
    public function restore(User $user, DataPegawai $model): bool { return $user->hasPermission('data-pegawais.delete'); }
    public function forceDelete(User $user, DataPegawai $model): bool { return $user->hasPermission('data-pegawais.delete'); }
}