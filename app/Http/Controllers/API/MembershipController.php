<?php

namespace App\Http\Controllers\API;

use App\Domain\Membership;
use App\Domain\MembershipRepository;
use App\Domain\UserId;
use App\Http\Controllers\Controller;
use App\Queries\MembershipQuery;
use Cake\Chronos\Chronos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipController extends Controller
{
    /** @var MembershipRepository  */
    protected $membershipRepository;
    /** @var MembershipQuery */
    protected $membershipQuery;

    public function __construct(MembershipRepository $membershipRepository, MembershipQuery $membershipQuery)
    {
        $this->membershipRepository = $membershipRepository;
        $this->membershipQuery = $membershipQuery;
    }

    public function current(Request $request)
    {
        return $this->show(Auth::id());
    }

    public function show(UserId $userId)
    {
        $now = Chronos::now();
        if (!$this->membershipRepository->hasMembership($userId, $now))
        {
            abort(404);
        }
        $membership = $this->membershipRepository->membership($userId, $now);
        $this->authorize('show', $membership);
        return $this->membershipQuery->show($userId, $now);
    }

    public function store(Request $request)
    {
        $userId = new UserId($request->userId);
        $now = Chronos::now();
        $membership = Membership::create($userId);
        $this->authorize('store', $membership);
        $this->membershipRepository->save($membership);
        return $this->membershipQuery->show($userId, $now);
    }

    public function setPaid(UserId $userId) {
        $now = Chronos::now();
        $membership = $this->membershipRepository->membership($userId, $now)->setPaid();
        $this->authorize('setPaid', $membership);
        $this->membershipRepository->save($membership);
        return $this->membershipQuery->show($userId, $now);
    }
}
