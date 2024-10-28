@extends('layouts.guest')

@section('title', 'Sticker Application Request')

@section('content')

    <div class="container px-md-5">
        <div class=" px-md-5 mb-3">
            @if ($errors->any())
                <div class="alert close-alert alert-danger alert-dismissible fade show text-center mt-3" role="alert">
                    {{-- <strong>{{ implode('', $errors->all(':message')) }}</strong> --}}
                    @foreach ($errors->all() as $message)
                        <strong>{{ $message }}</strong>
                        <br>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            {{-- @if (true) --}}
            @if (Session::has('requestAddSuccess'))
                <div class="alert alert-success mt-5" role="alert">
                    <div class="col-12 text-center">
                        <strong>Application Form Successfully Submitted!</strong>
                    </div>
                    <div class="col-12 mt-3">
                        Your Request ID: <strong>{{ Session::get('requestAddSuccess') }} </strong>
                    </div>
                    <div class="col-12 mt-2">
                        Once your request is approved, you will receive an appointment booking link from our admin agent.
                    </div>
                    <div class="col-12 mt-2">
                        You can check the status of your request with your request ID <a
                            href="{{ route('request.status', ['q' => session()->get('requestAddSuccess')]) }}"
                            target="_blank">here</a>.
                    </div>
                </div>
            @else
                <div class="card mt-3 shadow mb-5 bg-body rounded">
                    <div class="card-header text-center bg-primary" style="color: white;">
                        {{-- <img src="{{ asset('images/bflogo.png') }}" height="100" width="100" alt=""> --}}
                        <h5>BFFHAI</h5>
                        <h5>Sticker Application Form Version 3</h5>
                        {{-- <h5>{{ $tempId }}</h5> --}}
                    </div>
                    <div class="container justify-content-center align-items-center">
                        <div class="p-md-4 mt-1 mb-3">
                            <form action="{{ route('request.v3.store') }}" id="sticker_request_form" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row px-2 px-md-0">
                                    <!-- First Row -->
                                    <div class="col-md-6 col-12 mt-2">
                                        <div class="form-floating">
                                            <select class="form-select" name="account_type" id="account_type"
                                                onclick="toggleFields()">
                                                <option value="0" selected>Individual</option>
                                                <option value="1">Company</option>
                                            </select>
                                            <label for="account_type">Account Type</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12 mt-2">
                                        <div class="input-group" style="height: 100%;">
                                            <label for="" class="input-group-text">Request for</label>
                                            <select name="category" id="category" class="form-select">
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Old sub cat, have onload value --}}
                                    {{-- <div class="col-md-6 col-12 mt-2">
                                        <div class="form-floating">
                                            <select class="form-select" name="sub_category" id="sub_category"
                                                placeholder="Category" onchange="getRequirements()">
                                            </select>
                                            <label for="sub_categories">Sub-Category</label>
                                        </div>
                                    </div> --}}

                                    {{-- Old Hoa, connected to file attachements --}}
                                    {{-- <div class="col-md-6 col-12 mt-2" id="hoa_tab">
                                        <div class="form-floating">
                                            <select class="form-select" name="hoa" id="hoa" required>
                                                <!-- Options will be populated here -->
                                            </select>
                                            <label for="hoa" class="form-label" id="hoa_label">HOA</label>
                                        </div>
                                    </div> --}}

                                    <!-- Second Row -->
                                    <div class="col-md-6 col-12 mt-2">
                                        <div class="form-floating">
                                            <select class="form-select" name="sub_category_1" id="sub_category_1">
                                                <!-- Options will be populated dynamically -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 mt-2" id="hoa_tab">
                                        <div class="form-floating">
                                            <select class="form-select" name="hoa_1" id="hoa_1" required>
                                                @foreach ($hoas as $hoa)
                                                    <option value="{{ $hoa->id }}">
                                                        {{ $hoa->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div id="dos_msg" class="col-12 text-center mt-3" style="display: none;">
                                    <p style="color: red;"><b>For Deed of Sale, kindly secure application form with
                                            endorsement(HOA) and attach supporting documents. Visit Clubhouse for manual
                                            application.</b></p>
                                </div>
                                <div id="lock_msg" class="col-12 text-center mt-3" style="display: none;">
                                    <p style="color: red;"><b>All Non Residents Sticker Application will resume processing
                                            on Feb 2024</b></p>
                                </div>
                                <div id="lock2_msg" class="col-12 text-center mt-3" style="display: none;">
                                    <p style="color: red;"><b>Resident HOA Non-Member Application will resume processing on
                                            Feb 2024</b></p>
                                </div>
                                <div id="srs_tab">
                                    <div class="px-2 px-md-0 mb-4 mt-5">
                                        <strong>
                                            <h5>Owner Information</h5>
                                        </strong>
                                    </div>
                                    <div>
                                        {{-- Connected to account_type Toggle --}}
                                        <div class="px-2 px-md-4" id="individualFields">
                                            <div class="row mt-3">
                                                <div class="col-md-4 col-6">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" id="first_name"
                                                            name="first_name" placeholder="First Name"
                                                            value="{{ old('first_name') }}" required>
                                                        <label for="first_name" class="form-label"
                                                            style="color: grey;">First Name</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" id="last_name"
                                                            name="last_name" placeholder="Last Name"
                                                            value="{{ old('last_name') }}" required>
                                                        <label for="last_name" class="form-label" style="color: grey;">Last
                                                            Name</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-6 mt-2 mt-md-0">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" id="middle_name"
                                                            name="middle_name" placeholder="Middle Name"
                                                            value="{{ old('middle_name') }}" required>
                                                        <label for="middle_name" class="form-label"
                                                            style="color: grey;">Middle Name</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-2 px-md-4" id="companyFields" style="display: none;">
                                            <div class="row mt-3">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" id="company_name"
                                                            name="company_name" placeholder="Company Name"
                                                            value="{{ old('company_name') }}">
                                                        <label for="company_name" class="form-label"
                                                            style="color: grey;">Company Name</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12 mt-2 mt-md-0">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control"
                                                            id="company_representative" name="company_representative"
                                                            placeholder="Company Representative"
                                                            value="{{ old('company_representative') }}">
                                                        <label for="company_representative" class="form-label"
                                                            style="color: grey;">Company Representative</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Connected to account_type Toggle  End --}}
                                        <div class="row px-2 px-md-4 mt-3">
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <select name="civil_status" id="civil_status" class="form-control"
                                                        placeholder="Select Civil Status">
                                                        <option value="">-----</option>
                                                        @foreach ($civilStatus as $status)
                                                            <option value="{{ $status->id }}">{{ $status->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <label for="civil_status">Civil Status</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <select name="nationality" id="nationality" class="form-control"
                                                        placeholder="Select Nationality">
                                                        <option value="">-----</option>
                                                        @foreach ($nationalities as $nationality)
                                                            <option value="{{ $nationality->id }}">
                                                                {{ $nationality->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="nationality">Nationality</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="tin_no"
                                                        name="tin_no" placeholder="Middle Name"
                                                        value="{{ old('tin_no') }}" required>
                                                    <label for="tin_no" class="form-label" style="color: grey;">TIN
                                                        NO</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row px-2 px-md-4 mt-3">
                                            {{-- <p style="font-weight: bold; font-size: 12px;">Note: Enter active email. Request confirmation and appointment booking link will be sent by email.</p> --}}
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="email"
                                                        name="email" placeholder="Email Address"
                                                        value="{{ old('email') }}" required>
                                                    <label for="email" class="form-label" style="color: grey;">Email
                                                        Address</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="contact_no"
                                                        name="contact_no" placeholder="Tel. No./Mobile No."
                                                        value="{{ old('contact_no') }}" required>
                                                    <label for="contact_no" class="form-label"
                                                        style="color: grey; font-size: 0.8rem;">Main Contact No.</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="secondary_contact_no"
                                                        name="secondary_contact_no" placeholder="Tel. No./Mobile No."
                                                        value="{{ old('secondary_contact_no') }}">
                                                    <label for="secondary_contact_no" class="form-label"
                                                        style="color: grey; font-size: 0.8rem;">Secondary Contact
                                                        No.</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="tertiary_contact_no"
                                                        name="tertiary_contact_no" placeholder="Tel. No./Mobile No."
                                                        value="{{ old('tertiary_contact_no') }}">
                                                    <label for="tertiary_contact_no" class="form-label"
                                                        style="color: grey; font-size: 0.8rem;">Tertiary Contact
                                                        No.</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="px-2 px-md-0 mb-4 mt-5">
                                        <strong>
                                            <h5>Address</h5>
                                        </strong>
                                    </div>
                                    {{-- START TEST --}}
                                    <div class="container mt-4">
                                        <!-- Button to trigger modal -->
                                        <button type="button" id="addAddressBtn" class="btn btn-primary mb-3"
                                            data-bs-toggle="modal" data-bs-target="#addressModal">Add Address</button>

                                        <!-- Table to show added addresses -->
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Category/Sub Category</th>
                                                    <th>HOA/Member Type</th>
                                                    <th>Address</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="addressesTable">
                                                <!-- Addresses will appear here -->
                                                <tr>
                                                    <td colspan="5" class="text-center">No Address</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Hidden input to store addresses array -->
                                    <input type="hidden" id="addressesArray" name="addresses">

                                    <div class="modal fade" id="addressModal" tabindex="-1"
                                        aria-labelledby="addressModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addressModalLabel">Add New Address</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="addressForm" novalidate>
                                                        <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                <label for="category_modal"
                                                                    class="form-label">Category</label>
                                                                <select name="category_modal" id="category_modal"
                                                                    class="form-select" class="form-select">
                                                                    {{-- <option value="">---</option> --}}
                                                                    @foreach ($categories as $category)
                                                                        <option value="{{ $category->id }}"
                                                                            data-name="{{ $category->name }}">
                                                                            {{ $category->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="sub_category_modal" class="form-label">Sub
                                                                    Category</label>
                                                                <select class="form-select" name="sub_category_modal"
                                                                    class="form-select" id="sub_category_modal">
                                                                    @foreach ($subcats as $subcat)
                                                                        <option value="{{ $subcat->id }}"
                                                                            data-name="{{ $subcat->name }}">
                                                                            {{ $subcat->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="HOA_modal" class="form-label">HOA</label>
                                                                <select class="form-select" name="HOA_modal"
                                                                    id="HOA_modal">
                                                                    @foreach ($hoas as $hoa)
                                                                        <option value="{{ $hoa->id }}"
                                                                            data-name="{{ $hoa->name }}">
                                                                            {{ $hoa->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                <label for="member_type_modal" class="form-label">Member
                                                                    Type</label>
                                                                <select class="form-select" name="member_type_modal"
                                                                    id="member_type_modal">
                                                                    @foreach ($hoatypes as $hoatype)
                                                                        <option value="{{ $hoatype->id }}"
                                                                            data-name="{{ $hoatype->name }}">
                                                                            {{ $hoatype->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2 mb-3">
                                                                <label for="block" class="form-label">Block</label>
                                                                <input type="number" class="form-control"
                                                                    id="block">
                                                                <div class="invalid-feedback">Please enter a valid block
                                                                    number.</div>
                                                            </div>
                                                            <div class="col-md-2 mb-3">
                                                                <label for="lot" class="form-label">Lot</label>
                                                                <input type="number" class="form-control"
                                                                    id="lot">
                                                                <div class="invalid-feedback">Please enter a valid lot
                                                                    number.
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="houseNumber" class="form-label">House
                                                                    Number</label>
                                                                <input type="number" class="form-control"
                                                                    id="houseNumber">
                                                                <div class="invalid-feedback">Please enter a valid house
                                                                    number.</div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                <label for="street_modal"
                                                                    class="form-label">Street</label>
                                                                <input type="text" class="form-control"
                                                                    id="street_modal">
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="building_name_modal"
                                                                    class="form-label">Building / Apartment / Condo</label>
                                                                <input type="text" class="form-control"
                                                                    id="building_name_modal">
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="subdivision_village_modal"
                                                                    class="form-label">Subdivision / Village</label>
                                                                <input type="text" class="form-control"
                                                                    id="subdivision_village_modal">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                <label for="city_modal" class="form-label">City</label>
                                                                <select class="form-select" name="city_modal"
                                                                    id="city_modal">
                                                                    <option value =''>---</option>
                                                                    @foreach ($cities as $city)
                                                                        <option value="{{ $city->bl_id }}"
                                                                            data-name="{{ $city->description }}">
                                                                            {{ $city->description }}</option>
                                                                    @endforeach
                                                                    <!-- Options will be populated here -->


                                                                </select>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label for="zipcode_modal"
                                                                    class="form-label">Zipcode</label>
                                                                <input type="text" class="form-control"
                                                                    id="zipcode_modal">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="button" id="saveAddressBtn"
                                                        class="btn btn-primary">Save Address</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- END TEST --}}
                                    <!-- Dropdown to show addresses -->
                                    {{-- <div class="container mt-4">
                                        <label for="addressDropdown" class="form-label">Select Address</label>
                                        <select id="addressDropdown" class="form-select">
                                            <option value="">-- Select Address --</option>
                                        </select>
                                    </div> --}}

                                    <!-- Button to trigger dropdown population -->
                                    {{-- <button id="populateDropdownBtn" class="btn btn-info mt-3" type="button"
                                        onclick="populateAddressDropdown()">Populate Dropdown</button> --}}

                                    {{-- <div>
                                        <div class="row px-2 px-md-4 mt-3">
                                            <div class="col-md-4 col-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="house_no"
                                                        name="house_no" placeholder="House No."
                                                        value="{{ old('house_no') }}" required>
                                                    <label for="house_no" class="form-label" style="color: grey;">House
                                                        No. / Block Lot</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="street"
                                                        name="street" placeholder="Street" value="{{ old('street') }}"
                                                        required>
                                                    <label for="street" class="form-label"
                                                        style="color: grey;">Street</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="building_name"
                                                        name="building_name" placeholder="Bulding / Apartment / Condo"
                                                        value="{{ old('building_name') }}">
                                                    <label for="building_name" class="form-label"
                                                        style="color: grey;">Bulding / Apartment / Condo</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row px-2 px-md-4 mt-3">
                                            <div class="col-md-4 col-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="subdivision_village"
                                                        name="subdivision_village" placeholder="Subdivision / Village"
                                                        value="{{ old('subdivision_village') }}">
                                                    <label for="subdivision_village" class="form-label"
                                                        style="color: grey;">Subdivision / Village</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <div class="form-floating">
                                                    <select class="form-select" name="city" id="city"
                                                        value="{{ old('city') }}">
                                                        <option value="" selected>-----</option>
                                                        @foreach ($cities as $city)
                                                            <option value="{{ $city->description }}">
                                                                {{ $city->description }}</option>
                                                        @endforeach
                                                        <option value="Others">Others</option>
                                                    </select>
                                                    <input style="display: none" type="text" name="city"
                                                        class="form-control" id="city-input" placeholder="City" required
                                                        disabled />
                                                    <label for="city" class="form-label"
                                                        style="color: grey;">City</label>
                                                </div>
                                            </div>
                                        </div>

                                    </div> --}}

                                    <div class="px-2 px-md-0 mb-4 mt-5">
                                        <strong>
                                            <h5>Vehicle Information</h5>
                                        </strong>
                                    </div>
                                    {{-- TEST --}}
                                    <div>
                                        <div class="container mt-4">
                                            <button type="button" id="addVehicleBtn" class="btn btn-primary mb-3"
                                                data-bs-toggle="modal" data-bs-target="#vehicleModal"
                                                onclick="populateAddressDropdown()">Add Vehicle</button>

                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Plate No.</th>
                                                        <th>Brand</th>
                                                        <th>Series</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="vehiclesTable">
                                                    <tr>
                                                        <td colspan="5" class="text-center">No Vehicle</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <input type="hidden" id="vehiclesArrayInput" name="vehicles">

                                        <div class="modal fade" id="vehicleModal" tabindex="-1"
                                            aria-labelledby="vehicleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="vehicleModalLabel">Add New Vehicle
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="vehicleForm" novalidate>
                                                            <div class="row">
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="plateNo" class="form-label">Plate
                                                                        No.</label>
                                                                    <input type="text" class="form-control"
                                                                        id="plateNo" name="plateNo"
                                                                        placeholder="Enter Plate No." required>
                                                                    <div class="invalid-feedback">Please enter a plate
                                                                        number.
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 mb-3">
                                                                    <label for="year_model"
                                                                        class="form-label">Year/Model</label>
                                                                    <select class="form-select" id="year_model"
                                                                        name="year_model" required>
                                                                        <option value="" disabled selected>Select
                                                                            Year</option>
                                                                        @foreach ($years as $year)
                                                                            <option value="{{ $year }}">
                                                                                {{ $year }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-2 mb-3">
                                                                    <label for="series"
                                                                        class="form-label">Series</label>
                                                                    <input type="text" class="form-control"
                                                                        id="series" name="series"
                                                                        placeholder="Enter Series" required>
                                                                    <div class="invalid-feedback">Please enter a series.
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 mb-3">
                                                                    <label for="vehicle_type" class="form-label">Type</label>
                                                                    <select class="form-select" id="vehicle_type" required>
                                                                        <option value="">Select Type</option>
                                                                        <option value="Car">Car</option>
                                                                        <option value="Motorcycle">Motorcycle</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="brand" class="form-label">Brand</label>
                                                                    <select class="form-select" id="brand" required>
                                                                        <option disabled selected value=""
                                                                        style="color: grey;">Select brand</option>
                                                                        <option value="Abarth">Abarth</option>
                                                                        <option value="Alfa Romeo">Alfa Romeo</option>
                                                                        <option value="Aprilia">Aprilia</option>
                                                                        <option value="Aston Martin">Aston Martin</option>
                                                                    </select>
                                                                    <div class="invalid-feedback">Please select a brand.
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="color" class="form-label">Color</label>
                                                                    <select class="form-select" id="color" required>
                                                                        <option disabled selected value=""
                                                                        style="color: grey;">Select color</option>
                                                                        <option value="Red">Red</option>
                                                                        <option value="Blue">Blue</option>
                                                                        <option value="Green">Green</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <h5>Onwer Infomation</h5>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="populateFromIndividual" />
                                                                <label class="form-check-label" for="populateFromIndividual">Same as Account</label>
                                                            </div>

                                                            <div class="row mb-3">
                                                                <div class="col">
                                                                    <label for="first_name_modal">First Name</label>
                                                                    <input type="text" class="form-control" id="first_name_modal" placeholder="Enter first name">
                                                                </div>
                                                                <div class="col">
                                                                    <label for="middle_name_modal">Middle Name</label>
                                                                    <input type="text" class="form-control" id="middle_name_modal" placeholder="Enter middle name">
                                                                </div>
                                                                <div class="col">
                                                                    <label for="last_name_modal">Last Name</label>
                                                                    <input type="text" class="form-control" id="last_name_modal" placeholder="Enter last name">
                                                                </div>
                                                            </div>
                                                        
                                                            <div class="row mb-3">
                                                                <div class="col">
                                                                    <label for="main_contact_no_modal">Main Contact No</label>
                                                                    <input type="text" class="form-control" id="main_contact_no_modal" placeholder="Enter main contact number">
                                                                </div>
                                                                <div class="col">
                                                                    <label for="secondary_contact_no_modal">Secondary Contact No</label>
                                                                    <input type="text" class="form-control" id="secondary_contact_no_modal" placeholder="Enter secondary contact number">
                                                                </div>
                                                                <div class="col">
                                                                    <label for="tertiary_contact_no_modal">Tertiary Contact No</label>
                                                                    <input type="text" class="form-control" id="tertiary_contact_no_modal" placeholder="Enter tertiary contact number">
                                                                </div>
                                                            </div>

                                                            <br>
                                                            <div class="row">
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="addressDropdown" class="form-label">Select
                                                                        Address</label>
                                                                    <select class="form-select" id="addressDropdown" required>
                                                                        <option value="">-- Select Address --</option>
                                                                    </select>
                                                                    <div class="invalid-feedback">Please select an address.
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 mb-3">
                                                                    <label for="vehicle_ownership_type_modal"
                                                                        class="form-label">Vehicle Ownership Type</label>
                                                                    <select class="form-select" id="vehicle_ownership_type_modal"
                                                                        name="vehicle_ownership_type_modal" required>
                                                                        <option value="" disabled selected>Select Here...</option>
                                                                        @foreach ($vehicleOwnershipTypes as $type)
                                                                            <option value="{{ $type->id }}">
                                                                                {{ $type->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="mb-3">

                                                            </div>
                                                            <div>
                                                                
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" id="saveVehicleBtn"
                                                            class="btn btn-primary">Save Vehicle</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    {{-- TEST --}}


                                    {{-- <div>
                                        <div id="vehicle_tab" class="px-md-3">
                                            <div class="p-3 p-md-4 card shadow rounded mb-2 mb-md-4">
                                                <div class="row mt-2">
                                                    <div id="v_req_type_tab" class="col-12 col-md-3">
                                                        <div class="form-floating">
                                                            <select class="form-select req_type" id="req_type"
                                                                name="req_type[]" placeholder="Request Type" required>
                                                                <option value="0">New</option>
                                                            </select>
                                                            <label for="" class="form-label">Request Type</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2 g-2">
                                                    <div id="v_plate_no_tab" class="col-6 col-md">
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control" id="plate_no"
                                                                name="plate_no[]" placeholder="Plate No." required>
                                                            <label for="plate_no" class="form-label"
                                                                style="color: grey;">Plate No.</label>
                                                        </div>
                                                    </div>
                                                    <div id="v_brand_tab" class="col-6 col-md">
                                                        <div class="form-floating">
                                                            <select class="form-select" id="brand" name="brand[]"
                                                                placeholder="Brand" required>
                                                                <option disabled selected value=""
                                                                    style="color: grey;">Select brand</option>
                                                                <option value="Abarth">Abarth</option>
                                                                <option value="Alfa Romeo">Alfa Romeo</option>
                                                                <option value="Aprilia">Aprilia</option>
                                                                <option value="Aston Martin">Aston Martin</option>
                                                                <option value="Audi">Audi</option>
                                                                <option value="Bentley">Bentley</option>
                                                                <option value="BMW">BMW</option>
                                                                <option value="Bristol">Bristol</option>
                                                                <option value="BYD">BYD</option>
                                                                <option value="CFMoto">CFMoto</option>
                                                                <option value="Changan">Changan</option>
                                                                <option value="Changhe">Changhe</option>
                                                                <option value="Chery">Chery</option>
                                                                <option value="Chevrolet">Chevrolet</option>
                                                                <option value="Chrysler">Chrysler</option>
                                                                <option value="Dodge">Dodge</option>
                                                                <option value="Ducati">Ducati</option>
                                                                <option value="FAW">FAW</option>
                                                                <option value="Ferrari">Ferrari</option>
                                                                <option value="Fiat">Fiat</option>
                                                                <option value="Ford">Ford</option>
                                                                <option value="Foton">Foton</option>
                                                                <option value="GAC">GAC</option>
                                                                <option value="GAZ">GAZ</option>
                                                                <option value="Geely">Geely</option>
                                                                <option value="Great Wall">Great Wall</option>
                                                                <option value="Haima">Haima</option>
                                                                <option value="Harley-Davidson">Harley-Davidson</option>
                                                                <option value="Honda">Honda</option>
                                                                <option value="Husqvarna">Husqvarna</option>
                                                                <option value="Hyundai">Hyundai</option>
                                                                <option value="Isuzu">Isuzu</option>
                                                                <option value="JAC">JAC</option>
                                                                <option value="Jaguar">Jaguar</option>
                                                                <option value="Jeep">Jeep</option>
                                                                <option value="JMC">JMC</option>
                                                                <option value="Kaicene">Kaicene</option>
                                                                <option value="Kawasaki">Kawasaki</option>
                                                                <option value="Kia">Kia</option>
                                                                <option value="King Long">King Long</option>
                                                                <option value="KTM">KTM</option>
                                                                <option value="Kymco">Kymco</option>
                                                                <option value="Lamborghini">Lamborghini</option>
                                                                <option value="Land Rover">Land Rover</option>
                                                                <option value="Lexus">Lexus</option>
                                                                <option value="Lifan">Lifan</option>
                                                                <option value="Lotus">Lotus</option>
                                                                <option value="Mahindra">Mahindra</option>
                                                                <option value="Maserati">Maserati</option>
                                                                <option value="Maxus">Maxus</option>
                                                                <option value="Mazda">Mazda</option>
                                                                <option value="Mercedes-Benz">Mercedes-Benz</option>
                                                                <option value="MG">MG</option>
                                                                <option value="MINI">MINI</option>
                                                                <option value="Mitsubishi">Mitsubishi</option>
                                                                <option value="Morgan">Morgan</option>
                                                                <option value="Nissan">Nissan</option>
                                                                <option value="Peugeot">Peugeot</option>
                                                                <option value="Porsche">Porsche</option>
                                                                <option value="RAM">RAM</option>
                                                                <option value="Rolls-Royce">Rolls-Royce</option>
                                                                <option value="Royal Enfield">Royal Enfield</option>
                                                                <option value="SsangYong">SsangYong</option>
                                                                <option value="Subaru">Subaru</option>
                                                                <option value="Suzuki">Suzuki</option>
                                                                <option value="Tata">Tata</option>
                                                                <option value="Toyota">Toyota</option>
                                                                <option value="Volkswagen">Volkswagen</option>
                                                                <option value="Volvo">Volvo</option>
                                                                <option value="Yamaha">Yamaha</option>
                                                                <option value="Others">Others</option>
                                                            </select>
                                                            <input style="display: none" type="text" name="brand[]"
                                                                class="form-control" id="brand-input" placeholder="Brand"
                                                                required disabled />
                                                            <label for="" class="form-label">Brand</label>
                                                        </div>
                                                    </div>
                                                    <div id="v_series_tab" class="col-6 col-md">
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control" id="series"
                                                                name="series[]" placeholder="Series" required>
                                                            <label for="series" class="form-label"
                                                                style="color: grey;">Series</label>
                                                        </div>
                                                    </div>
                                                    <div id="v_year_model_tab" class="col-6 col-md">
                                                        <div class="form-floating">
                                                            <select class="form-select" id="year_model"
                                                                name="year_model[]" placeholder="Year/Model" required>
                                                                <option disabled selected value=""
                                                                    style="color: grey;">Select Year/Model</option>
                                                            </select>
                                                            <label for="" class="form-label">Year/Model</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2 g-2 g-md-3">
                                                    <div id="v_color_tab" class="col-6 col-md-3">
                                                        <div class="form-floating">
                                                            <select class="form-select" id="v_color" name="v_color[]"
                                                                placeholder="Color" required>
                                                                <option disabled selected value=""
                                                                    style="color: grey;">Select Color</option>
                                                                <option value="White">White</option>
                                                                <option value="Black">Black</option>
                                                                <option value="Gray">Gray</option>
                                                                <option value="Silver">Silver</option>
                                                                <option value="Blue">Blue</option>
                                                                <option value="Red">Red</option>
                                                                <option value="Brown">Brown</option>
                                                                <option value="Green">Green</option>
                                                                <option value="Orange">Orange</option>
                                                                <option value="Beige">Beige</option>
                                                                <option value="Purple">Purple</option>
                                                                <option value="Gold">Gold</option>
                                                                <option value="Yellow">Yellow</option>
                                                                <option value="Others">Others</option>
                                                            </select>
                                                            <input style="display: none" type="text" name="v_color[]"
                                                                class="form-control" id="v_color-input"
                                                                placeholder="Color" required disabled />
                                                            <label for="" class="form-label"
                                                                style="color: grey;">Color</label>
                                                        </div>
                                                    </div>
                                                    <div id="v_type_tab" class="col-6 col-md-3">
                                                        <div class="form-floating">
                                                            <select class="form-select" id="v_type" name="v_type[]"
                                                                placeholder="Type" required>
                                                                <option disabled selected value=""
                                                                    style="color: grey;">Select Type</option>
                                                                <option>Car</option>
                                                                <option>Motorcycle</option>
                                                            </select>
                                                            <label for="" class="form-label"
                                                                style="color: grey;">Type</label>
                                                        </div>
                                                    </div>
                                                    <div id="v_sticker_no_tab" class="col-6 col-md-3 class-sticker_no"
                                                        style="display: none;">
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control" id="sticker_no"
                                                                name="sticker_no[]" placeholder="Sticker No.">
                                                            <label for="sticker_no" class="form-label"
                                                                style="color: grey;">Sticker No.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2 g-2 g-md-3">
                                                    <div id="v_or_tab" class="col-12 col-md-4">
                                                        <label class="form-label">OR</label>
                                                        <input type="file" accept="image/*"
                                                            class="form-control form-control-sm" name="or[]" required>
                                                    </div>
                                                    <div id="v_cr_tab" class="col-12 col-md-4">
                                                        <label class="form-label">CR</label>
                                                        <input type="file" accept="image/*"
                                                            class="form-control form-control-sm" name="cr[]" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-md-3" id="vehicles_row">

                                        </div>
                                        <div class="col-12 p-3" style="text-align: right;">
                                            <button type="button" class="btn btn-sm btn-info" onclick="addVehicle()"
                                                style="color: white;">Add vehicle</button>
                                        </div>
                                    </div> --}}


                                    <div class="row justify-content-center">
                                        <div class="col-md-12 px-md-5">
                                            <div
                                                style="padding: 1.0rem;
                                            margin-top: 1.25rem;
                                            margin-bottom: 1.25rem;
                                            border: 1px solid #e9ecef;
                                            border-left-width: 0.25rem;
                                            border-radius: 0.25rem;
                                            border-left-color: #f0ad4e;
                                            font-size: 14px;">
                                                <strong>Note:</strong><br>
                                                <strong>File uploads are accepting file formats in .JPG, .JPEG, and
                                                    .PNG</strong><br>
                                                <strong>Valid ID's must contain your address</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-2 mt-5">
                                        <div id="requirements_table" class="table-responsive" style="display: none;">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" width="50%;">Mandatory Requirements</th>
                                                        <th class="text-center" width="50%;">Attachments</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="requirements_tbody">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div id="rules_regulation" class="px-2 px-md-0 mb-3 mt-4">
                                        <strong>
                                            <h3>Rules and Regulations</h3>
                                        </strong>
                                    </div>
                                    <div id="rules_regulation_tab"
                                        class="border-start border-top border-bottom border-2 rounded"
                                        style="height: 250px; overflow-y: scroll;">
                                        <div class="px-2 mt-md-2 px-md-2">
                                            <div>
                                                <ol>
                                                    <li class="mb-2">
                                                        Elizalde gate is for the exclusive use of homeowners with <b>BFFHAI
                                                            VALID</b> stickers. Non-residents and all commercial vehicles
                                                        <b>are NOT ALLOWED</b> to use this gate.
                                                    </li>
                                                    <li class="mb-2">
                                                        Observe the following traffic & parking rules:
                                                        <ol style="list-style-type: lower-alpha">
                                                            <li class="mb-1">
                                                                Use only the appropriate lanes at the entrance and exit
                                                                gates. Overtaking in line is strictly prohibited.
                                                            </li>
                                                            <li class="mb-1">
                                                                Observe maximum speed of 40kph.
                                                            </li>
                                                            <li class="mb-1">
                                                                Avoid blowing of horn loudly and unnecessarily. Modified
                                                                Mufflers are strictly prohibited.
                                                            </li>
                                                            <li class="mb-1">
                                                                Double parking is not allowed. No counter-directional
                                                                parking.
                                                            </li>
                                                            <li class="mb-1">
                                                                No Parking for Service and Public Utility Vehicles along the
                                                                streets. They should be parked outside BF Homes premises or
                                                                at
                                                                designated parking areas approved by BFFHAI.
                                                            </li>
                                                            <li class="mb-1">
                                                                The main avenues, namely : Aguirre, El Grande, Elizalde,
                                                                Tirona, Concha Cruz and Tropical Palace Streets, <b>are NO
                                                                    PARKING
                                                                    and CLAMPING ZONES</b>.
                                                            </li>
                                                        </ol>
                                                    </li>
                                                    <li class="mb-2">
                                                        The vehicle's owner shall be liable and responsible for all acts of
                                                        the driver while operating the vehicle inside BF Homes Subdivision
                                                        (BFFHAI) premises like violation of traffic rules, damages to person
                                                        and property, etc.
                                                    </li>
                                                    <li class="mb-2">
                                                        BFFHAI reserves the right to <b>REMOVE / CONFISCATE</b> the sticker
                                                        and/or <b>DENY ENTRY</b> in case of violation of BFFHAI's Traffic
                                                        Rules and Regulations. Also, the use of the sticker may be suspended
                                                        during the occurrence of any fortuitous event, public
                                                        health condition, pandemic, national/local emergency and the like
                                                        without any liability of BFFHAI.
                                                    </li>
                                                    <li class="mb-2">
                                                        In case of a breach or violation of BFFHAI Traffic Rules and
                                                        Regulations, violator must present his driver's license to the
                                                        BFFHAI security guards for identity and authentication purposes.
                                                    </li>
                                                    <li class="mb-2">
                                                        The issuance of this sticker does not carry any acceptance of
                                                        liability on the part of BFFHAI.
                                                    </li>
                                                    <li class="mb-2">
                                                        Lost/stolen or damaged stickers shall be replaced after submission
                                                        of a DULY NOTARIZED Affidavit of Loss and payment of the
                                                        corresponding replacement fee.
                                                    </li>
                                                    <li class="mb-2">
                                                        Use of the sticker may be suspended during the occurrence of any
                                                        fortuitous event, national / local emergency pandemic or any
                                                        public health condition and the like without any liability on the
                                                        part of BFFHAI.
                                                    </li>
                                                    <li class="mb-2">
                                                        Transfer of sticker to another vehicle is strictly prohibited and
                                                        subject to penalty.
                                                    </li>
                                                    <li class="mb-2">
                                                        Making misrepresentation or using tampered / falsified OR/CR in the
                                                        application for sticker is a criminal offense and, hence, is
                                                        strictly prohibited. Such application will be denied outright and is
                                                        subject to stricter penalty, without prejudice to possible criminal
                                                        prosecution.
                                                    </li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="rules_note" class="px-2 px-md-3 mt-2 text-end">
                                        <sup><span style="color: red;">*</span> <small><b>Scroll to the end of Rules and
                                                    Regulation</b></small></sup>
                                    </div>
                                    <div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="checkbox"
                                                value="1" id="checkboxCertify" onchange="toggleUndertaking()"
                                                disabled required>
                                            <label class="form-check-label text-muted" for="checkboxCertify">
                                                <b>I hereby certify that I have read all the Rules and Regulations above;
                                                    and I abide to follow them</b>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="undertaking_tab" style="display: none;">
                                        <div class="px-2 px-md-0 mb-4 mt-4">
                                            <strong>
                                                <h5>Applicant's Undertaking</h5>
                                            </strong>
                                        </div>
                                        <div>
                                            <div class="px-2 px-md-3">
                                                <div style="text-indent: 30px;">
                                                    <p style="font-weight: 600; font-size: 13px;">I HEREBY BIND MYSELF TO
                                                        FOLLOW ALL RULES AND REGULATION AS STATED ABOVE AND THAT UPON SALE
                                                        OR DISPOSAL
                                                        OF MY VEHICLE I COMMIT TO REMOVE THE VEHICLE STICKER AND RETURN THE
                                                        SAME TO THE BFFHAI OFFICE. I HEREBY FURTHER
                                                        BIND MYSELF TO THE FOLLOWING APPLICANT’S UNDERTAKING:</p>
                                                </div>
                                                <div class="row px-md-3" style="font-weight: 600; font-size: 13px;">
                                                    <ol>
                                                        <li>
                                                            THAT THE STICKER IS NON-TRANSFERRABLE, CANNOT BE SOLD AND, OR
                                                            ASSIGNED TO A THIRD PARTY.
                                                        </li>
                                                        <li>
                                                            THAT THE BFFHAI RESERVES THE RIGHT TO CONFISCATE THE STICKER FOR
                                                            ANY VIOLATION OF THE RULES AND
                                                            REGULATIONS OF BFFHAI, FOR ANY MISDECLARATION /
                                                            MISREPRESENTATION IN THE APPLICATION FORM AND FOR THE
                                                            MISUSE OF THE STICKER.
                                                        </li>
                                                        <li>
                                                            THAT A COPY OF THE BFFHAI OFFICIAL RECEIPT SHALL BE KEPT IN THE
                                                            VEHICLE AT ALL TIMES.
                                                        </li>
                                                        <li>
                                                            I AM FULLY AWARE THAT BFFHAI RETAINS PERMANENT OWNERSHIP OF THE
                                                            STICKER AND ISSUED TO APPLICANTS ONLY AS
                                                            A PRIVILEGE OF PASSAGE; HENCE, BFFHAI HAS THE LEGAL RIGHT TO
                                                            INITIATE AND FILE ANY, AND ALL CASES RELATIVE TO
                                                            THE STICKER.
                                                        </li>
                                                        <li>
                                                            THIS PRIVATE SUBDIVISION STICKER IS ISSUED ONLY AS A PRIVILEGE
                                                            AND NOT A RIGHT.
                                                        </li>
                                                    </ol>
                                                    <p class="text-center">
                                                        I CERTIFY THAT I HAVE READ ALL THE INFORMATION STATED HEREIN AND
                                                        COMMIT TO FOLLOW BFFHAI RULES AND
                                                        REGULATIONS. I FURTHER CERTIFY THAT ALL INFORMATION DECLARED HEREIN
                                                        ARE TRUE AND CORRECT.
                                                    </p>
                                                    <p class="text-center">
                                                        I HEREBY AUTHORIZE BFFHAI, TO COLLECT AND PROCESS THE DATA INDICATED
                                                        HEREIN.
                                                        I UNDERSTAND THAT MY PERSONAL INFORMATION IS PROTECTED BY RA 10173,
                                                        DATA PRIVACY ACT OF 2012, AND THAT I AM REQUIRED BY RA 11469, TO
                                                        PROVIDE TRUTHFUL INFORMATION.
                                                    </p>
                                                </div>
                                                <div class="row g-2 mt-2">
                                                    <div class="col-md-7 mt-md-2">
                                                        <div class="row border border-dark border-2 g-0 py-2">
                                                            <p class="text-center"
                                                                style="font-size: 11px; font-weight: 800; margin-bottom: 0;">
                                                                *** DO NOT ACQUIRE FAKE STICKER - PURCHASE ONLY AT BBFHAI
                                                                CLUBHOUSE ***</p>
                                                        </div>
                                                        <div class="row text-center p-1">
                                                            <p
                                                                style="font-size: 11px; font-weight: 800; margin-bottom: 0;">
                                                                P5,000 penalty. One-year ban. Criminal Case will be filed
                                                            </p>
                                                        </div>
                                                        {{-- <div class="row justify-content-center px-md-3">
                                                    <p class="text-center" style="font-weight: 600;">DO NOT BUY FAKE STICKER</p>
                                                    <div class="col-md-auto">
                                                        <ul style="font-weight: 500;">
                                                            <li>P5,000.00 penalty</li>
                                                            <li>One-year ban</li>
                                                            <li>Criminal Case will be filed</li>
                                                        </ul>
                                                    </div>
                                                </div> --}}
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="m-signature-pad--body">
                                                            <input id="sig-input" name="signature" type="hidden"
                                                                value="">
                                                            <canvas class="w-100"
                                                                style="border: 2px dashed #ccc;"></canvas>
                                                        </div>
                                                        <div class="m-signature-pad--footer" style="text-align: right">
                                                            <button type="button" class="btn btn-sm btn-dark"
                                                                id="clearSig" data-action="clear">Clear</button>
                                                        </div>
                                                        <div class="text-center">
                                                            <p style="font-weight: 500;">Signature</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="mt-5 text-center">
                                                <div class="d-flex justify-content-center">
                                                    <div id="g-recaptcha" class="g-recaptcha mt-3"
                                                        data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                                                </div>

                                                <button type="submit" id="request_submit_btn"
                                                    class="btn btn-primary mt-3" disabled>Submit Application</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('links_js')
    {{-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> --}}
    <script src="{{ asset('js/signature_pad.umd.min.js') }}"></script>
    {{-- <script src="{{ asset('js/11sr29.js') }}"></script> --}}
    <script src="{{ asset('js/11sr29_v3_decrypted.js') }}"></script>
    <script src="{{ asset('js/srs3/srs3NewApplication.js') }}"></script>
    <script src="{{ asset('js/srs3/srs3NewApplicationAddNewAddress.js') }}"></script>
    <script src="{{ asset('js/srs3/srs3PopulateDropdownWithAddress.js') }}"></script>
    <script src="{{ asset('js/srs3/srs3NewApplicationAddNewVehicle.js') }}"></script>
    {{-- <script src="{{ asset('js/srs3/srs3NewApplicationChangeSubCat.js') }}"></script> --}}

    {{-- Pivot of category to sub cat --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Trigger the fetch when the page loads with the current category value
            let categoryId = document.getElementById('category').value;
            fetchSubCategories(categoryId);
        });

        document.getElementById('category').addEventListener('change', function() {
            fetchSubCategories(this.value);
        });

        function fetchSubCategories(categoryId) {
            fetch(`/v3/sticker/request/sub_categories?category_id=${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    let subCategorySelect = document.getElementById('sub_category_1');
                    subCategorySelect.innerHTML = ''; // Clear current options

                    data.forEach(subcat => {
                        let option = document.createElement('option');
                        option.value = subcat.id;
                        option.text = subcat.name;
                        subCategorySelect.add(option);
                    });
                })
                .catch(error => console.error('Error fetching subcategories:', error));
        }
    </script>

    {{-- Disable HOA if category is (2) NON-RESIDENT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let categorySelect = document.getElementById('category');
            categorySelect.addEventListener('change', handleCategoryChange);
            handleCategoryChange(); // Check on page load
        });

        function handleCategoryChange() {
            let categorySelect = document.getElementById('category');
            let hoaSelect = document.getElementById('hoa_1');

            if (categorySelect.value == '2') {
                hoaSelect.disabled = true;
                hoaSelect.value = ''; // Clear selection if disabled
            } else {
                hoaSelect.disabled = false;
            }
        }
    </script>

    {{--  --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let categoryModalSelect = document.getElementById('category_modal');
            categoryModalSelect.addEventListener('change', handleModalCategoryChange);
            handleModalCategoryChange(); // Check on modal load
        });

        function handleModalCategoryChange() {
            let categoryModalSelect = document.getElementById('category_modal');
            let hoaModalSelect = document.getElementById('HOA_modal');

            if (categoryModalSelect.value == '2') {
                hoaModalSelect.disabled = true;
                hoaModalSelect.value = ''; // Clear selection if disabled
            } else {
                hoaModalSelect.disabled = false;
            }
        }
    </script>

@endsection
