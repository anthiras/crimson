<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 05-11-2018
 * Time: 20:53
 */

namespace App\Queries;


use App\Domain\UserId;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;

interface UserQuery
{
    public function show(UserId $userId): UserResource;
    public function list(
        ?string $searchText = null,
        $includes = null,
        ?bool $isMember = null,
        ?bool $isPaidMember = null,
        ?bool $isRecentInstructor = null)
        : UserResourceCollection;
}