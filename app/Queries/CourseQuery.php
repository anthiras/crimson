<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 05-11-2018
 * Time: 20:27
 */

namespace App\Queries;


use App\Domain\CourseId;
use App\Domain\UserId;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseResourceCollection;
use Cake\Chronos\Chronos;

interface CourseQuery
{
    public function show(CourseId $courseId): CourseResource;
    public function list(
        $includes = null,
        Chronos $startsBefore = null,
        Chronos $startsAfter = null,
        Chronos $endsBefore = null,
        Chronos $endsAfter = null,
        UserId $userId = null)
        : CourseResourceCollection;
}