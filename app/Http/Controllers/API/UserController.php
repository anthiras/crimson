<?php

namespace App\Http\Controllers\API;

use App\Domain\UserId;
use App\Domain\UserRepository;
use App\Persistence\UserModel;
use Cake\Chronos\Chronos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $users = UserModel::query();

        $query = $request->query('query');
        if ($query)
        {
            $users = $users->where('name', 'like', '%'.$query.'%');
        }

        $users = $users->orderBy('name')->get();

        $availableIncludes = collect(['roles', 'takingCourses', 'teachingCourses']);
        $includes = $request->query('include');
        if ($includes)
        {
            $availableIncludes
                ->intersect($includes)
                ->each(function ($include) use ($users) {
                    $users->load($include);
                });
        }

        return UserResource::collection($users);
    }

    public function show(UserId $userId)
    {
        $user = $this->userRepository->user($userId);
        $this->authorize('show', $user);
        return new UserResource(UserModel::with(['roles', 'takingCourses', 'teachingCourses'])->find($userId));
    }

    public function current(Request $request)
    {
        return $this->show(Auth::id());
    }

    public function update(Request $request, UserId $userId)
    {
        $user = $this->userRepository->user($userId);
        $this->authorize('update', $user);
        $user->setName($request->name)
            ->setGender($request->gender)
            ->setBirthDate(Chronos::parse($request->birthDate));
            //->setEmail($request->email)
            //->setPicture($request->picture);
        $this->userRepository->save($user);
        return 204;
    }
}
