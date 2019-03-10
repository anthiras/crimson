<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 20-11-2018
 * Time: 21:25
 */

namespace App\Http\Controllers\V1;


use App\Domain\UserRepository;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserPermissionsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function current(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(new \stdClass());
        }
        $user = $this->userRepository->user(Auth::id());
        return new UserPermissionsResource($user);
    }
}