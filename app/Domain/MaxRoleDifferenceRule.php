<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 21-03-2019
 * Time: 22:06
 */

namespace App\Domain;


use Illuminate\Support\Collection;

class MaxRoleDifferenceRule implements IRegistrationRule
{
    /**
     * @var int
     */
    protected $maxRoleDifference;

    public function __construct(int $maxRoleDifference)
    {
        $this->maxRoleDifference = $maxRoleDifference;
    }

    public function validate(Collection $participants): bool
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

        return abs($countLeads-$countFollowers) <= $this->maxRoleDifference;
    }
}