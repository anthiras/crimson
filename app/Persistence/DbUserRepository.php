<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 23-05-2018
 * Time: 12:43
 */

namespace App\Persistence;


use App\Domain\Auth0Id;
use App\Domain\User;
use App\Domain\UserId;
use App\Domain\UserNotFound;
use App\Domain\UserRepository;

class DbUserRepository implements UserRepository
{
    public function user(UserId $userId): User
    {
        $userModel = UserModel::with('auth0Users')->find($userId);
        if ($userModel == null)
        {
            throw new UserNotFound();
        }
        return UserFactory::createUser($userModel);
    }

    /**
     * @param Auth0Id $auth0Id
     * @return User
     * @throws UserNotFound
     */
    public function userByAuth0Id(Auth0Id $auth0Id): User
    {
        $auth0UserModel = Auth0UserModel::with('user')->find($auth0Id);
        if ($auth0UserModel == null)
        {
            throw new UserNotFound();
        }
        return UserFactory::createUser($auth0UserModel->user);
    }

    public function userByEmail(string $email): User
    {
        $userModel = UserModel::with('auth0Users')->where('email', $email)->first();
        if ($userModel == null)
        {
            throw new UserNotFound();
        }
        return UserFactory::createUser($userModel);
    }

    public function exists(UserId $userId): bool
    {
        return UserModel::find($userId) != null;
    }

    public function userExistsWithAuth0Id(Auth0Id $auth0Id): bool
    {
        return Auth0UserModel::find($auth0Id) != null;
    }

    public function userExistsWithEmail(string $email): bool
    {
        return UserModel::where('email', $email)->first() != null;
    }

    public function save(User $user)
    {
        $model = UserModel::updateOrCreate(
            ["id" => $user->getUserId()],
            UserToDb::map($user));

        $model->auth0Users()->delete();
        $model->auth0Users()->saveMany(
            $user
                ->getAuth0Ids()
                ->map(function($auth0Id) use ($user) {
                    return new Auth0UserModel(UserToDb::mapAuth0Id($auth0Id, $user->id()));
                }));

        $model->roles()->sync($user->getRoleIds()->map->string());

        $model->save();
    }

    public function userIdByAuth0Id(Auth0Id $auth0Id): UserId
    {
        return Auth0UserModel::where('auth0_id', $auth0Id)
            ->pluck('user_id')
            ->map(function ($userIdStr) { return new UserId($userIdStr); })
            ->first();
    }
}