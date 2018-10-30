<?php

namespace App\Persistence;

use Illuminate\Database\Eloquent\Model;

class CourseModel extends Model
{
    protected $table = 'courses';

	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['id', 'name', 'starts_at'];

    protected $guarded = [];

    public function participants()
    {
    	return $this->belongsToMany('App\Persistence\UserModel', 'course_participants', 'course_id', 'user_id')
    		->withPivot('status', 'role')
            ->withTimestamps();
    }

    public function participant($userId)
    {
        return $this->participants()->where('id', '=', $userId);
    }

    public function instructors()
    {
        return $this->belongsToMany('App\Persistence\UserModel', 'course_instructors', 'course_id', 'user_id')
            ->withTimestamps();
    }
}
