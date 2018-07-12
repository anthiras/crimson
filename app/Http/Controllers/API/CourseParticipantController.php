<?php

namespace App\Http\Controllers\API;

use App\Domain\CourseId;
use App\Domain\CourseRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CourseParticipantController extends Controller
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
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