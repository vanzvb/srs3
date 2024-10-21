@component('mail::message')
<div style="padding: 20px;">
    <h1 style="color: navy; text-align: center;">SRS #{{ $request->request_id }}</h1>
</div>
      
Good day! <br>

Here is a summary of your submitted request. <br>
Please be reminded that the approval of requests will take approximately 5-8 working days.
<br>
<br>

@component('mail::panel')
<table style="width: 100%;">
    <tbody>
        <tr>
            <th style="text-align: left;">Requestor's Name</th>
            <td>{{ $request->first_name . ' ' . $request->last_name }}</td>
        </tr>
        <tr>
            <th style="text-align: left;">HOA</th>
            <td>{{ $request->hoa3->name ?? '' }}</td>
        </tr>
        <tr>
            <th style="text-align: left;">Vehicle Count</th>
            <td>{{ $request->crmVehicleRequests->count() }}</td>
        </tr>
    </tbody>
</table>
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