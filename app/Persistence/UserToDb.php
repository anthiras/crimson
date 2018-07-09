<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 26-05-2018
 * Time: 11:41
 */

namespace App\Persistence;


use App\Domain\Auth0Id;
use App\Domain\RoleId;
use App\Domain\User;
use App\Domain\UserId;

class UserToDb
{
    public static function map(User $user)
    {
        return [
            //"first_name" => $user->getFirstName(),
            //"last_name" => $user->getLastName()
            "name" => $user->getName(),
            "email" => $user->getEmail(),
            "picture" => $user->getPicture()
        ];
    }

    public static function mapAuth0Id(Auth0Id $auth0Id, UserId $userId)
    {
        return [
            "auth0_id" => $auth0Id->string(),
            "user_id" => $userId->string()
        ];
    }

    public static function mapRoleId(RoleId $roleId, UserId $userId)
    {
        return [
            "user_id" => $userId->string(),
            "role_id" => $roleId->string()
        ];
    }
}