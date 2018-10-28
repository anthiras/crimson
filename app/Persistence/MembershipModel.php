<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 28-10-2018
 * Time: 12:58
 */

namespace App\Persistence;


use App\Domain\UserId;
use Cake\Chronos\Chronos;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MembershipModel extends Model
{
    protected $table = 'membership';
    public $incrementing = false;
    protected $guarded = [];
    protected $primaryKey = ['user_id', 'expires_at'];

    public function user()
    {
        return $this->belongsTo('App\Persistence\UserModel', 'user_id');
    }

    public static function whereActive(Chronos $atDate)
    {
        return static::where('created_at', '<', $atDate)
            ->where('expires_at', '>', $atDate);
    }

    public static function forUserAndDate(UserId $userId, Chronos $activeAtDate)
    {
        return self::whereActive($activeAtDate)->where('user_id', $userId)->first();
    }

    protected function getKeyForSaveQuery()
    {

        $primaryKeyForSaveQuery = array(count($this->primaryKey));

        foreach ($this->primaryKey as $i => $pKey) {
            $primaryKeyForSaveQuery[$i] = isset($this->original[$this->getKeyName()[$i]])
                ? $this->original[$this->getKeyName()[$i]]
                : $this->getAttribute($this->getKeyName()[$i]);
        }

        return $primaryKeyForSaveQuery;

    }

    /**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery(Builder $query)
    {

        foreach ($this->primaryKey as $i => $pKey) {
            $query->where($this->getKeyName()[$i], '=', $this->getKeyForSaveQuery()[$i]);
        }

        return $query;
    }
}