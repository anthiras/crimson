@component('mail::message')
# Besked til holdet / Message to the class

{{ $message }}

Tak/Thanks,<br>
{{ $courseName }}<br>
{{ config('app.name') }}
@endcomponent