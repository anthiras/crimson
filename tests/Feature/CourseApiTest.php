<?php

namespace Tests\Feature;

use App\Domain\UserId;
use App\Persistence\DbUserRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseApiTest extends TestCase
{
    use RefreshDatabase;

    protected $instructor;
    protected $normalUser;
    protected $courseData;

    protected function setUp()
    {
        parent::setUp();
        $this->seed();

        $userRepo = new DbUserRepository();
        $this->instructor = $userRepo->user(\DatabaseSeeder::instructorUserId());
        $this->normalUser = $userRepo->user(\DatabaseSeeder::normalUserId());

        $this->courseData = [
            'name' => 'new course',
            'startAt' => '2019-01-01',
            'weeks' => 10,
            'durationMinutes' => 60,
            'instructors' => []
        ];
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
        $this->json('POST','/v1/courses', $this->courseData)
            ->assertStatus(401);
    }

    public function testCoursesStoreForbiddenForNormalUser()
    {
        $this->actingAs($this->normalUser)
            ->json('POST','/v1/courses', $this->courseData)
            ->assertStatus(403);
    }

    public function testCoursesStoreAuthorizedAsInstructor()
    {
        $this->actingAs($this->instructor)
            ->json('POST','/v1/courses', $this->courseData)
            ->assertStatus(200);
    }
}
