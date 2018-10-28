<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 28-10-2018
 * Time: 12:36
 */

namespace App\Domain;


use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterval;
use Cake\Chronos\Date;

class MembershipRenewal
{
    public static function nextRenewal(Chronos $date): Chronos
    {
        $renewal = Chronos::parse(env('MEMBERSHIP_RENEWAL_AT'));
        $period = \DateInterval::createFromDateString(env('MEMBERSHIP_RENEWAL_PERIOD'));
        while ($renewal < $date) {
            $renewal = $renewal->add($period);
        }
        return $renewal;
    }
}