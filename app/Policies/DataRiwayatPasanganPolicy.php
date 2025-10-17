<?php

namespace App\Policies;

use App\Models\DataRiwayatPasangan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DataRiwayatPasanganPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('data-riwayat-pasangans.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DataRiwayatPasangan $dataRiwayatPasangan): bool
    {
        return $user->hasPermission('data-riwayat-pasangans.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('data-riwayat-pasangans.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DataRiwayatPasangan $dataRiwayatPasangan): bool
    {
        return $user->hasPermission('data-riwayat-pasangans.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DataRiwayatPasangan $dataRiwayatPasangan): bool
    {
        return $user->hasPermission('data-riwayat-pasangans.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DataRiwayatPasangan $dataRiwayatPasangan): bool
    {
        return $user->hasPermission('data-riwayat-pasangans.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DataRiwayatPasangan $dataRiwayatPasangan): bool
    {
        return $user->hasPermission('data-riwayat-pasangans.delete');
    }
}