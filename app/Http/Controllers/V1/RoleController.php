<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 08-07-2018
 * Time: 16:12
 */

namespace App\Http\Controllers\V1;


use App\Http\Controllers\Controller;
use App\Queries\RoleQuery;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleQuery;

    public function __construct(RoleQuery $roleQuery)
    {
        $this->roleQuery = $roleQuery;
    }

    public function index(Request $request)
    {
        return $this->roleQuery->listExceptAdmin();
    }
}