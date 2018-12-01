<?php

namespace App\Http\Controllers\V1;

use App\Domain\Course;
use App\Domain\CourseId;
use App\Domain\CourseRepository;
use App\Domain\Schedule;
use App\Domain\UserId;
use App\Queries\CourseQuery;
use Cake\Chronos\Chronos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource as CourseResource;
use App\Http\Resources\IdResource as IdResource;
use function PHPSTORM_META\map;

class CourseController extends Controller
{
    protected $courseRepository;
    protected $courseQuery;

    public function __construct(CourseRepository $courseRepository, CourseQuery $courseQuery)
    {
        $this->courseRepository = $courseRepository;
        $this->courseQuery = $courseQuery;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \App\Http\Resources\CourseResourceCollection
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
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Course::class);
        $schedule = new Schedule(
            Chronos::parse($request->startsAt),
            $request->weeks,
            $request->durationMinutes);
        $course = new Course(
            $this->courseRepository->nextId(),
            $request->name,
            $schedule,
            iterator_to_array($schedule->createLessons()),
            collect($request->instructors)->map(function ($id) { return new UserId($id); })->toArray(),
            array());
        $this->courseRepository->save($course);
        return new IdResource($course->id());
    }

    /**
     * Display the specified resource.
     *
     * @param  CourseId $courseId
     * @return CourseResource
     */
    public function show(CourseId $courseId)
    {
        $course = $this->courseQuery->show($courseId);
        $this->authorize('show', $course);
        return $course;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  CourseId $courseId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseId $courseId)
    {
        $course = $this->courseRepository->course($courseId);
        $course->setName($request->name);
        $this->courseRepository->save($course);
        return 204;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  CourseId $courseId
     * @return int
     */
    public function destroy($courseId)
    {
        $this->courseRepository->delete($courseId);

        return 204;
    }


}
