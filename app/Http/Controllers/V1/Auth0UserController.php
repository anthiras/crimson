<?php

namespace App\Http\Controllers\V1;

use App\Domain\Auth0Id;
use App\Domain\Auth0UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class Auth0UserController extends Controller
{
    /**
     * @var Auth0UserService
     */
    protected $auth0UserService;

    public function __construct(Auth0UserService $auth0UserService)
    {
        $this->auth0UserService = $auth0UserService;
    }

    public function store(Request $request)
    {
        //Log::debug("store auth0 user", $request->idToken);
        $auth0 = \App::make('auth0');
        $tokenInfo = get_object_vars($auth0->decodeJWT($request->idToken));
        //Log::debug("idToken", $tokenInfo);

        $auth0Id = new Auth0Id($tokenInfo['sub']);

        $this->auth0UserService->createOrUpdateUser($auth0Id, $tokenInfo['email'], $tokenInfo['name'], $tokenInfo['picture']);

        return 204;
    }
}
