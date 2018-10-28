<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 28-10-2018
 * Time: 13:07
 */

namespace App\Persistence;


use App\Domain\Membership;
use App\Domain\MembershipRepository;
use App\Domain\UserId;
use Cake\Chronos\Chronos;

class DbMembershipRepository implements MembershipRepository
{

    public function membership(UserId $userId, Chronos $atDate): Membership
    {
        $model = MembershipModel::forUserAndDate($userId, $atDate);
        return MembershipFactory::create($model);
    }

    public function hasMembership(UserId $userId, Chronos $atDate): bool
    {
        $model = MembershipModel::forUserAndDate($userId, $atDate);
        return $model != null;
    }

    public function save(Membership $membership)
    {
        MembershipModel::updateOrCreate(
            ['user_id' => $membership->getUserId(), 'expires_at' => $membership->getExpiresAt()],
            MembershipToDb::map($membership)
        );
    }
}