<div class="modal fade" id="viewDetailsModal-{{ $vehicle->id }}" tabindex="-1"
    aria-labelledby="viewDetailsModalLabel-{{ $vehicle->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDetailsModalLabel-{{ $vehicle->id }}">
                    For Renewal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add more details as needed -->
                <h5>Vehicle Information</h5>
                <div class="row">
                    <div class="col-md-2">
                        <label for="plate_no" class="form-label"
                            style="color: grey;">Plate
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
                    {{-- Plate no --}}
                    <div class="row mt-2">
                        <div class="px-3 py-1">
                            <div class="form-check">
                                <input class="form-check-input new_plate_no"
                                    type="checkbox"
                                    name="new_plate_no_chk[{{ $vehicle->id }}]"
                                    value="1"
                                    id="new_plate_no[{{ $vehicle->id }}]">
                                <label class="form-check-label"
                                    for="new_plate_no[{{ $vehicle->id }}]">
                                    I have a new plate no. <sup>(If using conduction
                                        no.)</sup>
                                </label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 new_plate_no_input"
                            style="display: none;">
                            <div class="form-floating">
                                <input type="text" class="form-control form-control-sm"
                                    id=""
                                    name="new_plate_no[{{ $vehicle->id }}]"
                                    placeholder="New Plate No.">
                                <label for="" class="form-label"
                                    style="color: grey;">New
                                    Plate No.</label>
                            </div>
                        </div>
                    </div>
                    {{-- Colors --}}
                    <div class="row">
                        <div class="px-3 py-1">
                            <div class="form-check">
                                <input class="form-check-input new_color" type="checkbox"
                                    name="new_color_chk[{{ $vehicle->id }}]"
                                    value="1"
                                    id="new_color_chk[{{ $vehicle->id }}]">
                                <label class="form-check-label"
                                    for="new_color_chk[{{ $vehicle->id }}]">
                                    New color
                                </label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 new_color_input"
                            style="display: none;">
                            <div class="form-floating">
                                <input type="text" class="form-control form-control-sm"
                                    id="" name="new_color[{{ $vehicle->id }}]"
                                    placeholder="New Color">
                                <label for="" class="form-label"
                                    style="color: grey;">New
                                    Color</label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <h5>Vehicle Owner Information</h5>
                <div class="row">
                    <div class="col-md-6 p-2">
                        <label for="vehicle_ownership_status_{{ $loop->index }}" class="form-label"><b>Vehicle Ownership Status</b></label>
                        <select class="form-select" name="vehicle_ownership_status[{{ $loop->index }}]" id="vehicle_ownership_status_{{ $loop->index }}">
                            <option value="" style="color: grey;">Please Select Ownership Status...</option>
                            <option value="1">Sample Vehicle Ownership1</option>
                            <option value="2">Sample Vehicle Ownership2</option>
                        </select>  
                    </div>
                    <div class="col-md-6 p-2">
                        <label for="membership_type_{{ $loop->index }}" class="form-label"><b>Membership Type</b></label>
                        <select class="form-select" name="membership_type[{{ $loop->index }}]" id="membership_type_{{ $loop->index }}">
                            <option value="" style="color: grey;">Please Select Membership Type...</option>
                            <option value="1">Sample Membership1</option>
                            <option value="2">Sample Membership2</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>