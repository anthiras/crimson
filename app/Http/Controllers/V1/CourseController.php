<?php

namespace App\Http\Controllers\V1;

use App\Domain\Course;
use App\Domain\CourseId;
use App\Domain\CourseRepository;
use App\Domain\RegistrationSettings;
use App\Domain\Schedule;
use App\Domain\UserId;
use App\Http\Resources\CourseResourceCollection;
use App\Queries\CourseQuery;
use Cake\Chronos\Chronos;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource as CourseResource;
use App\Http\Resources\IdResource as IdResource;
use Illuminate\Http\Response;

class CourseController extends Controller
{
    /**
     * @var CourseRepository
     */
    protected $courseRepository;

    /**
     * @var CourseQuery
     */
    protected $courseQuery;

    /**
     * CourseController constructor.
     * @param CourseRepository $courseRepository
     * @param CourseQuery $courseQuery
     */
    public function __construct(CourseRepository $courseRepository, CourseQuery $courseQuery)
    {
        $this->courseRepository = $courseRepository;
        $this->courseQuery = $courseQuery;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return CourseResourceCollection
     */
    public function index(Request $request)
    {
        return $this->courseQuery->list(
            $request->query('include'),
            $this->parseDate($request, 'startsBefore'),
            $this->parseDate($request, 'startsAfter'),
            $this->parseDate($request, 'endsBefore'),
            $this->parseDate($request, 'endsAfter'));
    }

    private function parseDate(Request $request, string $key): ?Chronos
    {
        return $request->has($key) ? Chronos::parse($request->query($key)) : null;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return mixed
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Course::class);

        $schedule = new Schedule(
            Chronos::parse($request->startsAt),
            $request->weeks,
            $request->durationMinutes);

        $registrationSettings = new RegistrationSettings(
            $request->allowRegistration,
            $request->autoConfirm,
            $request->maxParticipants,
            $request->maxRoleDifference);

        $course = new Course(
            $this->courseRepository->nextId(),
            $request->name,
            $schedule,
            iterator_to_array($schedule->createLessons()),
            collect($request->instructors)->map(function ($id) { return new UserId($id); })->toArray(),
            array(),
            $registrationSettings);

        $this->courseRepository->save($course);

        return new IdResource($course->id());
    }

    /**
     * Display the specified resource.
     *
     * @param  CourseId $courseId
     * @return CourseResource
     * @throws AuthorizationException
     */
    public function show(CourseId $courseId)
    {
        $course = $this->courseQuery->show($courseId);
        $this->authorize('showResource', $course);
        return $course;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param CourseId $courseId
     * @return int
     * @throws AuthorizationException
     */
    public function update(Request $request, CourseId $courseId)
    {
        $course = $this->courseRepository->course($courseId);

        $this->authorize('update', $course);

        $schedule = new Schedule(
            Chronos::parse($request->startsAt),
            $request->weeks,
            $request->durationMinutes);

        $registrationSettings = new RegistrationSettings(
            $request->allowRegistration,
            $request->autoConfirm,
            $request->maxParticipants,
            $request->maxRoleDifference);

        $course->setName($request->name)
            ->setSchedule($schedule)
            ->setRegistrationSettings($registrationSettings)
            ->setInstructors(collect($request->instructors)->map(function ($id) { return new UserId($id); })->toArray());

        $this->courseRepository->save($course);

        return 204;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  CourseId $courseId
     * @return int
     * @throws AuthorizationException
     */
    public function destroy(CourseId $courseId)
    {
        $course = $this->courseRepository->course($courseId);
        $this->authorize('delete', $course);
        $this->courseRepository->delete($courseId);

        return 204;
    }


}
