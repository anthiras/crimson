<?php

namespace App\Http\Controllers\V1;

use App\Domain\User;
use App\Domain\UserId;
use App\Domain\UserRepository;
use App\Queries\UserQuery;
use Cake\Chronos\Chronos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userRepository;
    protected $userQuery;

    public function __construct(UserRepository $userRepository, UserQuery $userQuery)
    {
        $this->userRepository = $userRepository;
        $this->userQuery = $userQuery;
    }

    public function index(Request $request)
    {
        $this->authorize('list', User::class);
        return $this->userQuery->list($request->query('query'), $request->query('include'));
    }

    public function show(UserId $userId)
    {
        $user = $this->userRepository->user($userId);
        $this->authorize('show', $user);
        return $this->userQuery->show($userId);
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
        $this->userRepository->save($user);
        return 204;
    }
}
