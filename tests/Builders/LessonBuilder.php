<?php
namespace Tests\Builders;

use App\Domain\Lesson;
use Carbon\Carbon;
use Cake\Chronos\Chronos;
use Faker\Factory;

class LessonBuilder
{
    public static function build(Chronos $startTime = null, Chronos $endTime = null)
    {
        $faker = Factory::create();
        if ($startTime == null)
        {
        	$startTime = Chronos::createFromTimestamp($faker->unixTime());
    	}
    	if ($endTime == null)
    	{
        	$endTime = $startTime->addMinutes($faker->numberBetween(1,24)*5);
        }
        return new Lesson($startTime, $endTime);
    }
}