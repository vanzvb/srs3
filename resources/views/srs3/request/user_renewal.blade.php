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
                                                        {{ !$crm->subCategory->id ? 'selected' : '' }}>Please select Sub Category
                                                    </option>
                                                    @foreach ($srsSubCategories as $srsSubCategory)
                                                    <option value="{{ $srsCategory->id }}"
                                                        {{ $srsSubCategory->id == $crm->subCategory->id ? 'selected' : '' }}>
                                                        {{ $srsSubCategory->name }}
                                                    </option>
                                                    @endforeach
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
                                                    <th>Status</th>
                                                    <th>Category</th>
                                                    <th>Sub Category</th>
                                                    <th>HOA</th>
                                                    <th>VOS</th>
                                                    <th>Membership Type</th>
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
                                {{-- <div class="row">
                                    <div class="col-12 p-3" style="text-align: right;">
                                        <button id="add_vehicle_btn" type="button" class="btn btn-sm btn-info"
                                            style="color: white;">Add new vehicle</button>
                                    </div>
                                </div> --}}
                            </div>
                            {{-- test --}}
                            <div class="container mt-4">
                                <h5>New Vehicle</h5>

                                <!-- Button to trigger the modal -->
                                <button id="openModalBtn" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                    data-bs-target="#addVehicleModal">
                                    Add New Vehicle
                                </button>

                                <!-- Vehicle Table -->
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
                                    <tbody id="vehicleTableBody">
                                        <!-- Dynamic content will be added here -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Add Vehicle Modal -->
                            <div class="modal fade" id="addVehicleModal" tabindex="-1"
                                aria-labelledby="addVehicleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addVehicleModalLabel">Add Vehicle</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h4>Vehicle Info</h4>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="plateNumber" class="form-label">Plate Number</label>
                                                    <input type="text" id="plateNumber" class="form-control"
                                                        placeholder="Enter Plate Number">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="brand1" class="form-label">Brand</label>
                                                    <select id="brand1" class="form-select">
                                                        <option value="" disabled selected>Select Brand</option>
                                                        <option value="Toyota">Toyota</option>
                                                        <option value="BMW">BMW</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="series1" class="form-label">Series</label>
                                                    <input type="text" id="series1" class="form-control"
                                                        placeholder="Enter Series">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="yearmodel1" class="form-label">Year Model</label>
                                                    <input type="text" id="yearmodel1" class="form-control"
                                                        placeholder="Enter Year Model">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="plateNumber" class="form-label">Vehicle Color</label>
                                                    <input type="text" id="plateNumber" class="form-control"
                                                        placeholder="Enter Vehicle Color">
                                                </div>
                                                <div class="col-md-3"> 
                                                    <label for="vehicletype1" class="form-label">Vehicle Type</label>
                                                    <input type="text" id="vehicletype1" class="form-control"
                                                        placeholder="Enter Vehicle Type">
                                                </div>
                                            </div>
                                            <hr>
                                            <h4>Vehicle Owner Information</h4>

                                            <div class="col-md-4 p-2">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" id="sameAsAccount">
                                                    <label class="form-check-label" for="sameAsAccount">
                                                        Same as Account
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row mt-2">

                                                <div class="col-md-4">
                                                    <label for="first_name" class="form-label"><b>First Name</b></label>

                                                    <!-- Visible text input -->
                                                    <input type="text" class="form-control" id="first_name"
                                                        name="first_name" placeholder="Enter First Name" value="">

                                                </div>
                                                <div class="col-md-4">
                                                    <label for="middle_name" class="form-label"><b>Middle Name</b></label>

                                                    <!-- Visible text input -->
                                                    <input type="text" class="form-control" id="middle_name"
                                                        name="middle_name" placeholder="Enter Middle Name"
                                                        value="">

                                                </div>

                                                <div class="col-md-4">
                                                    <label for="last_name" class="form-label"><b>Last Name</b></label>

                                                    <!-- Visible text input for last name -->
                                                    <input type="text" class="form-control" id="last_name"
                                                        name="last_name" placeholder="Enter Last Name" value="">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-1">
                                                    <label for="block" class="form-label"><b>Block</b></label>
                                            
                                                    <!-- Visible text input for block, restricted to numbers only -->
                                                    <input type="number" class="form-control" id="block" name="block[]" placeholder="..." inputmode="numeric" min="1">
                                                </div>
                                            
                                                <div class="col-md-1">
                                                    <label for="lot" class="form-label"><b>Lot</b></label>
                                            
                                                    <!-- Visible text input for lot, restricted to numbers only -->
                                                    <input type="number" class="form-control" id="lot" name="lot[]" placeholder="..." inputmode="numeric" min="1">
                                                </div>
                                            
                                                <div class="col-md-1">
                                                    <label for="house_no" class="form-label" style="white-space: nowrap;"><b>House No.</b></label>
                                            
                                                    <!-- Visible text input for house number, restricted to numbers only -->
                                                    <input type="number" class="form-control" id="house_no" name="house_no[]" placeholder="..." inputmode="numeric" min="1">
                                                </div>
                                            
                                                <div class="col-md-5">
                                                    <label for="street" class="form-label"><b>Street</b></label>
                                            
                                                    <!-- Visible text input for street -->
                                                    <input type="text" class="form-control" id="street" name="street[]" placeholder="...">
                                                </div>
                                            
                                                <div class="col-md-4">
                                                    <label for="building_apartment_condo" class="form-label" style="white-space: nowrap;">
                                                        <b>Building / Apartment / Condo</b>
                                                    </label>
                                            
                                                    <!-- Visible text input for building / apartment / condo -->
                                                    <input type="text" class="form-control" id="building_apartment_condo" name="building_apartment_condo[]" placeholder="...">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="city" class="form-label" style="white-space: nowrap;"><b>City</b></label>
                                                    <!-- Visible text input for city -->
                                                    <input type="text" class="form-control" id="city" name="city[]" placeholder="...">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="zip_code" class="form-label" style="white-space: nowrap;"><b>Zip Code</b></label>
                                                    <!-- Visible text input for zip code -->
                                                    <input type="text" class="form-control" id="zip_code" name="zip_code[]" placeholder="...">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="category1" class="form-label"><b>Category</b></label>
                                                    <select class="form-select" name="category1" id="category1">
                                                        <option value="" style="color: grey;">Please select Category</option>
                                                        @foreach ($srsCategories as $srsCategory)
                                                            <option value="{{ $srsCategory->id }}"
                                                                {{ old('new_category') == $srsCategory->id ? 'selected' : '' }}>
                                                                {{ $srsCategory->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="subCategory1" class="form-label"><b>Sub Category</b></label>
                                                    <select class="form-select" name="subCategory1" id="subCategory1">
                                                        <option value="" style="color: grey;">Please select Sub Category</option>
                                                        <option value="1">Sample Sub Cat1</option>
                                                        <option value="2">Sample Sub Cat2</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <br>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="selected_hoa" class="form-label"><b>HOA</b></label>
                                                    <select class="form-select" name="selected_hoa" id="selected_hoa">
                                                        <option value="" style="color: grey;">Please select HOA</option>
                                                        @foreach ($hoas as $hoa)
                                                            <option value="{{ $hoa->id }}">
                                                                {{ $hoa->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>             
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="membership_type" class="form-label"><b>Membership Type</b></label>
                                                    <select class="form-select" name="membership_type" id="membership_type">
                                                        <option value="" style="color: grey;">Please Select Membership Type...</option>
                                                        <option value="1">Sample Membership1</option>
                                                        <option value="2">Sample Membership2</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="vehicle_ownership_status" class="form-label"><b>Vehicle Ownership Status</b></label>
                                                    <select class="form-select" name="vehicle_ownership_status" id="vehicle_ownership_status">
                                                        <option value="" style="color: grey;">Please Select Ownership Status...</option>
                                                        <option value="1">Sample Vehicle Ownership1</option>
                                                        <option value="2">Sample Vehicle Ownership2</option>
                                                    </select>                                                         
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button id="addVehicleBtn" type="button" class="btn btn-primary">Add
                                                Vehicle</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- test --}}
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
        <script src="{{ asset('js/srs3addvehicle.js') }}"></script>
    @endsection
