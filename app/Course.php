<?php
//
//namespace App;
//
//use Illuminate\Database\Eloquent\Model;
//
//class Course extends Model
//{
//	use Uuids;
//
//	/**
//	 * Indicates if the IDs are auto-incrementing.
//	 *
//	 * @var bool
//	 */
//	public $incrementing = false;
//
//    /**
//     * The attributes that are mass assignable.
//     *
//     * @var array
//     */
//    protected $fillable = ['name'];
//
//    public function participants()
//    {
//    	return $this->belongsToMany('App\User', 'course_participants', 'course_id', 'user_id')
//    		->withPivot('status')
//            ->withTimestamps();
//    }
//
//    public function instructors()
//    {
//        return $this->belongsToMany('App\User', 'course_instructors', 'course_id', 'user_id')
//            ->withTimestamps();
//    }
//}
