@extends('layouts.guest')

@section('title', 'Sticker Application Request - Renewal')

@section('content')
<div class="container px-md-5">
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
                <img src="{{ asset('images/bflogo.png') }}" height="100" width="100" alt="">
                <h5>BFFHAI</h5>
                <h5>Sticker Application - Renewal</h5>
            </div>
            <div id="request_renewal_msg" class="row justify-content-center">
            </div>
            <div class="container justify-content-center align-items-center">
                <div class="px-md-4 mt-3 mb-3">
                    <form action="{{ route('request.user-renewal.process') }}" id="renewal_request_form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="p-2">
                            <div class="row p-2">
                                <b>{{ $crm->category->name . ' / ' . $crm->subCategory->name }}</b>
                            </div>
                            <div class="row p-2 g-0">
                                <div class="col-md-2">
                                    <b>Name:</b>
                                </div>
                                <div class="col-md-10">
                                    {{ $crm->lastname . ', '. $crm->firstname . ' ' . $crm->middlename }}
                                </div>
                            </div>
                            <div class="row p-2 g-0">
                                <div class="col-md-2">
                                    <b>Address:</b>
                                </div>
                                <div class="col-md-10">
                                    {{ $crm->blk_lot . ' ' . $crm->street . ($crm->building_name ? ', ' . $crm->building_name : '') . ($crm->subdivision_village ? ', ' . $crm->subdivision_village : '') . ($crm->city ? ', ' . $crm->city : '') }}
                                </div>
                            </div>
                            <div class="row p-2 g-0">
                                <div class="col-md-2">
                                    <b>Email:</b>
                                </div>
                                <div class="col-md-10">
                                    {{ $crm->email }}
                                </div>
                            </div>
                            <div class="row p-2 g-0">
                                <div class="col-md-2">
                                    <b>Contact No.:</b>
                                </div>
                                <div class="col-md-10">
                                    {{ $crm->main_contact }}
                                </div>
                            </div>
                            <div class="row p-2 g-0">
                                <div class="col-md-2">
                                    <b>HOA:</b>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" name="hoa" id="hoa">
                                        <option value="" style="color: grey;" {{ !$crmHoaId ? 'selected' : '' }}>Please select HOA</option>
                                        @foreach($hoas as $hoa)
                                            <option value="{{ $hoa->id }}" {{ $hoa->id == $crmHoaId ? 'selected' : ''}}>{{ $hoa->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="px-2 px-md-0 mb-4 mt-5">
                            <b><h5>Vehicle Information</h5></b>
                        </div>
                        <div>
                            @foreach($crm->vehicles as $vehicle)
                                <div class="p-3 p-md-3 card shadow rounded mb-2 mb-md-4">
                                    <div class="card-header" style="background-color: white; border-bottom: 0;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>FOR RENEWAL</label>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <button class="btn-close v_remove_btn"></button>
                                            </div>
                                        </div>  
                                    </div>
                                    {{-- <div class="row mt-2 g-2">
                                        <div class="col-6 col-md-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="plate_no" name="plate_no[]" placeholder="Plate No." required readonly value="Renewal">
                                                <label for="" class="form-label">Request Type</label>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="row mt-2 g-2">
                                        <!-- <div class="col-6 col-md">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="plate_no" name="" placeholder="Plate No." required readonly value="{{ $vehicle->plate_no }}">
                                                <label for="plate_no" class="form-label" style="color: grey;">Plate No.</label>
                                            </div>
                                        </div> -->
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label for="plate_no" class="form-label" style="color: grey;">Plate No.:</label>
                                            </div>      
                                            <div class="col-md-3">
                                                {{ $vehicle->plate_no }}
                                                <input type="hidden" class="form-control" id="plate_no" name="" placeholder="Plate No." required readonly value="{{ $vehicle->plate_no }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label for="brand" class="form-label" style="color: grey;">Brand:</label>
                                            </div>
                                            <div class="col-md-3">
                                                {{ $vehicle->brand }}
                                                <input type="hidden" name="ref[]">
                                                <input type="hidden" name="vref[]" value="{{ $vehicle->id }}">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="series" class="form-label" style="color: grey;">Series:</label>
                                            </div>
                                            <div class="col-md-3">
                                                {{ $vehicle->series }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label for="color" class="form-label" style="color: grey;">Color:</label>
                                            </div>
                                            <div class="col-md-3">
                                                {{ $vehicle->color }}
                                            </div>
                                            <div class="col-md-2">
                                                <label for="year_model" class="form-label" style="color: grey;">Year/Model:</label>
                                            </div>
                                            <div class="col-md-3">
                                                {{ $vehicle->year_model }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label for="type" class="form-label" style="color: grey;">Type:</label>
                                            </div>
                                            <div class="col-md-3">
                                                {{ $vehicle->type }}
                                            </div>
                                            <div class="col-md-2">
                                                <label for="year_model" class="form-label" style="color: grey;">Sticker No.:</label>
                                            </div>
                                            <div class="col-md-3">
                                                {{ $vehicle->new_sticker_no }}
                                            </div>
                                        </div>
                                        <!-- <div class="col-6 col-md">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="brand" name="" placeholder="Brand" required readonly value="{{ $vehicle->brand }}">
                                                <label for="brand" class="form-label" style="color: grey;">Brand</label>
                                                <input type="hidden" name="ref[]">
                                                <input type="hidden" name="vref[]" value="{{ $vehicle->id }}">
                                            </div>
                                        </div>  -->
                                        <!-- <div class="col-6 col-md">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="series" name="" placeholder="Series" required readonly value="{{ $vehicle->series }}">
                                                <label for="series" class="form-label" style="color: grey;">Series</label>
                                            </div>
                                        </div> -->
                                        <!-- <div class="col-6 col-md">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="year_model" name="" placeholder="Year/Model" required readonly value="{{ $vehicle->year_model }}">
                                                <label for="year_model" class="form-label" style="color: grey;">Year/Model</label>
                                            </div>
                                        </div> -->
                                    </div>
                                    <!-- <div class="row mt-2 g-2 g-md-3">
                                        <div class="col-6 col-md-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="color" name="" placeholder="Color" required readonly value="{{ $vehicle->color }}">
                                                <label for="color" class="form-label" style="color: grey;">Color</label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="type" name="" placeholder="Type" required readonly value="{{ $vehicle->type }}">
                                                <label for="type" class="form-label" style="color: grey;">Type</label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="sticker_no" name="sticker_no[{{ $vehicle->id }}]" placeholder="Sticker No." required>
                                                <label for="sticker_no" class="form-label" style="color: grey;">Sticker No.</label>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="row mt-2">
                                        <div class="px-3 py-1">
                                            <div class="form-check">
                                                <input class="form-check-input new_plate_no" type="checkbox" name="new_plate_no_chk[{{ $vehicle->id }}]" value="1" id="new_plate_no[{{ $vehicle->id }}]">
                                                <label class="form-check-label" for="new_plate_no[{{ $vehicle->id }}]">
                                                    I have a new plate no. <sup>(If using conduction no.)</sup>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3 new_plate_no_input" style="display: none;">
                                            <div class="form-floating">
                                                <input type="text" class="form-control form-control-sm" id="" name="new_plate_no[{{ $vehicle->id }}]" placeholder="New Plate No.">
                                                <label for="" class="form-label" style="color: grey;">New Plate No.</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="px-3 py-1">
                                            <div class="form-check">
                                                <input class="form-check-input new_color" type="checkbox" name="new_color_chk[{{ $vehicle->id }}]" value="1" id="new_color_chk[{{ $vehicle->id }}]">
                                                <label class="form-check-label" for="new_color_chk[{{ $vehicle->id }}]">
                                                    New color
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3 new_color_input" style="display: none;">
                                            <div class="form-floating">
                                                <input type="text" class="form-control form-control-sm" id="" name="new_color[{{ $vehicle->id }}]" placeholder="New Color">
                                                <label for="" class="form-label" style="color: grey;">New Color</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2 g-2 g-md-3">
                                        <div class="col-12 col-md-3">
                                            <label class="form-label">Official Receipt</label>
                                            <input type="file" accept="image/*" class="form-control form-control-sm" name="v_or[{{ $vehicle->id }}]" required>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="" id="vehicles_row">
                                    
                            </div>
                            <div class="row">
                                <div class="col-12 p-3" style="text-align: right;">
                                    <button id="add_vehicle_btn" type="button" class="btn btn-sm btn-info" style="color: white;">Add new vehicle</button>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-md-12 px-md-5">
                                <div style="padding: 1.0rem;
                                        margin-top: 1.25rem;
                                        margin-bottom: 1.25rem;
                                        border: 1px solid #e9ecef;
                                        border-left-width: 0.25rem;
                                        border-radius: 0.25rem;
                                        border-left-color: #f0ad4e;
                                        font-size: 14px;">
                                        <strong>Note: File uploads are accepting file formats in .JPG, .JPEG, and .PNG</strong>
                                </div>
                            </div>
                        </div>

                        <div class="px-2 mt-5">
                            <div id="requirements_table" class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="50%;">Mandatory Requirements</th>
                                            <th class="text-center" width="50%;">Attachments</th>
                                        </tr>
                                    </thead>
                                    <tbody id="requirements_tbody">
                                        @foreach($requirements as $requirement)
                                            <tr>
                                                <td>
                                                    <div class="my-3">
                                                        <label class="form-label">{{ $requirement->description }}</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="my-3">
                                                        <input class="form-control form-control-sm" type="file" accept="image/*" name="{{ $requirement->name }}" id="" {{ $requirement->required ? 'required' : '' }}>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div>
                            <div class="mt-5 text-center">
                                <div class="d-flex justify-content-center">
                                    <div id="g-recaptcha" class="g-recaptcha mt-3" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                                </div>

                                <button type="submit" id="request_submit_btn" class="btn btn-primary mt-3">Submit Renewal</button>
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