<?php

use App\Domain\Participant;
use App\Domain\RoleId;
use App\Domain\UserId;
use App\Persistence\CourseModel;
use App\Persistence\UserModel;
use App\Persistence\MembershipModel;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public static function normalUserId(): UserId
    {
        return new UserId("normaluser");
    }

    public static function instructorUserId(): UserId
    {
        return new UserId("instructor");
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(UserModel::class, 50)->create();

        // Set first 5 users as unpaid members
        $users->take(5)->each(function ($user) {
            $user->memberships()->save(factory(MembershipModel::class)->state('unpaid')->make());
        });
        // Set next 3 users as paid members
        $users->slice(5, 3)->each(function ($user) {
            $user->memberships()->save(factory(MembershipModel::class)->state('paid')->make());
        });

        $courses = factory(CourseModel::class, 10)->create();

        $normalUser = factory(UserModel::class)->make();
        $normalUser->id = self::normalUserId();
        $normalUser->save();

        $instructor = factory(UserModel::class)->make();
        $instructor->id = self::instructorUserId();
        $instructor->save();
        $instructor->roles()->attach(RoleId::instructor());

        $courses->each(function ($course) use ($users) {
            $randomUserIds = $users->random(12)->pluck('id');
            $instructors = $randomUserIds->take(2);
            $participants = $randomUserIds->take(-10);

            $participants->each(function ($userId) use ($course) {
                $course->participants()->attach($userId, [
                    'status' => collect([Participant::STATUS_PENDING, Participant::STATUS_CONFIRMED])->random(),
                    'role' => collect([Participant::ROLE_LEAD, Participant::ROLE_FOLLOW])->random()]);
            });

            $course->instructors()->attach($instructors);
        });
    }
}
