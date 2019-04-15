<?php

namespace Tests\Feature;

use App\Domain\User;
use App\Persistence\DbUserRepository;
use Tests\Builders\UserBuilder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var DbUserRepository
     */
    protected $repo;

    /**
     * @var UserBuilder
     */
    protected $builder;

    protected function setUp()
    {
        parent::setUp();
        $this->repo = new DbUserRepository();
        $this->builder = new UserBuilder();
    }

    public function testCreateLoadUser()
    {
        $user = $this->builder->build();
        $userId = $user->id();

        $this->repo->save($user);

        // Load
        $reloadedUser = $this->repo->user($userId);
        $this->assertEqualUsers($user, $reloadedUser);

        // Load by auth0 id
        $auth0Id = $user->getAuth0Ids()->first();
        $auth0User = $this->repo->userByAuth0Id($auth0Id);
        $this->assertEqualUsers($user, $auth0User);

        // Load by email
        $email = $user->getEmail();
        $emailUser = $this->repo->userByEmail($email);
        $this->assertEqualUsers($user, $emailUser);
    }

    public function testDeleteUser()
    {
        // Create user
        $user = $this->builder->build();
        $userId = $user->id();
        $name = $user->getName();
        $email = $user->getEmail();
        $this->repo->save($user);

        // Delete user
        $user = $user->delete();
        $this->repo->save($user);

        // Load user
        $reloadedUser = $this->repo->user($userId);

        $this->assertTrue($reloadedUser->isDeleted(), "Expected deleted user");
        $this->assertNotEquals($name, $reloadedUser->getName(), "Expected name to be overwritten");
        $this->assertNotEquals($email, $reloadedUser->getEmail(), "Expected email to be overwritten");
    }

    private function assertEqualUsers(User $user, User $reloadedUser)
    {
        $this->assertEquals($user->id(), $reloadedUser->id());
        $this->assertEquals($user->getName(), $reloadedUser->getName());
        $this->assertEquals($user->getEmail(), $reloadedUser->getEmail());
        $this->assertEquals($user->getPicture(), $reloadedUser->getPicture());
        $this->assertEquals($user->getGender(), $reloadedUser->getGender());
        $this->assertEquals($user->getBirthDate(), $reloadedUser->getBirthDate());
        $this->assertEquals($user->getAuth0Ids(), $reloadedUser->getAuth0Ids());

    }
}
