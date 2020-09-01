<?php

namespace App\Persistence;

use Cake\Chronos\Chronos;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = 'users';
    public $incrementing = false;
    protected $guarded = [];
    protected $keyType = 'string';

    const AVAILABLE_INCLUDES = ['roles', 'takingCourses', 'teachingCourses', 'memberships'];

    public function takingCourses()
    {
        return $this->belongsToMany('App\Persistence\CourseModel', 'course_participants', 'user_id', 'course_id')
            ->withPivot('status', 'role', 'signed_up_at')
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

    public function memberships()
    {
        return $this->hasMany('App\Persistence\MembershipModel', 'user_id');
    }

    public function currentMembership()
    {
        if (!$this->relationLoaded('memberships'))
            return null;

        $now = Chronos::now();
        return $this->memberships()
            ->where('created_at', '<', $now)
            ->where('expires_at', '>', $now)
            ->first();
    }
}
