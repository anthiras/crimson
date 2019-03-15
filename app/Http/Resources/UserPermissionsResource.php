<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 27-11-2018
 * Time: 18:19
 */

namespace App\Http\Resources;


use App\Domain\Course;
use App\Domain\RoleId;
use App\Domain\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPermissionsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'users:list' => $this->can('list', User::class),
            'roles:assignRole:instructor' => $this->can('assignRole', RoleId::instructor()),
            'courses:create' => $this->can('create', Course::class)
        ];
    }
}