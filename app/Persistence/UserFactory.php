<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 26-05-2018
 * Time: 11:51
 */

namespace App\Persistence;


use App\Domain\Auth0Id;
use App\Domain\RoleId;
use App\Domain\User;
use App\Domain\UserId;
use Cake\Chronos\Chronos;

class UserFactory
{
    public static function createUser(UserModel $userModel): User
    {
        return new User(
            new UserId($userModel->id),
            $userModel->name,
            $userModel->email,
            $userModel->picture,
            $userModel->gender,
            Chronos::parse($userModel->birth_date),
            $userModel->auth0Users()->get()->map(function($model) { return new Auth0Id($model->auth0_id); })->toArray(),
            $userModel->roles()->get()->map(function ($roleModel) { return new RoleId($roleModel->id); })->toArray(),
            $userModel->deleted
        );
    }
}