<?php
namespace App\Domain;

use Cake\Chronos\Chronos;

class Schedule
{
	protected $startsAt;
    protected $weeks;
    protected $durationMinutes;

    public function __construct(Chronos $startsAt, int $weeks, int $durationMinutes)
    {
        $this->startsAt = $startsAt;
        $this->weeks = $weeks;
        $this->durationMinutes = $durationMinutes;
    }

    public function startsAt()
    {
        return $this->startsAt;
    }

    public function weeks()
    {
        return $this->weeks;
    }

    public function durationMinutes()
    {
        return $this->durationMinutes;
    }

    public function createLessons()
    {
    	return Lesson::createWeekly($this->startsAt(), $this->weeks(), $this->durationMinutes());
    }

//    public function equals(Schedule $other)
//    {
//        return
//            $this->startsAt() == $other->startsAt() &&
//            $this->weeks() == $other->weeks() &&
//            $this->durationMinutes() == $other->durationMinutes();
//    }
}