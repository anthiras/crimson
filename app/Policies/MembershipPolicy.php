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
        if ($user->hasRole(RoleId::admin()) || $user->hasRole(RoleId::instructor()))
        {
            return true;
        }

        return $user->id()->equals($membership->getUserId());
    }

    public function showResource(User $user, MembershipResource $membership)
    {
        if ($user->hasRole(RoleId::admin()) || $user->hasRole(RoleId::instructor()))
        {
            return true;
        }

        return $user->id()->equals(new UserId($membership->user_id));
    }

    public function store(User $user, Membership $membership)
    {
        if ($user->hasRole(RoleId::admin()))
        {
            return true;
        }

        return $user->id()->equals($membership->getUserId());
    }

    public function setPaid(User $user, Membership $membership = null)
    {
        return $user->hasRole(RoleId::admin());
    }

    public function delete(User $user, Membership $membership = null)
    {
        return $user->hasRole(RoleId::admin());
    }
}