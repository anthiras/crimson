<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 08-07-2018
 * Time: 16:12
 */

namespace App\Http\Controllers\API;


use App\Domain\RoleId;
use App\Http\Controllers\Controller;
use App\Http\Resources\IdName;
use App\Persistence\RoleModel;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        return IdName::collection(RoleModel::where('id', '!=', RoleId::admin())->get());
    }
}