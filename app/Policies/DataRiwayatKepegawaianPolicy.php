<?php

namespace App\Policies;

use App\Models\DataRiwayatKepegawaian;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * DataRiwayatKepegawaianPolicy
 *
 * Policy untuk mengatur authorization pada model DataRiwayatKepegawaian
 * Mengatur akses berdasarkan role dan permission yang dimiliki user
 *
 * @package App\Policies
 * @author SIMKA9 Development Team
 * @version 1.0.0
 */
class DataRiwayatKepegawaianPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {

        if ($user->isAdmin() || $user->hasPermission('data-riwayat-kepegawaians.view')) {
            return true;
        }

        return $user->hasPermission('data-riwayat-kepegawaians.view');

    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DataRiwayatKepegawaian $dataRiwayatKepegawaian): bool
    {
        // Super Admin dan Admin HR dapat melihat semua data
        // if ($user->hasRole(['Super Admin', 'Admin HR'])) {
        //     return true;
        // }

        // // Manager/Supervisor dapat melihat data pegawai di unit kerjanya
        // if ($user->hasRole(['Manager', 'Supervisor'])) {
        //     return $dataRiwayatKepegawaian->pegawai?->unit_kerja === $user->dataPegawai?->unit_kerja;
        // }

        // // Employee hanya dapat melihat data kepegawaiannya sendiri
        // if ($user->hasRole('Employee')) {
        //     return $dataRiwayatKepegawaian->nik_data_pegawai === $user->dataPegawai?->nik;
        // }

        if ($user->isAdmin() || $user->hasPermission('data-riwayat-kepegawaians.view')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {

        if ($user->isAdmin() || $user->hasPermission('data-riwayat-kepegawaians.create')) {
            return true;
        }

        return $user->hasPermission('data-riwayat-kepegawaians.create');

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DataRiwayatKepegawaian $dataRiwayatKepegawaian): bool
    {
        // Super Admin dan Admin HR dapat mengupdate semua data
        // if ($user->hasRole(['Super Admin', 'Admin HR'])) {
        //     return $user->hasPermission('data-riwayat-kepegawaians.edit');
        // }

        // // Manager/Supervisor dapat mengupdate data pegawai di unit kerjanya
        // if ($user->hasRole(['Manager', 'Supervisor'])) {
        //     return $user->hasPermission('data-riwayat-kepegawaians.edit') &&
        //         $dataRiwayatKepegawaian->pegawai?->unit_kerja === $user->dataPegawai?->unit_kerja;
        // }

        if ($user->isAdmin() || $user->hasPermission('data-riwayat-kepegawaians.edit')) {
            return true;
        }

        // Employee tidak dapat mengupdate data kepegawaian
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DataRiwayatKepegawaian $dataRiwayatKepegawaian): bool
    {
        // Hanya Super Admin dan Admin HR yang dapat menghapus
        // if ($user->hasRole(['Super Admin', 'Admin HR'])) {
        //     return $user->hasPermission('data-riwayat-kepegawaians.delete');
        // }

        if ($user->isAdmin() || $user->hasPermission('data-riwayat-kepegawaians.delete')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DataRiwayatKepegawaian $dataRiwayatKepegawaian): bool
    {
        // return $user->hasRole(['Super Admin', 'Admin HR']) &&
        //     $user->hasPermission('data-riwayat-kepegawaians.restore');

        return $user->isAdmin() || $user->hasPermission('data-riwayat-kepegawaians.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DataRiwayatKepegawaian $dataRiwayatKepegawaian): bool
    {
        // return $user->hasRole('Super Admin') &&
        //     $user->hasPermission('data-riwayat-kepegawaians.force-delete');

        return $user->isAdmin() &&
            $user->hasPermission('data-riwayat-kepegawaians.force-delete');

    }

    /**
     * Determine whether the user can download berkas.
     */
    public function downloadBerkas(User $user, DataRiwayatKepegawaian $dataRiwayatKepegawaian): bool
    {
        return $this->view($user, $dataRiwayatKepegawaian);
    }
}
