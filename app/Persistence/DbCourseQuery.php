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
use Cake\Chronos\Chronos;

class DbCourseQuery implements CourseQuery
{

    public function show(CourseId $courseId): CourseResource
    {
        return new CourseResource(CourseModel::with(['instructors', 'participants'])->find($courseId));
    }

    public function list(
        $includes = null,
        Chronos $startsBefore = null,
        Chronos $startsAfter = null,
        Chronos $endsBefore = null,
        Chronos $endsAfter = null)
        : CourseResourceCollection
    {
        $courses = CourseModel::query();

        if ($startsBefore)
        {
            $courses = $courses->where('starts_at', '<', $startsBefore);
        }
        if ($startsAfter)
        {
            $courses = $courses->where('starts_at', '>', $startsAfter);
        }
        if ($endsBefore)
        {
            $courses = $courses->where('ends_at', '<', $endsBefore);
        }
        if ($endsAfter)
        {
            $courses = $courses->where('ends_at', '>', $endsAfter);
        }

        //dd($courses->toSql());

        $courses = $courses->orderBy('starts_at')->paginate(10);//->get();

        $availableIncludes = collect(['instructors']);

        if ($includes)
        {
            if (in_array('participants', $includes))
            {
                $courses->load(['participants' => function ($query) {
                    $query->orderBy('signed_up_at', 'asc');
                }]);
            }

            $availableIncludes
                ->intersect($includes)
                ->each(function ($include) use ($courses) {
                    $courses->load($include);
                });
        }

        return new CourseResourceCollection($courses);
    }
}