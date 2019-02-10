@component('mail::message')
# Tilmelding modtaget

Hej {{ $userName }}

Din tilmelding til **{{ $courseName }}** er modtaget, og vi sender dig en bekræftelse når/hvis du kommer ind på holdet.

Tak,<br>
{{ config('app.name') }}

# Registration received

Hi, {{ $userName }}

Your registration for **{{ $courseName }}** has been received, and we will send a confirmation if and when you are approved into the class.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
