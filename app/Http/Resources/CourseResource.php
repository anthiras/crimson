<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Cake\Chronos\Chronos;

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
            'startsAt' => $this->starts_at, // TODO: Convert to UTC
            'endsAt' => $this->ends_at,
            'createdAt' => $this->created_at,
            'durationMinutes' => $this->duration_minutes,
            'instructors' => UserResource::collection($this->whenLoaded('instructors')),
            'participants' => UserResource::collection($this->whenLoaded('participants')),
            'myParticipation' => Auth::check() ? $this->participant(Auth::id())->get()->map(function ($user) { return new UserResource($user); })->first() : null,
            'canShow' => Auth::check() ? Auth::user()->can('showResource', $this) : false,
            'canUpdate' => Auth::check() ? Auth::user()->can('updateResource', $this) : false,
            'allowRegistration' => (bool) $this->allow_registration,
            $this->mergeWhen(Auth::check() && Auth::user()->can('updateResource', $this), [
                'autoConfirm' => (bool) $this->auto_confirm,
                'maxParticipants' => $this->max_participants,
                'maxRoleDifference' => $this->max_role_difference
            ])
        ];
    }

    public function echoICalString()
    {
        echo join("\r\n", [
            "BEGIN:VEVENT",
            "UID:" . $this->id,
            "DTSTAMP:" . self::formatUtcICalDate(Chronos::parse($this->created_at)),
            "DTSTART;TZID=Europe/Copenhagen:" . self::formatLocalICalDate(Chronos::parse($this->starts_at, 'Europe/Copenhagen')),
            "DURATION:PT" . intdiv($this->duration_minutes, 60) . "H" . ($this->duration_minutes % 60) . "M",
            "SUMMARY:" . $this->name,
            "RRULE:FREQ=WEEKLY;COUNT=" . $this->weeks,
            "END:VEVENT"
        ]);
    }

    private static function formatLocalICalDate(Chronos $date)
    {
        return $date->format("Ymd\THis");
    }

    private static function formatUtcICalDate(Chronos $date)
    {
        return $date->format("Ymd\THis\Z");
    }
}
