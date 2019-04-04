<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 21-03-2019
 * Time: 21:39
 */

namespace App\Domain;


class RegistrationSettings
{
    protected $allowRegistration;
    protected $autoConfirm;
    protected $maxParticipants;
    protected $maxRoleDifference;

    public function __construct(bool $allowRegistration, bool $autoConfirm, $maxParticipants, $maxRoleDifference)
    {
        if ($maxParticipants != null && !is_int($maxParticipants)) {
            throw new \Exception("maxParticipants must be integer or null");
        }
        if ($maxRoleDifference != null && !is_int($maxRoleDifference)) {
            throw new \Exception("maxRoleDifference must be integer or null");
        }
        $this->allowRegistration = $allowRegistration;
        $this->autoConfirm = $autoConfirm;
        $this->maxParticipants = $maxParticipants;
        $this->maxRoleDifference = $maxRoleDifference;
    }

    public static function default(): RegistrationSettings
    {
        return new RegistrationSettings(true, false, null, null);
    }

    /**
     * @return bool
     */
    public function getAllowRegistration(): bool
    {
        return $this->allowRegistration;
    }

    /**
     * @return bool
     */
    public function getAutoConfirm(): bool
    {
        return $this->autoConfirm;
    }

    /**
     * @return int|null
     */
    public function getMaxParticipants()
    {
        return $this->maxParticipants;
    }

    /**
     * @return int|null
     */
    public function getMaxRoleDifference()
    {
        return $this->maxRoleDifference;
    }

    /**
     * @return \Generator
     */
    public function iterateRules()
    {
        if (!is_null($this->maxParticipants))
        {
            yield new MaxParticipantsRule($this->maxParticipants);
        }

        if (!is_null($this->maxRoleDifference))
        {
            yield new MaxRoleDifferenceRule($this->maxRoleDifference);
        }
    }
}