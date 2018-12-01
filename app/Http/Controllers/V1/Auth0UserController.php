<?php

namespace App\Http\Controllers\V1;

use App\Domain\Auth0Id;
use App\Domain\User;
use App\Domain\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class Auth0UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function store(Request $request)
    {
        //Log::debug("store auth0 user", $request->idToken);
        $auth0 = \App::make('auth0');
        $tokenInfo = get_object_vars($auth0->decodeJWT($request->idToken));
        Log::debug("idToken", $tokenInfo);

        $auth0Id = new Auth0Id($tokenInfo['sub']);
        $user = $this->userRepository->userExistsWithEmail($tokenInfo['email'])
            ? $this->userRepository->userByEmail($tokenInfo['email'])
            : User::createNew($tokenInfo['name'], $tokenInfo['email'], $tokenInfo['picture'], $auth0Id);
        $user->assignAuth0Id($auth0Id);
        $this->userRepository->save($user);
    }
}
