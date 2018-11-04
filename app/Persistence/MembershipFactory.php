<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 28-10-2018
 * Time: 13:08
 */

namespace App\Persistence;


use App\Domain\Membership;
use App\Domain\UserId;
use Cake\Chronos\Chronos;

class MembershipFactory
{
    public static function create(MembershipModel $model): Membership
    {
        return new Membership(
            new UserId($model->user_id),
            Chronos::parse($model->starts_at),
            Chronos::parse($model->expires_at),
            $model->paid_at == null ? null : Chronos::parse($model->paid_at));
    }
}