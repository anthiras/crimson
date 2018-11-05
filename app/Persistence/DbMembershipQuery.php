<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 05-11-2018
 * Time: 20:46
 */

namespace App\Persistence;


use App\Domain\UserId;
use App\Http\Resources\MembershipResource;
use App\Queries\MembershipQuery;
use Cake\Chronos\Chronos;

class DbMembershipQuery implements MembershipQuery
{

    public function show(UserId $userId, Chronos $atDate): MembershipResource
    {
        return new MembershipResource(MembershipModel::forUserAndDate($userId, $atDate));
    }
}