<?php

namespace App\Policies;

use App\Models\DataRiwayatSertifikasi;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * DataRiwayatSertifikasiPolicy
 *
 * Policy untuk mengatur authorization pada model DataRiwayatSertifikasi
 * Mengatur akses berdasarkan role dan permission yang dimiliki user
 *
 * @package App\Policies
 * @author SIMKA9 Development Team
 * @version 1.0.0
 */
class DataRiwayatSertifikasiPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->isAdmin() || $user->hasPermission('data-riwayat-sertifikasis.view')) {
            return true;
        }

        return $user->hasPermission('data-riwayat-sertifikasis.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DataRiwayatSertifikasi $dataRiwayatSertifikasi): bool
    {
        // Super Admin dan Admin HR dapat melihat semua data
        if ($user->isAdmin() || $user->hasPermission('data-riwayat-sertifikasis.view')) {
            return true;
        }

        // Manager/Supervisor dapat melihat data pegawai di unit kerjanya
        if ($user->hasRole(['Manager', 'Supervisor'])) {
            return $dataRiwayatSertifikasi->pegawai?->unit_kerja === $user->dataPegawai?->unit_kerja;
        }

        // Employee hanya dapat melihat data sertifikasinya sendiri
        if ($user->hasRole('Employee')) {
            return $dataRiwayatSertifikasi->nik_data_pegawai === $user->dataPegawai?->nik;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->isAdmin() || $user->hasPermission('data-riwayat-sertifikasis.create')) {
            return true;
        }

        return $user->hasPermission('data-riwayat-sertifikasis.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DataRiwayatSertifikasi $dataRiwayatSertifikasi): bool
    {
        // Super Admin dan Admin HR dapat mengupdate semua data
        if ($user->isAdmin() || $user->hasPermission('data-riwayat-sertifikasis.edit')) {
            return true;
        }

        // Manager/Supervisor dapat mengupdate data pegawai di unit kerjanya
        if ($user->hasRole(['Manager', 'Supervisor'])) {
            return $user->hasPermission('data-riwayat-sertifikasis.edit') &&
                $dataRiwayatSertifikasi->pegawai?->unit_kerja === $user->dataPegawai?->unit_kerja;
        }

        // Employee dapat mengupdate data sertifikasinya sendiri (jika diizinkan)
        if ($user->hasRole('Employee') && $user->hasPermission('data-riwayat-sertifikasis.edit')) {
            return $dataRiwayatSertifikasi->nik_data_pegawai === $user->dataPegawai?->nik;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DataRiwayatSertifikasi $dataRiwayatSertifikasi): bool
    {
        // Hanya Super Admin dan Admin HR yang dapat menghapus
        if ($user->isAdmin() || $user->hasPermission('data-riwayat-sertifikasis.delete')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DataRiwayatSertifikasi $dataRiwayatSertifikasi): bool
    {
        return $user->isAdmin() || $user->hasPermission('data-riwayat-sertifikasis.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DataRiwayatSertifikasi $dataRiwayatSertifikasi): bool
    {
        return $user->isAdmin() &&
            $user->hasPermission('data-riwayat-sertifikasis.force-delete');
    }

    /**
     * Determine whether the user can download berkas.
     */
    public function downloadBerkas(User $user, DataRiwayatSertifikasi $dataRiwayatSertifikasi): bool
    {
        return $this->view($user, $dataRiwayatSertifikasi);
    }
}
