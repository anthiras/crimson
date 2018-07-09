<?php

namespace App\Http\Middleware;

use App\Domain\Auth0Id;
use App\Domain\User;
use App\Domain\UserId;
use App\Domain\UserNotFound;
use App\Domain\UserRepository;
use Auth0\SDK\Exception\CoreException;
use Auth0\SDK\Exception\InvalidTokenException;
use Closure;
use Illuminate\Support\Facades\Log;

class Auth0JWTAuthentication
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        $accessToken = $request->bearerToken();

        if ($accessToken != null) {
            try {
                $auth0 = \App::make('auth0');
                $tokenInfo = get_object_vars($auth0->decodeJWT($accessToken));
                //Log::debug("tokenInfo", $tokenInfo);
                $auth0Id = new Auth0Id($tokenInfo['sub']);
                $user = $this->userRepository->userByAuth0Id($auth0Id);
                \Auth::login($user);
            } catch (UserNotFound $e) {
                Log::error($e->getMessage());
                return response()->json(["message" => $e->getMessage()], 401);
            } catch (CoreException $e) {
                Log::error($e->getMessage());
                return response()->json(["message" => $e->getMessage()], 401);
            } catch (InvalidTokenException $e) {
                Log::error($e->getMessage());
                return response()->json(["message" => $e->getMessage()], 401);
            }
        }

        return $next($request);
    }
}
