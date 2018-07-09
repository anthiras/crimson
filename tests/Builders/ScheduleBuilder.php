<?php
namespace Tests\Builders;

use App\Domain\Schedule;
use Faker\Factory;
use Carbon\Carbon;
use Cake\Chronos\Chronos;

class ScheduleBuilder
{
	public static function build()
	{
		$faker = Factory::create();
        $startTime = Chronos::createFromTimestamp($faker->unixTime());
        $weeks = $faker->numberBetween(1,20);
        $durationMinutes = $faker->numberBetween(1,24)*5;
		return new Schedule($startTime, $weeks, $durationMinutes);
	}
}