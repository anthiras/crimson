<?php
namespace App\Domain;

class RoleId
{
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function admin(): RoleId
    {
        return new RoleId('5374a818-1064-4670-a86b-612385bc7081');
    }

    public static function instructor(): RoleId
    {
        return new RoleId('e4dff4be-253c-4782-a480-89d9202dacbd');
    }

    public function string(): string
    {
        return $this->id;
    }

    public function equals(RoleId $id)
    {
        return $this->string() == $id->string();
    }

    public function __toString()
    {
        return $this->string();
    }
}