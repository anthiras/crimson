<?php

namespace Tests\Unit;

use App\Domain\Schedule;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Cake\Chronos\Chronos;

class ScheduleTest extends TestCase
{
    public function testEquality(): void
    {
        $schedule1 = new Schedule(Chronos::create('2018-01-01'), 1, 1);
        $schedule2 = new Schedule(Chronos::create('2018-01-01'), 1, 1);
        $schedule3 = new Schedule(Chronos::create('2018-01-01'), 1, 2);
        $this->assertEquals($schedule1, $schedule2);
        $this->assertNotEquals($schedule1, $schedule3);
    }
}
