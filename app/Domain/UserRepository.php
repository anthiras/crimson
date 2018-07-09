<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 26-05-2018
 * Time: 11:31
 */

namespace App\Domain;


interface UserRepository
{
    public function user(UserId $userId): User;
    public function userByAuth0Id(Auth0Id $auth0Id): User;
    public function userByEmail(string $email): User;
    public function exists(UserId $userId): bool;
    public function userExistsWithEmail(string $email): bool;
    public function userExistsWithAuth0Id(Auth0Id $auth0Id): bool;
    public function userIdByAuth0Id(Auth0Id $auth0Id): UserId;
    public function save(User $user);
}