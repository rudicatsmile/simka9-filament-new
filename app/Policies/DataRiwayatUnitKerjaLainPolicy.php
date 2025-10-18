<?php

namespace App\Policies;

use App\Models\DataRiwayatUnitKerjaLain;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * DataRiwayatUnitKerjaLainPolicy
 * 
 * Policy untuk mengatur hak akses terhadap model DataRiwayatUnitKerjaLain
 * Menggunakan permission-based authorization dengan role matrix
 * 
 * @package App\Policies
 * @author Laravel Filament SIMKA9
 * @version 1.0.0
 */
class DataRiwayatUnitKerjaLainPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('data-riwayat-unit-kerja-lains.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DataRiwayatUnitKerjaLain $dataRiwayatUnitKerjaLain): bool
    {
        return $user->hasPermission('data-riwayat-unit-kerja-lains.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('data-riwayat-unit-kerja-lains.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DataRiwayatUnitKerjaLain $dataRiwayatUnitKerjaLain): bool
    {
        return $user->hasPermission('data-riwayat-unit-kerja-lains.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DataRiwayatUnitKerjaLain $dataRiwayatUnitKerjaLain): bool
    {
        return $user->hasPermission('data-riwayat-unit-kerja-lains.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DataRiwayatUnitKerjaLain $dataRiwayatUnitKerjaLain): bool
    {
        return $user->hasPermission('data-riwayat-unit-kerja-lains.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DataRiwayatUnitKerjaLain $dataRiwayatUnitKerjaLain): bool
    {
        return $user->hasPermission('data-riwayat-unit-kerja-lains.force-delete');
    }
}