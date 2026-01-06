<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Manufacturer;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManufacturerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the manufacturer can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list manufacturers');
    }

    /**
     * Determine whether the manufacturer can view the model.
     */
    public function view(User $user, Manufacturer $model): bool
    {
        return $user->hasPermissionTo('view manufacturers');
    }

    /**
     * Determine whether the manufacturer can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create manufacturers');
    }

    /**
     * Determine whether the manufacturer can update the model.
     */
    public function update(User $user, Manufacturer $model): bool
    {
        return $user->hasPermissionTo('update manufacturers');
    }

    /**
     * Determine whether the manufacturer can delete the model.
     */
    public function delete(User $user, Manufacturer $model): bool
    {
        return $user->hasPermissionTo('delete manufacturers');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete manufacturers');
    }

    /**
     * Determine whether the manufacturer can restore the model.
     */
    public function restore(User $user, Manufacturer $model): bool
    {
        return false;
    }

    /**
     * Determine whether the manufacturer can permanently delete the model.
     */
    public function forceDelete(User $user, Manufacturer $model): bool
    {
        return false;
    }
}
