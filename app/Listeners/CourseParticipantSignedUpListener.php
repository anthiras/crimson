<?php

namespace App\Listeners;

use App\Domain\CourseRepository;
use App\Domain\UserRepository;
use App\Events\CourseParticipantSignedUp;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class CourseParticipantSignedUpListener
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
     * @param  CourseParticipantSignedUp  $event
     * @return void
     */
    public function handle(CourseParticipantSignedUp $event)
    {
        $user = $this->userRepository->user($event->userId);
        $course = $this->courseRepository->course($event->courseId);
        Mail::to($user->getEmail(), $user->getName())
            ->send(new \App\Mail\CourseParticipantSignedUp($course, $user));
    }
}
