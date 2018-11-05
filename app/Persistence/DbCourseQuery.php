<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 05-11-2018
 * Time: 20:29
 */

namespace App\Persistence;


use App\Domain\CourseId;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseResourceCollection;
use App\Queries\CourseQuery;

class DbCourseQuery implements CourseQuery
{

    public function show(CourseId $courseId): CourseResource
    {
        return new CourseResource(CourseModel::with(['instructors', 'participants'])->find($courseId));
    }

    public function list($includes): CourseResourceCollection
    {
        $courses = CourseModel::all();

        $availableIncludes = collect(['instructors', 'participants']);

        if ($includes)
        {
            $availableIncludes
                ->intersect($includes)
                ->each(function ($include) use ($courses) {
                    $courses->load($include);
                });
        }

        return new CourseResourceCollection($courses);
    }
}