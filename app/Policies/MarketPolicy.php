<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Market;
use Illuminate\Auth\Access\HandlesAuthorization;

class MarketPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the market can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list markets');
    }

    /**
     * Determine whether the market can view the model.
     */
    public function view(User $user, Market $model): bool
    {
        return $user->hasPermissionTo('view markets');
    }

    /**
     * Determine whether the market can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create markets');
    }

    /**
     * Determine whether the market can update the model.
     */
    public function update(User $user, Market $model): bool
    {
        return $user->hasPermissionTo('update markets');
    }

    /**
     * Determine whether the market can delete the model.
     */
    public function delete(User $user, Market $model): bool
    {
        return $user->hasPermissionTo('delete markets');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete markets');
    }

    /**
     * Determine whether the market can restore the model.
     */
    public function restore(User $user, Market $model): bool
    {
        return false;
    }

    /**
     * Determine whether the market can permanently delete the model.
     */
    public function forceDelete(User $user, Market $model): bool
    {
        return false;
    }
}
