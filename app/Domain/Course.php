<?php
namespace App\Domain;

use App\Events\CourseParticipantStatusChanged;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class Course extends AggregateRoot
{
    /**
     * @var CourseId
     */
    protected $courseId;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var Schedule
     */
    protected $schedule;
    /**
     * Lesson
     * @var Collection
     */
    protected $lessons;
    /**
     * UserId
     * @var Collection
     */
    protected $instructors;
    /**
     * Participant
     * @var Collection
     */
    protected $participants;
    /**
     * @var RegistrationSettings
     */
    protected $registrationSettings;

    /**
     * @var string
     */
    protected $description;

    public function __construct(CourseId $courseId, string $name, Schedule $schedule, array $lessons,
                                array $instructors, array $participants, RegistrationSettings $registrationSettings,
                                string $description = null)
    {
        $this->courseId = $courseId;
        $this->name = $name;
        $this->schedule = $schedule;
        $this->setInstructors($instructors);
        $this->participants = collect($participants)->verifyType(Participant::class)->keyBy([$this, 'participantKey']);
        $this->lessons = collect($lessons)->verifyType(Lesson::class);
        $this->registrationSettings = $registrationSettings;
        $this->description = $description;
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

    public function description()
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
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

    public function setSchedule(Schedule $schedule)
    {
        $this->schedule = $schedule;
        $this->lessons = collect($schedule->createLessons());
        return $this;
    }

    public function lessons()
    {
        return $this->lessons;
    }

    /**
     * @return Collection
     */
    public function getInstructors(): Collection
    {
        return $this->instructors;
    }

    /**
     * @param UserId $userId
     * @return Course
     */
    public function addInstructor(UserId $userId): Course
    {
        $this->instructors = $this->instructors->union($this->instructorItem($userId));
        return $this;
    }

    /**
     * @param UserId $userId
     * @return Course
     */
    public function removeInstructor(UserId $userId): Course
    {
        $this->instructors = $this->instructors->except($userId->string());
        return $this;
    }

    /**
     * @param array $instructors
     * @return Course
     */
    public function setInstructors(array $instructors): Course
    {
        $this->instructors = collect($instructors)->verifyType(UserId::class)->keyBy([$this, 'instructorKey']);
        return $this;
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

    /**
     * @return Collection
     */
    public function getParticipantsSorted()
    {
        return $this->participants->sortBy(function ($participant) { return $participant->getSignedUpAt(); });
    }

    /**
     * @return Collection
     */
    public function getPendingParticipants()
    {
        return $this->getParticipantsSorted()
            ->filterStatus(Participant::STATUS_PENDING);
    }

    /**
     * @return Collection
     */
    public function getConfirmedParticipants()
    {
        return $this->getParticipantsSorted()
            ->filterStatus(Participant::STATUS_CONFIRMED);
    }

    /**
     * @param UserId $userId
     * @return Participant
     */
    public function getParticipant(UserId $userId): Participant
    {
        return $this->participants()->get($userId->string());
    }

    public function participantKey(Participant $participant)
    {
        return $participant->getUserId()->string();
    }

    /**
     * @return RegistrationSettings
     */
    public function getRegistrationSettings(): RegistrationSettings
    {
        return $this->registrationSettings;
    }

    /**
     * @param RegistrationSettings $registrationSettings
     * @return Course
     */
    public function setRegistrationSettings(RegistrationSettings $registrationSettings): Course
    {
        $this->registrationSettings = $registrationSettings;

        $this->confirmAllPossibleParticipants();

        return $this;
    }

    /**
     * @param UserId $userId
     * @param string $role
     * @return $this
     * @throws ClosedForRegistration
     * @throws IllegalTransition
     */
    public function signUp(UserId $userId, string $role)
    {
        if (!$this->registrationSettings->getAllowRegistration())
        {
            throw new ClosedForRegistration("Course is closed for registration");
        }

        if ($this->participants->has($userId->string()))
        {
            $status = $this->getParticipant($userId)->getStatus();
            if ($status == Participant::STATUS_PENDING || $status == Participant::STATUS_CONFIRMED)
            {
                throw new IllegalTransition("Cannot sign up a user already signed up");
            }
            $this->participants = $this->participants->merge([$userId->string() => Participant::create($userId, $role)]);
        }
        else
        {
            $this->participants = $this->participants->union([$userId->string() => Participant::create($userId, $role)]);
        }

        $this->confirmAllPossibleParticipants();

        if ($this->getParticipant($userId)->getStatus() == Participant::STATUS_PENDING)
        {
            $this->registerEvent(new CourseParticipantStatusChanged($this->courseId, $userId, Participant::STATUS_PENDING));
        }

        return $this;
    }

    /**
     * @param UserId $userId
     * @return $this
     * @throws UserNotFound
     */
    public function confirmParticipant(UserId $userId)
    {
        if (!$this->participants->has($userId->string()))
        {
            throw new UserNotFound(sprintf("User %s is not signed up for the course", $userId));
        }

        $participant = $this->getParticipant($userId)->confirm();
        $this->participants = $this->participants->merge([$userId->string() => $participant]);

        $this->registerEvent(new CourseParticipantStatusChanged($this->courseId, $userId, Participant::STATUS_CONFIRMED));

        $this->confirmAllPossibleParticipants();

        return $this;
    }

    /**
     * @param UserId $userId
     * @return $this
     * @throws UserNotFound
     */
    public function cancelParticipant(UserId $userId)
    {
        if (!$this->participants->has($userId->string()))
        {
            throw new UserNotFound(sprintf("User %s is not signed up for the course", $userId));
        }

        $participant = $this->getParticipant($userId)->cancel();
        $this->participants = $this->participants->merge([$userId->string() => $participant]);

        $this->registerEvent(new CourseParticipantStatusChanged($this->courseId, $userId, Participant::STATUS_CANCELLED));

        $this->confirmAllPossibleParticipants();

        return $this;
    }

    /**
     * Automatically confirm all pending participants allowed by the registration rules
     */
    public function confirmAllPossibleParticipants()
    {
        if (!$this->registrationSettings->getAutoConfirm())
            return;

        $rules = collect(iterator_to_array($this->registrationSettings->iterateRules()));

        while (true) {
            $pendingParticipants = $this->getPendingParticipants();
            $confirmedParticipants = $this->getConfirmedParticipants();

            // Find next participant to be confirmed
            $nextParticipant = $pendingParticipants->first();

            // Break when there are no more pending participants
            if ($nextParticipant == null)
                break;

            // Find next participant of opposite role
            $nextOtherParticipant = $pendingParticipants
                ->filter(function ($p) use ($nextParticipant) { return $p->getRole() != $nextParticipant->getRole(); })
                ->first();

            $leadFollowerDiff = ParticipantStats::getLeadFollowerDifference($confirmedParticipants);
            $absRoleDiff = abs($leadFollowerDiff);
            $maxRoleDiff = $this->registrationSettings->getMaxRoleDifference();

            // Try confirm next participant
            $confirmed = $this->tryConfirmParticipants($nextParticipant, $rules);
            // If no luck, try confirm next participant of the opposite role
            if (!$confirmed && $nextOtherParticipant != null)
            {
                $confirmed = $this->tryConfirmParticipants($nextOtherParticipant, $rules);
            }
            // If still no luck, try confirm the couple at the same time
            if (!$confirmed && $nextOtherParticipant != null)
            {
                $confirmed = $this->tryConfirmParticipants([$nextParticipant, $nextOtherParticipant], $rules);
            }

            // If still no luck, and role difference is too high, try confirm multiple of the same role
            if (!$confirmed && $maxRoleDiff != null && $absRoleDiff > $maxRoleDiff)
            {
                $roleToConfirm = $leadFollowerDiff > 0 ? Participant::ROLE_FOLLOW : Participant::ROLE_LEAD;
                $participantsNeeded = $absRoleDiff - $maxRoleDiff;
                $participantsToConfirm = $pendingParticipants
                    ->filterRole($roleToConfirm)
                    ->take($participantsNeeded)
                    ->toArray();
                $confirmed = $this->tryConfirmParticipants($participantsToConfirm, $rules);
            }

            // Break when it is no longer possible to confirm anyone, otherwise loop to next participant
            if (!$confirmed)
                break;
        }
    }

    private function tryConfirmParticipants($participants, $rules): bool
    {
        if (!$this->registrationSettings->getAutoConfirm())
            return false;

        if (!is_array($participants))
        {
            $participants = [$participants];
        }
        $participants = collect($participants);

        $confirmedParticipants = $participants
            ->map(function ($p) { return $p->confirm(); })
            ->keyBy([$this, 'participantKey']);

        $updatedParticipants = $this->participants->merge($confirmedParticipants);

        if ($rules->every(function ($rule) use ($updatedParticipants) {
            return $rule->validate($updatedParticipants);
        }))
        {
            $this->participants = $updatedParticipants;
            foreach ($confirmedParticipants as $participant)
            {
                $this->registerEvent(new CourseParticipantStatusChanged($this->courseId, $participant->getUserId(), Participant::STATUS_CONFIRMED));
            }
            //Log::debug("Auto-confirmed users ".$participants->map(function ($p) { return $p->getUserId(); })->implode(', '));
            //print("Confirmed users");
            //print_r($updatedParticipants);
            return true;
        }
        return false;
    }

    /**
     * @param UserId $userId
     * @param string $amountPaid
     * @return $this
     * @throws UserNotFound
     */
    public function setParticipantAmountPaid(UserId $userId, string $amountPaid) : Course
    {
        if (!$this->participants->has($userId->string()))
        {
            throw new UserNotFound(sprintf("User %s is not signed up for the course", $userId));
        }

        $participant = $this->getParticipant($userId)->setAmountPaid($amountPaid);
        $this->participants = $this->participants->merge([$userId->string() => $participant]);

        return $this;
    }
}
