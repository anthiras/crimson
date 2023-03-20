<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Persistence\DbUserQuery;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserQueryTest extends TestCase
{
    use RefreshDatabase;

    protected $userQuery;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userQuery = new DbUserQuery();
        $this->seed();
    }

    public function testFilterByMembership(): void
    {
        // There should be 5 unpaid + 3 paid members in the database (8 in total)
        $members = $this->userQuery->list(null, null, true)->count();
        $paidMembers = $this->userQuery->list(null, null, true, true)->count();
        $unpaidMembers = $this->userQuery->list(null, null, true, false)->count();
        $nonMembers = $this->userQuery->list(null, null, false)->count();
        $this->assertEquals(8, $members, "Expected 8 members");
        $this->assertEquals(5, $unpaidMembers, "Expected 5 unpaid members");
        $this->assertEquals(3, $paidMembers, "Expected 3 paid members");
        $this->assertEquals(20, $nonMembers, "Expected a full page (20) non-members");
    }

    public function testFilterRecentInstructors(): void
    {
        $users = $this->userQuery->list(null, ['teachingCourses'], null, null, true);
        $this->assertNotEmpty($users);
        foreach ($users as $user) {
            $this->assertNotEmpty($user->teachingCourses());
        }
    }
}
