<?php

namespace App\Policies;

use App\Models\User;
use App\Models\History;
use Illuminate\Auth\Access\HandlesAuthorization;

class HistoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the history can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the history can view the model.
     */
    public function view(User $user, History $model): bool
    {
        return true;
    }

    /**
     * Determine whether the history can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the history can update the model.
     */
    public function update(User $user, History $model): bool
    {
        return true;
    }

    /**
     * Determine whether the history can delete the model.
     */
    public function delete(User $user, History $model): bool
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
     * Determine whether the history can restore the model.
     */
    public function restore(User $user, History $model): bool
    {
        return false;
    }

    /**
     * Determine whether the history can permanently delete the model.
     */
    public function forceDelete(User $user, History $model): bool
    {
        return false;
    }
}
