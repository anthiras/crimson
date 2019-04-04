<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 21-03-2019
 * Time: 21:57
 */

namespace App\Domain;


use Illuminate\Support\Collection;

class MaxParticipantsRule implements IRegistrationRule
{
    /**
     * @var int
     */
    protected $maxParticipants;

    public function __construct(int $maxParticipants)
    {
        $this->maxParticipants = $maxParticipants;
    }

    public function validate(Collection $participants): bool
    {
        return $participants
            ->verifyType(Participant::class)
            ->filter(function ($participant) {
                return $participant->getStatus() == Participant::STATUS_CONFIRMED;
            })
            ->count()
            <= $this->maxParticipants;
    }
}