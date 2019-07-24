<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 06-02-2019
 * Time: 18:25
 */

namespace App\Queries;


use App\Domain\CourseId;
use App\Domain\UserId;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;

interface CourseParticipantQuery
{
    public function list(CourseId $courseId, array $status = null): UserResourceCollection;
    public function show(CourseId $courseId, UserId $userId): UserResource;
}