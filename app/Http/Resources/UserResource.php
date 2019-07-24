<?php

namespace App\Http\Resources;

use App\Persistence\CourseModel;
use App\Persistence\RoleModel;
use Cake\Chronos\Chronos;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $membership = $this->currentMembership() == null ? null : new MembershipResource($this->currentMembership());

        return [
            'id' => $this->id,
            'name' => $this->name,
            $this->mergeWhen(Auth::check() && Auth::user()->can('showResource', $this), [
                'email' => $this->email,
                'picture' => $this->picture,
                'gender' => $this->gender,
                'birthDate' => $this->birth_date == null ? null : Chronos::parse($this->birth_date)->toDateString(),
                'participation' => $this->whenPivotLoaded('course_participants', function() {
                    return [
                        'status' => $this->pivot->status,
                        'role' => $this->pivot->role,
                        'signedUpAt' => $this->pivot->signed_up_at,
                        'amountPaid' => $this->pivot->amount_paid,
                    ];
                }),
                'roles' => IdNameResource::collection($this->whenLoaded('roles')),
                'takingCourses' => IdNameResource::collection($this->whenLoaded('takingCourses')),
                'teachingCourses' => IdNameResource::collection($this->whenLoaded('teachingCourses'))
            ]),
            'currentMembership' => $this->when(
                $membership != null && Auth::check() && Auth::user()->can('showResource', $membership),
                function () use ($membership) { return $membership; })
        ];
    }
}
