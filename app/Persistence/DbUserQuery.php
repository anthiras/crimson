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
use Cake\Chronos\Chronos;
use Illuminate\Database\Eloquent\Builder;

class DbUserQuery implements UserQuery
{

    public function show(UserId $userId): UserResource
    {
        return new UserResource(UserModel::with(UserModel::AVAILABLE_INCLUDES)->find($userId));
    }

    public function list(
        ?string $searchText = null,
        $includes = null,
        ?bool $isMember = null,
        ?bool $isPaidMember = null,
        ?bool $isRecentInstructor = null)
        : UserResourceCollection
    {
        //DB::connection()->enableQueryLog();

        $users = UserModel::query()->where('deleted', '=', false)
            ->when(!is_null($isMember), function ($query) use ($isMember) {
                return $isMember
                ? $query->whereHas('memberships', function (Builder $subQuery) {
                    return self::membershipQuery($subQuery);
                })
                : $query->whereDoesntHave('memberships', function (Builder $subQuery) {
                    return self::membershipQuery($subQuery);
                });

            })
            ->when(!is_null($isPaidMember), function($query) use ($isPaidMember) {
                return $isPaidMember
                    ? $query->whereHas('memberships', function (Builder $subQuery) {
                        return self::paidMembershipQuery($subQuery);
                    })
                    : $query->whereDoesntHave('memberships', function (Builder $subQuery) {
                        return self::paidMembershipQuery($subQuery);
                    });
            })
            ->when($searchText, function($query, $searchText) {
                return $query->where('name', 'like', '%'.$searchText.'%');
            })
            ->when(!is_null($isRecentInstructor), function($query) use ($isRecentInstructor) {
                return $isRecentInstructor
                    ? $query->whereHas('teachingCourses', function (Builder $subQuery) {
                        return self::recentCoursesQuery($subQuery);
                    })
                    : $query->whereDoesntHave('teachingCourses', function (Builder $subQuery) {
                        return self::recentCoursesQuery($subQuery);
                    });
            })
            ->orderBy('name')->paginate(10);//->get();

        //dd($users->toSql());

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

    private static function membershipQuery(Builder $query): Builder {
        $now = Chronos::now();
        return $query
            ->where('created_at', '<', $now)
            ->where('expires_at', '>', $now);
    }

    private static function paidMembershipQuery(Builder $query) : Builder {
        return self::membershipQuery($query)->whereNotNull('paid_at');
    }

    private static function unpaidMembershipQuery(Builder $query) : Builder {
        return self::membershipQuery($query)->whereNull('paid_at');
    }

    private static function recentCoursesQuery(Builder $query) : Builder {
        return $query->where('starts_at', '>', Chronos::now()->modify('-1 years'));
    }
}