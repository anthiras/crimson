<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        $this->seed();
    }

    public function testCoursesIndex()
    {
        $this->json('GET','/v1/courses?include[]=instructors')
            ->assertSuccessful()
            ->assertJsonStructure([['id', 'name', 'instructors', 'myParticipation']])
            ->assertJsonFragment(['myParticipation' => null]);
    }

    public function testCoursesStoreUnauthorized()
    {
        $this->json('POST','/v1/courses', ["name" => "new course"])
            ->assertStatus(401);
    }
}
