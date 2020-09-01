<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 06-07-2018
 * Time: 13:57
 */

namespace App\Persistence;


use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    protected $table = 'roles';
    public $incrementing = false;
    protected $guarded = [];
    protected $keyType = 'string';

    public function users()
    {
        return $this->belongsToMany('App\Persistence\UserModel', 'user_roles', 'role_id', 'user_id')
            ->withTimestamps();
    }
}