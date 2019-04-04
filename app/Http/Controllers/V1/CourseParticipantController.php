<?php

namespace App\Http\Controllers\V1;

use App\Domain\ClosedForRegistration;
use App\Domain\CourseId;
use App\Domain\CourseRepository;
use App\Domain\IllegalTransition;
use App\Domain\UserId;
use App\Domain\UserNotFound;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
use App\Queries\CourseParticipantQuery;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseParticipantController extends Controller
{
    /**
     * @var CourseRepository
     */
    protected $courseRepository;

    /**
     * @var CourseParticipantQuery
     */
    protected $courseParticipantQuery;

    /**
     * CourseParticipantController constructor.
     * @param CourseRepository $courseRepository
     * @param CourseParticipantQuery $courseParticipantQuery
     */
    public function __construct(CourseRepository $courseRepository, CourseParticipantQuery $courseParticipantQuery)
    {
        $this->courseRepository = $courseRepository;
        $this->courseParticipantQuery = $courseParticipantQuery;
    }

    /**
     * @param CourseId $courseId
     * @return UserResourceCollection
     * @throws AuthorizationException
     */
    public function index(CourseId $courseId)
    {
        $this->authorize('showId', $courseId);
        return $this->courseParticipantQuery->list($courseId);
    }

    /**
     * @param Request $request
     * @param CourseId $courseId
     * @return UserResource
     * @throws ClosedForRegistration
     * @throws IllegalTransition
     */
    public function signUp(Request $request, CourseId $courseId)
    {
        $userId = Auth::id();
        $course = $this->courseRepository->course($courseId)->signUp($userId, $request->role);
        $this->courseRepository->save($course);
        return $this->courseParticipantQuery->show($courseId, $userId);
    }

    /**
     * @param CourseId $courseId
     * @return UserResource
     * @throws UserNotFound
     */
    public function cancelSignUp(CourseId $courseId)
    {
        $userId = Auth::id();
        $course = $this->courseRepository->course($courseId)->cancelParticipant($userId);
        $this->courseRepository->save($course);
        return $this->courseParticipantQuery->show($courseId, $userId);
    }

    /**
     * @param CourseId $courseId
     * @param UserId $userId
     * @return UserResource
     * @throws AuthorizationException
     * @throws UserNotFound
     */
    public function confirm(CourseId $courseId, UserId $userId)
    {
        $course = $this->courseRepository->course($courseId);
        $this->authorize('manageParticipants', $course);
        $course = $course->confirmParticipant($userId);
        $this->courseRepository->save($course);
        return $this->courseParticipantQuery->show($courseId, $userId);
    }

    /**
     * @param CourseId $courseId
     * @param UserId $userId
     * @return UserResource
     * @throws AuthorizationException
     * @throws UserNotFound
     */
    public function cancel(CourseId $courseId, UserId $userId)
    {
        $course = $this->courseRepository->course($courseId);
        $this->authorize('manageParticipants', $course);
        $course = $course->cancelParticipant($userId);
        $this->courseRepository->save($course);
        return $this->courseParticipantQuery->show($courseId, $userId);
    }
}