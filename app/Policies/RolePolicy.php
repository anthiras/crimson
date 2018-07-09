<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 08-07-2018
 * Time: 16:05
 */

namespace App\Policies;

use App\Domain\RoleId;
use App\Domain\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function assignRole(User $user, RoleId $roleId)
    {
        if ($user->hasRole(RoleId::admin()))
        {
            return true;
        }

        // Let instructors promote other users to instructors
        if ($roleId->equals(RoleId::instructor()))
        {
            return $user->hasRole(RoleId::instructor());
        }

        return false;
    }
}