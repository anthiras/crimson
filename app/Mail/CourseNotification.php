<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Domain\Course;
use App\Http\Resources\UserResource;

class CourseNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Course
     */
    protected $course;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var UserResource
     */
    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Course $course, string $message, UserResource $user)
    {
        $this->course = $course;
        $this->message = $message;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.courses.notification')
        ->subject("Besked til holdet / Message to the class")
        ->with([
            'courseName' => $this->course->name(),
            'message' => $this->message,
            'userName' => $this->user->name
        ]);
    }

    public function getName(): string
    {
        return $this->user->name;
    }

    public function getEmail(): string
    {
        return $this->user->email;
    }
}
