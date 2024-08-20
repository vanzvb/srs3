@component('mail::message')

<div style="padding: 20px;">
   <h1 style="text-align: center;">BFFHAI: Unlock Account</h1>
</div>

Good day! We have received a request to unlock your account. Please click the button to unlock your account, and provide the given passcode.

@component('mail::panel')
<table style="width: 100%;">
    <tbody>
         <tr>
            <th style="text-align: left;">Passcode</th>
            <td>{{ $passcode }}</td>
         </tr>
    </tbody>
</table>
@endcomponent

<br>

@component('mail::button', ['url' => $url])
Unlock Account
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
