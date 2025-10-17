<?php

namespace App\Policies;

use App\Models\DataRiwayatPelatihan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DataRiwayatPelatihanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('data-riwayat-pelatihan.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DataRiwayatPelatihan $dataRiwayatPelatihan): bool
    {
        return $user->hasPermission('data-riwayat-pelatihan.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('data-riwayat-pelatihan.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DataRiwayatPelatihan $dataRiwayatPelatihan): bool
    {
        return $user->hasPermission('data-riwayat-pelatihan.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DataRiwayatPelatihan $dataRiwayatPelatihan): bool
    {
        return $user->hasPermission('data-riwayat-pelatihan.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DataRiwayatPelatihan $dataRiwayatPelatihan): bool
    {
        return $user->hasPermission('data-riwayat-pelatihan.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DataRiwayatPelatihan $dataRiwayatPelatihan): bool
    {
        return $user->hasPermission('data-riwayat-pelatihan.delete');
    }

    /**
     * Determine whether the user can download certificate files.
     */
    public function download(User $user, DataRiwayatPelatihan $dataRiwayatPelatihan): bool
    {
        return $user->hasPermission('data-riwayat-pelatihan.download');
    }

    /**
     * Determine whether the user can perform bulk operations.
     */
    public function bulk(User $user): bool
    {
        return $user->hasPermission('data-riwayat-pelatihan.bulk');
    }
}