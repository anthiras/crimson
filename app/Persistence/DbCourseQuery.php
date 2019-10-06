<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 05-11-2018
 * Time: 20:29
 */

namespace App\Persistence;


use App\Domain\CourseId;
use App\Domain\UserId;
use App\Domain\Participant;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseResourceCollection;
use App\Queries\CourseQuery;
use Cake\Chronos\Chronos;
use Illuminate\Database\Eloquent\Builder;

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
        Chronos $endsAfter = null,
        UserId $userId = null)
        : CourseResourceCollection
    {
        $courses = CourseModel::query()
            ->when($startsBefore, function ($query, $startsBefore) {
                return $query->where('starts_at', '<', $startsBefore);
            })
            ->when($startsAfter, function ($query, $startsAfter) {
                return $query->where('starts_at', '>', $startsAfter);
            })
            ->when($endsBefore, function ($query, $endsBefore) {
                return $query->where('ends_at', '<', $endsBefore);
            })
            ->when($endsAfter, function ($query, $endsAfter) {
                return $query->where('ends_at', '>', $endsAfter);
            })
            ->when($userId, function($query, $userId) {
                // Course has user as non-cancelled participant, or instructor
                return $query->where(function ($userQuery) use ($userId) {
                    return $userQuery->whereHas('participants', function (Builder $subQuery) use ($userId) {
                        return $subQuery->where('user_id', '=', $userId)->where('status', '!=', Participant::STATUS_CANCELLED);
                    })->orwhereHas('instructors', function (Builder $subQuery) use ($userId) {
                        return $subQuery->where('user_id', '=', $userId);
                    });
                });
            });

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