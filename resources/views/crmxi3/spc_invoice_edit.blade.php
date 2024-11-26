@extends('layouts.main-app')

@section('title', 'CRMXi')

@section('links_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap');

        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
@endsection

@section('content')

    <div class="container-fluid mt-4">
        <div class="card myCard">
            <div class="card-body p-5">
                <div class="text-muted h1  fw-bold">
                    <i class="fas fa-edit"></i> Edit Billing
                </div>
                <hr>

                <div class="mt-5 h5">
                    <span class="text-muted fw-bold h5">Account ID: </span> <span
                        class="text-muted">{{ $crm->account_id }}</span>
                </div>
                <div>
                    <span class="text-muted fw-bold  h1">{{ $crm->firstname }} {{ $crm->middlename }}
                        {{ $crm->lastname }}</span>
                </div>
                <div class="h6">
                    <span class="fw-bold text-muted">Account Status:

                        <?php
                    if ($crm->status == 1) {
                    ?>
                        <span class="badge text-bg-success">Active</span>
                        <?php
                    } elseif ($crm->status == 2) {
                    ?>
                        <span class="badge text-bg-warning">Inactive</span>
                        <?php
                    } elseif ($crm->status == 3) {
                    ?>
                        <span class="badge text-bg-danger">Suspended</span>
                        <?php
                    } elseif ($crm->status == 4) {
                    ?>
                        <span class="badge text-bg-danger">Banned</span>
                        <?php
                    }
                    ?>
                    </span>
                </div>
                <div class="h6   text-muted">
                    <span class="fw-bold">Date Registered:</span><?= ' [' . date('d M, Y (D) h:i a',
                        strtotime($crm->created_at)) . ']' ?>
                </div>
                {{-- <div class="h6 text-muted">
                    <span class="fw-bold">Address:</span>
                    [
                    @if ($crm->address)
                        {{ $crm->address }}
                    @else
                        {{ $crm->blk_lot }}, {{ $crm->street }},
                        {{ $crm->building_name }}, {{ $crm->subdivision_village }},
                        {{ $crm->hoa }}, {{ $crm->city }}, {{ $crm->zipcode }}
                    @endif
                    ]
                </div> --}}

                <hr>
                <form action="{{ route('edit_billing.v3') }}" method="POST">
                    @csrf
                    <div class="d-flex justify-content-start">
                        @foreach ($ins as $in)
                            <div class="h2">
                                <span class="fw-bold"> Billing No. : <?= $in->invoice_no ?><br></span>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-end">
                        @foreach ($ins as $invoice)
                            <a href="{{ route('generate.billing3', ['id' => $invoice->invoice_no, 'crm_id' => $invoice->c_id]) }}"
                                class="btn btn-primary btn-sm" target="_blank"><i class="fas fa-print"></i> PRINT</a>
                        @endforeach
                    </div>

                    <div class="p-4">
                        <div class="h3 text-muted  fw-bold">
                            Line Items :
                        </div>


                        <div class="row p-4">

                            <table class="table table-sm table-stripe table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-center">Plate No.</th>
                                        <th class="text-center">Car Details</th>
                                        <th class="text-center text-nowrap">Old Sticker</th>
                                        <th class="text-center">New Sticker</th>
                                        <th class="text-center text-nowrap">Selling Price</th>
                                        <th class="text-center text-nowrap">Base Price</th>
                                        <th class="text-center">Security</th>
                                        <th class="text-center text-nowrap">RMF | EPF</th>



                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $index => $item)
                                        <tr>
                                            <input type="hidden" name="vehicle_id[]" value="{{ $item->crm_vehicle_id }}">
                                            <td class="text-center">{{ $index + 1 }}.</td>
                                            <td class="text-center">{{ $item->plate_no }}</td>
                                            <td class="text-center">{{ $item->type }} , {{ $item->color }}
                                                {{ $item->series }} , {{ $item->brand }}</td>
                                            <td class="text-center">{{ $item->old_sticker_no }} </td>
                                            <td class="text-center"><input class="form-control" type="text"
                                                    name="new_sticker_no[]" value="{{ $item->sticker_no }}"> </td>
                                            <td class="text-center">{{ number_format($item->sellingPrice, 2) }} </td>
                                            <td class="text-center">{{ number_format($item->basePrice, 2) }} </td>
                                            <td class="text-center">{{ number_format($item->security, 2) }} </td>
                                            <td class="text-center">{{ number_format($item->epf, 2) }} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>

                    <div class="mb-3">
                        <input type="hidden" class="form-control" id="floatingInput" name="crm_id"
                            value="{{ request()->segment(4) }}">

                        <input type="hidden" class="form-control" id="floatingInput" name="invoice_id"
                            value="{{ request()->segment(5) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reason for edit : </label>
                        <div class="form-floating">
                            <textarea class="form-control" name="reeason_of" placeholder="Leave a comment here" id="floatingTextarea" required
                                style="height: 120px;"></textarea>
                            <label for="floatingTextarea">Leave a comment . . . . </label>
                        </div>
                    </div>




                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success btn-md  me-2">Update</button>
                        <a href="/crmxi" class="btn btn-danger btn-md">Cancel</a>
                    </div>
                </form>

            </div>
        </div>


    </div>

@endsection

@section('links_js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
    <script>
        //Start input
        $(document).on('input', '.discounted', function() {
            var selected_vat_type = $("#vat-type").val();
            discount = $('.discounted').val();
            grandTotal = subTotal - discount;
            var sub_s = subTotal.toFixed(2) - vat.toFixed(2);

            vat = calculateVAT(selected_vat_type, grandTotal.toFixed(2));
            $('#totalAmount2').val(grandTotal.toFixed(2));
            $('#totalSub2').val(subTotal.toFixed(2));

            $('#totalDue').text(grandTotal.toFixed(2));
            $('#totalDiscount').text('-' + discount);
            $('#totalDiscount2').text('-' + discount);

            $('#totalSub').text(sub_s.toFixed(2));
            $("#selected-vat-type").text(vat.toFixed(2));


        })
    </script>
    <?php
if (session()->has('success')) {
?>

    <script>
        Swal.fire({
            title: '<?php echo session()->get('success'); ?>',
            icon: 'success',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Okay'
        })
    </script>
    <?php
}


?>

    <?php
if (session()->has('error')) {
?>

    <script>
        Swal.fire({
            title: '<?php echo session()->get('error'); ?>',
            icon: 'error',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Okay'
        })
    </script>
    <?php
}


?>

@endsection
