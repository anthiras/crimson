<?php
namespace App\Persistence;

use App\Domain\Course;
use App\Domain\Participant;

class CourseToDb
{
    public static function map(Course $course)
    {
        return [
            "name" => $course->name(),
            "starts_at" => $course->startsAt(),
            "ends_at" => $course->endsAt(),
            "weeks" => $course->schedule()->weeks(),
            "duration_minutes" => $course->schedule()->durationMinutes()
        ];
    }

    public static function mapParticipant(Participant $participant)
    {
        return [
            $participant->userId()->string() => 
            [
                "status" => $participant->status(),
                "role" => $participant->role()
            ]
        ];
    }
}