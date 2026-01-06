<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Medicine;
use Illuminate\Auth\Access\HandlesAuthorization;

class MedicinePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the medicine can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list medicines');
    }

    /**
     * Determine whether the medicine can view the model.
     */
    public function view(User $user, Medicine $model): bool
    {
        return $user->hasPermissionTo('view medicines');
    }

    /**
     * Determine whether the medicine can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create medicines');
    }

    /**
     * Determine whether the medicine can update the model.
     */
    public function update(User $user, Medicine $model): bool
    {
        return $user->hasPermissionTo('update medicines');
    }

    /**
     * Determine whether the medicine can delete the model.
     */
    public function delete(User $user, Medicine $model): bool
    {
        return $user->hasPermissionTo('delete medicines');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete medicines');
    }

    /**
     * Determine whether the medicine can restore the model.
     */
    public function restore(User $user, Medicine $model): bool
    {
        return false;
    }

    /**
     * Determine whether the medicine can permanently delete the model.
     */
    public function forceDelete(User $user, Medicine $model): bool
    {
        return false;
    }
}
