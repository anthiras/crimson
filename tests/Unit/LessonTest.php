<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Lesson;
use Carbon\Carbon;
use Cake\Chronos\Chronos;

class LessonTest extends TestCase
{
    public function testCreateWeekly(): void
    {
        $startsAt = Chronos::create(2018, 1, 1, 19, 0, 0);
        $weeks = 3;
        $durationMinutes = 90;
        $lessons = iterator_to_array(Lesson::createWeekly($startsAt, $weeks, $durationMinutes));

        $expectedStart1 = $startsAt;
        $expectedStart2 = Chronos::create(2018, 1, 8, 19, 0, 0);
        $expectedStart3 = Chronos::create(2018, 1, 15, 19, 0, 0);

        $this->assertCount($weeks, $lessons);
        $this->assertEquals($expectedStart1, $lessons[0]->startsAt());
        $this->assertEquals($expectedStart2, $lessons[1]->startsAt());
        $this->assertEquals($expectedStart3, $lessons[2]->startsAt());
    }
}
