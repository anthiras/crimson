<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 05-11-2018
 * Time: 21:01
 */

namespace App\Persistence;


use App\Domain\RoleId;
use App\Http\Resources\IdNameResource;
use App\Queries\RoleQuery;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DbRoleQuery implements RoleQuery
{

    public function listExceptAdmin(): AnonymousResourceCollection
    {
        return IdNameResource::collection(RoleModel::where('id', '!=', RoleId::admin())->get());
    }
}