<?php

namespace App\Policies;

use App\Models\Store;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StorePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_store');
    }

    public function view(User $user, Store $store): bool
    {
        return $user->can('view_any_store');
    }

    public function create(User $user): bool
    {
        return $user->can('create_store');
    }

    public function update(User $user, Store $store): bool
    {
        return $user->can('update_store');
    }

    public function delete(User $user, Store $store): bool
    {
        return $user->can('delete_store');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_store');
    }
}
