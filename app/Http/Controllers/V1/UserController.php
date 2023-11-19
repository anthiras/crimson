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

        $isMember = $request->has('isMember') ? in_array($request->query('isMember'), ["1", "true"]) : null;
        $isPaidMember = $request->has('isPaidMember') ? in_array($request->query('isPaidMember'), ["1", "true"]) : null;
        $isRecentInstructor = $request->has('isRecentInstructor') ? in_array($request->query('isRecentInstructor'), ["1", "true"]) : null;
        $pageSize = $request->query('pageSize', 20);

        return $this->userQuery->list(
            $request->query('query'),
            $request->query('include'),
            $isMember,
            $isPaidMember,
            $isRecentInstructor,
            $pageSize);
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

    public function delete(UserId $userId)
    {
        $user = $this->userRepository->user($userId);
        $this->authorize('delete', $user);
        $user = $user->delete();
        $this->userRepository->save($user);
        return 204;
    }

    public function deleteCurrent()
    {
        return $this->delete(Auth::id());
    }
}
