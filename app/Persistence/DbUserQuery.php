<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 05-11-2018
 * Time: 20:55
 */

namespace App\Persistence;


use App\Domain\UserId;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
use App\Queries\UserQuery;
use Illuminate\Support\Facades\DB;

class DbUserQuery implements UserQuery
{

    public function show(UserId $userId): UserResource
    {
        return new UserResource(UserModel::with(UserModel::AVAILABLE_INCLUDES)->find($userId));
    }

    public function list($query, $includes): UserResourceCollection
    {
        //DB::connection()->enableQueryLog();

        $users = UserModel::query()->where('deleted', '=', false);

        if ($query)
        {
            $users = $users->where('name', 'like', '%'.$query.'%');
        }

        $users = $users->orderBy('name')->paginate(10);//->get();

        $availableIncludes = collect(UserModel::AVAILABLE_INCLUDES);
        if ($includes)
        {
            $availableIncludes
                ->intersect($includes)
                ->each(function ($include) use ($users) {
                    $users->load($include);
                });
        }

        $uc = new UserResourceCollection($users);

        //dd(DB::getQueryLog());

        return $uc;
    }
}