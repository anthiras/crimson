<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 06-02-2019
 * Time: 18:26
 */

namespace App\Persistence;


use App\Domain\CourseId;
use App\Http\Resources\UserResourceCollection;
use App\Queries\CourseParticipantQuery;

class DbCourseParticipantQuery implements CourseParticipantQuery
{

    public function list(CourseId $courseId): UserResourceCollection
    {
        $course = CourseModel::with(['participants'])->find($courseId);
        return new UserResourceCollection($course->participants()->get());
    }
}