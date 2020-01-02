<?php
/**
 * Created by PhpStorm.
 * User: anders
 * Date: 28-10-2018
 * Time: 12:15
 */

namespace App\Domain;


use Cake\Chronos\Chronos;

class Membership
{

    protected $userId;
    protected $startsAt;
    protected $expiresAt;
    protected $paidAt;
    protected $paymentMethod;
    protected $signupComment;

    public function __construct(UserId $userId, Chronos $startsAt, Chronos $expiresAt, string $paymentMethod, ?string $signupComment = null, $paidAt = null)
    {
        $this->userId = $userId;
        $this->startsAt = $startsAt;
        $this->expiresAt = $expiresAt;
        $this->paymentMethod = $paymentMethod;
        $this->signupComment = $signupComment;
        $this->paidAt = $paidAt;
    }

    public static function create(UserId $userId, string $paymentMethod, ?string $signupComment = null): Membership
    {
        if (!MembershipRenewal::isOpenForRegistration())
        {
            throw new \Exception("Membership is not open for registration");
        }
        return new Membership($userId, Chronos::now(), MembershipRenewal::nextRenewal(Chronos::now()), $paymentMethod, $signupComment);
    }

    /**
     * @return UserId
     */
    public function getUserId(): UserId
    {
        return $this->userId;
    }

    /**
     * @return Chronos
     */
    public function getStartsAt(): Chronos
    {
        return $this->startsAt;
    }

    /**
     * @return Chronos
     */
    public function getExpiresAt(): Chronos
    {
        return $this->expiresAt;
    }

    /**
     * @return null | Chronos
     */
    public function getPaidAt()
    {
        return $this->paidAt;
    }

    /**
     * @return $this
     */
    public function setPaid() {
        $this->paidAt = Chronos::now();
        return $this;
    }

    /**
     * @return string
     */
    public function getSignupComment(): ?string
    {
        return $this->signupComment;
    }

    /**
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }
}