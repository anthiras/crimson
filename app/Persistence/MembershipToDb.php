<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 28-10-2018
 * Time: 13:10
 */

namespace App\Persistence;


use App\Domain\Membership;

class MembershipToDb
{
    public static function map(Membership $membership)
    {
        return [
            'user_id' => $membership->getUserId(),
            'starts_at' => $membership->getStartsAt(),
            'expires_at' => $membership->getExpiresAt(),
            'paid_at' => $membership->getPaidAt(),
            'payment_method' => $membership->getPaymentMethod(),
            'signup_comment' => $membership->getSignupComment()
        ];
    }
}