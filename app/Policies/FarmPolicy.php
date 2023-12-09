<?php

namespace App\Policies;

use App\Models\Farm;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FarmPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the farm can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the farm can view the model.
     */
    public function view(User $user, Farm $model): bool
    {
        return true;
    }

    /**
     * Determine whether the farm can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the farm can update the model.
     */
    public function update(User $user, Farm $model): bool
    {
        return true;
    }

    /**
     * Determine whether the farm can delete the model.
     */
    public function delete(User $user, Farm $model): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     */
    public function deleteAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the farm can restore the model.
     */
    public function restore(User $user, Farm $model): bool
    {
        return false;
    }

    /**
     * Determine whether the farm can permanently delete the model.
     */
    public function forceDelete(User $user, Farm $model): bool
    {
        return false;
    }
}
