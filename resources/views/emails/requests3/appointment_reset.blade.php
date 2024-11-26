@component('mail::message')
<div style="padding: 20px;">
    <h1 style="color: navy; text-align: center;">SRS #{{ $request->request_id }}</h1>
</div>

We apologize that your appointment on {{ $dateTime }} had been cancelled
due to new Holiday proclamations/emergency BFFHAI HOA engagements.

<br>
<br>
Please use below link to pick another preferred schedule.

@component('mail::button', ['url' => $url])
Set Appointment
@endcomponent

<br>

Note:
<br>
Appointment link will expire within 72 hours.

<br>
<br>
<br>

This is a system generated email. Do not reply.
<br>
<br>
<br>

Best Regards, <br>
BFFHAI
<br>
<br>
<small>Contact No. Trunkline:</small>
<br>
<small>+632 . 8403 . 4586</small>
<br>
<small>+632 . 8807 . 5170</small>
<br>
<small>Email Address: wecare@bffhai.com</small>
<br>
@endcomponent
