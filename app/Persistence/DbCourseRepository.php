<?php
namespace App\Persistence;

use App\Domain\Course;
use App\Domain\CourseId;
use App\Domain\CourseRepository;
use App\Domain\CourseNotFound;
use App\Persistence\CourseModel;
use Illuminate\Support\Collection;

class DbCourseRepository implements CourseRepository
{
    const RELATED_TABLES = ['instructors', 'participants'];

    public function course(CourseId $courseId): Course
    {
        $courseModel = CourseModel::with(self::RELATED_TABLES)
            ->find($courseId);
        if ($courseModel == null)
        {
            throw new CourseNotFound();
            
        }
        return CourseFactory::createCourse($courseModel);
    }

    public function courses(): Collection
    {
        return CourseModel::with(self::RELATED_TABLES)
            ->get()
            ->map([CourseFactory::class, 'createCourse']);
    }

    public function save(Course $course)
    {
        $courseModel = CourseModel::updateOrCreate(
            ["id" => $course->id()],
            CourseToDb::map($course));
        
        $courseModel->instructors()->sync($course->getInstructors()->map->string());
        $courseModel->participants()->sync($course->participants()->mapWithKeys([CourseToDb::class, 'mapParticipant']));

        $courseModel->version++;

        $courseModel->save();

        $course->dispatchEvents();
    }

    public function delete(CourseId $courseId)
    {
        CourseModel::destroy($courseId);
    }

    public function nextId(): CourseId
    {
        return CourseId::create();
    }
}