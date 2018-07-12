<?php

use App\Domain\Participant;
use Illuminate\Database\Seeder;
use Webpatser\Uuid\Uuid;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(\App\Persistence\UserModel::class, 50)->create();
        $courses = factory(\App\Persistence\CourseModel::class, 10)->create();

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
