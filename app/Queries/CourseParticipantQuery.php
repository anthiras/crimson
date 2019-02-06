<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 06-02-2019
 * Time: 18:25
 */

namespace App\Queries;


use App\Domain\CourseId;
use App\Http\Resources\UserResourceCollection;

interface CourseParticipantQuery
{
    public function list(CourseId $courseId): UserResourceCollection;
}