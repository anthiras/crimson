<?php

namespace App\Listeners;

use App\Domain\CourseRepository;
use App\Domain\Participant;
use App\Domain\UserRepository;
use App\Events\CourseParticipantStatusChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
            $this->trySendEmail($user->getEmail(), $user->getName(), new \App\Mail\CourseParticipantSignedUp($course, $user));
        }

        if ($event->status == Participant::STATUS_CONFIRMED) {
            $this->trySendEmail($user->getEmail(), $user->getName(), new \App\Mail\CourseParticipantConfirmed($course, $user));
        }
    }

    private function trySendEmail(string $email, string $name, Mailable $mail)
    {
        try {
            Mail::to($email, $name)->send($mail);
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}
