<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 05-11-2018
 * Time: 21:00
 */

namespace App\Queries;


use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface RoleQuery
{
    public function listExceptAdmin(): AnonymousResourceCollection;
}