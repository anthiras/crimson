<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 28-10-2018
 * Time: 12:15
 */

namespace App\Domain;


use Cake\Chronos\Chronos;

class Membership
{

    protected $userId;
    protected $startsAt;
    protected $expiresAt;
    protected $paidAt;

    public function __construct(UserId $userId, Chronos $startsAt, Chronos $expiresAt, $paidAt = null)
    {
        $this->userId = $userId;
        $this->startsAt = $startsAt;
        $this->expiresAt = $expiresAt;
        $this->paidAt = $paidAt;
    }

    public static function create(UserId $userId): Membership
    {
        return new Membership($userId, Chronos::now(), MembershipRenewal::nextRenewal(Chronos::now()));
    }

    /**
     * @return UserId
     */
    public function getUserId(): UserId
    {
        return $this->userId;
    }

    /**
     * @return Chronos
     */
    public function getStartsAt(): Chronos
    {
        return $this->startsAt;
    }

    /**
     * @return Chronos
     */
    public function getExpiresAt(): Chronos
    {
        return $this->expiresAt;
    }

    /**
     * @return null | Chronos
     */
    public function getPaidAt()
    {
        return $this->paidAt;
    }

    /**
     * @return $this
     */
    public function setPaid() {
        $this->paidAt = Chronos::now();
        return $this;
    }
}