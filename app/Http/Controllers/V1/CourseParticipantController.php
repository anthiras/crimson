<?php

namespace App\Http\Controllers\V1;

use App\Domain\CourseId;
use App\Domain\CourseRepository;
use App\Http\Controllers\Controller;
use App\Queries\CourseParticipantQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $status = $course->participant($userId)->status();
        $this->courseRepository->save($course);
        return response()->json(['status' => $status]);
    }

    public function cancel(CourseId $courseId)
    {
        $userId = Auth::id();
        $course = $this->courseRepository->course($courseId)->cancelParticipant($userId);
        $status = $course->participant($userId)->status();
        $this->courseRepository->save($course);
        return response()->json(['status' => $status]);
    }
}