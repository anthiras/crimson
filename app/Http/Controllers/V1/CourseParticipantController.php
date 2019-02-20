<?php

namespace App\Http\Controllers\V1;

use App\Domain\CourseId;
use App\Domain\CourseRepository;
use App\Domain\UserId;
use App\Events\CourseParticipantStatusChanged;
use App\Http\Controllers\Controller;
use App\Queries\CourseParticipantQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CourseParticipantController extends Controller
{
    protected $courseRepository;
    protected $courseParticipantQuery;

    public function __construct(CourseRepository $courseRepository, CourseParticipantQuery $courseParticipantQuery)
    {
        $this->courseRepository = $courseRepository;
        $this->courseParticipantQuery = $courseParticipantQuery;
    }

    public function index(CourseId $courseId)
    {
        $this->authorize('showId', $courseId);
        return $this->courseParticipantQuery->list($courseId);
    }

    public function signUp(Request $request, CourseId $courseId)
    {
        $userId = Auth::id();
        $course = $this->courseRepository->course($courseId)->signUp($userId, $request->role);
        $participant = $course->participant($userId);
        $status = $participant->status();
        $this->courseRepository->save($course);
        event(new CourseParticipantStatusChanged($courseId, $userId, $status));
        return $this->courseParticipantQuery->show($courseId, $userId);
    }

    public function cancelSignUp(CourseId $courseId)
    {
        $userId = Auth::id();
        $course = $this->courseRepository->course($courseId)->cancelParticipant($userId);
        $status = $course->participant($userId)->status();
        $this->courseRepository->save($course);
        event(new CourseParticipantStatusChanged($courseId, $userId, $status));
        return $this->courseParticipantQuery->show($courseId, $userId);
    }

    public function confirm(CourseId $courseId, UserId $userId)
    {
        $course = $this->courseRepository->course($courseId);
        $this->authorize('manageParticipants', $course);
        $course = $course->confirmParticipant($userId);
        $status = $course->participant($userId)->status();
        $this->courseRepository->save($course);
        event(new CourseParticipantStatusChanged($courseId, $userId, $status));
        return $this->courseParticipantQuery->show($courseId, $userId);
    }

    public function cancel(CourseId $courseId, UserId $userId)
    {
        $course = $this->courseRepository->course($courseId);
        $this->authorize('manageParticipants', $course);
        $course = $course->cancelParticipant($userId);
        $status = $course->participant($userId)->status();
        $this->courseRepository->save($course);
        event(new CourseParticipantStatusChanged($courseId, $userId, $status));
        return $this->courseParticipantQuery->show($courseId, $userId);
    }
}