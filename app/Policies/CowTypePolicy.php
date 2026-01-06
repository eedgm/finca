<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CowType;
use Illuminate\Auth\Access\HandlesAuthorization;

class CowTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the cowType can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list cow-types');
    }

    /**
     * Determine whether the cowType can view the model.
     */
    public function view(User $user, CowType $model): bool
    {
        return $user->hasPermissionTo('view cow-types');
    }

    /**
     * Determine whether the cowType can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create cow-types');
    }

    /**
     * Determine whether the cowType can update the model.
     */
    public function update(User $user, CowType $model): bool
    {
        return $user->hasPermissionTo('update cow-types');
    }

    /**
     * Determine whether the cowType can delete the model.
     */
    public function delete(User $user, CowType $model): bool
    {
        return $user->hasPermissionTo('delete cow-types');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete cow-types');
    }

    /**
     * Determine whether the cowType can restore the model.
     */
    public function restore(User $user, CowType $model): bool
    {
        return false;
    }

    /**
     * Determine whether the cowType can permanently delete the model.
     */
    public function forceDelete(User $user, CowType $model): bool
    {
        return false;
    }
}
