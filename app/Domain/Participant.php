<?php
namespace App\Domain;

class Participant
{
    protected $userId;
    protected $status;
    protected $role;

    const STATUS_PENDING = "pending";
    const STATUS_CONFIRMED = "confirmed";
    const STATUS_CANCELLED = "cancelled";

    const ROLE_LEAD = "lead";
    const ROLE_FOLLOW = "follow";

    public function __construct(UserId $userId, string $status, string $role)
    {
        $this->userId = $userId;
        $this->status = $status;
        $this->role = $role;
    }

    public static function create(UserId $userId, string $role)
    {
        return new Participant($userId, static::STATUS_PENDING, $role);
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function role(): string
    {
        return $this->role;
    }

    public function confirm(): Participant
    {
        return new Participant($this->userId(), static::STATUS_CONFIRMED, $this->role());
    }

    public function cancel(): Participant
    {
        return new Participant($this->userId(), static::STATUS_CANCELLED, $this->role());
    }
}