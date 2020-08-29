<?php

namespace Tests\Feature;

use App\Domain\MembershipRepository;
use App\Domain\UserId;
use App\Persistence\DbMembershipRepository;
use App\Persistence\UserModel;
use Cake\Chronos\Chronos;
use Tests\Builders\MembershipBuilder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MembershipRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /** @var MembershipRepository */
    protected $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new DbMembershipRepository();
    }

    public function testCreateLoadMembership(): void
    {
        $this->seed();
        $userId = new UserId(UserModel::query()->take(1)->pluck('id')->first());
        $date = Chronos::create(2018, 10, 1, 0, 0, 0);
        $builder = new MembershipBuilder();

        $activeMembership = $builder
            ->withUserId($userId)
            ->activeAt($date)
            ->build();
        $oldMembership = $builder
            ->withUserId($userId)
            ->activeAt($date->addYear(-7))
            ->build();

        $this->repo->save($activeMembership);
        $this->repo->save($oldMembership);

        $this->assertTrue($this->repo->hasMembership($userId, $date));

        $reloadedMembership = $this->repo->membership($userId, $date);
        $this->assertEquals($activeMembership->getUserId(), $reloadedMembership->getUserId());
        $this->assertEquals($activeMembership->getStartsAt(), $reloadedMembership->getStartsAt());
        $this->assertEquals($activeMembership->getExpiresAt(), $reloadedMembership->getExpiresAt());
        $this->assertEquals($activeMembership->getPaidAt(), $reloadedMembership->getPaidAt());
        $this->assertEquals($activeMembership->getPaymentMethod(), $reloadedMembership->getPaymentMethod());
        $this->assertEquals($activeMembership->getSignupComment(), $reloadedMembership->getSignupComment());
    }
}
