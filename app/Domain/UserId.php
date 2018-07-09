<?php
namespace App\Domain;

use Webpatser\Uuid\Uuid;

class UserId
{
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function create(): UserId
    {
        return new UserId(Uuid::generate()->string);
    }

    public function string(): string
    {
        return $this->id;
    }

    public function equals(UserId $userId)
    {
        return $this->string() == $userId->string();
    }

    public function __toString()
    {
        return $this->string();
    }
}