<?php
namespace App\Domain;

class Auth0Id
{
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function string(): string
    {
        return $this->id;
    }

    public function equals(Auth0Id $id)
    {
        return $this->string() == $id->string();
    }

    public function __toString()
    {
        return $this->string();
    }
}