<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CourseResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function echoICalString()
    {
        echo join("\r\n", [
            "BEGIN:VCALENDAR",
            "VERSION:2.0",
            "PRODID:-//" . config('app.name') . "//Courses//EN"
        ]);

        echo "\r\n";

        foreach ($this->collection as $course) {
            $course->echoICalString();
        }
        echo "\r\n";
        echo "END:VCALENDAR";
    }
}
