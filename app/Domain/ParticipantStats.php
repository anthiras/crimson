<?php


namespace App\Domain;


use Illuminate\Support\Collection;

class ParticipantStats
{
    public static function getLeadFollowerDifference(Collection $participants): int
    {
        $confirmedParticipants = $participants
            ->verifyType(Participant::class)
            ->filter(function ($participant) {
                return $participant->getStatus() == Participant::STATUS_CONFIRMED;
            });

        $countLeads = $confirmedParticipants
            ->filter(function ($participant) {
                return $participant->getRole() == Participant::ROLE_LEAD;
            })
            ->count();

        $countFollowers = $confirmedParticipants
            ->filter(function ($participant) {
                return $participant->getRole() == Participant::ROLE_FOLLOW;
            })
            ->count();

        return $countLeads-$countFollowers;
    }

    public static function getRoleDifference(Collection $participants): int
    {
        return abs(self::getLeadFollowerDifference($participants));
    }
}