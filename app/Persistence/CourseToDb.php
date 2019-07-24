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
            "duration_minutes" => $course->schedule()->durationMinutes(),
            "allow_registration" => $course->getRegistrationSettings()->getAllowRegistration(),
            "auto_confirm" => $course->getRegistrationSettings()->getAutoConfirm(),
            "max_participants" => $course->getRegistrationSettings()->getMaxParticipants(),
            "max_role_difference" => $course->getRegistrationSettings()->getMaxRoleDifference()
        ];
    }

    public static function mapParticipant(Participant $participant)
    {
        return [
            $participant->getUserId()->string() =>
            [
                "status" => $participant->getStatus(),
                "role" => $participant->getRole(),
                "signed_up_at" => $participant->getSignedUpAt(),
                "amount_paid" => $participant->getAmountPaid()
            ]
        ];
    }
}