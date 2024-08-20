@component('mail::message')
<div style="padding: 20px;">
   <h1 style="color: navy; text-align: center;">SIMS: Receiving # {{ $receivingDetails->rcvID }}</h1>
   <br>
   <p style="text-align: center;">Date Received: {!! \Carbon\Carbon::parse($receivingDetails->dateRcvd)->format('m-d-y g:ia') !!}</p>
   <p style="text-align: center;">Received By: {{ $receivingDetails->user->name }}</p>
</div>

# Good day,

This is to inform you that the following stickers has been successfully received:
@component('mail::panel')
<table style="width: 100%;">
   <thead>
         <tr>
            <th style="text-align: left;">Item</th>
            <th style="text-align: left;">Quantity</th>
         </tr>
   </thead>
   <tbody>
      @foreach($receivingDetails->rcvItems as $item)
         <tr>
            <td>{{ $item->item->itemName }}</td>
            <td>{{ $item->qty }}</td>
         </tr>
      @endforeach
   </tbody>
</table>
@endcomponent

@component('mail::button', ['url' => $url])
Check Receiving
@endcomponent

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

