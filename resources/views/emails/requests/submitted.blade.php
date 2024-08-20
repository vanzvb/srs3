@component('mail::message')
<div style="padding: 20px;">
    <h1 style="color: navy; text-align: center;">SRS #{{ $request->request_id }}</h1>
</div>
      
{{-- @component('mail::table')
| Requestor's Name       |          |
| ------------- |:-------------:|
| HOA     | {{ $request->hoa->name }} |
| Vehicle Count     | {{ $request->vehicles->count() }} |
@endcomponent --}}

@component('mail::panel')
<table style="width: 100%;">
    <tbody>
        <tr>
            <th style="text-align: left;">Requestor's Name</th>
            <td>{{ $request->first_name . ' ' . $request->last_name }}</td>
        </tr>
        <tr>
            <th style="text-align: left;">HOA</th>
            <td>{{ $request->hoa->name }}</td>
        </tr>
        <tr>
            <th style="text-align: left;">Vehicle Count</th>
            <td>{{ $request->vehicles->count() }}</td>
        </tr>
    </tbody>
</table>
@endcomponent
<br>

<br>

@component('mail::button', ['url' => $url])
View SRS Details #{{ $request->request_id }}
@endcomponent

<br>

Note:
<br>
SRS link will expire within 120 hours.

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