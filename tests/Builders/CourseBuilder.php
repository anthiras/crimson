<?php
namespace Tests\Builders;

use App\Domain\Course;
use App\Domain\CourseId;
use App\Domain\UserId;
use Faker\Factory;
use Illuminate\Support\Collection;

class CourseBuilder
{
    protected $courseId;
    protected $name;
    protected $schedule;
    protected $lessons;
    protected $instructors;
    protected $participants;

    public function withId(CourseId $courseId)
    {
        $this->courseId = $courseId;
        return $this;
    }

    public function withLessons(array $lessons)
    {
        $this->lessons = $lessons;
        return $this;
    }

    public function withInstructors(array $instructors)
    {
        $this->instructors = $instructors;
        return $this;
    }

    public function withParticipants(array $participants)
    {
        $this->participants = $participants;
        return $this;
    }

    public function build()
    {
        $faker = Factory::create();
        $schedule = $this->schedule ?? ScheduleBuilder::build();
        return new Course(
            $this->courseId ?? CourseId::create(),
            $this->name ?? $faker->sentence(3),
            $schedule,
            $this->lessons ?? iterator_to_array($schedule->createLessons()),
            $this->instructors ?? array(UserId::create(), UserId::create()),
            $this->participants ?? Collection::times(20, function() { return ParticipantBuilder::build(); })->toArray());
    }

    public static function buildRandom()
    {
        $courseBuilder = new CourseBuilder();
        return $courseBuilder->build();
    }
}