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
            "PRODID:-//" . config('app.name') . "//Courses//EN",
            "X-WR-TIMEZONE:Europe/Copenhagen",
            "BEGIN:VTIMEZONE",
            "TZID:Europe/Copenhagen",
            "TZURL:http://tzurl.org/zoneinfo-outlook/Europe/Copenhagen",
            "X-LIC-LOCATION:Europe/Copenhagen",
            "BEGIN:DAYLIGHT",
            "TZOFFSETFROM:+0100",
            "TZOFFSETTO:+0200",
            "TZNAME:CEST",
            "DTSTART:19700329T020000",
            "RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU",
            "END:DAYLIGHT",
            "BEGIN:STANDARD",
            "TZOFFSETFROM:+0200",
            "TZOFFSETTO:+0100",
            "TZNAME:CET",
            "DTSTART:19701025T030000",
            "RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU",
            "END:STANDARD",
            "END:VTIMEZONE"
        ]);

        echo "\r\n";

        foreach ($this->collection as $course) {
            $course->echoICalString();
            echo "\r\n";
        }
        echo "END:VCALENDAR";
    }
}
