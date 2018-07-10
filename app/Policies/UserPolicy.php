<?php

namespace App\Policies;

use App\Domain\RoleId;
use App\Domain\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function show(User $authenticatedUser, User $accessedUser)
    {
        if ($authenticatedUser->hasRole(RoleId::admin()))
        {
            return true;
        }

        return $authenticatedUser->id()->equals($accessedUser->id());
    }

    public function update(User $authenticatedUser, User $accessedUser)
    {
        if ($authenticatedUser->hasRole(RoleId::admin()))
        {
            return true;
        }

        return $authenticatedUser->id()->equals($accessedUser->id());
    }
}
