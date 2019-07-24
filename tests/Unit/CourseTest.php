<?php

namespace Tests\Unit;

use App\Domain\ClosedForRegistration;
use App\Domain\IllegalTransition;
use App\Domain\UserId;
use App\Domain\Participant;
use App\Domain\UserNotFound;
use Illuminate\Support\Collection;
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

    /**
     * @dataProvider roleDifferenceProvider
     * @param $confirmedLeads
     * @param $confirmedFollowers
     * @param $pendingLeads
     * @param $pendingFollowers
     * @param $maxRoleDiff
     * @param $maxParticipants
     * @param $expectedConfirmedLeads
     * @param $expectedConfirmedFollowers
     */
    public function testRoleDifference($confirmedLeads, $confirmedFollowers, $pendingLeads, $pendingFollowers, $maxRoleDiff, $maxParticipants, $expectedConfirmedLeads, $expectedConfirmedFollowers)
    {
        $participantBuilder = $this->participantBuilder;

        $confirmedLeadParticipants = Collection::times($confirmedLeads, function() use ($participantBuilder) {
            return $participantBuilder
                ->withStatus(Participant::STATUS_CONFIRMED)
                ->withRole(Participant::ROLE_LEAD)
                ->build();
        });

        $pendingLeadParticipants = Collection::times($pendingLeads, function() use ($participantBuilder) {
            return $participantBuilder
                ->withStatus(Participant::STATUS_PENDING)
                ->withRole(Participant::ROLE_LEAD)
                ->build();
        });

        $confirmedFollowerParticipants = Collection::times($confirmedFollowers, function() use ($participantBuilder) {
            return $participantBuilder
                ->withStatus(Participant::STATUS_CONFIRMED)
                ->withRole(Participant::ROLE_FOLLOW)
                ->build();
        });

        $pendingFollowerParticipants = Collection::times($pendingFollowers, function() use ($participantBuilder) {
            return $participantBuilder
                ->withStatus(Participant::STATUS_PENDING)
                ->withRole(Participant::ROLE_FOLLOW)
                ->build();
        });

        $allParticipants = $confirmedLeadParticipants
            ->concat($confirmedFollowerParticipants)
            ->concat($pendingLeadParticipants)
            ->concat($pendingFollowerParticipants)
            ->toArray();

        $course = $this->courseBuilder
            ->withParticipants($allParticipants)
            ->withRegistrationSettings(
                $this->registrationSettingsBuilder
                    ->allowRegistration(true)
                    ->autoConfirm()
                    ->withMaxRoleDifference($maxRoleDiff)
                    ->withMaxParticipants($maxParticipants)
                    ->build())
            ->build();

        $course->confirmAllPossibleParticipants();

        $leadsConfirmed = $pendingLeadParticipants->filter(function ($participant) use ($course) {
            return $course->getParticipant($participant->getUserId())->getStatus() == Participant::STATUS_CONFIRMED;
        });

        $followersConfirmed = $pendingFollowerParticipants->filter(function ($participant) use ($course) {
            return $course->getParticipant($participant->getUserId())->getStatus() == Participant::STATUS_CONFIRMED;
        });

        $this->assertEquals($expectedConfirmedLeads, $leadsConfirmed->count(),
            "Expected $expectedConfirmedLeads of the pending leads to be confirmed");

        $this->assertEquals($expectedConfirmedFollowers, $followersConfirmed->count(),
            "Expected $expectedConfirmedFollowers of the pending followers to be confirmed");
    }

    public function roleDifferenceProvider()
    {
        // $confirmedLeads, $confirmedFollowers, $pendingLeads, $pendingFollowers, $maxRoleDiff, $maxParticipants, $expectedConfirmedLeads, $expectedConfirmedFollowers
        return [
            'zero role diff, cannot confirmed one pending'                  => [0, 0, 1, 0, 0, null, 0, 0],
            'zero role diff, confirm pair'                                  => [0, 0, 1, 1, 0, null, 1, 1],
            'role diff 2, confirm 2 of 3 followers'                         => [0, 0, 0, 3, 2, null, 0, 2],
            'role diff 2, confirm 1 lead and 3 of 4 followers'              => [0, 0, 1, 4, 2, null, 1, 3],
            'confirm two followers to reduce role difference from 4 to 2'   => [4, 0, 0, 2, 2, null, 0, 2],
            'max role diff 4, confirm 4 of 5 followers'                     => [0, 0, 0, 5, 4, null, 0, 4],
            'confirm 2 of 3 leads, 4 already confirmed, max 6'              => [4, 0, 3, 0, null, 6, 2, 0],
            'role diff 2, but max participants 1, confirm 1'                => [0, 0, 2, 0, 2, 1, 1, 0]
        ];
    }

    public function testSetParticipantAmount()
    {
        $course = CourseBuilder::buildRandom();
        $userId = $course->participants()->first()->getUserId();
        $amountPaid = "123.45";
        $course->setParticipantAmountPaid($userId, $amountPaid);

        $this->assertEquals($amountPaid, $course->getParticipant($userId)->getAmountPaid());
    }
}
