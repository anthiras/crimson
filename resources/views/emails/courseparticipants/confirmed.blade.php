@component('mail::message')
# Velkommen på holdet

Hej {{ $userName }}

Vi kan bekræfte at du er optaget på **{{ $courseName }}**. Vi glæder os til at se dig!

Tak,<br>
{{ config('app.name') }}

# Welcome in class

Hi, {{ $userName }}

You have been approved into the class **{{ $courseName }}**. We look forward to seeing you!

Thanks,<br>
{{ config('app.name') }}
@endcomponent
