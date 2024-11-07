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
                        <form action="{{ route('request.v3.user-renewal.process') }}" id="renewal_request_form"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="p-2">
                                <div class="px-2 px-md-0 mb-4 mt-5">
                                    <h5>Account Information</h5>
                                    {{-- <div class="row p-2">
                                        <b>{{ $crm->category->name . ' / ' . $crm->subCategory->name }}</b>
                                    </div> --}}
                                </div>

                                <div class="row p-2 g-0">
                                    <div class="col-md-2 me-2">
                                        <label for="account_type" class="form-label"><b>Account Type</b></label>
                                        <select class="form-select form-select-md" name="account_type" id="account_type"
                                            disabled>
                                            <option value="0" style="color: grey;"
                                                {{ $crm->account_type == 0 ? 'selected' : '' }}>Individual</option>
                                            <option value="1" {{ $crm->account_type == 1 ? 'selected' : '' }}>Company
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 me-2">
                                        <div class="" id="individual-name-container" style="display: none;">
                                            <div class="col-md-12">
                                                <label for="individualName" class="form-label"><b>Name</b></label>
                                                <input type="text" class="form-control" id="individualName"
                                                    name="individualName" placeholder=""
                                                    value="{{ $crm->lastname . ', ' . $crm->firstname . ' ' . $crm->middlename }}"
                                                    disabled>
                                            </div>
                                        </div>

                                        <div class="" id="company-name-container" style="display: none;">
                                            <div class="col-md-12">
                                                <label for="companyName" class="form-label"><b>Company Name</b></label>
                                                <input type="text" class="form-control" id="companyName"
                                                    name="companyName" placeholder="" value="{{ $crm->firstname }}"
                                                    disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="" id="company-representative-container" style="display: none;">
                                            <div class="col-md-12">
                                                <label for="representativeName" class="form-label"><b>Company
                                                        Representative</b></label>
                                                <input type="text" class="form-control" id="representativeName"
                                                    name="representativeName" placeholder="" value="{{ $crm->name }}"
                                                    disabled>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row p-2 g-0">
                                    <div class="col-md-2 me-2">
                                        <label class="form-label"><b>Account ID</b></label>
                                        <input type="text" class="form-control" value="{{ $crm->account_id }}" disabled>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><b>Email</b></label>
                                        <input type="text" class="form-control" value="{{ $crm->email }}" disabled>
                                    </div>

                                </div>

                                <div class="row p-2 g-0">

                                    <div class="col-md-3 me-2">
                                        <label class="form-label"><b>Main Contact</b></label>
                                        <input type="text" class="form-control" value="{{ $crm->main_contact }}"
                                            disabled>
                                    </div>

                                    <div class="col-md-3 me-2">
                                        <label class="form-label"><b>Secondary Contact</b></label>
                                        <input type="text" class="form-control" value="{{ $crm->secondary_contact }}"
                                            disabled>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label"><b>Alternate Email</b></label>
                                        <input type="text" class="form-control" value="{{ $crm->tertiary_contact }}"
                                            disabled>
                                    </div>
                                </div>

                                {{-- <div class="row p-2 g-0"> --}}
                                {{-- <div class="col-md-6"> --}}
                                {{-- 
                                        <div class="row p-2 g-0">
                                            <div class="col-md-12">
                                                <label for="account_type" class="form-label"><b>Account Type</b></label>
                                                <select class="form-select" name="account_type" id="account_type" disabled>
                                                    <option value="0" style="color: grey;" {{ $crm->account_type == 0 ? 'selected' : '' }}>Individual</option>
                                                    <option value="1" {{ $crm->account_type == 1 ? 'selected' : '' }}>Company</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row p-2 g-0" id="company-name-container" style="display: none;">
                                            <div class="col-md-12">
                                                <label for="companyName" class="form-label"><b>Company Name</b></label>
                                                <input type="text" class="form-control" id="companyName"
                                                    name="companyName" placeholder="" value="{{ $crm->firstname }}" disabled>
                                            </div>
                                        </div>
                                        <div class="row p-2 g-0" id="company-representative-container"
                                            style="display: none;">
                                            <div class="col-md-12">
                                                <label for="representativeName" class="form-label"><b>Company
                                                        Representative</b></label>
                                                <input type="text" class="form-control" id="representativeName"
                                                    name="representativeName" placeholder="" value="{{ $crm->name }}" disabled>
                                            </div>
                                        </div> 
                                        --}}

                                {{-- <div class="row p-2 g-0">
                                            <div class="col-md-12">
                                                <label for="hoa" class="form-label"><b>HOA</b></label>
                                                <select class="form-select" name="hoa" id="hoa">
                                                    <option value="" style="color: grey;" {{ is_null($crm->hoas) ? 'selected' : '' }}>
                                                        Please select HOA
                                                    </option>
                                                    @foreach ($crmxiHoas as $crmxiHoa)
                                                        <option value="{{ $crmxiHoa->id }}" {{ $crm->hoas && $crmxiHoa->id == $crm->hoas->id ? 'selected' : '' }}>
                                                            {{ $crmxiHoa->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> --}}
                                {{-- <div class="row p-2 g-0">
                                            <div class="col-md-12">
                                                <label for="category" class="form-label"><b>Category</b></label>
                                                <select class="form-select" name="category" id="category" disabled>
                                                    <option value="" style="color: grey;" {{ is_null($crm->CRMXIcategory) ? 'selected' : '' }}>
                                                        Please select Category
                                                    </option>
                                                    @foreach ($crmxiCategories as $crmxiCategory)
                                                        <option value="{{ $crmxiCategory->id }}" 
                                                            {{ $crm->CRMXIcategory && $crmxiCategory->id == $crm->CRMXIcategory->id ? 'selected' : '' }}>
                                                            {{ $crmxiCategory->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="category" value="{{ $crm->CRMXIcategory ? $crm->CRMXIcategory->id : '' }}">
                                            </div>
                                        </div> --}}
                                {{-- <div class="row p-2 g-0">
                                            <div class="col-md-12">
                                                <label for="subcat" class="form-label"><b>Sub Category</b></label>
                                                <select class="form-select" name="subcat" id="subcat">
                                                    <option value="" style="color: grey;" {{ is_null($crm->CRMXIsubCategory) ? 'selected' : '' }}>
                                                        Please select Sub Category
                                                    </option>
                                                    @foreach ($crmxiSubCategories as $crmxiSubCategory)
                                                        <option value="{{ $crmxiSubCategory->id }}" 
                                                            {{ $crm->CRMXIsubCategory && $crmxiSubCategory->id == $crm->CRMXIsubCategory->id ? 'selected' : '' }}>
                                                            {{ $crmxiSubCategory->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> --}}
                                {{-- </div> --}}
                                {{-- </div> --}}
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
                                                    {{-- <th>Status</th> --}}
                                                    <th>Category</th>
                                                    <th>Sub Category</th>
                                                    <th>HOA</th>
                                                    {{-- <th>VOS</th> --}}
                                                    {{-- <th>Membership Type</th> --}}
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($crm->CRMXIvehicles as $vehicle)
                                                    <tr id="vehicle-row-{{ $vehicle->id }}">
                                                        <td>{{ $vehicle->plate_no ?? 'N/A' }}</td>
                                                        <td>{{ $vehicle->brand ?? 'N/A' }},
                                                            {{ $vehicle->series ?? 'N/A' }}</td>
                                                        {{-- <td></td> --}}
                                                        <td>{{ $vehicle->vehicleAddress->CRMXIcategory->name ?? 'N/A' }}
                                                        </td>
                                                        <td>{{ $vehicle->vehicleAddress->CRMXIsubCategory->name ?? 'N/A' }}
                                                        </td>
                                                        <td>{{ $vehicle->vehicleAddress->CRMXIhoa->name ?? 'N/A' }}</td>

                                                        {{-- <td></td> --}}
                                                        {{-- <td></td> --}}
                                                        <td style="white-space: nowrap;">
                                                            {{-- <button type="button" class="btn btn-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#viewDetailsModal-{{ $vehicle->id }}">
                                                                View/Update Details
                                                            </button>
                                                            <button type="button" class="btn btn-danger btn-remove"
                                                                data-id="{{ $vehicle->id }}">
                                                                X
                                                            </button> --}}
                                                            <!-- View/Update Details Button with Edit Icon -->
                                                            <button type="button" class="btn btn-primary btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#viewDetailsModal-{{ $vehicle->id }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>

                                                            <!-- Close Button with Close Icon -->
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm btn-remove"
                                                                data-id="{{ $vehicle->id }}">
                                                                {{-- <i class="fas fa-times"></i> --}}
                                                                Don't Renew
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
                            @foreach ($crm->CRMXIvehicles as $vehicle)
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
                            {{-- <div class="container mt-4">
                                <h5>New Vehicle</h5>

                                <button id="openModalBtn" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                    data-bs-target="#addVehicleModal">
                                    Add New Vehicle
                                </button>

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

                                    </tbody>
                                </table>
                            </div> --}}

                            {{-- <div class="modal fade" id="addVehicleModal" tabindex="-1"
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

                                                    <input type="text" class="form-control" id="first_name"
                                                        name="first_name" placeholder="Enter First Name" value="">

                                                </div>
                                                <div class="col-md-4">
                                                    <label for="middle_name" class="form-label"><b>Middle Name</b></label>

                                                    <input type="text" class="form-control" id="middle_name"
                                                        name="middle_name" placeholder="Enter Middle Name"
                                                        value="">

                                                </div>

                                                <div class="col-md-4">
                                                    <label for="last_name" class="form-label"><b>Last Name</b></label>

                                                    <input type="text" class="form-control" id="last_name"
                                                        name="last_name" placeholder="Enter Last Name" value="">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-1">
                                                    <label for="block" class="form-label"><b>Block</b></label>
                                            
                                                    <input type="number" class="form-control" id="block" name="block[]" placeholder="..." inputmode="numeric" min="1">
                                                </div>
                                            
                                                <div class="col-md-1">
                                                    <label for="lot" class="form-label"><b>Lot</b></label>
                                            
                                                    <input type="number" class="form-control" id="lot" name="lot[]" placeholder="..." inputmode="numeric" min="1">
                                                </div>
                                            
                                                <div class="col-md-1">
                                                    <label for="house_no" class="form-label" style="white-space: nowrap;"><b>House No.</b></label>
                                            
                                                    <input type="number" class="form-control" id="house_no" name="house_no[]" placeholder="..." inputmode="numeric" min="1">
                                                </div>
                                            
                                                <div class="col-md-5">
                                                    <label for="street" class="form-label"><b>Street</b></label>
                                            
                                                    <input type="text" class="form-control" id="street" name="street[]" placeholder="...">
                                                </div>
                                            
                                                <div class="col-md-4">
                                                    <label for="building_apartment_condo" class="form-label" style="white-space: nowrap;">
                                                        <b>Building / Apartment / Condo</b>
                                                    </label>
                                            
                                                    <input type="text" class="form-control" id="building_apartment_condo" name="building_apartment_condo[]" placeholder="...">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="city" class="form-label" style="white-space: nowrap;"><b>City</b></label>

                                                    <input type="text" class="form-control" id="city" name="city[]" placeholder="...">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="zip_code" class="form-label" style="white-space: nowrap;"><b>Zip Code</b></label>

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
                            </div> --}}
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
                                                <label class="form-label"><b>Upload Valid ID with Address</b></label>
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

        {{-- For Counting Vehicles --}}

        <input type="hidden" id="list-of-vehicles" name="list_of_vehicles" value="[]">
    @endsection

    @section('links_js')
        {{-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> --}}
        <script src="{{ asset('js/12srur0123.js') }}"></script>
        <script src="{{ asset('js/srs3renewal1.js') }}"></script>
        {{-- <script src="{{ asset('js/srs3addvehicle.js') }}"></script> --}}
        <script>
            let listOfVehicles = [];
        
            @foreach ($crm->CRMXIvehicles as $vehicle)
                listOfVehicles.push({{ $vehicle->id }});
            @endforeach
            
            // Set the hidden input's value as a JSON string
            document.getElementById('list-of-vehicles').value = JSON.stringify(listOfVehicles);

        // For Removing Button
        document.querySelectorAll('.btn-remove').forEach(button => {
            button.addEventListener('click', function() {
                const vehicleId = this.getAttribute('data-id');
                const row = document.getElementById('vehicle-row-' + vehicleId);

                // Show a confirmation alert
                if (confirm('Are you sure you want to remove this vehicle from the list?')) {
                    if (row) {
                        row.remove(); // Remove the row from the DOM
                        listOfVehicles = listOfVehicles.filter(id => id != vehicleId); // Remove the vehicle ID from the array
                    }
                }
                // If the user clicks 'No', the row won't be removed

                // Update the hidden input field with the modified array
                document.getElementById('list-of-vehicles').value = JSON.stringify(listOfVehicles);
            });
        });
        </script>
    @endsection
