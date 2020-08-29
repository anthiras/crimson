<?php

namespace Tests\Unit;

use App\Domain\MembershipRenewal;
use Cake\Chronos\Chronos;
use Cake\Chronos\Date;
use Tests\TestCase;

class MembershipRenewalTest extends TestCase
{
    public function testNextRenewal(): void
    {
        // Next year
        $date = Chronos::create(2018, 10, 10);
        $expectedRenewal = Chronos::create(2019, 2, 1, 0, 0, 0);
        $nextRenewal = MembershipRenewal::nextRenewal($date);
        $this->assertEquals($expectedRenewal, $nextRenewal);

        // Same year, next month
        $date = Chronos::create(2020, 1, 31);
        $expectedRenewal = Chronos::create(2020, 2, 1, 0, 0, 0);
        $nextRenewal = MembershipRenewal::nextRenewal($date);
        $this->assertEquals($expectedRenewal, $nextRenewal);
    }
}
