<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InventoryMaterial;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryMaterialPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the inventory material can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list inventory materials');
    }

    /**
     * Determine whether the inventory material can view the model.
     */
    public function view(User $user, InventoryMaterial $model): bool
    {
        return $user->hasPermissionTo('view inventory materials');
    }

    /**
     * Determine whether the inventory material can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create inventory materials');
    }

    /**
     * Determine whether the inventory material can update the model.
     */
    public function update(User $user, InventoryMaterial $model): bool
    {
        return $user->hasPermissionTo('update inventory materials');
    }

    /**
     * Determine whether the inventory material can delete the model.
     */
    public function delete(User $user, InventoryMaterial $model): bool
    {
        return $user->hasPermissionTo('delete inventory materials');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     */
    public function deleteAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the inventory material can restore the model.
     */
    public function restore(User $user, InventoryMaterial $model): bool
    {
        return false;
    }
}
