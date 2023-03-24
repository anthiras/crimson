<?php

namespace App\Http\Controllers\V1;

use App\Domain\Course;
use App\Domain\CourseId;
use App\Domain\CourseRepository;
use App\Domain\RegistrationSettings;
use App\Domain\Schedule;
use App\Domain\UserId;
use App\Domain\Participant;
use App\Http\Resources\CourseResourceCollection;
use App\Mail\CourseNotification;
use App\Queries\CourseQuery;
use App\Queries\CourseParticipantQuery;
use Cake\Chronos\Chronos;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource as CourseResource;
use App\Http\Resources\IdResource as IdResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

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
     * @var CourseParticipantQuery
     */
    protected $courseParticipationQuery;

    /**
     * CourseController constructor.
     * @param CourseRepository $courseRepository
     * @param CourseQuery $courseQuery
     * @param CourseParticipantQuery $courseParticipationQuery
     */
    public function __construct(CourseRepository $courseRepository, CourseQuery $courseQuery, CourseParticipantQuery $courseParticipationQuery)
    {
        $this->courseRepository = $courseRepository;
        $this->courseQuery = $courseQuery;
        $this->courseParticipationQuery = $courseParticipationQuery;
    }

    /**
     * List courses
     *
     * @param Request $request
     * @return CourseResourceCollection
     */
    public function index(Request $request)
    {
        $userId = $request->query('mine') ? Auth::id() : null;

        return $this->listCourses($request, $userId)
            ->additional(['links' => ['ics' => $this->buildICalUrl($request, $userId)]]);
    }

    /**
     * List courses in iCalendar (.ics) format
     * 
     * @param Request $request
     */
    public function ical(Request $request) {
        $userId = $request->query('mine') && $request->query('userId') ? new UserId($request->query('userId')) : null;

        $courses = $this->listCourses($request, $userId);

        return response()->streamDownload(
            function () use ($courses) {
                $courses->echoICalString();
            }, 
            'courses.ics', 
            ['Content-Type' => 'text/calendar']);
    }

    private function buildICalUrl(Request $request, UserId $userId = null)
    {
        // Use same parameters for the iCal URL, except paging, and add userID if it's a personal list
        $iCalParams = $request->all();
        unset($iCalParams['page']);

        if ($userId != null)
        {
            $iCalParams['userId'] = $userId->__toString();
        }

        return URL::signedRoute('courses.ical', $iCalParams);
    }

    private function listCourses(Request $request, UserId $userId = null)
    {
        return $this->courseQuery->list(
            $request->query('include'),
            $this->parseDate($request, 'startsBefore'),
            $this->parseDate($request, 'startsAfter'),
            $this->parseDate($request, 'endsBefore'),
            $this->parseDate($request, 'endsAfter'),
            $userId,
            $request->query('direction') == 'desc');
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
            $registrationSettings,
            $request->description);

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
        
        if ($request->has('description')) {
            $course = $course->setDescription($request->description);
        }

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

    /**
     * Send notification to all (pending/confirmed) participants in the course
     * 
     * @param Request $request
     * @param CourseId $courseId
     * @return int
     * @throws AuthorizationException
     */
    public function notify(Request $request, CourseId $courseId)
    {
        $message = $request->message;
        $course = $this->courseRepository->course($courseId);
        $this->authorize('sendNotification', $course);

        $mails = $this->courseParticipationQuery
            ->list($courseId, [Participant::STATUS_PENDING, Participant::STATUS_CONFIRMED])
            ->map(function ($participant) use ($course, $message) {
                return new CourseNotification($course, $message, $participant);
            });

        foreach ($mails as $mail)
        {
            Mail::to($mail->getEmail(), $mail->getName())->send($mail);
        }
        
        return 204;
    }
}
