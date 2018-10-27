<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 25-05-2018
 * Time: 15:28
 */

namespace App\Domain;

use Cake\Chronos\Chronos;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable;

class User extends AggregateRoot implements Authenticatable, Authorizable
{
    use \Illuminate\Foundation\Auth\Access\Authorizable;

    protected $userId;
    protected $email;
    protected $name;
    protected $picture;
    protected $gender;
    protected $birthDate;
    protected $auth0ids;
    protected $roleIds;

    const GENDER_MALE = "male";
    const GENDER_FEMALE = "female";

    public function __construct(UserId $userId, string $name, string $email, string $picture, string $gender = null,
                                Chronos $birthDate = null, array $auth0ids, array $roleIds) {
        $this->userId = $userId;
        $this->name = $name;
        $this->email = $email;
        $this->picture = $picture;
        $this->gender = $gender;
        $this->birthDate = $birthDate;
        $this->auth0ids = collect($auth0ids)->verifyType(Auth0Id::class)->keyBy([$this, 'auth0Key']);
        $this->roleIds = collect($roleIds)->verifyType(RoleId::class)->keyBy([$this, 'roleKey']);
    }

    public function id(): UserId
    {
        return $this->userId;
    }

    public static function createNew(string $name, string $email, string $picture, Auth0Id $auth0Id): User
    {
        return new User(UserId::create(), $name, $email, $picture, null, null, [$auth0Id], []);
    }

    /**
     * @return UserId
     */
    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function getPicture(): string
    {
        return $this->picture;
    }

    public function setPicture(string $picture)
    {
        $this->picture = $picture;
        return $this;
    }

    /**
     * @return string
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     * @return User
     */
    public function setGender(string $gender): User
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return Chronos
     */
    public function getBirthDate(): Chronos
    {
        return $this->birthDate;
    }

    /**
     * @param Chronos $birthDate
     * @return User
     */
    public function setBirthDate(Chronos $birthDate): User
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    protected function auth0Item(Auth0Id $id)
    {
        return [$this->auth0Key($id) => $id];
    }

    public function auth0Key(Auth0Id $id)
    {
        return $id->string();
    }

    public function getAuth0Ids()
    {
        return $this->auth0ids;
    }

    public function assignAuth0Id(Auth0Id $id)
    {
        $this->auth0ids = $this->auth0ids->union($this->auth0Item($id));
    }

    protected function roleItem(RoleId $id)
    {
        return [$this->roleKey($id) => $id];
    }

    public function roleKey(RoleId $id)
    {
        return $id->string();
    }

    public function addRole(RoleId $id)
    {
        $this->roleIds = $this->roleIds->union($this->roleItem($id));
        return $this;
    }

    public function removeRole(RoleId $id)
    {
        $this->roleIds = $this->roleIds->except($id->string());
        return $this;
    }

    public function getRoleIds()
    {
        return $this->roleIds;
    }

    public function hasRole(RoleId ...$roles): bool
    {
        return $this->roleIds->intersect($roles)->isNotEmpty();
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getUserId();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return null;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return null;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value)
    {
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return null;
    }
}