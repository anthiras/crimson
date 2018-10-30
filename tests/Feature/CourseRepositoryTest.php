<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Builders\CourseBuilder;
use Tests\Builders\ParticipantBuilder;
use App\Persistence\DbCourseRepository;
use App\Persistence\UserModel;
use App\Domain\UserId;
use App\Domain\CourseId;
use App\Domain\CourseNotFound;
use App\Domain\Course;

class CourseRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repo;
    protected $courseBuilder;

    protected function setUp()
    {
        parent::setUp();
        $this->repo = new DbCourseRepository();
        $this->courseBuilder = new CourseBuilder();
        $this->seed();
    }


    public function testCreateLoadUpdateDeleteCourse()
    {
        $users = UserModel::all();

        // Create
        $course = $this->courseBuilder
            ->withInstructors(array(new UserId($users->first()->id)))
            ->withParticipants(array(ParticipantBuilder::build(new UserId($users->last()->id))))
            ->build();
        $courseId = $course->id();
        $this->repo->save($course);

        // Load
        $reloadedCourse = $this->repo->course($courseId);
        $this->assertEqualCourses($course, $reloadedCourse);

        // Load multiple
        $courseFromList = $this->repo->courses()->first(function($course) use ($courseId) { return $course->id() == $courseId; });
        $this->assertEqualCourses($course, $courseFromList);
        
        // Update
        $course->setName("New name");
        $this->repo->save($course);

        // Load
        $reloadedCourse = $this->repo->course($courseId);
        $this->assertEqualCourses($course, $reloadedCourse);
        $this->assertEquals("New name", $course->name());

        // Delete
        $this->repo->delete($courseId);
        $this->expectException(CourseNotFound::class);
        $this->repo->course($courseId);
    }

    private function assertEqualCourses(Course $course, Course $reloadedCourse)
    {
        $this->assertEquals($course->id(), $reloadedCourse->id());
        $this->assertEquals($course->name(), $reloadedCourse->name());
        $this->assertEquals($course->schedule(), $reloadedCourse->schedule());
        $this->assertEquals($course->lessons(), $reloadedCourse->lessons());
        $this->assertEquals($course->instructors(), $reloadedCourse->instructors());
        $this->assertEquals($course->participants(), $reloadedCourse->participants());
    }
}
