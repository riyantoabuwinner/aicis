<?php

namespace App\Policies;

use Datlechin\FilamentMenuBuilder\Models\Menu;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole(['superadmin', 'admin']);
    }

    public function view(User $user, Menu $menu): bool
    {
        return $user->hasRole(['superadmin', 'admin']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['superadmin', 'admin']);
    }

    public function update(User $user, Menu $menu): bool
    {
        return $user->hasRole(['superadmin', 'admin']);
    }

    public function delete(User $user, Menu $menu): bool
    {
        return $user->hasRole(['superadmin', 'admin']);
    }
}
