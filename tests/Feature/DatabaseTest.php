<?php

namespace Tests\Feature;

use App\Domain\RoleId;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DatabaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        $this->seed();
    }

    public function testDatabase()
    {
        $this->assertDatabaseHas('users', [
            'id' => \DatabaseSeeder::instructorUserId()
        ]);

        $this->assertDatabaseHas('user_roles', [
            'user_id' => \DatabaseSeeder::instructorUserId(),
            'role_id' => RoleId::instructor()->string()
        ]);

        $this->assertDatabaseHas('users', [
            'id' => \DatabaseSeeder::normalUserId()
        ]);
    }
}
