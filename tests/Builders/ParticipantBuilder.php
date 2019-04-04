<?php
namespace Tests\Builders;

use App\Domain\Participant;
use App\Domain\UserId;
use Cake\Chronos\Chronos;
use Faker\Factory;

class ParticipantBuilder
{
    protected $userId;
    protected $status;
    protected $role;
    protected $signedUpAt;

    public function withUserId(UserId $userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function withStatus(string $status)
    {
        $this->status = $status;
        return $this;
    }

    public function withRole(string $role)
    {
        $this->role = $role;
        return $this;
    }
    public function withSignedUpAt(Chronos $signedUpAt)
    {
        $this->signedUpAt = $signedUpAt;
        return $this;
    }

	public function build()
	{
        $faker = Factory::create();
		return new Participant(
		    $this->userId ?? UserId::create(),
            $this->status ?? Participant::STATUS_CONFIRMED,
            $this->role ?? Participant::ROLE_LEAD,
            $this->signedUpAt ?? Chronos::createFromTimestamp($faker->unixTime()));
	}

	public static function buildRandom() : Participant
    {
        $builder = new ParticipantBuilder();
        return $builder->build();
    }
}
