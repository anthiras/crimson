<?php
namespace App\Domain;

class Course extends AggregateRoot
{
    protected $courseId;
    protected $name;
    protected $schedule;
    protected $lessons;
    protected $instructors;
    protected $participants;

    public function __construct(CourseId $courseId, string $name, Schedule $schedule, array $lessons, array $instructors, array $participants)
    {
        $this->courseId = $courseId;
        $this->name = $name;
        $this->schedule = $schedule;
        $this->instructors = collect($instructors)->keyBy([$this, 'instructorKey']);
        $this->participants = collect($participants)->keyBy([$this, 'participantKey']);
        $this->lessons = collect($lessons);
    }

    public function id()
    {
        return $this->courseId;
    }

    public function name()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function startsAt()
    {
        return $this->lessons()->map->startsAt()->min();
    }

    public function endsAt()
    {
        return $this->lessons()->map->endsAt()->max();
    }

    public function schedule()
    {
        return $this->schedule;
    }

    public function lessons()
    {
        return $this->lessons;
    }

    public function instructors()
    {
        return $this->instructors;
    }

    public function addInstructor(UserId $userId)
    {
        $this->instructors = $this->instructors->union($this->instructorItem($userId));
    }

    public function removeInstructor(UserId $userId)
    {
        $this->instructors = $this->instructors->except($userId->string());
    }

    protected function instructorItem(UserId $userId)
    {
        return [$this->instructorKey($userId) => $userId];
    }

    public function instructorKey(UserId $userId)
    {
        return $userId->__toString();
    }

    public function participants()
    {
        return $this->participants;
    }

    public function participant(UserId $userId)
    {
        return $this->participants()->get($userId->string());
    }

    public function participantKey(Participant $participant)
    {
        return $participant->userId()->string();
    }

    public function signUp(UserId $userId)
    {
        if ($this->participants->has($userId->string()))
        {
            $this->participants = $this->participants->merge([$userId->string() => Participant::create($userId)]);
            return $this;
        }

        $this->participants = $this->participants->union([$userId->string() => Participant::create($userId)]);
        return $this;
    }

    public function confirmParticipant(UserId $userId)
    {
        if (!$this->participants->has($userId->string()))
        {
            throw new UserNotFound(sprintf("User %s is not signed up for the course", $userId));
        }

        $participant = $this->participant($userId)->confirm();
        $this->participants = $this->participants->merge([$userId->string() => $participant]);
        return $this;
    }

    public function cancelParticipant(UserId $userId)
    {
        if (!$this->participants->has($userId->string()))
        {
            throw new UserNotFound(sprintf("User %s is not signed up for the course", $userId));
        }

        $participant = $this->participant($userId)->cancel();
        $this->participants = $this->participants->merge([$userId->string() => $participant]);
        return $this;
    }
}
