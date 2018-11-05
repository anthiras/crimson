<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 05-11-2018
 * Time: 20:27
 */

namespace App\Queries;


use App\Domain\CourseId;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseResourceCollection;

interface CourseQuery
{
    public function show(CourseId $courseId): CourseResource;
    public function list($includes): CourseResourceCollection;
}