@extends('layouts.main-app')

@section('title', 'CRMXi-View Account')

@section('links_css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.css"
        integrity="sha512-Woz+DqWYJ51bpVk5Fv0yES/edIMXjj3Ynda+KWTIkGoynAMHrqTcDUQltbipuiaD5ymEo9520lyoVOo9jCQOCA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/view-account-style.css') }}">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap');

        body {
            font-family: 'Nunito', sans-serif;
        }

        .no-header-border {
            border-top: 1px solid #ccc;
            /* Add top border to table body */
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
@endsection

@section('content')
    <div class="container-fluid mt-4" style="width: 100%">
        <div class="d-flex justify-start" style="width: 100%">
            <div class="d-flex flex-column accountDetail" style="width: 90%">
                <div style="width: 100%;" class="d-flex flex-wrap flex-lg-nowrap flex-column flex-lg-row">
                    <div style="width: 50%;" class="fs-5 d-flex justify-start">
                        <span class="fw-bold text-black">Account no. : &nbsp;</span>
                        <span>{{ $crms_account[0]->account_id }}</span>
                    </div>
                    <div style="width: 50%;" class="fs-5">
                        <span class="fw-bold fs-5 text-black">Account Name. : &nbsp;</span>
                        <span>{{ $crms_account[0]->account_type == 0
                            ? $crms_account[0]->firstname . ' ' . $crms_account[0]->middlename . ' ' . $crms_account[0]->lastname
                            : $crms_account[0]->name }}
                        </span>
                    </div>
                </div>

                <div style="width: 100%;" class="d-flex flex-wrap flex-lg-nowrap flex-column flex-lg-row">
                    <div style="width: 50%;" class="fs-5">
                        <span class="fw-bold fs-5 text-black">Date Registered. : &nbsp; </span>
                        <span id="createdDate"></span>
                    </div>
                    <div style="width: 50%;" class="fs-5 d-flex justify-start">
                        <span class="fw-bold fs-5 text-black">Account Status. : &nbsp; </span>
                        @if ($crms_account[0]->red_tag == 1)
                            <div class="statusRedTag">Red Tag</div>
                        @elseif ($crms_account[0]->status == 1)
                            <div class="statusActive">Active</div>
                        @elseif ($crms_account[0]->status == 0)
                            <div class="statusInactive">Inactive</div>
                        @endif
                    </div>
                </div>

                {{-- For Patch 11/18/24 Merge Account --}}
                <div class="d-flex justify-content-between align-items-center w-100">
                    <span class="fw-bold text-black mr-3" style="font-size:14px;">Enter Account to Merge: &nbsp;</span>
                    <div class="input-group">
                        <form id="accIDMergeForm" class="d-flex">
                            <input type="text" class="form-control w-auto" placeholder="Search Account ID"
                                id="accIDMerge" name="account_id" style="max-width: 300px;">
                            <button type="button" class="btn btn-primary ml-2" id="mergeSearchBtn">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- <span>{{ $crms_account[0]->blk_lot . ', ' .  $crms_account[0]->street . ( $crms_account[0]->building_name ? ', ' .  $crms_account[0]->building_name : '') . ( $crms_account[0]->subdivision_village ? ', ' .  $crms_account[0]->subdivision_village : ''). ($crms_account[0]->hoa_name ? ', ' . $crms_account[0]->hoa_name : '') . ($crms_account[0]->city_name ? ', ' . $crms_account[0]->city_name : '') . ($crms_account[0]->zipcode ? ', ' . $crms_account[0]->zipcode : '') }}</span> --}}
                {{-- <span>{{ 'Blk ' . $crms_account[0]->block . ' Lot ' . $crms_account[0]->lot  . ( $crms_account[0]->house_number ? ', ' .  $crms_account[0]->house_number . ', ' : '') .   $crms_account[0]->street . ( $crms_account[0]->building_name ? ', ' .  $crms_account[0]->building_name : '') . ( $crms_account[0]->subdivision_village ? ', ' .  $crms_account[0]->subdivision_village : ''). ($crms_account[0]->hoa_name ? ', ' . $crms_account[0]->hoa_name : '') . ($crms_account[0]->city_name ? ', ' . $crms_account[0]->city_name : '') . ($crms_account[0]->zipcode ? ', ' . $crms_account[0]->zipcode : '') }}</span> --}}

                @foreach ($crms_account[0]->acc_address as $i => $address)
                    {{-- <div style="border: 1px solid rgb(196, 196, 196);">
                        <div class="fs-6">
                            <span class="fw-bold fs-6 text-black">Address {{ $i + 1 }} : &nbsp;</span>
                            <span>{{ 'Blk ' . $address->block . ' Lot ' . $address->lot  . ( $address->house_number ? ', ' .  $address->house_number . ', ' : '') .   $address->street . ( $address->building_name ? ', ' .  $address->building_name : '') . ( $address->subdivision_village ? ', ' .  $address->subdivision_village : ''). ($address->hoa_name ? ', ' . $address->hoa_name : '') . ($address->city_name ? ', ' . $address->city_name : '') . ($address->zipcode ? ', ' . $address->zipcode : '') }}</span>
                        </div>
                        <div style="width: 100%;" class="d-flex flex-wrap flex-lg-nowrap flex-column flex-lg-row">
                            <div style="width: 50%;"  class="fs-6 ">
                                <span class="fw-bold fs-6 text-black">Category : &nbsp;</span>
                                <span>{{ $crms_account[0]->category_name }}</span>
                            </div>
                            <div  style="width: 50%;"  class="fs-6">
                                <span class="fw-bold fs-6 text-black">Sub Category : &nbsp;</span>
                                <span>{{ $crms_account[0]->subcat_name }}</span>
                            </div>
                        </div>
                        <div class="fs-6">
                            <span class="fw-bold fs-6 text-black">HOA : &nbsp;</span>
                            <span>{{ $crms_account[0]->hoa_name }}</span>
                        </div>
                    </div> --}}
                @endforeach
            </div>
            <div style="width: 10%; text-align: end;">
                <a href="/crmxi" type="button" class="ms-auto backBtn">
                    <i class="fa fa-arrow-circle-left" aria-hidden="true"></i> BACK
                </a>
            </div>
        </div>

        <div class="mt-3">
            <ul class="nav nav-tabs" id="myTab">
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="nav-p2c-tab" data-bs-toggle="tab" data-bs-target="#nav-p2c" type="button"
                        role="tab" aria-controls="nav-p2c" aria-selected="false">
                        <i class="fa-solid fa-users"></i> Linked Accounts 2.0
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="nav-vehicle-tab" data-bs-toggle="tab" data-bs-target="#vehicle"
                        type="button" role="tab" aria-controls="vehicle" aria-selected="true">
                        <i class="fa-solid fa-car"></i> Vehicle
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="nav-addressTab-tab" data-bs-toggle="tab" data-bs-target="#addressTab"
                        type="button" role="tab" aria-controls="addressTab" aria-selected="false">
                        <i class="fa-solid fa-location-pin"></i> Address
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="nav-redTag-tab" data-bs-toggle="tab" data-bs-target="#redTag"
                        type="button" role="tab" aria-controls="redTag" aria-selected="false">
                        <i class="fa-solid fa-tag"></i> Red Tag History
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="nav-billing-tab" data-bs-toggle="tab" data-bs-target="#billing"
                        type="button" role="tab" aria-controls="billing" aria-selected="false">
                        <i class="fa-solid fa-credit-card"></i> Billing 3.0
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade" id="nav-p2c" role="tabpanel" aria-labelledby="nav-p2c-tab" tabindex="0">
                    @include('crmxi3_p2child.crm_p2c', [
                        'crm' => $crms_account[0],
                        'distinctCounts' => $distinctCounts,
                        'distinctVehicleCounts' => $distinctVehicleCounts,
                    ])
                </div>

                <div id="vehicle" class=" tab-pane active"><br>
                    <div style="display: flex; justify-content: flex-end;">
                        <div style="flex-direction: column;">
                            <div style="text-align: end">
                                <button type="button" style="background-color: #275317"
                                    class="btn btn-sm text-white mb-3" data-bs-toggle="modal"
                                    data-bs-target="#addVehicleModal" data-title=" ADD VEHICLE">
                                    <i class="fas fa-plus"></i> Add Vehicle
                                </button>
                            </div>
                            <div>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search..." id="searchField"
                                        name="searchField">
                                    <button type="button" class="btn btn-primary" id="searchBtn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-4 ">
                        <table id="vehicle_table" class="table table-bordered w-100 bg-white">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Plate no.</th>
                                    <th>Owner Name</th>
                                    <th>Address</th>
                                    <th>
                                        Brand
                                        <br>
                                        Series
                                        <br>
                                        Year/Model
                                        <br>
                                        Color
                                        <br>
                                        Type
                                        <br>
                                        Old Sticker no.
                                    </th>
                                    <th>Latest Stricker No. <br> (Sticker Year)</th>
                                    {{-- <th>Sticker Year</th> --}}
                                    <th>Category / Sub Category</th>
                                    {{-- <th>Sub Category</th> --}}
                                    <th>HOA</th>
                                    <th>Member Type / Vehicle Ownership Status</th>
                                    {{-- <th>Vehicle Ownership Status</th> --}}
                                    {{-- <th>Status</th> --}}
                                    {{-- <th>Previous Owner</th> --}}
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="addressTab" class="container tab-pane fade"><br>
                    <div id="tabAddress" class=" tab-pane active"><br>
                        <div style="display: flex; justify-content: flex-end;">
                            <div style="flex-direction: column;">
                                <div style="text-align: end">
                                    <button type="button" style="background-color: #275317"
                                        class="btn btn-sm text-white mb-3" data-bs-toggle="modal"
                                        data-bs-target="#addAddressModal" data-title=" ADD ADDRESS">
                                        <i class="fas fa-plus"></i> Add Address
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-4 ">
                            <table id="acc_address_table" class="table table-bordered w-100 bg-white">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Category /
                                            <br>
                                            Sub Category
                                        </th>
                                        <th>HOA /
                                            <br>
                                            Member Type
                                        </th>
                                        <th>Address</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($crms_account[0]->acc_address as $i => $address)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>
                                                {{ $address->category_name }} /
                                                <br>
                                                {{ $address->subcat_name }}
                                            </td>
                                            <td>
                                                {{ $address->hoa_name ? $address->hoa_name . ' ' . '/' : '' }}
                                                <br>
                                                {{ $address->hoa_type_name }}
                                            </td>
                                            <td>
                                                <span>{{ ($address->block ? 'Blk ' . $address->block : '') . ($address->lot ? ' Lot ' . $address->lot : '') . ($address->house_number ? ', ' . $address->house_number . ', ' : '') . $address->street . ($address->building_name ? ', ' . $address->building_name : '') . ($address->subdivision_village ? ', ' . $address->subdivision_village : '') . ($address->hoa_name ? ', ' . $address->hoa_name : '') . ($address->city_name ? ', ' . $address->city_name : '') . ($address->zipcode ? ', ' . $address->zipcode : '') }}</span>
                                            </td>
                                            <td>
                                                @if (auth()->user()->can('access_crmxi3_address_delete'))
                                                    <button class="btn btn-sm delete-address"
                                                        data-val="{{ $address->id }}">
                                                        <i class="fa-solid fa-trash"
                                                            style="color:red; font-size:20px"></i>
                                                    </button>
                                                @endif
                                                <button class="btn btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#addAddressModal" data-title="EDIT ADDRESS"
                                                    onclick="editAddress({{ $address }})">
                                                    <i class="fa-solid fa-edit" style="color:#000000; font-size:20px"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
                <div id="redTag" class="container tab-pane fade"><br>
                    <h3>Red Tag History</h3>
                    <div style="text-align: end">
                        <button type="button" style="background-color: #275317" class="btn btn-sm text-white mb-3"
                            data-bs-toggle="modal" data-bs-target="#addRedTagModal" data-title=" ADD RED TAG">
                            <i class="fas fa-plus"></i> Add Red Tag
                        </button>
                    </div>
                    <table id="redtag_table" class="table table-bordered w-100 bg-white">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Red Tag Type</th>
                                <th>Red Tag Description</th>
                                <th>Plate no.</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th>Action By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($redtags as $i => $redtag)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td> {{ $redtag->type == 1 ? 'Vehicle' : 'Account' }}</td>
                                    <td>{{ $redtag->description }}</td>
                                    <td>{{ $redtag->plate_no }}</td>
                                    <td>{{ $redtag->status == 1 ? 'Active' : 'Lifted' }}</td>
                                    <td>{{ $redtag->date_created }}</td>
                                    <td>{{ $redtag->action_by }}</td>
                                    <td>
                                        <button class="btn btn-sm" id="removeRedtag"
                                            onclick="removeRedTag({{ json_encode($redtag) }})">
                                            <i class="fa-solid fa-trash" style="color:#000000; font-size:20px"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="billing" class="container p-3 tab-pane fade"><br>
                <h3>Billing</h3>
                @include('crmxi3.view_billing')
            </div>
        </div>
    </div>

    </div>
    @include('crmxi3.add_vehicle')
    @include('crmxi3.add_address')
    @include('crmxi3.add_redtag')
    @include('crmxi3.crmxi_view_vehicle')

@endsection

@section('links_js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"
        integrity="sha512-Ixzuzfxv1EqafeQlTCufWfaC6ful6WFqIz4G+dWvK0beHw0NVJwvCKSgafpy5gwNqKmgUfIBraVwkKI+Cz0SEQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

    <script>
        // For Patch 11/18/24 Merge Account - Done Patched
        $(document).ready(function() {
            // Merge Account
            $('#mergeSearchBtn').click(function() {
                let btn = $(this);

                // Get the account_id to merge search
                let accMergeForm = $('#accIDMergeForm')[0];
                let accMergeFormData = new FormData(accMergeForm);

                // Append CSRF token to the form data
                accMergeFormData.append('current_account_id', '{{ $crms_account[0]->account_id }}');
                accMergeFormData.append('_token', '{{ csrf_token() }}');

                // Call ajax to check if account_id exists
                $.ajax({
                    type: "post",
                    url: "{{ route('search.merge.account') }}",
                    data: accMergeFormData,
                    dataType: "json",
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        // Disable and Loading State
                        btn.prop('disabled', true);
                        btn.html('<span class="fa fa-spinner fa-spin"></span> Searching...');
                    },
                    complete: function() {
                        // Enable and Reset State
                        btn.prop('disabled', false);
                        btn.html('<i class="fas fa-search"></i>');
                    },
                    success: function(response) {
                        let data = response.data;

                        // Show the account_id to merge
                        Swal.fire({
                            icon: 'success',
                            title: `Account Merging`,
                            text: `Transfer Vehicles: [${data.account_id}] to this [{{ $crms_account[0]->account_id }}]`,
                            showCancelButton: true,
                            confirmButtonText: 'Merge',
                            cancelButtonText: 'Cancel',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Call ajax to merge account
                                $.ajax({
                                    type: "post",
                                    url: "{{ route('merge.accounts') }}",
                                    dataType: 'json',
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        account_id: data.account_id,
                                        merge_account_id: '{{ $crms_account[0]->account_id }}'
                                    },
                                    success: function(response) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: response.message,
                                        }).then((result) => {
                                            // location.href = response.redirect;
                                            location.reload();
                                        });
                                    },
                                    error: function(error) {
                                        let errorValidation = JSON.parse(
                                            error.responseText);

                                        // Account self merge validation
                                        if (errorValidation) {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Oops...',
                                                text: errorValidation
                                                    .message,
                                            });
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Oops...',
                                                text: 'Something went wrong! Please try again.',
                                            });
                                        }
                                    }
                                });
                            }
                        });
                    },
                    error: function(error) {
                        let errorValidation = JSON.parse(error.responseText);

                        // Account self merge validation
                        if (errorValidation && error.status != 200) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: errorValidation.message,
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong! Please try again.',
                            });
                        }
                    }
                });
            });
        });

        $(document).ready(function() {
            // ${'#createdDate'}.val({{ $crms_account[0]->created_at }})
            var dateFromData = @json($crms_account[0]->created_at);
            var date = new Date(dateFromData);

            // Format the date
            var optionsDate = {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            };
            var formattedDate = date.toLocaleDateString('en-GB', optionsDate);
            formattedDate = formattedDate.replace(',', '');

            // Get the day of the week and remove the leading comma
            // var dayOfWeek = date.toLocaleDateString('en-US', { weekday: 'short' });

            // var finalFormattedDate = formattedDate + ' (' + dayOfWeek + ') ';
            var finalFormattedDate = formattedDate;

            $('#createdDate').text(finalFormattedDate);

            // For Switching tabs
            $('#myTab').on('shown.bs.tab', function(event) {
                var tabId = $(event.target).attr('href'); // Get the href attribute of the clicked tab
                // Additional actions can be performed here when a tab is shown
            });

            // To Search Vehicle
            $('#searchBtn').click(function() {
                let searchField = $('#searchField');

                // Assuming you have a text input field for the search query with id "searchField"
                let searchQuery = searchField.val();

                // Assuming your DataTable AJAX URL accepts a query parameter named "search"
                // Using Blade syntax to generate the URL dynamically
                let ajaxUrl =
                    "{{ route('getVehicles', ['account_id' => $crms_account[0]->account_id]) }}" +
                    "?to_search=" + searchQuery;

                // Reload the DataTable with the new AJAX URL
                table.ajax.url(ajaxUrl).load();
            });

        })

        function loadVehicle() {
            table = $('#vehicle_table').DataTable({
                processing: true,
                pageLength: 15,
                serverSide: true,
                searching: false,
                destroy: true,
                lengthMenu: [
                    [15, 30, 50, 100],
                    ['15', '30', '50', '100']
                ],
                ajax: {
                    url: "{{ url('/crmxi/vehicles/' . $crms_account[0]->account_id) }}"
                },
                order: [
                    [1, "desc"]
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    // { data: 'plate_no' },
                    {
                        render: function(data, type, row) {
                            // let plate =  `
                        //     <a href="#" data-bs-toggle="modal" data-bs-target="#viewVehicleModal"  data-title=" VIEW VEHICLE DETAIL" onclick='getVehicleDetails(${JSON.stringify(row)})' >${row.plate_no}</a>
                        // `
                            let plate = `
                            ${row.plate_no}
                        `
                            let status = ''
                            if (row.red_tag == 1) {
                                status = `
                            <div class="statusRedTag" >Red Tag</div>`
                            } else if (row.status == 0) {
                                status = `<div class="statusInactive" >Inactive</div>`
                            } else {
                                status = `<div class="statusActive" >Active</div>`
                            }
                            return plate + '<br>' + '<br>' + status;
                        }
                    },
                    // { data: 'firstname' },
                    {
                        render: function(data, type, row) {
                            return row.firstname + (row.middlename ? ' ' + row.middlename + ' ' : '') + (row
                                .lastname ? ' ' + row.lastname + ' ' : '');
                        },
                    },
                    {
                        data: 'address'
                    },
                    // { data: 'new_sticker_no' },
                    {
                        render: function(data, type, row) {
                            return `
                                ${row.brand ?? ''},
                                ${row.series ?? ''},
                                ${row.year_model ?? ''},
                                ${row.color ?? ''},
                                ${row.type ?? ''},
                                ${row.old_sticker_no ?? ''}

                            `
                        }
                    },
                    {
                        render: function(data, type, row) {
                            if (row.new_sticker_no) {
                                return `${row.new_sticker_no}
                                    (${row.sticker_date
                                        ? row.sticker_date
                                        : row.old_sticker_year ? row.old_sticker_year : ''})
                                    `;
                            } else {
                                return row.old_sticker_no ? `${row.old_sticker_no}
                                    (${row.sticker_date
                                        ? row.sticker_date
                                        : row.old_sticker_year ? row.old_sticker_year : ''})
                                    ` : '';
                            };
                        },
                    },

                    // {
                    //     render: function (data, type, row) {
                    //         if(row.sticker_date) {
                    //             return row.sticker_date;
                    //         } else {
                    //             return row.old_sticker_year;
                    //         };
                    //     },
                    // },
                    // { data: 'category_name' },
                    // { data: 'subcat_name' },
                    {
                        render: function(data, type, row) {
                            return `${row.category_name ? row.category_name : ''} / ${row.subcat_name ? row.subcat_name : ''}`
                        }
                    },
                    {
                        data: 'hoa_name'
                    },
                    // { data: 'hoa_type_name' },
                    // { data: 'vos_name' },
                    {
                        render: function(data, type, row) {
                            return `${row.hoa_type_name ? row.hoa_type_name : ''}  ${row.vos_name ? ' / ' + row.vos_name : ''}`
                        }
                    },
                    // { data: 'status' },
                    // {
                    //     render: function(data, type, row) {
                    //         if(row.red_tag == 1){
                    //             return `<div class="statusRedTag" >Red Tag</div>`
                    //         }
                    //         else if(row.status == 0){
                    //             return `<div class="statusInactive" >Inactive</div>`
                    //         }
                    //         else{
                    //             return `<div class="statusActive" >Active</div>`
                    //         }
                    //     },
                    //     orderable: false,
                    //     searchable: false
                    // },
                    // { data: 'previous_owner' },
                    {
                        render: function(data, type, row) {
                            return `<div class="d-flex justify-content-center">
                                    <button type="button" style="border: none;" class="delete-vehicle me-3" data-val="${row.vehicle_id}">
                                        <i class="fa-solid fa-trash" style="color:red; font-size:20px"></i>
                                    </button>
                                    <button type="button" style="border: none;" data-bs-toggle="modal" data-bs-target="#addVehicleModal"  data-title="EDIT VEHICLE" onclick='editVehicle(${JSON.stringify(row)})'>
                                        <i class="fa-solid fa-edit" style="color:#000000; font-size:20px"></i>
                                    </button>
                                </div>`;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    emptyTable: "No registered vehicle..."
                },
                drawCallback: function() {
                    // Check if table is empty and show/hide header accordingly
                    if (table.rows().count() === 0) {
                        $('#vehicle_table thead').hide();
                        $('#vehicle_table tbody').find('tr:first td:first').addClass('border-0');
                    } else {
                        $('#vehicle_table thead').show();
                        $('#vehicle_table tbody').find('tr:first td:first').removeClass('border-0');
                    }
                }

            });
        }
        loadVehicle();

        // var vehicleDetail;

        function getVehicleDetails(data) {
            var vehicleDetail = data;
            var element = `
            <tr>
                <td>${vehicleDetail.plate_no ?? ''}</td>
                <td>${vehicleDetail.brand  ?? ''}</td>
                <td>${vehicleDetail.series  ?? ''}</td>
                <td>${vehicleDetail.year_model  ?? ''}</td>
                <td>${vehicleDetail.color  ?? ''}</td>
                <td>${vehicleDetail.type  ?? ''}</td>
                <td>${vehicleDetail.old_sticker_no  ?? ''}</td>
                <td>${vehicleDetail.new_sticker_no  ?? ''}</td>
                <td>
                    <a id="crLightbox" href="{{ Storage::url('or/') }}${vehicleDetail.req1}" data-lightbox="gallery1" >
                        <img class="myImg" id="orImage" scr="" data-lightbox="gallery1"  width="200px" height="150px" style="cursor:pointer;border-radius:10px" >
                    </a>
                </td>
                <td>
                    <a id="orLightbox" href="{{ Storage::url('cr/') }}${vehicleDetail.cr}" data-lightbox="gallery1" >
                        <img class="myImg"  id="crImage"  scr="" data-lightbox="gallery1" width="200px" height="150px" style="cursor:pointer;border-radius:10px">
                    </a>
                </td>
            </tr>
        `

            $('#vehicleDetailTable tbody').append(element);

            var crUrl = "{{ Storage::url('cr/') }}";
            var crImage = crUrl + vehicleDetail.cr
            $('#crImage').attr('src', crImage);

            var orUrl = "{{ Storage::url('or/') }}";
            var orImage = orUrl + vehicleDetail.req1
            $('#orImage').attr('src', orImage);




        }


        $('#addVehicleModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var title = button.data('title'); // Extract info from data-* attributes
            var modal = $(this);
            modal.find('.modal-title').text(title);
            $('#addVehicleBtn').show();
            $('.formFileDiv').show();


        });

        $('#addAddressModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var title = button.data('title'); // Extract info from data-* attributes
            var modal = $(this);
            modal.find('.modal-title').text(title);
            $('#current_account_id').val(@json($crms_account[0]->account_id))

        });

        $('#addRedTagModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var title = button.data('title'); // Extract info from data-* attributes
            var modal = $(this);
            modal.find('.modal-title').text(title);

        });

        $('#viewVehicleModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var title = button.data('title'); // Extract info from data-* attributes
            var modal = $(this);
            modal.find('.modal-title').text(title);


        });

        $('#viewVehicleModal').on('hide.bs.modal', function() {
            $('#vehicleDetailTable tbody').empty(); // Clear only the tbody content
        });

        function editVehicle(data) {
            // console.log(data,'edit')
            $('#addVehicleBtn').hide();
            $('.formFileDiv').hide();

            // getAddress(data.address_id, 1, data.address_id);
            if (!data.address_id) {
            // Auto-select the first address in the list
            Swal.fire({
                title: 'Vehicle has no define address',
                text: '',
                icon: 'info',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                // cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            });

            const firstAddressId = $('#owner_address1 option:eq(1)').val();
            $('#owner_address1').val(firstAddressId).trigger('change').change();
            }else{
                getAddress(data.address_id, 1, data.address_id);
            }
            const fields = {
                '#current_vehicle_id1': data.vehicle_id,
                '#current_owner_id1': data.owner_id,
                '#owner_address1': data.address_id,
                '#plate_no1': data.plate_no,
                '#brand1': data.brand,
                '#vehicle_series1': data.series,
                '#year_model1': data.year_model,
                '#color1': data.color,
                '#type1': data.type,
                '#orID1': data.orID,
                '#crID1': data.crID,
                '#sticker_year1': data.old_sticker_year,
                '#sticker_no1': data.old_sticker_no,
                // '#or1': ,
                // '#cr1': ,
                '#first_name1': data.firstname,
                '#middle_name1': data.middlename,
                '#last_name1': data.lastname,
                '#block1': data.block,
                '#lot1': data.lot,
                '#house_number1': data.house_number,
                '#street1': data.street,
                '#building_name1': data.building_name,
                '#subdivision1': data.subdivision_village,
                '#city1': data.city,
                '#zip_code1': data.zipcode,
                '#category1': data.category_id,
                '#sub_category1': data.sub_category_id,
                '#hoa1': data.hoa, // for patch 11/25/24
                '#hoa_type1': data.hoa_type,
                '#vos1': data.vehicle_ownership_status_id,
                '#email1': data.email,
                '#main_contact1': data.main_contact,
                '#secondary_contact1': data.secondary_contact,
                // '#tertiary_contact1': data.tertiary_contact,

            }
            $.each(fields, function(selector, value) {
                $(selector).val(value);
            });

            // cityChange(data.city,1,data.zipcode)
            // categoryChange(data.category_id,1, data.sub_category_id);
            // sub_categoryChange(data.sub_category_id,1, data.hoa_type);
            // hoa_change(data.hoa_type,1, data.vehicle_ownership_status_id);
        }

        $('#addVehicleModal').on('hidden.bs.modal', function() {
            $('#vehicleForm')[0].reset();
        })
        // var address = {!! json_encode($crms_account[0]->acc_address) !!};
        // $(document).ready(function () {
        //     getAccAddress(address[0],0)
        //     // $(`#active_tab_1`).prop('active',true)
        // })
        // function getAccAddress(address, index){
        //     // console.log(address,'address')
        //     let holdAddress = {
        //         '#current_id1' : address.id,
        //         '#category_id1' : address.category_id,
        //         '#sub_category_id1' : address.sub_category_id,
        //         '#hoa1' : address.hoa,
        //         '#hoa_type1' : address.hoa_type,
        //         '#block1' : address.block,
        //         '#lot1' : address.lot,
        //         '#house_number1' : address.house_number,
        //         '#street1' : address.street,
        //         '#building_name1' : address.building_name,
        //         '#subdivision1' : address.subdivision_village,
        //         '#city1' : address.city,
        //         '#zip_code1' : address.zipcode
        //     }
        //     console.log(holdAddress,'address')

        //     $.each(holdAddress, function(selector, value) {
        //         $(selector).val(value);
        //     });
        //      // Remove 'active' class from all tabs and add it to the selected tab
        //     $('.nav-link').removeClass('active');
        //     $(`#active_tab_${index + 1}`).addClass('active');
        //     console.log($(`#active_tab_${index + 1}`),'$(`#active_tab_${index + 1}`)')
        // }
        $(document).ready(function() {
            var table = $('#acc_address_table').DataTable();

        })

        function editAddress(data) {
            // let address = JSON.parse(data.acc_address)

            let addressFields = {}
            // address.map((rec,i)=> {
            //     if(i !== 0){
            //         addressForm()
            //     }
            let holdAddress = {
                '#current_id1': data.id,
                '#category_id1': data.category_id,
                '#sub_category_id1': data.sub_category_id,
                '#hoa1': data.hoa,
                '#hoa_type1': data.hoa_type,
                '#block1': data.block,
                '#lot1': data.lot,
                '#house_number1': data.house_number,
                '#street1': data.street,
                '#building_name1': data.building_name,
                '#subdivision1': data.subdivision_village,
                '#city1': data.city,
                '#zip_code1': data.zipcode
            }
            categoryChange(data.category_id, 1, data.sub_category_id);
            sub_categoryChange(data.sub_category_id, 1, data.hoa_type);
            cityChange(data.city, 1, data.zipcode)
            // addressFields = {...addressFields, ...holdAddress}

            // })
            $.each(holdAddress, function(selector, value) {
                $('#accountAddressForm ' + selector).val(value);
            });
            // // Handle radio buttons
            // if (data.account_type === 1) {
            //     $('#account_type_individual').prop('checked', false);
            //     $('#account_type_company').prop('checked', true);
            //     $('.compInputs').show();
            //     $('.indiInputs').hide();
            //     $('#representative_name').attr('required', true);
            //     $('#first_name').attr('required', false);
            // } else {
            //     $('#account_type_individual').prop('checked', true);
            //     $('#account_type_company').prop('checked', false);
            //     $('.compInputs').hide();
            //     $('.indiInputs').show();
            //     $('#first_name').attr('required', true);
            //     $('#representative_name').attr('required', false);
            // }


        }

        function removeRedTag(data) {
            if (!(data.status == 1)) {
                return;
            }
            Swal.fire({
                title: 'Lift Red Tag',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log('Okay clicked');
                    var id = data.id;
                    $.ajax({
                        url: '{{ route('remove-redtag') }}?id=' + id, // Your update route here
                        type: 'GET', // The HTTP method for updating
                        // data: id, // The form data to send
                        success: function(response) {
                            location.reload()
                            Swal.fire({
                                title: 'Red Tag Lifted',
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Okay'
                            })
                            // console.log('Update successful:', response);
                        },
                        // error: function(xhr) {
                        //     // Handle errors
                        //     console.error('Update failed:', xhr.responseText);
                        // }
                    });

                }
            });
        }

        // For Patch 11/19/24
        $(document).on('click', '.delete-vehicle', function() {
            // Get the vehicle_id to delete
            let vehicle_id = $(this).data('val');

            // Confirm Sweet Alert
            Swal.fire({
                title: 'Delete Vehicle',
                text: 'Are you sure you want to delete this vehicle?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Call ajax to delete vehicle
                    $.ajax({
                        type: "delete",
                        url: "{{ route('crms.delete-vehicle') }}",
                        dataType: 'json',
                        data: {
                            _token: '{{ csrf_token() }}',
                            vehicle_id: vehicle_id
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            }).then((result) => {
                                // Reload the DataTable
                                $('#vehicle_table').DataTable().ajax.reload();

                                location.reload();
                            });
                        },
                        error: function(error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong! Please try again.',
                            });
                        }
                    });
                }
            });
        });

        $(document).on('click', '.delete-address', function() {
            let btn = $(this);
            let address_id = btn.data('val');

            // Confirm Sweet Alert
            Swal.fire({
                title: 'Delete Address',
                text: 'Deleting this address will delete all associated vehicles. Are you sure you want to delete this address?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Call ajax to delete address
                    $.ajax({
                        type: "delete",
                        url: "{{ route('crms.delete-address') }}",
                        dataType: 'json',
                        data: {
                            _token: '{{ csrf_token() }}',
                            address_id: address_id
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            }).then((result) => {
                                location.reload();
                            });
                        },
                        error: function(error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong! Please try again.',
                            });
                        }
                    });
                }
            });
        });
        // End of Patch 11/19/24

        // $(document).ready(function () {
        //     function disableRemoveRedTag(){
        //         var redtags = {!! json_encode($redtags) !!} // Fixing the blade syntax to properly pass the redtags data

        //         // Assume button is enabled by default
        //         var shouldDisable = true;

        //         // Loop through the redtags to check their status
        //         redtags.map(function(rec) {
        //             if (rec.status == 1) {
        //                 shouldDisable = false; // If any status == 1, we enable the button
        //             }
        //         });

        //         // Disable or enable the button based on the result
        //         $('#removeRedtag').prop('disabled', shouldDisable);
        //     }

        //     disableRemoveRedTag();
        // });
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
