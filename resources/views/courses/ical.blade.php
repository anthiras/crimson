BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//{{ config('app.name') }}//Courses//EN
@foreach ($courses as $course)
BEGIN:VEVENT
UID:{{ $course['id'] }}
DTSTAMP:{{ $dateFormatter($course['createdAt']) }}
DTSTART:{{ $dateFormatter($course['startsAtUtc']) }}
DURATION:PT{{ intdiv($course['durationMinutes'], 60) }}H{{ $course['durationMinutes'] % 60 }}M
SUMMARY:{{ $course['name'] }}
RRULE:FREQ=WEEKLY;COUNT={{ $course['weeks'] }}
END:VEVENT
@endforeach
END:VCALENDAR