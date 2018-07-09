<?php
namespace App\Domain;

use Carbon\Carbon;
use Cake\Chronos\Chronos;

class Lesson
{
    protected $startsAt;
    protected $endsAt;

    public function __construct(Chronos $startsAt, Chronos $endsAt)
    {
        $this->startsAt = $startsAt;
        $this->endsAt = $endsAt;
    }

    public static function createWeekly(Chronos $startsAt, int $weeks, int $durationMinutes)
    {
        for ($week = 0; $week < $weeks; $week++)
        {
            $lessonStartsAt = $startsAt->addWeeks($week);
            $lessonEndsAt = $lessonStartsAt->addMinutes($durationMinutes);
            yield new Lesson($lessonStartsAt, $lessonEndsAt);
        }
    }

    public function startsAt()
    {
        return $this->startsAt;
    }

    public function endsAt()
    {
        return $this->endsAt;
    }
}