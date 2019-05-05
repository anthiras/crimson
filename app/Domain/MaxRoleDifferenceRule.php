<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 21-03-2019
 * Time: 22:06
 */

namespace App\Domain;


use Illuminate\Support\Collection;

class MaxRoleDifferenceRule implements IRegistrationRule
{
    /**
     * @var int
     */
    protected $maxRoleDifference;

    public function __construct(int $maxRoleDifference)
    {
        $this->maxRoleDifference = $maxRoleDifference;
    }

    public function validate(Collection $participants): bool
    {
        return ParticipantStats::getRoleDifference($participants) <= $this->maxRoleDifference;
    }
}