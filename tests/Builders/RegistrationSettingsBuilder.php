<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 26-03-2019
 * Time: 21:27
 */

namespace Tests\Builders;


use App\Domain\RegistrationSettings;

class RegistrationSettingsBuilder
{
    protected $allowRegistration;
    protected $autoConfirm;
    protected $maxParticipants = null;
    protected $maxRoleDifference = null;

    public function allowRegistration($allow = true): RegistrationSettingsBuilder
    {
        $this->allowRegistration = $allow;
        return $this;
    }

    public function autoConfirmAll(): RegistrationSettingsBuilder
    {
        $this->autoConfirm = true;
        $this->maxParticipants = null;
        return $this;
    }

    public function autoConfirm(): RegistrationSettingsBuilder
    {
        $this->autoConfirm = true;
        return $this;
    }

    public function withMaxParticipants($maxParticipants): RegistrationSettingsBuilder
    {
        $this->maxParticipants = $maxParticipants;
        return $this;
    }

    public function withMaxRoleDifference($maxRoleDifference): RegistrationSettingsBuilder
    {
        $this->maxRoleDifference = $maxRoleDifference;
        return $this;
    }

    public function autoConfirmMaxParticipants(int $maxParticipants): RegistrationSettingsBuilder
    {
        $this->autoConfirm = true;
        $this->maxParticipants = $maxParticipants;
        return $this;
    }

    public function autoConfirmMaxRoleDifference(int $maxRoleDifference): RegistrationSettingsBuilder
    {
        $this->autoConfirm = true;
        $this->maxRoleDifference = $maxRoleDifference;
        return $this;
    }

    public function build(): RegistrationSettings
    {
        return new RegistrationSettings(
            $this->allowRegistration ?? true,
            $this->autoConfirm ?? false,
            $this->maxParticipants,
            $this->maxRoleDifference);
    }

    public static function buildRandom() : RegistrationSettings
    {
        $builder = new RegistrationSettingsBuilder();
        return $builder
            ->allowRegistration()
            ->autoConfirmMaxParticipants(7)
            ->autoConfirmMaxRoleDifference(2)
            ->build();
    }
}