<?php

namespace Tests\Unit;

use App\Domain\ClosedForRegistration;
use App\Domain\IllegalTransition;
use App\Domain\UserId;
use App\Domain\Participant;
use App\Domain\UserNotFound;
use Tests\Builders\ParticipantBuilder;
use Tests\Builders\RegistrationSettingsBuilder;
use Tests\TestCase;
use Tests\Builders\CourseBuilder;
use Tests\Builders\LessonBuilder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Cake\Chronos\Chronos;

class CourseTest extends TestCase
{
    /**
     * @var CourseBuilder
     */
    protected $courseBuilder;

    /**
     * @var ParticipantBuilder
     */
    protected $participantBuilder;

    /**
     * @var RegistrationSettingsBuilder
     */
    protected $registrationSettingsBuilder;

    protected function setUp()
    {
        parent::setUp();
        $this->courseBuilder = new CourseBuilder();
        $this->participantBuilder = new ParticipantBuilder();
        $this->registrationSettingsBuilder = new RegistrationSettingsBuilder();
    }

    public function testStartAndEnd()
    {
        $firstStartTime = Chronos::create(2018, 1, 1, 19, 0, 0);
        $firstEndTime = Chronos::create(2018, 1, 1, 20, 0, 0);
        $firstLesson = LessonBuilder::build($firstStartTime, $firstEndTime);

        $lastStartTime = Chronos::create(2018, 2, 1, 19, 0, 0);
        $lastEndTime = Chronos::create(2018, 2, 1, 20, 0, 0);
        $lastLesson = LessonBuilder::build($lastStartTime, $lastEndTime);

        $course = $this->courseBuilder
            ->withLessons(array($lastLesson, $firstLesson))
            ->build();

        $this->assertEquals($firstStartTime, $course->startsAt(), "Expected course start to be start time of first lesson");
        $this->assertEquals($lastEndTime, $course->endsAt(), "Expected course end to be end time of last lesson");
    }

    public function testInstructors()
    {
        $course = CourseBuilder::buildRandom();
        $instructorId = UserId::create();
        
        $course->addInstructor($instructorId);
        $this->assertTrue($course->getInstructors()->contains($instructorId), "Expected instructorId to be in course instructors");
        
        $course->removeInstructor($instructorId);
        $this->assertFalse($course->getInstructors()->contains($instructorId), "Did not expect instructorId to be in course instructors");
    }

    public function testSignUpConfirmCancel()
    {
        $course = CourseBuilder::buildRandom();
        $userId = UserId::create();

        $course->signUp($userId, Participant::ROLE_LEAD);
        $this->assertEquals(Participant::STATUS_PENDING, $course->getParticipant($userId)->getStatus(),
            "Expected user to be signed up pending confirmation");

        $course->confirmParticipant($userId);
        $this->assertEquals(Participant::STATUS_CONFIRMED, $course->getParticipant($userId)->getStatus(),
            "Expected user to be confirmed as a participant");

        $course->cancelParticipant($userId);
        $this->assertEquals(Participant::STATUS_CANCELLED, $course->getParticipant($userId)->getStatus(),
            "Expected user to be confirmed as a participant");
    }

    public function testErrorSigningUpWhenRegistrationIsClosed()
    {
        $this->expectException(ClosedForRegistration::class);

        $course = $this->courseBuilder
            ->withRegistrationSettings($this->registrationSettingsBuilder->allowRegistration(false)->build())
            ->build();

        $course->signUp(UserId::create(), Participant::ROLE_LEAD);
    }

    public function testErrorConfirmingNonExistingParticipant()
    {
        $this->expectException(UserNotFound::class);
        $course = CourseBuilder::buildRandom();
        $course->confirmParticipant(UserId::create());
    }

    public function testErrorSigningUpWhenAlreadySignedUp()
    {
        $this->expectException(IllegalTransition::class);

        $course = CourseBuilder::buildRandom();
        $userId = UserId::create();

        $course->signUp($userId, Participant::ROLE_LEAD);
        $course->signUp($userId, Participant::ROLE_LEAD);
    }

    public function testCanSignUpAgainAfterCancelled()
    {
        $course = CourseBuilder::buildRandom();
        $userId = UserId::create();

        $course->signUp($userId, Participant::ROLE_LEAD);
        $course->cancelParticipant($userId);
        $course->signUp($userId, Participant::ROLE_FOLLOW);

        $this->assertEquals(Participant::STATUS_PENDING, $course->getParticipant($userId)->getStatus());
        $this->assertEquals(Participant::ROLE_FOLLOW, $course->getParticipant($userId)->getRole());
    }

    public function testFirstPendingParticipantIsConfirmed()
    {
        $participantBuilder = $this->participantBuilder->withStatus(Participant::STATUS_PENDING);
        $p1 = $participantBuilder->withSignedUpAt(Chronos::yesterday())->build();
        $p2 = $participantBuilder->withSignedUpAt(Chronos::today())->build();
        $p3 = $participantBuilder->withSignedUpAt(Chronos::tomorrow())->build();

        $course = $this->courseBuilder
            ->withParticipants([$p2, $p1, $p3])
            ->withRegistrationSettings(
                $this->registrationSettingsBuilder
                    ->autoConfirmMaxParticipants(1)
                    ->build())
            ->build();

        $course->confirmAllPossibleParticipants();

        $this->assertEquals(Participant::STATUS_CONFIRMED,
            $course->getParticipant($p1->getUserId())->getStatus(),
            "Expected the first signup to be confirmed");
        $this->assertEquals(Participant::STATUS_PENDING,
            $course->getParticipant($p2->getUserId())->getStatus(),
            "Did not expect the second signup to be confirmed");
        $this->assertEquals(Participant::STATUS_PENDING,
            $course->getParticipant($p3->getUserId())->getStatus(),
            "Did not expect the last participant to be confirmed");
    }

    public function testMaxParticipantsAreConfirmed()
    {
        $p1 = $this->participantBuilder->withStatus(Participant::STATUS_CONFIRMED)->withSignedUpAt(Chronos::yesterday())->build();
        $p2 = $this->participantBuilder->withStatus(Participant::STATUS_PENDING)->withSignedUpAt(Chronos::yesterday())->build();
        $p3 = $this->participantBuilder->withStatus(Participant::STATUS_PENDING)->withSignedUpAt(Chronos::yesterday())->build();
        $p4 = $this->participantBuilder->withStatus(Participant::STATUS_PENDING)->withSignedUpAt(Chronos::today())->build();

        $course = $this->courseBuilder
            ->withParticipants([$p1, $p2, $p3, $p4])
            ->withRegistrationSettings(
                $this->registrationSettingsBuilder
                    ->autoConfirmMaxParticipants(3)
                    ->build())
            ->build();

        $course->confirmAllPossibleParticipants();

        $this->assertEquals(Participant::STATUS_CONFIRMED, $course->getParticipant($p1->getUserId())->getStatus());
        $this->assertEquals(Participant::STATUS_CONFIRMED, $course->getParticipant($p2->getUserId())->getStatus());
        $this->assertEquals(Participant::STATUS_CONFIRMED, $course->getParticipant($p3->getUserId())->getStatus());
        $this->assertEquals(Participant::STATUS_PENDING, $course->getParticipant($p4->getUserId())->getStatus());
    }

    public function testParticipantsConfirmedInCouplesWhenZeroRoleDifference()
    {
        $lead1 = $this->participantBuilder
            ->withStatus(Participant::STATUS_PENDING)
            ->withRole(Participant::ROLE_LEAD)
            ->withSignedUpAt(Chronos::yesterday())
            ->build();

        $lead2 = $this->participantBuilder
            ->withStatus(Participant::STATUS_PENDING)
            ->withRole(Participant::ROLE_LEAD)
            ->withSignedUpAt(Chronos::yesterday())
            ->build();

        $course = $this->courseBuilder
            ->withParticipants([$lead1, $lead2])
            ->withRegistrationSettings(
                $this->registrationSettingsBuilder
                    ->allowRegistration(true)
                    ->autoConfirmMaxRoleDifference(0)
                    ->build())
            ->build();

        $course->confirmAllPossibleParticipants();

        $this->assertEquals(0,
            $course->getConfirmedParticipants()->count(),
            "No confirms expected because it would cause unequal role counts");

        $follow1 = UserId::create();
        $course->signUp($follow1, Participant::ROLE_FOLLOW);
        $course->confirmAllPossibleParticipants();

        $this->assertEquals(2,
            $course->getConfirmedParticipants()->count(),
            "Expected first couple to be confirmed");
        $this->assertEquals(Participant::STATUS_CONFIRMED, $course->getParticipant($lead1->getUserId())->getStatus());
        $this->assertEquals(Participant::STATUS_CONFIRMED, $course->getParticipant($follow1)->getStatus());
        $this->assertEquals(Participant::STATUS_PENDING, $course->getParticipant($lead2->getUserId())->getStatus());
    }
}
