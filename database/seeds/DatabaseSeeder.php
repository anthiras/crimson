<?php

use App\Domain\Participant;
use App\Domain\UserId;
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
        $users = factory(\App\Persistence\UserModel::class, 50)->create();
        $courses = factory(\App\Persistence\CourseModel::class, 10)->create();

        $normalUser = factory(\App\Persistence\UserModel::class)->make();
        $normalUser->id = self::normalUserId();
        $normalUser->save();

        $instructor = factory(\App\Persistence\UserModel::class)->make();
        $instructor->id = self::instructorUserId();
        $instructor->save();
        $instructor->roles()->attach(\App\Domain\RoleId::instructor());

        $courses->each(function ($course) use ($users) {
            $randomUserIds = $users->random(12)->pluck('id');
            $instructors = $randomUserIds->take(2);
            $participants = $randomUserIds->take(-10);
            $course->participants()->attach($participants, 
                ['status' => Participant::STATUS_CONFIRMED, 'role' => Participant::ROLE_LEAD]);
            $course->instructors()->attach($instructors);
        });
    }
}
