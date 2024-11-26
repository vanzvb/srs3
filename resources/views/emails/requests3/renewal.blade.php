@component('mail::message')

<div style="padding: 20px;">
    <h1 style="text-align: center;">BFFHAI Sticker Application Renewal</h1>
</div>

@component('mail::panel')
<table style="width: 100%;">
    <tbody>
        <tr>
            <th style="text-align: left;">Email</th>
            <td>{{ $email }}</td>
        </tr>
    </tbody>
</table>
@endcomponent
    
<br>

@component('mail::button', ['url' => $url])
Sticker Request Renewal Application Link
@endcomponent

<br>

Please click the button above to proceed to sticker appplication renewal.

<br>
<br>

Note:
<br>
Renewal Application link will expire within 72 hours.

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
