@component('mail::message')
<div style="padding: 20px;">
    <h1 style="color: navy; text-align: center;">SRS #{{ $requestId }}</h1>
</div>

Good day! <br>

We regret to inform you that your application has been rejected. <br>
Below is the reason of rejection.
<br>
<br>
Thank you for your understanding.

<br>

@component('mail::panel')
Reason of rejection

{{ $rejectMessage }}

@endcomponent

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