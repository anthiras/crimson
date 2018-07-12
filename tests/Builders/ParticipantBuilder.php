<?php
namespace Tests\Builders;

use App\Domain\Participant;
use App\Domain\UserId;

class ParticipantBuilder
{
	public static function build(UserId $userId = null)
	{
		return new Participant(
		    $userId ?? UserId::create(),
            Participant::STATUS_CONFIRMED,
            Participant::ROLE_LEAD);
	}
}
