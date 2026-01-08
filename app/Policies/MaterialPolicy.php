<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Material;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterialPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the material can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list materials');
    }

    /**
     * Determine whether the material can view the model.
     */
    public function view(User $user, Material $model): bool
    {
        return $user->hasPermissionTo('view materials');
    }

    /**
     * Determine whether the material can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create materials');
    }

    /**
     * Determine whether the material can update the model.
     */
    public function update(User $user, Material $model): bool
    {
        return $user->hasPermissionTo('update materials');
    }

    /**
     * Determine whether the material can delete the model.
     */
    public function delete(User $user, Material $model): bool
    {
        return $user->hasPermissionTo('delete materials');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     */
    public function deleteAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the material can restore the model.
     */
    public function restore(User $user, Material $model): bool
    {
        return false;
    }
}
