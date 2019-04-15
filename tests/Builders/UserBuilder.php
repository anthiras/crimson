<?php
namespace Tests\Builders;

use App\Domain\Auth0Id;
use App\Domain\RoleId;
use App\Domain\User;
use App\Domain\UserId;
use Cake\Chronos\Chronos;

class UserBuilder
{
    protected $userId;
    protected $email;
    protected $name;
    protected $picture;
    protected $gender;
    protected $birthDate;
    protected $auth0ids;
    protected $roleIds;

    public function withRoles($roleIds)
    {
        $this->roleIds = $roleIds;
        return $this;
    }

    public function build() : User
    {
        $faker = \Faker\Factory::create();
        return new User(
            $this->userId ?? UserId::create(),
            $this->name ?? $faker->name,
            $this->email ?? $faker->email,
            $this->picture ?? $faker->imageUrl,
            $this->gender ?? User::GENDER_FEMALE,
            $this->birthDate ?? Chronos::createFromTimestamp($faker->unixTime()),
            $this->auth0ids ?? [ new Auth0Id($faker->uuid)],
            $this->roleIds ?? [ RoleId::instructor() ],
            false
        );
    }

    public static function buildRandom() : User
    {
        $builder = new UserBuilder();
        return $builder->build();
    }
}