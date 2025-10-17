<?php

namespace App\Policies;

use App\Models\DataRiwayatAnak;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Policy untuk mengatur akses ke model DataRiwayatAnak
 * 
 * Aturan akses:
 * - Super Admin: Full access ke semua data
 * - Admin HR: Full access ke semua data
 * - Manager/Supervisor: Access ke data anak pegawai di departemen mereka
 * - Employee: Hanya dapat mengakses data anak mereka sendiri
 * 
 * @package App\Policies
 * @author SIMKA9 Development Team
 * @version 1.0.0
 */
class DataRiwayatAnakPolicy
{
    /**
     * Determine whether the user can view any models.
     * 
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // Super Admin dan Admin HR dapat melihat semua data
        if ($user->isAdmin() || $user->hasPermission('data-anak.view-all')) {
            return true;
        }

        // Manager/Supervisor dapat melihat data di departemen mereka
        if ($user->hasPermission('data-anak.view-department')) {
            return true;
        }

        // Employee dapat melihat data anak mereka sendiri
        return $user->hasPermission('data-anak.view-own');
    }

    /**
     * Determine whether the user can view the model.
     * 
     * @param User $user
     * @param DataRiwayatAnak $dataRiwayatAnak
     * @return bool
     */
    public function view(User $user, DataRiwayatAnak $dataRiwayatAnak): bool
    {
        // Super Admin dan Admin HR dapat melihat semua data
        if ($user->isAdmin() || $user->hasPermission('data-anak.view-all')) {
            return true;
        }

        // Manager/Supervisor dapat melihat data di departemen mereka
        if ($user->hasPermission('data-anak.view-department')) {
            // TODO: Implementasi logic untuk cek departemen
            // Sementara return true untuk manager
            return true;
        }

        // Employee hanya dapat melihat data anak mereka sendiri
        if ($user->hasPermission('data-anak.view-own')) {
            // Cek apakah data anak ini milik user yang sedang login
            return $dataRiwayatAnak->pegawai && 
                   $dataRiwayatAnak->pegawai->email === $user->email;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     * 
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Super Admin dan Admin HR dapat membuat data
        if ($user->isAdmin() || $user->hasPermission('data-anak.create')) {
            return true;
        }

        // Employee dapat membuat data anak untuk diri mereka sendiri
        return $user->hasPermission('data-anak.create-own');
    }

    /**
     * Determine whether the user can update the model.
     * 
     * @param User $user
     * @param DataRiwayatAnak $dataRiwayatAnak
     * @return bool
     */
    public function update(User $user, DataRiwayatAnak $dataRiwayatAnak): bool
    {
        // Super Admin dan Admin HR dapat mengupdate semua data
        if ($user->isAdmin() || $user->hasPermission('data-anak.edit')) {
            return true;
        }

        // Manager/Supervisor dapat mengupdate data di departemen mereka
        if ($user->hasPermission('data-anak.edit-department')) {
            // TODO: Implementasi logic untuk cek departemen
            return true;
        }

        // Employee hanya dapat mengupdate data anak mereka sendiri
        if ($user->hasPermission('data-anak.edit-own')) {
            return $dataRiwayatAnak->pegawai && 
                   $dataRiwayatAnak->pegawai->email === $user->email;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     * 
     * @param User $user
     * @param DataRiwayatAnak $dataRiwayatAnak
     * @return bool
     */
    public function delete(User $user, DataRiwayatAnak $dataRiwayatAnak): bool
    {
        // Hanya Super Admin dan Admin HR yang dapat menghapus data
        if ($user->isAdmin() || $user->hasPermission('data-anak.delete')) {
            return true;
        }

        // Manager/Supervisor dapat menghapus data di departemen mereka
        if ($user->hasPermission('data-anak.delete-department')) {
            // TODO: Implementasi logic untuk cek departemen
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     * 
     * @param User $user
     * @param DataRiwayatAnak $dataRiwayatAnak
     * @return bool
     */
    public function restore(User $user, DataRiwayatAnak $dataRiwayatAnak): bool
    {
        // Hanya Super Admin dan Admin HR yang dapat restore data
        return $user->isAdmin() || $user->hasPermission('data-anak.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     * 
     * @param User $user
     * @param DataRiwayatAnak $dataRiwayatAnak
     * @return bool
     */
    public function forceDelete(User $user, DataRiwayatAnak $dataRiwayatAnak): bool
    {
        // Hanya Super Admin yang dapat force delete
        return $user->isAdmin();
    }
}
