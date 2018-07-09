<?php

namespace App\Persistence;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = 'users';
    public $incrementing = false;
    protected $guarded = [];

    public function takingCourses()
    {
        return $this->belongsToMany('App\Persistence\CourseModel', 'course_participants', 'user_id', 'course_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function teachingCourses()
    {
        return $this->belongsToMany('App\Persistence\CourseModel', 'course_instructors', 'user_id', 'course_id')
            ->withTimestamps();
    }

    public function auth0Users()
    {
        return $this->hasMany('App\Persistence\Auth0UserModel', 'user_id');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Persistence\RoleModel', 'user_roles', 'user_id', 'role_id')
            ->withTimestamps();
    }
}
