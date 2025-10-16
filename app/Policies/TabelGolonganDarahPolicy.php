<?php

namespace App\Policies;

use App\Models\TabelGolonganDarah;
use App\Models\User;

class TabelGolonganDarahPolicy
{
    public function viewAny(User $user): bool { return $user->hasPermission('tabel-golongan-darahs.view'); }
    public function view(User $user, TabelGolonganDarah $model): bool { return $user->hasPermission('tabel-golongan-darahs.view'); }
    public function create(User $user): bool { return $user->hasPermission('tabel-golongan-darahs.create'); }
    public function update(User $user, TabelGolonganDarah $model): bool { return $user->hasPermission('tabel-golongan-darahs.edit'); }
    public function delete(User $user, TabelGolonganDarah $model): bool { return $user->hasPermission('tabel-golongan-darahs.delete'); }
    public function restore(User $user, TabelGolonganDarah $model): bool { return $user->hasPermission('tabel-golongan-darahs.delete'); }
    public function forceDelete(User $user, TabelGolonganDarah $model): bool { return $user->hasPermission('tabel-golongan-darahs.delete'); }
}