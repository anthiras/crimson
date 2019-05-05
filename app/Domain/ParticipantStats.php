<?php


namespace App\Domain;


use Illuminate\Support\Collection;

class ParticipantStats
{
    public static function getLeadFollowerDifference(Collection $participants): int
    {
        $confirmedParticipants = $participants
            ->verifyType(Participant::class)
            ->filterStatus(Participant::STATUS_CONFIRMED);

        $countLeads = $confirmedParticipants
            ->filterRole(Participant::ROLE_LEAD)
            ->count();

        $countFollowers = $confirmedParticipants
            ->filterRole(Participant::ROLE_FOLLOW)
            ->count();

        return $countLeads-$countFollowers;
    }

    public static function getRoleDifference(Collection $participants): int
    {
        return abs(self::getLeadFollowerDifference($participants));
    }
}