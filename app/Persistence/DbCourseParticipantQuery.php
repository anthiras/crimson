<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 06-02-2019
 * Time: 18:26
 */

namespace App\Persistence;


use App\Domain\CourseId;
use App\Domain\UserId;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
use App\Queries\CourseParticipantQuery;

class DbCourseParticipantQuery implements CourseParticipantQuery
{

    public function list(CourseId $courseId, array $status = null): UserResourceCollection
    {
        $course = CourseModel::with(['participants'])->find($courseId);

        $participants = $course->participants();

        if ($status != null)
        {
            $participants = $participants->whereIn('status', $status);
        }
        
        $participants = $participants->orderBy('signed_up_at')->get();

        $participants->load('memberships');

        return new UserResourceCollection($participants);
    }

    public function show(CourseId $courseId, UserId $userId): UserResource
    {
        $course = CourseModel::with(['participants'])->find($courseId);
        $participant = $course->participants()->where('user_id', $userId)->first();
        return new UserResource($participant);
    }
}