<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 08-07-2018
 * Time: 15:58
 */

namespace App\Http\Controllers\API;


use App\Domain\RoleId;
use App\Domain\UserId;
use App\Domain\UserRepository;
use App\Http\Controllers\Controller;

class UserRoleController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function addRole(UserId $userId, RoleId $roleId)
    {
        $this->authorize('assignRole', $roleId);
        $user = $this->userRepository->user($userId)
            ->addRole($roleId);
        $this->userRepository->save($user);
    }

    public function removeRole(UserId $userId, RoleId $roleId)
    {
        $this->authorize('assignRole', $roleId);
        $user = $this->userRepository->user($userId)
            ->removeRole($roleId);
        $this->userRepository->save($user);
    }
}