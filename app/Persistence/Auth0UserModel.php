<?php

namespace App\Persistence;

use Illuminate\Database\Eloquent\Model;

class Auth0UserModel extends Model
{
    protected $table = 'auth0_users';

    protected $primaryKey = 'auth0_id';

    public $incrementing = false;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Persistence\UserModel', 'user_id');
    }
}
