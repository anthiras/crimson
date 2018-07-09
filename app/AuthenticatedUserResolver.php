<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 23-05-2018
 * Time: 12:42
 */

namespace App;


use App\Domain\UserId;

interface AuthenticatedUserResolver
{
    public function id(): UserId;
}