@extends('layouts.guest')

@section('title', 'Sticker Application Request - Renewal')

@section('content')
    <div class="container px-md-10">
        <div class=" px-md-5 mb-3">
            @if ($errors->any())
                <div class="alert close-alert alert-danger alert-dismissible fade show text-center mt-3" role="alert">
                    @foreach ($errors->all() as $message)
                        <strong>{{ $message }}</strong>
                        <br>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card mt-3 shadow mb-5 bg-body rounded">
                <div class="card-header text-center bg-primary" style="color: white;">
                    {{-- <img src="{{ asset('images/bflogo.png') }}" height="100" width="100" alt="">
                <h5>BFFHAI</h5> --}}
                    <br>
                    <h5>Sticker Application - Renewal</h5>

                </div>
                <div id="request_renewal_msg" class="row justify-content-center">
                </div>
                <div class="container justify-content-center align-items-center">
                    <div class="px-md-4 mt-3 mb-3">
                        <form action="{{ route('request.user-renewal.process') }}" id="renewal_request_form" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="p-2">
                                <div class="px-2 px-md-0 mb-4 mt-5">
                                    <h5>Account Information</h5>
                                    {{-- <div class="row p-2">
                                        <b>{{ $crm->category->name . ' / ' . $crm->subCategory->name }}</b>
                                    </div> --}}
                                </div>
                                <div class="row p-2 g-0">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="row p-2 g-0">
                                                <div class="col-md-12">
                                                    <label class="form-label"><b>Account ID</b></label>
                                                    <div>
                                                        {{-- {{ $crm->lastname . ', ' . $crm->firstname . ' ' . $crm->middlename }} --}}
                                                        01
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="row p-2 g-0">
                                                {{-- <div class="col-md-2">
                                                    <b>Name:</b>
                                                </div>
                                                <div class="col-md-10">
                                                    {{ $crm->lastname . ', ' . $crm->firstname . ' ' . $crm->middlename }}
                                                </div> --}}
                                                <div class="col-md-12">
                                                    <label class="form-label"><b>Name</b></label>
                                                    <div>
                                                        {{ $crm->lastname . ', ' . $crm->firstname . ' ' . $crm->middlename }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="row p-2 g-0">
                                                {{-- <div class="col-md-2">
                                                    <b>Address:</b>
                                                </div>
                                                <div class="col-md-10">
                                                    {{ $crm->blk_lot . ' ' . $crm->street . ($crm->building_name ? ', ' . $crm->building_name : '') . ($crm->subdivision_village ? ', ' . $crm->subdivision_village : '') . ($crm->city ? ', ' . $crm->city : '') }}
                                                </div> --}}
                                                <div class="col-md-12">
                                                    <label class="form-label"><b>Address</b></label>
                                                    <div>
                                                        {{ $crm->blk_lot . ' ' . $crm->street . ($crm->building_name ? ', ' . $crm->building_name : '') . ($crm->subdivision_village ? ', ' . $crm->subdivision_village : '') . ($crm->city ? ', ' . $crm->city : '') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="row p-2 g-0">
                                                {{-- <div class="col-md-2">
                                                    <b>Email:</b>
                                                </div>
                                                <div class="col-md-10">
                                                    {{ $crm->email }}
                                                </div> --}}

                                                <div class="col-md-12">
                                                    <label class="form-label"><b>Email</b></label>
                                                    <div>
                                                        {{ $crm->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="row p-2 g-0">
                                                {{-- <div class="col-md-2">
                                                    <b>Contact No.:</b>
                                                </div>
                                                <div class="col-md-10">
                                                    {{ $crm->main_contact }}
                                                </div> --}}

                                                <div class="col-md-12">
                                                    <label class="form-label"><b>Contact No.</b></label>
                                                    <div>
                                                        {{ $crm->main_contact }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {{-- Individual / Company --}}
                                        <div class="row p-2 g-0">
                                            <div class="col-md-12">
                                                <label for="account_type" class="form-label"><b>Account Type</b></label>
                                                <select class="form-select" name="account_type" id="account_type">
                                                    <option value="individual" style="color: grey;"
                                                        {{ !$crmHoaId == 'individual' ? 'selected' : '' }}>Individual
                                                    </option>
                                                    <option value="company" {{ $crmHoaId == 'company' ? 'selected' : '' }}>
                                                        Company</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row p-2 g-0" id="company-name-container" style="display: none;">
                                            <div class="col-md-12">
                                                <label for="companyName" class="form-label"><b>Company Name</b></label>
                                                <input type="text" class="form-control" id="companyName"
                                                    name="companyName" placeholder="Enter company name">
                                            </div>
                                        </div>
                                        {{-- Individual / Company --}}
                                        <div class="row p-2 g-0">
                                            <div class="col-md-12">
                                                <label for="hoa" class="form-label"><b>HOA</b></label>
                                                <select class="form-select" name="hoa" id="hoa">
                                                    <option value="" style="color: grey;"
                                                        {{ !$crmHoaId ? 'selected' : '' }}>Please select HOA</option>
                                                    @foreach ($hoas as $hoa)
                                                        <option value="{{ $hoa->id }}"
                                                            {{ $hoa->id == $crmHoaId ? 'selected' : '' }}>
                                                            {{ $hoa->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row p-2 g-0">
                                            <div class="col-md-12">
                                                <label for="hoa" class="form-label"><b>Category</b></label>
                                                <select class="form-select" name="category" id="category" disabled>
                                                    <option value="" style="color: grey;"
                                                        {{ !$crm->category->id ? 'selected' : '' }}>Please select Category
                                                    </option>
                                                    @foreach ($srsCategories as $srsCategory)
                                                        <option value="{{ $srsCategory->id }}"
                                                            {{ $srsCategory->id == $crm->category->id ? 'selected' : '' }}>
                                                            {{ $srsCategory->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <!-- Hidden input to include the value in the form submission -->
                                                <input type="hidden" name="category" value="{{ $crm->category->id }}">
                                            </div>
                                        </div>
                                        <div class="row p-2 g-0">
                                            <div class="col-md-12">
                                                <label for="hoa" class="form-label"><b>Sub Category</b></label>
                                                <select class="form-select" name="subcat" id="subcat">
                                                    <option value="" style="color: grey;"
                                                        {{ !$crmHoaId ? 'selected' : '' }}>Please select Sub Category
                                                    </option>
                                                    <option value="1" {{ '1' == $crmHoaId ? 'selected' : '' }}>Sample
                                                        Sub Cat</option>
                                                    {{-- @foreach ($hoas as $hoa)
                                                    <option value="{{ $hoa->id }}" {{ $hoa->id == $crmHoaId ? 'selected' : ''}}>{{ $hoa->name }}</option>
                                                @endforeach --}}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- For Renewal (Table) --}}
                            <div class="p-2">
                                <div class="px-2 px-md-0 mb-4 mt-4">
                                    <h5>For Renewal</h5>
                                </div>
                                <div class="row p-2 g-0">
                                    <div class="col-md-12">
                                        <!-- Button outside the table -->  
                                        <!-- Vehicles Table -->
                                        <table class="table table-bordered table-hover mt-2">
                                            <thead>
                                                <tr>
                                                    <th>Plate Number</th>
                                                    <th>Brand, Series</th>
                                                    <th>Category</th>
                                                    <th>Sub Category</th>
                                                    <th>HOA</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($crm->vehicles as $vehicle)
                                                    <tr id="vehicle-row-{{ $vehicle->id }}">
                                                        <td>{{ $vehicle->plate_no }}</td>
                                                        <td>{{ $vehicle->brand . ', ' . $vehicle->series }}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>
                                                            <button type="button" class="btn btn-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#viewDetailsModal-{{ $vehicle->id }}">
                                                                View/Update Details
                                                            </button>
                                                            <button type="button" class="btn btn-danger btn-remove"
                                                                data-id="{{ $vehicle->id }}">
                                                                X
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{-- Modal for View/Update Details --}}
                            @foreach ($crm->vehicles as $vehicle)
                                @include('srs3.request.user_renewal_view_modal', ['vehicle' => $vehicle])
                            @endforeach
                            {{-- Modals --}}
                            {{-- For Renewal (Table) --}}

                            <div class="px-2 px-md-0 mb-4 mt-5">
                                <b>
                                    {{-- <h5>Vehicle Information</h5> --}}
                                </b>
                            </div>
                            <div>

                                <div class="" id="vehicles_row">

                                </div>
                                <div class="row">
                                    <div class="col-12 p-3" style="text-align: right;">
                                        <button id="add_vehicle_btn" type="button" class="btn btn-sm btn-info"
                                            style="color: white;">Add new vehicle</button>
                                    </div>
                                </div>
                            </div>

                            <div class="p-2">
                                <div class="px-2 px-md-0 mb-4 mt-5">
                                    <h5>File Attachment</h5>
                                </div>
                                <div class="px-2 mt-2">
                                    <div id="requirements_list">
                                        @foreach ($requirements as $requirement)
                                            <div class="mb-4">
                                                <!-- Requirement description above the file input -->
                                                <label class="form-label"><b>Upload Valid ID</b></label>
                                                {{-- <label class="form-label"><b>{{ $requirement->description }}</b></label> --}}
                                                <input class="form-control form-control-sm" type="file"
                                                    accept="image/*" name="{{ $requirement->name }}"
                                                    {{ $requirement->required ? 'required' : '' }} style="width: 30%;">
                                                <!-- Adjust the width percentage here -->
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <div class="mt-5 text-center">
                                        <div class="d-flex justify-content-center">
                                            {{-- <div id="g-recaptcha" class="g-recaptcha mt-3"
                                                data-sitekey="{{ config('services.recaptcha.site_key') }}"></div> --}}
                                        </div>

                                        <button type="submit" id="request_submit_btn"
                                            class="btn btn-primary mt-3">Submit
                                            Renewal</button>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('links_js')
        {{-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> --}}
        <script src="{{ asset('js/12srur0123.js') }}"></script>
        <script src="{{ asset('js/srs3renewal1.js') }}"></script>
    @endsection
