<?php

namespace App\Policies;

use App\Models\JabatanUtama;
use App\Models\User;

class JabatanUtamaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('jabatan-utamas.view');
    }
    public function view(User $user, JabatanUtama $model): bool
    {
        return $user->hasPermission('jabatan-utamas.view');
    }
    public function create(User $user): bool
    {
        return $user->hasPermission('jabatan-utamas.create');
    }
    public function update(User $user, JabatanUtama $model): bool
    {
        return $user->hasPermission('jabatan-utamas.edit');
    }
    public function delete(User $user, JabatanUtama $model): bool
    {
        return $user->hasPermission('jabatan-utamas.delete');
    }
    public function restore(User $user, JabatanUtama $model): bool
    {
        return $user->hasPermission('jabatan-utamas.delete');
    }
    public function forceDelete(User $user, JabatanUtama $model): bool
    {
        return $user->hasPermission('jabatan-utamas.delete');
    }
}
