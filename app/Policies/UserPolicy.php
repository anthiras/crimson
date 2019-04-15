<?php

namespace App\Policies;

use App\Domain\RoleId;
use App\Domain\User;
use App\Domain\UserId;
use App\Http\Resources\UserResource;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function show(User $authenticatedUser, User $accessedUser)
    {
        if ($authenticatedUser->hasRole(RoleId::admin()) || $authenticatedUser->hasRole(RoleId::instructor()))
        {
            return true;
        }

        return $authenticatedUser->id()->equals($accessedUser->id());
    }

    public function showResource(User $authenticatedUser, UserResource $accessedUser)
    {
        if ($authenticatedUser->hasRole(RoleId::admin()) || $authenticatedUser->hasRole(RoleId::instructor()))
        {
            return true;
        }

        return $authenticatedUser->id()->equals(new UserId($accessedUser->id));
    }

    public function update(User $authenticatedUser, User $accessedUser)
    {
        if ($authenticatedUser->hasRole(RoleId::admin()))
        {
            return true;
        }

        return $authenticatedUser->id()->equals($accessedUser->id());
    }

    public function list(User $user)
    {
        return true;
    }

    public function delete(User $authenticatedUser, User $accessedUser)
    {
        if ($authenticatedUser->hasRole(RoleId::admin()))
        {
            return true;
        }

        return $authenticatedUser->id()->equals($accessedUser->id());
    }
}
