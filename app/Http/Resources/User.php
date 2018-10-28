<?php

namespace App\Http\Resources;

use App\Persistence\CourseModel;
use App\Persistence\RoleModel;
use Cake\Chronos\Chronos;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'picture' => $this->picture,
            'gender' => $this->gender,
            'birthDate' => $this->birth_date == null ? null : Chronos::parse($this->birth_date)->toDateString(),
            'participation' => $this->whenPivotLoaded('course_participants', function() {
                return [
                    'status' => $this->pivot->status,
                    'role' => $this->pivot->role,
                    'createdAt' => $this->pivot->created_at->__toString()
                ];
            }),
            'roles' => IdName::collection($this->whenLoaded('roles')),
            'takingCourses' => IdName::collection($this->whenLoaded('takingCourses')),
            'teachingCourses' => IdName::collection($this->whenLoaded('teachingCourses'))
        ];
    }
}
