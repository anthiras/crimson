<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 28-10-2018
 * Time: 13:04
 */

namespace App\Domain;


use Cake\Chronos\Chronos;

interface MembershipRepository
{
    public function membership(UserId $userId, Chronos $atDate): Membership;
    public function hasMembership(UserId $userId, Chronos $atDate): bool;
    public function save(Membership $membership);
}