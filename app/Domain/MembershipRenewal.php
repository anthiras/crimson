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
    public static function lastRenewal(Chronos $date): Chronos
    {
        return self::nextRenewal($date)->sub(self::period());
    }

    public static function nextRenewal(Chronos $date): Chronos
    {
        $renewal = Chronos::parse(config('membership.renewal_at'));
        $period = self::period();
        while ($renewal < $date) {
            $renewal = $renewal->add($period);
        }
        return $renewal;
    }

    private static function period(): \DateInterval
    {
        return \DateInterval::createFromDateString(config('membership.renewal_period'));
    }
}