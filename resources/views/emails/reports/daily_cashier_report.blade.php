@component('mail::message')
<div style="padding: 20px;">
   <h1 style="color: navy; text-align: center;">Daily Cashier Reports</h1>
   <br>
   <p style="text-align: center;">As of {{ $date }}</p>
</div>

# Good day,

This is to inform you that the daily cashier report is now available for viewing.
{{-- @component('mail::panel')
<table style="width: 100%;">
   <thead>
         <tr>
            <th style="text-align: left;">Item</th>
            <th style="text-align: left;">Quantity</th>
         </tr>
   </thead>
   <tbody>
      FFF
   </tbody>
</table>
@endcomponent --}}

{{-- @component('mail::button', ['url' => $url])
Check Receiving
@endcomponent --}}

<br>

Best Regards,
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
BFFHAI 

@endcomponent

