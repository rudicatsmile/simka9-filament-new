<?php

namespace App\Policies;

use App\Models\TabelHubunganKeluarga;
use App\Models\User;

class TabelHubunganKeluargaPolicy
{
    public function viewAny(User $user): bool { return $user->hasPermission('tabel-hubungan-keluargas.view'); }
    public function view(User $user, TabelHubunganKeluarga $model): bool { return $user->hasPermission('tabel-hubungan-keluargas.view'); }
    public function create(User $user): bool { return $user->hasPermission('tabel-hubungan-keluargas.create'); }
    public function update(User $user, TabelHubunganKeluarga $model): bool { return $user->hasPermission('tabel-hubungan-keluargas.edit'); }
    public function delete(User $user, TabelHubunganKeluarga $model): bool { return $user->hasPermission('tabel-hubungan-keluargas.delete'); }
    public function restore(User $user, TabelHubunganKeluarga $model): bool { return $user->hasPermission('tabel-hubungan-keluargas.delete'); }
    public function forceDelete(User $user, TabelHubunganKeluarga $model): bool { return $user->hasPermission('tabel-hubungan-keluargas.delete'); }
}