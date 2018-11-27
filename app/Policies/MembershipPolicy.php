<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 28-10-2018
 * Time: 16:18
 */

namespace App\Policies;


use App\Domain\Membership;
use App\Domain\RoleId;
use App\Domain\User;
use App\Domain\UserId;
use App\Http\Resources\MembershipResource;
use Illuminate\Auth\Access\HandlesAuthorization;

class MembershipPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Membership $membership)
    {
        if ($user->hasRole(RoleId::admin()))
        {
            return true;
        }

        return $user->id()->equals($membership->getUserId());
    }

    public function showResource(User $user, MembershipResource $membership)
    {
        if ($user->hasRole(RoleId::admin()))
        {
            return true;
        }

        return $user->id()->equals(new UserId($membership->getUserId()));
    }

    public function store(User $user, Membership $membership)
    {
        if ($user->hasRole(RoleId::admin()))
        {
            return true;
        }

        return $user->id()->equals($membership->getUserId());
    }

    public function setPaid(User $user, Membership $membership)
    {
        return $user->hasRole(RoleId::admin());
    }
}