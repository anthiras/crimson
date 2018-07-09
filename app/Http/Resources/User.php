<?php

namespace App\Http\Resources;

use App\Persistence\CourseModel;
use App\Persistence\RoleModel;
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
            'picture' => $this->picture,
            'status' => $this->whenPivotLoaded('course_participants', function() {
                return $this->pivot->status;
            }),
            'roles' => IdName::collection($this->whenLoaded('roles')),
            'takingCourses' => IdName::collection($this->whenLoaded('takingCourses')),
            'teachingCourses' => IdName::collection($this->whenLoaded('teachingCourses'))
        ];
    }
}
