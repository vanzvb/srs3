@component('mail::message')
<div style="padding: 20px;">
    <h1 style="color: navy; text-align: center;">SRS #{{ $srsRequest->request_id }}</h1>
</div>

Good day! <br>

We regret to inform you that your application has been rejected. <br>
Below is the reason of rejection.
<br>
<br>
Thank you for your understanding.

<br>
@php
    $rejectedVehicles = $srsRequest->vehicles3->where('hoa_pres_status', 1);
@endphp

@if ($rejectedVehicles->isNotEmpty())
@component('mail::panel') 
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="border: 1px solid #ddd; padding: 8px;">Plate Number</th>
                <th style="border: 1px solid #ddd; padding: 8px;">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rejectedVehicles as $vehicle)
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $vehicle->plate_no ?? '' }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $vehicle->hoa_pres_remarks ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <br>
@endcomponent
@endif


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