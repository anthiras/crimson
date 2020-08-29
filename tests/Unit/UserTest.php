<?php

namespace Tests\Unit;

use App\Domain\Auth0Id;
use App\Domain\Role;
use App\Domain\RoleId;
use Tests\Builders\UserBuilder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    public function testAuth0Ids(): void
    {
        $user = UserBuilder::buildRandom();
        $auth0Id = new Auth0Id("testId|1234");

        $user->assignAuth0Id($auth0Id);
        $this->assertTrue($user->getAuth0Ids()->contains($auth0Id), "Expected auth0 ID to be in user's list of auth0 IDs");
    }

    public function testUserHasRole(): void
    {
        $userBuilder = new UserBuilder();
        $adminRoleId = RoleId::admin();
        $otherRoleId = new RoleId("1234");
        $adminUser = $userBuilder->withRoles([$adminRoleId])->build();

        $this->assertTrue($adminUser->hasRole($adminRoleId), "Expected user to have the given role");
        $this->assertFalse($adminUser->hasRole($otherRoleId), "Did not expect user to have the given role");
        $this->assertTrue($adminUser->hasRole($adminRoleId, $otherRoleId), "Expected user to have one of the given roles");
    }

    public function testAddRemoveRoles(): void
    {
        $userBuilder = new UserBuilder();
        $user = $userBuilder->withRoles([])->build();

        $this->assertCount(0, $user->getRoleIds());

        $user->addRole(RoleId::admin());
        $this->assertCount(1, $user->getRoleIds(), "Expected one role to be added");

        $user->addRole(RoleId::admin());
        $this->assertCount(1, $user->getRoleIds(), "Expected only one role, no duplicates");

        $user->addRole(RoleId::instructor());
        $this->assertCount(2, $user->getRoleIds(), "Expected two roles");

        $user->removeRole(RoleId::admin());
        $this->assertCount(1, $user->getRoleIds(), "Expected one role");
    }
}
