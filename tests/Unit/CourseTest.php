<?php

namespace Tests\Unit;

use App\Domain\UserId;
use App\Domain\Participant;
use App\Domain\UserNotFound;
use Tests\TestCase;
use Tests\Builders\CourseBuilder;
use Tests\Builders\LessonBuilder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Cake\Chronos\Chronos;

class CourseTest extends TestCase
{
    public function testStartAndEnd()
    {
        $firstStartTime = Chronos::create(2018, 1, 1, 19, 0, 0);
        $firstEndTime = Chronos::create(2018, 1, 1, 20, 0, 0);
        $firstLesson = LessonBuilder::build($firstStartTime, $firstEndTime);

        $lastStartTime = Chronos::create(2018, 2, 1, 19, 0, 0);
        $lastEndTime = Chronos::create(2018, 2, 1, 20, 0, 0);
        $lastLesson = LessonBuilder::build($lastStartTime, $lastEndTime);

        $courseBuilder = new CourseBuilder();
        $course = $courseBuilder
            ->withLessons(array($lastLesson, $firstLesson))
            ->build();

        $this->assertEquals($firstStartTime, $course->startsAt(), "Expected course start to be start time of first lesson");
        $this->assertEquals($lastEndTime, $course->endsAt(), "Expected course end to be end time of last lesson");
    }

    public function testInstructors()
    {
        $course = CourseBuilder::buildRandom();
        $instructorId = UserId::create();
        
        $course->addInstructor($instructorId);
        $this->assertTrue($course->instructors()->contains($instructorId), "Expected instructorId to be in course instructors");
        
        $course->removeInstructor($instructorId);
        $this->assertFalse($course->instructors()->contains($instructorId), "Did not expect instructorId to be in course instructors");
    }

    public function testParticipants()
    {
        $course = CourseBuilder::buildRandom();
        $userId = UserId::create();

        $course->signUp($userId);
        $this->assertEquals(Participant::STATUS_PENDING, $course->participant($userId)->status(),
            "Expected user to be signed up pending confirmation");

        $course->confirmParticipant($userId);
        $this->assertEquals(Participant::STATUS_CONFIRMED, $course->participant($userId)->status(),
            "Expected user to be confirmed as a participant");

        $course->cancelParticipant($userId);
        $this->assertEquals(Participant::STATUS_CANCELLED, $course->participant($userId)->status(),
            "Expected user to be confirmed as a participant");
    }

    public function testErrorConfirmingNonExistingParticipant()
    {
        $this->expectException(UserNotFound::class);
        $course = CourseBuilder::buildRandom();
        $course->confirmParticipant(UserId::create());
    }
}
