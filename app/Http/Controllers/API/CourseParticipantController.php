<?php

namespace App\Http\Controllers\API;

use App\Domain\CourseId;
use App\Domain\CourseRepository;
use Illuminate\Support\Facades\Auth;

class CourseParticipantController
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function signUp(CourseId $courseId)
    {
        $this->courseRepository->save(
            $this->courseRepository->course($courseId)->signUp(Auth::id()));
        return 204;
    }

    public function cancel(CourseId $courseId)
    {
        $this->courseRepository->save(
            $this->courseRepository->course($courseId)->cancelParticipant(Auth::id()));
        return 204;
    }
}