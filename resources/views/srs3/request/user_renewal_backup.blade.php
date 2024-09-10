@extends('layouts.guest')

@section('title', 'Sticker Application Request - Renewal')

@section('content')
<div class="container px-md-5">
    <div class="px-md-5 mb-3">
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
            <div class="container justify-content-center align-items-center">
                <div class="px-md-4 mt-3 mb-3">
                    <form action="{{ route('request.user-renewal.process') }}" id="renewal_request_form" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- User Information Section -->
                        <div class="p-2">
                            <!-- Name, Address, Email, Contact No., HOA fields -->

                            <div class="px-2 px-md-0 mb-4 mt-5">
                                <b><h5>Vehicle Information</h5></b>
                            </div>

                            <!-- Vehicle Information Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Plate No.</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($crm->vehicles as $vehicle)
                                            <tr>
                                                <td>{{ $vehicle->plate_no }}</td>
                                                <td>
                                                    <!-- Update Profile Button -->
                                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#vehicleModal_{{ $vehicle->id }}">
                                                        Update Profile
                                                    </button>

                                                    <!-- Modal for Vehicle Information -->
                                                    <div class="modal fade" id="vehicleModal_{{ $vehicle->id }}" tabindex="-1" aria-labelledby="vehicleModalLabel_{{ $vehicle->id }}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="vehicleModalLabel_{{ $vehicle->id }}">Update Vehicle - {{ $vehicle->plate_no }}</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row mb-3">
                                                                        <label for="plate_no_{{ $vehicle->id }}" class="col-sm-4 col-form-label">Plate No.</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" id="plate_no_{{ $vehicle->id }}" value="{{ $vehicle->plate_no }}" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-3">
                                                                        <label for="brand_{{ $vehicle->id }}" class="col-sm-4 col-form-label">Brand</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" id="brand_{{ $vehicle->id }}" value="{{ $vehicle->brand }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-3">
                                                                        <label for="series_{{ $vehicle->id }}" class="col-sm-4 col-form-label">Series</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" id="series_{{ $vehicle->id }}" value="{{ $vehicle->series }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-3">
                                                                        <label for="color_{{ $vehicle->id }}" class="col-sm-4 col-form-label">Color</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" id="color_{{ $vehicle->id }}" value="{{ $vehicle->color }}">
                                                                        </div>
                                                                    </div>
                                                                    <!-- Add more fields as necessary -->
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    <button type="button" class="btn btn-primary save-vehicle-changes" data-vehicle-id="{{ $vehicle->id }}">Save changes</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="px-2 mt-5">
                            <!-- Requirements Table -->

                            <div class="mt-5 text-center">
                                <button type="submit" id="request_submit_btn" class="btn btn-primary mt-3">Submit Renewal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('links_js')
<script>
    document.querySelectorAll('.save-vehicle-changes').forEach(function(button) {
        button.addEventListener('click', function() {
            let vehicleId = button.getAttribute('data-vehicle-id');
            // Fetch and update vehicle information using the modal inputs.
            // You can use AJAX here if needed for asynchronous updates.
        });
    });
</script>
<script src="{{ asset('js/12srur0123.js') }}"></script>
@endsection