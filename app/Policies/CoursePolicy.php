<?php

namespace App\Policies;

use App\Domain\Course;
use App\Domain\RoleId;
use App\Domain\User;
use App\Http\Resources\CourseResource;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class CoursePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the course.
     *
     * @param  \App\Domain\User  $user
     * @param  \App\Http\Resources\CourseResource  $course
     * @return mixed
     */
    public function showResource(User $user, CourseResource $course)
    {
        return $user->hasRole(RoleId::instructor(), RoleId::admin());
    }

    /**
     * Determine whether the user can create courses.
     *
     * @param  \App\Domain\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        Log::debug("Authorize create course for user ".$user->id());
        return $user->hasRole(RoleId::instructor(), RoleId::admin());
    }

    /**
     * Determine whether the user can update the course.
     *
     * @param  \App\Domain\User  $user
     * @param  \App\Domain\Course  $course
     * @return mixed
     */
    public function update(User $user, Course $course)
    {
        return $user->hasRole(RoleId::instructor(), RoleId::admin());
    }

    public function updateResource(User $user, CourseResource $course)
    {
        return $user->hasRole(RoleId::instructor(), RoleId::admin());
    }

    /**
     * Determine whether the user can delete the course.
     *
     * @param  \App\Domain\User  $user
     * @param  \App\Domain\Course  $course
     * @return mixed
     */
    public function delete(User $user, Course $course)
    {
        return $user->hasRole(RoleId::instructor(), RoleId::admin());
    }

    public function deleteResource(User $user, CourseResource $course)
    {
        return $user->hasRole(RoleId::instructor(), RoleId::admin());
    }
}
