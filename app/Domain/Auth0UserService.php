<?php
namespace App\Domain;

use App\Domain\Auth0Id;

class Auth0UserService
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Store user data from Auth0
     * Create/update a user account, if it does not already exist
     */
    public function createOrUpdateUser(Auth0Id $auth0Id, string $email, string $name, string $picture)
    {
        $userIdByEmail = $this->userRepository->userIdByEmail($email);
        $userIdByAuth0Id = $this->userRepository->userIdByAuth0Id($auth0Id);

        if ($userIdByEmail != null && $userIdByAuth0Id != null && $userIdByEmail != $userIdByAuth0Id)
        {
            throw new \Exception("Failed to determine user account. The supplied Auth0 data matched two separate accounts: Auth0 Id ". $auth0Id ." matched user ID " . $userIdByAuth0Id . ", email " . $email . " matched user ID " . $userIdByEmail);
        }

        $userId = $userIdByEmail ?? $userIdByAuth0Id;
        $user = $userId != null 
            ? $this->userRepository->user($userId)
            : User::createNew($name, $email, $picture, $auth0Id);

        $user->assignAuth0Id($auth0Id)
            ->setEmail($email)
            ->setPicture($picture);

        $this->userRepository->save($user);
    }
}