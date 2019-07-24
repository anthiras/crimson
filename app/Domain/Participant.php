<?php
namespace App\Domain;

use Cake\Chronos\Chronos;

class Participant
{
    /**
     * @var UserId
     */
    protected $userId;
    /**
     * @var string
     */
    protected $status;
    /**
     * @var string
     */
    protected $role;
    /**
     * @var Chronos
     */
    protected $signedUpAt;

    /**
     * @var string
     */
    protected $amountPaid;

    const STATUS_PENDING = "pending";
    const STATUS_CONFIRMED = "confirmed";
    const STATUS_CANCELLED = "cancelled";

    const ROLE_LEAD = "lead";
    const ROLE_FOLLOW = "follow";

    public function __construct(UserId $userId, string $status, string $role, Chronos $signedUpAt, string $amountPaid)
    {
        $this->userId = $userId;
        $this->status = $status;
        $this->role = $role;
        $this->signedUpAt = $signedUpAt;
        $this->amountPaid = $amountPaid;
    }

    /**
     * @param UserId $userId
     * @param string $role
     * @return Participant
     */
    public static function create(UserId $userId, string $role)
    {
        return new Participant($userId, static::STATUS_PENDING, $role, Chronos::now(), "0.00");
    }

    /**
     * @return UserId
     */
    public function getUserId(): UserId
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return Chronos
     */
    public function getSignedUpAt(): Chronos
    {
        return $this->signedUpAt;
    }

    /**
     * @return string
     */
    public function getAmountPaid(): string
    {
        return $this->amountPaid;
    }

    /**
     * @return Participant
     */
    public function setAmountPaid(string $amountPaid): Participant
    {
        return new Participant($this->getUserId(), $this->getStatus(), $this->getRole(), $this->getSignedUpAt(), $amountPaid);
    }

    /**
     * @return Participant
     */
    public function confirm(): Participant
    {
        return new Participant($this->getUserId(), static::STATUS_CONFIRMED, $this->getRole(), $this->getSignedUpAt(), $this->amountPaid);
    }

    /**
     * @return Participant
     */
    public function cancel(): Participant
    {
        return new Participant($this->getUserId(), static::STATUS_CANCELLED, $this->getRole(), $this->getSignedUpAt(), $this->amountPaid);
    }
}