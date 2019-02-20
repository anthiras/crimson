<?php

namespace App\Mail;

use App\Domain\Course;
use App\Domain\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CourseParticipantConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    protected $course;
    protected $user;

    /**
     * Create a new message instance.
     *
     * @param Course $course
     * @param User $user
     */
    public function __construct(Course $course, User $user)
    {
        $this->course = $course;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.courseparticipants.confirmed')
            ->subject("Tilmelding bekræftet / Registration approved")
            ->with([
                'courseName' => $this->course->name(),
                'userName' => $this->user->getName()
            ]);
    }
}
