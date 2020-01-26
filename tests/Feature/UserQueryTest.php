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

    protected function setUp()
    {
        parent::setUp();
        $this->userQuery = new DbUserQuery();
        $this->seed();
    }

    public function testFilterByMembership()
    {
        // There should be 5 unpaid + 3 paid members in the database (8 in total)
        $members = $this->userQuery->list(null, null, true)->count();
        $paidMembers = $this->userQuery->list(null, null, true, true)->count();
        $unpaidMembers = $this->userQuery->list(null, null, true, false)->count();
        $nonMembers = $this->userQuery->list(null, null, false)->count();
        $this->assertEquals(8, $members, "Expected 8 members");
        $this->assertEquals(5, $unpaidMembers, "Expected 5 unpaid members");
        $this->assertEquals(3, $paidMembers, "Expected 3 paid members");
        $this->assertEquals(10, $nonMembers, "Expected a full page (10) non-members");
    }
}
