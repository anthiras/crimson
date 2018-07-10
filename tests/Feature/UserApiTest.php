<?php

namespace Tests\Feature;

use App\Domain\UserId;
use App\Persistence\DbUserRepository;
use App\Persistence\UserModel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $otherUser;

    protected function setUp()
    {
        parent::setUp();
        $this->seed();

        $userIds = UserModel::query()->take(2)->pluck('id');
        $userRepo = new DbUserRepository();
        $this->user = $userRepo->user(new UserId($userIds[0]));
        $this->otherUser = $userRepo->user(new UserId($userIds[1]));
    }

    public function testListUsersUnauthorized()
    {
        $this->json('GET','/api/users')
            ->assertStatus(401);
    }

    public function testListUsersAuthorized()
    {
        $this->actingAs($this->user)
            ->json('GET','/api/users')
            ->assertSuccessful();
    }

    public function testUserCanShowOwnUserDetails()
    {
        $this->actingAs($this->user)
            ->json('GET', '/api/users/current')
            ->assertSuccessful();

        $this->actingAs($this->user)
            ->json('GET', '/api/users/'.$this->user->id())
            ->assertSuccessful();
    }

    public function testUserCannotShowOtherUserDetails()
    {
        $this->actingAs($this->user)
            ->json('GET', '/api/users/'.$this->otherUser->id())
            ->assertStatus(403);
    }
}
