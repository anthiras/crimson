<?php

namespace App\Listeners;

use App\Domain\CourseRepository;
use App\Domain\Participant;
use App\Domain\UserRepository;
use App\Events\CourseParticipantStatusChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class CourseParticipantStatusChangedListener
{
    /**
     * @var CourseRepository
     */
    private $courseRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Create the event listener.
     *
     * @param CourseRepository $courseRepository
     * @param UserRepository $userRepository
     */
    public function __construct(CourseRepository $courseRepository, UserRepository $userRepository)
    {
        $this->courseRepository = $courseRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Handle the event.
     *
     * @param  CourseParticipantStatusChanged  $event
     * @return void
     */
    public function handle(CourseParticipantStatusChanged $event)
    {
        $user = $this->userRepository->user($event->userId);
        $course = $this->courseRepository->course($event->courseId);

        if ($event->status == Participant::STATUS_PENDING) {
            Mail::to($user->getEmail(), $user->getName())
                ->send(new \App\Mail\CourseParticipantSignedUp($course, $user));
        }

        if ($event->status == Participant::STATUS_CONFIRMED) {
            Mail::to($user->getEmail(), $user->getName())
                ->send(new \App\Mail\CourseParticipantConfirmed($course, $user));
        }
    }
}
