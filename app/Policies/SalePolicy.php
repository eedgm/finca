<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list animal-sales');
    }

    public function view(User $user, Sale $sale): bool
    {
        return $user->hasPermissionTo('view animal-sales');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create animal-sales');
    }

    public function update(User $user, Sale $sale): bool
    {
        return $user->hasPermissionTo('update animal-sales');
    }

    public function delete(User $user, Sale $sale): bool
    {
        return $user->hasPermissionTo('delete animal-sales');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete animal-sales');
    }
}
