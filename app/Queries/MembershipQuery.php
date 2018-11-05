<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 05-11-2018
 * Time: 20:45
 */

namespace App\Queries;


use App\Domain\UserId;
use App\Http\Resources\MembershipResource;
use Cake\Chronos\Chronos;

interface MembershipQuery
{
    public function show(UserId $userId, Chronos $atDate): MembershipResource;
}