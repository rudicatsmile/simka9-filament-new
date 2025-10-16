<?php

namespace App\Policies;

use App\Models\Agama;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AgamaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('agamas.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Agama $agama): bool
    {
        return $user->hasPermission('agamas.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('agamas.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Agama $agama): bool
    {
        return $user->hasPermission('agamas.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Agama $agama): bool
    {
        return $user->hasPermission('agamas.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Agama $agama): bool
    {
        return $user->hasPermission('agamas.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Agama $agama): bool
    {
        return $user->hasPermission('agamas.delete');
    }
}
