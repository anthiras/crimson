<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 21-03-2019
 * Time: 21:54
 */

namespace App\Domain;


use Illuminate\Support\Collection;

interface IRegistrationRule
{
    public function validate(Collection $participants): bool;
}