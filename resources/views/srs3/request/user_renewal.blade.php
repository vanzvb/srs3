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
                                                <label for="hoa" class="form-label"><b>Account Type</b></label>
                                                <select class="form-select" name="hoa" id="hoa">
                                                    <option value="" style="color: grey;"
                                                        {{ !$crmHoaId ? 'selected' : '' }}>Select Account Type...</option>
                                                    <option value="individual"
                                                        {{ $crmHoaId == 'individual' ? 'selected' : '' }}>Individual
                                                    </option>
                                                    <option value="company" {{ $crmHoaId == 'company' ? 'selected' : '' }}>
                                                        Company</option>
                                                </select>
                                            </div>
                                        </div>
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
                                                <select class="form-select" name="hoa" id="hoa">
                                                    <option value="" style="color: grey;"
                                                        {{ !$crmHoaId ? 'selected' : '' }}>Please select Category</option>
                                                    {{-- @foreach ($hoas as $hoa)
                                                    <option value="{{ $hoa->id }}" {{ $hoa->id == $crmHoaId ? 'selected' : ''}}>{{ $hoa->name }}</option>
                                                @endforeach --}}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row p-2 g-0">
                                            <div class="col-md-12">
                                                <label for="hoa" class="form-label"><b>Sub Category</b></label>
                                                <select class="form-select" name="hoa" id="hoa">
                                                    <option value="" style="color: grey;"
                                                        {{ !$crmHoaId ? 'selected' : '' }}>Please select Sub Category
                                                    </option>
                                                    {{-- @foreach ($hoas as $hoa)
                                                    <option value="{{ $hoa->id }}" {{ $hoa->id == $crmHoaId ? 'selected' : ''}}>{{ $hoa->name }}</option>
                                                @endforeach --}}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="px-2 px-md-0 mb-4 mt-5">
                                <b>
                                    {{-- <h5>Vehicle Information</h5> --}}
                                </b>
                            </div>
                            <div>
                                @foreach ($crm->vehicles as $vehicle)
                                    <div class="p-3 p-md-3 card shadow rounded mb-2 mb-md-4">
                                        <div class="card-header" style="background-color: white; border-bottom: 0;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{-- <label>FOR RENEWAL</label> --}}

                                                </div>
                                                <div class="col-md-6 text-end">
                                                    <button class="btn-close v_remove_btn"></button>
                                                </div>
                                            </div>
                                        </div>
                                        <h4>For Renewal</h5>
                                            <br>
                                            <h5>Vehicle Information</h5>
                                            <div class="row mt-2 g-2">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label for="plate_no" class="form-label" style="color: grey;">Plate
                                                            No.:</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        {{ $vehicle->plate_no }}
                                                        <input type="hidden" class="form-control" id="plate_no"
                                                            name="" placeholder="Plate No." required readonly
                                                            value="{{ $vehicle->plate_no }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label for="brand" class="form-label"
                                                            style="color: grey;">Brand:</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        {{ $vehicle->brand }}
                                                        <input type="hidden" name="ref[]">
                                                        <input type="hidden" name="vref[]"
                                                            value="{{ $vehicle->id }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="series" class="form-label"
                                                            style="color: grey;">Series:</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        {{ $vehicle->series }}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label for="color" class="form-label"
                                                            style="color: grey;">Color:</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        {{ $vehicle->color }}
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="year_model" class="form-label"
                                                            style="color: grey;">Year/Model:</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        {{ $vehicle->year_model }}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label for="type" class="form-label"
                                                            style="color: grey;">Type:</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        {{ $vehicle->type }}
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="year_model" class="form-label"
                                                            style="color: grey;">Sticker No.:</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        {{ $vehicle->new_sticker_no }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="px-3 py-1">
                                                    <div class="form-check">
                                                        <input class="form-check-input new_plate_no" type="checkbox"
                                                            name="new_plate_no_chk[{{ $vehicle->id }}]" value="1"
                                                            id="new_plate_no[{{ $vehicle->id }}]">
                                                        <label class="form-check-label"
                                                            for="new_plate_no[{{ $vehicle->id }}]">
                                                            I have a new plate no. <sup>(If using conduction no.)</sup>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-3 new_plate_no_input" style="display: none;">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control form-control-sm"
                                                            id="" name="new_plate_no[{{ $vehicle->id }}]"
                                                            placeholder="New Plate No.">
                                                        <label for="" class="form-label" style="color: grey;">New
                                                            Plate No.</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="px-3 py-1">
                                                    <div class="form-check">
                                                        <input class="form-check-input new_color" type="checkbox"
                                                            name="new_color_chk[{{ $vehicle->id }}]" value="1"
                                                            id="new_color_chk[{{ $vehicle->id }}]">
                                                        <label class="form-check-label"
                                                            for="new_color_chk[{{ $vehicle->id }}]">
                                                            New color
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-3 new_color_input" style="display: none;">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control form-control-sm"
                                                            id="" name="new_color[{{ $vehicle->id }}]"
                                                            placeholder="New Color">
                                                        <label for="" class="form-label" style="color: grey;">New
                                                            Color</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">

                                                <h5>Owner Information</h5>
                                                <div class="row">
                                                    <div class="col-md-4 p-2">
                                                        <label for="first_name" class="form-label"><b>First
                                                                Name</b></label>
                                                        <input type="text" class="form-control" id="first_name"
                                                            name="first_name" placeholder="Enter First Name">
                                                    </div>
                                                    <div class="col-md-4 p-2">
                                                        <label for="middle_name" class="form-label"><b>Middle
                                                                Name</b></label>
                                                        <input type="text" class="form-control" id="middle_name"
                                                            name="middle_name" placeholder="Enter Middle Name">
                                                    </div>
                                                    <div class="col-md-4 p-2">
                                                        <label for="last_name" class="form-label"><b>Last Name</b></label>
                                                        <input type="text" class="form-control" id="last_name"
                                                            name="last_name" placeholder="Enter Last Name">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1 p-2">
                                                        <label for="block" class="form-label"><b>Block</b></label>
                                                        <input type="text" class="form-control" id="block"
                                                            name="block" placeholder="...">
                                                    </div>
                                                    <div class="col-md-1 p-2">
                                                        <label for="lot" class="form-label"><b>Lot</b></label>
                                                        <input type="text" class="form-control" id="lot"
                                                            name="lot" placeholder="...">
                                                    </div>
                                                    <div class="col-md-1 p-2">
                                                        <label for="house_no" class="form-label"
                                                            style="white-space: nowrap;"><b>House No.</b></label>
                                                        <input type="text" class="form-control" id="house_no"
                                                            name="house_no" placeholder="...">
                                                    </div>
                                                    <div class="col-md-5 p-2">
                                                        <label for="hoa" class="form-label"><b>Street</b></label>
                                                        <select class="form-select" name="hoa" id="hoa">
                                                            <option value="" style="color: grey;"
                                                                {{ !$crmHoaId ? 'selected' : '' }}>Please Select Street
                                                            </option>
                                                            {{-- @foreach ($hoas as $hoa)
                                                        <option value="{{ $hoa->id }}" {{ $hoa->id == $crmHoaId ? 'selected' : ''}}>{{ $hoa->name }}</option>
                                                        @endforeach --}}
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 p-2">
                                                        <label for="house_no" class="form-label"
                                                            style="white-space: nowrap;"><b>Building / Apartment /
                                                                Condo</b></label>
                                                        <input type="text" class="form-control" id="house_no"
                                                            name="house_no" placeholder="...">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 p-2">
                                                        <label for="hoa" class="form-label"><b>Subdivision /
                                                                Village</b></label>
                                                        <select class="form-select" name="hoa" id="hoa">
                                                            <option value="" style="color: grey;"
                                                                {{ !$crmHoaId ? 'selected' : '' }}>Please Select Subd /
                                                                Village</option>
                                                            {{-- @foreach ($hoas as $hoa)
                                                        <option value="{{ $hoa->id }}" {{ $hoa->id == $crmHoaId ? 'selected' : ''}}>{{ $hoa->name }}</option>
                                                        @endforeach --}}
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 p-2">
                                                        <label for="house_no" class="form-label"
                                                            style="white-space: nowrap;"><b>City</b></label>
                                                        <input type="text" class="form-control" id="house_no"
                                                            name="house_no" placeholder="...">
                                                    </div>
                                                    <div class="col-md-2 p-2">
                                                        <label for="house_no" class="form-label"
                                                            style="white-space: nowrap;"><b>Zip Code</b></label>
                                                        <input type="text" class="form-control" id="house_no"
                                                            name="house_no" placeholder="...">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 p-2">
                                                        <label for="hoa" class="form-label"><b>Category</b></label>
                                                        <select class="form-select" name="hoa" id="hoa">
                                                            <option value="" style="color: grey;"
                                                                {{ !$crmHoaId ? 'selected' : '' }}>Please select Category
                                                            </option>
                                                            {{-- @foreach ($hoas as $hoa)
                                                        <option value="{{ $hoa->id }}" {{ $hoa->id == $crmHoaId ? 'selected' : ''}}>{{ $hoa->name }}</option>
                                                        @endforeach --}}
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 p-2">
                                                        <label for="sub_hoa" class="form-label"><b>Sub
                                                                Category</b></label>
                                                        <select class="form-select" name="sub_hoa" id="sub_hoa">
                                                            <option value="" style="color: grey;"
                                                                {{ !$crmHoaId ? 'selected' : '' }}>Please select Sub
                                                                Category</option>
                                                            {{-- @foreach ($hoas as $hoa)
                                                        <option value="{{ $hoa->id }}" {{ $hoa->id == $crmHoaId ? 'selected' : ''}}>{{ $hoa->name }}</option>
                                                        @endforeach --}}
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 p-2">
                                                        <label for="hoa" class="form-label"><b>HOA</b></label>
                                                        <select class="form-select" name="hoa" id="hoa">
                                                            <option value="" style="color: grey;"
                                                                {{ !$crmHoaId ? 'selected' : '' }}>Please Select HOA...
                                                            </option>
                                                            {{-- @foreach ($hoas as $hoa)
                                                    <option value="{{ $hoa->id }}" {{ $hoa->id == $crmHoaId ? 'selected' : ''}}>{{ $hoa->name }}</option>
                                                    @endforeach --}}
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 p-2">
                                                        <label for="sub_hoa" class="form-label"><b>Membership
                                                                Type</b></label>
                                                        <select class="form-select" name="sub_hoa" id="sub_hoa">
                                                            <option value="" style="color: grey;"
                                                                {{ !$crmHoaId ? 'selected' : '' }}>Please Select Membership
                                                                Type...</option>
                                                            {{-- @foreach ($hoas as $hoa)
                                                    <option value="{{ $hoa->id }}" {{ $hoa->id == $crmHoaId ? 'selected' : ''}}>{{ $hoa->name }}</option>
                                                    @endforeach --}}
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 p-2">
                                                        <label for="hoa" class="form-label"><b>Vehicle Ownership
                                                                Status</b></label>
                                                        <select class="form-select" name="hoa" id="hoa">
                                                            <option value="" style="color: grey;"
                                                                {{ !$crmHoaId ? 'selected' : '' }}>Please Select Ownership
                                                                Status...</option>
                                                            {{-- @foreach ($hoas as $hoa)
                                                    <option value="{{ $hoa->id }}" {{ $hoa->id == $crmHoaId ? 'selected' : ''}}>{{ $hoa->name }}</option>
                                                    @endforeach --}}
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 p-2">
                                                        <label for="first_name" class="form-label"><b>Email</b></label>
                                                        <input type="text" class="form-control" id="first_name"
                                                            name="first_name" placeholder="Enter Email">
                                                    </div>
                                                    <div class="col-md-3 p-2">
                                                        <label for="middle_name" class="form-label"><b>Main Contact No.</b></label>
                                                        <input type="text" class="form-control" id="middle_name"
                                                            name="middle_name" placeholder="Enter Contact No.">
                                                    </div>
                                                    <div class="col-md-3 p-2">
                                                        <label for="last_name" class="form-label"><b>Seconday Contact No.</b></label>
                                                        <input type="text" class="form-control" id="last_name"
                                                            name="last_name" placeholder="Enter Seconday Contact No">
                                                    </div>
                                                    <div class="col-md-3 p-2">
                                                        <label for="last_name" class="form-label"><b>Tertiary Contact No.</b></label>
                                                        <input type="text" class="form-control" id="last_name"
                                                            name="last_name" placeholder="Enter Tertiary Contact No">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2 g-2 g-md-3">
                                                <div class="col-12 col-md-3">
                                                    {{-- <label class="form-label">Official Receipt</label>
                                                <input type="file" accept="image/*"
                                                    class="form-control form-control-sm" name="v_or[{{ $vehicle->id }}]"
                                                    required> --}}
                                                </div>
                                            </div>
                                    </div>
                                @endforeach
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
                                            <div id="g-recaptcha" class="g-recaptcha mt-3"
                                                data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
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
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <script src="{{ asset('js/12srur0123.js') }}"></script>
    @endsection
