<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 28-10-2018
 * Time: 13:22
 */

namespace Tests\Builders;


use App\Domain\Membership;
use App\Domain\UserId;
use Cake\Chronos\Chronos;

class MembershipBuilder
{
    protected $userId;
    protected $startsAt;
    protected $expiresAt;
    protected $paidAt;

    public function __construct()
    {
        $faker = \Faker\Factory::create();
        $this->userId = UserId::create();
        $this->startsAt = Chronos::createFromTimestamp($faker->unixTime());
        $this->expiresAt = $this->startsAt->addYear(1);
        $this->paidAt = $this->startsAt->addDay(1);
    }

    public function withUserId(UserId $userId): MembershipBuilder
    {
        $this->userId = $userId;
        return $this;
    }

    public function activeAt(Chronos $date): MembershipBuilder
    {
        $this->startsAt = $date->addMonth(-1);
        $this->expiresAt = $date->addMonth(1);
        return $this;
    }

    public function inactiveAt(Chronos $date): MembershipBuilder
    {
        $this->startsAt = $date->addMonth(-2);
        $this->expiresAt = $date->addMonth(-1);
        return $this;
    }

    public function build(): Membership
    {
        return new Membership(
            $this->userId,
            $this->startsAt,
            $this->expiresAt,
            $this->paidAt
        );
    }
}