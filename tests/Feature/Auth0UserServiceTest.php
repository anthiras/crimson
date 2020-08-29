<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Auth0Id;
use App\Domain\Auth0UserService;
use App\Persistence\DbUserRepository;

class Auth0UserServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Auth0UserService
     */
    protected $auth0UserService;

    /**
     * @var DbUserRepository
     */
    protected $userRepo;

    protected $auth0Id;
    protected $email;
    protected $name;
    protected $picture;

    protected $auth0Id2;
    protected $email2;

    protected function setUp(): void
    {
        parent::setUp();
        $this->auth0Id = new Auth0Id("test1");
        $this->email = "test@test.example";
        $this->name = "Test User";
        $this->picture = "http://testimage.example/";
        $this->auth0Id2 = new Auth0Id("test2");
        $this->email2 = "test2@test.example";
        $this->userRepo = new DbUserRepository();
        $this->auth0UserService = new Auth0UserService($this->userRepo);
    }

    public function testCreateUser(): void
    {
        $this->auth0UserService->createOrUpdateUser($this->auth0Id, $this->email, $this->name, $this->picture);
        $user = $this->userRepo->userByAuth0Id($this->auth0Id);
        $this->assertEquals($this->auth0Id, $user->getAuth0Ids()->first());
        $this->assertEquals($this->email, $user->getEmail());
        $this->assertEquals($this->name, $user->getName());
        $this->assertEquals($this->picture, $user->getPicture());
    }

    public function testUpdateUserEmail(): void
    {
        $this->auth0UserService->createOrUpdateUser($this->auth0Id, $this->email, $this->name, $this->picture);
        $this->auth0UserService->createOrUpdateUser($this->auth0Id, $this->email2, $this->name, $this->picture);

        $user = $this->userRepo->userByAuth0Id($this->auth0Id);
        $this->assertEquals($this->email2, $user->getEmail());
    }

    public function testAddAuth0IdToExistingUser(): void
    {
        $this->auth0UserService->createOrUpdateUser($this->auth0Id, $this->email, $this->name, $this->picture);
        $this->auth0UserService->createOrUpdateUser($this->auth0Id2, $this->email, $this->name, $this->picture);

        $userId1 = $this->userRepo->userIdByAuth0Id($this->auth0Id);
        $userId2 = $this->userRepo->userIdByAuth0Id($this->auth0Id2);

        $this->assertEquals($userId1, $userId2);
    }

    public function testErrorWhenAuth0IdAndEmailMatchesTwoSeparateAccounts(): void
    {
        $this->auth0UserService->createOrUpdateUser($this->auth0Id, $this->email, $this->name, $this->picture);
        $this->auth0UserService->createOrUpdateUser($this->auth0Id2, $this->email2, $this->name, $this->picture);

        $this->expectException(\Exception::class);
        $this->auth0UserService->createOrUpdateUser($this->auth0Id, $this->email2, $this->name, $this->picture);
    }
}
