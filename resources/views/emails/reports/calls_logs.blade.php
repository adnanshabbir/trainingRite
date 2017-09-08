@component('mail::message')
# Daily Calls Logs Report

Please find daily calls logs report file in the attachments

@component('mail::button', ['url' => $csvPath])
Download Calls Logs Summary Report
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
