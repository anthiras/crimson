<?php

namespace App\Http\Controllers\API;

use App\Domain\Course;
use App\Domain\CourseId;
use App\Domain\CourseRepository;
use App\Domain\Schedule;
use App\Domain\UserId;
use App\Persistence\CourseModel;
use Cake\Chronos\Chronos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Course as CourseResource;
use App\Http\Resources\Id as IdResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $courses = CourseModel::all();

        $availableIncludes = collect(['instructors', 'participants']);
        $includes = $request->query('include');
        if ($includes)
        {
            $availableIncludes
                ->intersect($includes)
                ->each(function ($include) use ($courses) { 
                    $courses->load($include); 
                });
        }

        return CourseResource::collection($courses);
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
        return new CourseResource(CourseModel::with(['instructors', 'participants'])->find($courseId));
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
