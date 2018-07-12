<?php
namespace App\Persistence;

use App\Domain\Course;
use App\Domain\Schedule;
use App\Domain\Participant;
use App\Domain\CourseId;
use App\Domain\UserId;
use Cake\Chronos\Chronos;

class CourseFactory
{
    public static function createCourse(CourseModel $courseModel): Course
    {
        $schedule = static::createSchedule($courseModel);
        return new Course(
            new CourseId($courseModel->id), 
            $courseModel->name,
            $schedule,
            iterator_to_array($schedule->createLessons()),
            $courseModel->instructors()->select('id')->get()->map(function ($user) { return new UserId($user->id); })->toArray(),
            $courseModel->participants()->select(['id', 'status'])->get()->map([CourseFactory::class, 'createParticipant'])->toArray());
    }

    public static function createSchedule(CourseModel $courseModel): Schedule
    {
        return new Schedule(
            Chronos::parse($courseModel->starts_at),
            $courseModel->weeks,
            $courseModel->duration_minutes);
    }

    public static function createParticipant($participant): Participant
    {
        return new Participant(new UserId($participant->id), $participant->pivot->status, $participant->pivot->role);
    }
}