<?php
namespace App\Domain;

class Participant
{
    protected $userId;
    protected $status;

    const STATUS_PENDING = "pending";
    const STATUS_CONFIRMED = "confirmed";
    const STATUS_CANCELLED = "cancelled";

    public function __construct(UserId $userId, string $status)
    {
        $this->userId = $userId;
        $this->status = $status;
    }

    public static function create(UserId $userId)
    {
        return new Participant($userId, static::STATUS_PENDING);
    }

    public function userId()
    {
        return $this->userId;
    }

    public function status()
    {
        return $this->status;
    }

    public function confirm(): Participant
    {
        return new Participant($this->userId(), static::STATUS_CONFIRMED);
    }

    public function cancel(): Participant
    {
        return new Participant($this->userId(), static::STATUS_CANCELLED);
    }
}