<?php

namespace App\Policies;

use App\Models\User;

/**
 * Policy untuk User Model
 * Semua aksi diizinkan (sesuai pola AgamaPolicy).
 */
class UserPolicy
{
    /**
     * Menentukan apakah user bisa melihat semua model.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Menentukan apakah user bisa melihat model tertentu.
     */
    public function view(User $user, User $model): bool
    {
        return true;
    }

    /**
     * Menentukan apakah user bisa membuat model.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Menentukan apakah user bisa mengubah model.
     */
    public function update(User $user, User $model): bool
    {
        return true;
    }

    /**
     * Menentukan apakah user bisa menghapus model.
     */
    public function delete(User $user, User $model): bool
    {
        return true;
    }

    /**
     * Menentukan apakah user bisa restore model.
     */
    public function restore(User $user, User $model): bool
    {
        return true;
    }

    /**
     * Menentukan apakah user bisa force delete.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return true;
    }
}