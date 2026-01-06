<?php

namespace App\Policies;

use App\Models\Cow;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CowPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the cow can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list cows');
    }

    /**
     * Determine whether the cow can view the model.
     */
    public function view(User $user, Cow $model): bool
    {
        return $user->hasPermissionTo('view cows');
    }

    /**
     * Determine whether the cow can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create cows');
    }

    /**
     * Determine whether the cow can update the model.
     */
    public function update(User $user, Cow $model): bool
    {
        return $user->hasPermissionTo('update cows');
    }

    /**
     * Determine whether the cow can delete the model.
     */
    public function delete(User $user, Cow $model): bool
    {
        return $user->hasPermissionTo('delete cows');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete cows');
    }

    /**
     * Determine whether the cow can restore the model.
     */
    public function restore(User $user, Cow $model): bool
    {
        return false;
    }

    /**
     * Determine whether the cow can permanently delete the model.
     */
    public function forceDelete(User $user, Cow $model): bool
    {
        return false;
    }
}
