<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CourseResource extends JsonResource
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
            'weeks' => $this->weeks,
            'startsAt' => $this->starts_at,
            'endsAt' => $this->ends_at,
            'durationMinutes' => $this->duration_minutes,
            'instructors' => UserResource::collection($this->whenLoaded('instructors')),
            'participants' => UserResource::collection($this->whenLoaded('participants')),
            'myParticipation' => Auth::check() ? $this->participant(Auth::id())->get()->map(function ($user) { return new UserResource($user); })->first() : null,
            'canShow' => Auth::check() ? Auth::user()->can('show', $this) : false
        ];
    }
}
