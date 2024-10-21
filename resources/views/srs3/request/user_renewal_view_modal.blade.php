<div class="modal fade" id="viewDetailsModal-{{ $vehicle->id }}" tabindex="-1"
    aria-labelledby="viewDetailsModalLabel-{{ $vehicle->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDetailsModalLabel-{{ $vehicle->id }}">
                    For Renewal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add more details as needed -->
                <h5>Vehicle Information</h5>
                <div class="row">
                    <div class="col-md-2">
                        <label for="plate_no" class="form-label" style="color: grey;">Plate
                            No.:</label>
                    </div>
                    <div class="col-md-3">
                        {{ $vehicle->plate_no }}
                        <input type="hidden" class="form-control" id="plate_no" name="" placeholder="Plate No."
                            required readonly value="{{ $vehicle->plate_no }}">
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
                    {{-- Plate no --}}
                    <div class="row mt-2">
                        <div class="px-3 py-1">
                            <div class="form-check">
                                <input class="form-check-input new_plate_no" type="checkbox"
                                    name="new_plate_no_chk[{{ $vehicle->id }}]" value="1"
                                    id="new_plate_no[{{ $vehicle->id }}]">
                                <label class="form-check-label" for="new_plate_no[{{ $vehicle->id }}]">
                                    I have a new plate no. <sup>(If using conduction
                                        no.)</sup>
                                </label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 new_plate_no_input" style="display: none;">
                            <div class="form-floating">
                                <input type="text" class="form-control form-control-sm" id=""
                                    name="new_plate_no[{{ $vehicle->id }}]" placeholder="New Plate No.">
                                <label for="" class="form-label" style="color: grey;">New
                                    Plate No.</label>
                            </div>
                        </div>
                    </div>
                    {{-- Colors --}}
                    <div class="row">
                        <div class="px-3 py-1">
                            <div class="form-check">
                                <input class="form-check-input new_color" type="checkbox"
                                    name="new_color_chk[{{ $vehicle->id }}]" value="1"
                                    id="new_color_chk[{{ $vehicle->id }}]">
                                <label class="form-check-label" for="new_color_chk[{{ $vehicle->id }}]">
                                    New color
                                </label>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 new_color_input" style="display: none;">
                            <div class="form-floating">
                                <input type="text" class="form-control form-control-sm" id=""
                                    name="new_color[{{ $vehicle->id }}]" placeholder="New Color">
                                <label for="" class="form-label" style="color: grey;">New
                                    Color</label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                {{-- Vehicle Owner Info --}}
                <h5>Owner Information :</h5>

                {{--  Get Vehicle Owner ID --}}
                <div class="col-12 col-md-3 vehicle_owners_input" style="display: none;">
                    <div class="form-floating">
                        <input type="text" class="form-control form-control-sm" id=""
                            name="vehicle_owners_input[{{ $vehicle->vehicleOwner->id }}]">
                    </div>
                </div>

                <div class="row">
                    <div class="row">
                        <div class="col-md-4 p-2">
                            <label for="first_name_{{ $vehicle->vehicleOwner->firstname }}"
                                class="form-label"><b>First Name</b></label>
                            <input type="text" class="form-control"
                                id="first_name_{{ $vehicle->vehicleOwner->firstname }}"
                                name="first_name[{{ $vehicle->vehicleOwner->firstname }}]"
                                value="{{ $vehicle->vehicleOwner->firstname }}" placeholder="" disabled>
                        </div>
                        <div class="col-md-4 p-2">
                            <label for="middle_name_{{ $vehicle->vehicleOwner->middlename }}"
                                class="form-label"><b>Middle
                                    Name</b></label>
                            <input type="text" class="form-control"
                                id="middle_name_{{ $vehicle->vehicleOwner->middlename }}"
                                name="middle_name[{{ $vehicle->vehicleOwner->middlename }}]"
                                value="{{ $vehicle->vehicleOwner->middlename }}" placeholder="" disabled>
                        </div>
                        <div class="col-md-4 p-2">
                            <label for="last_name_{{ $vehicle->vehicleOwner->lastname }}" class="form-label"><b>Last
                                    Name</b></label>
                            <input type="text" class="form-control"
                                id="last_name_{{ $vehicle->vehicleOwner->lastname }}"
                                name="last_name[{{ $vehicle->vehicleOwner->lastname }}]"
                                value="{{ $vehicle->vehicleOwner->lastname }}" placeholder="" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 p-2">
                            <label for="main_contact_no_{{ $vehicle->vehicleOwner->main_contact }}"
                                class="form-label"><b>Main Contact
                                    No.</b></label>
                            <input type="text" class="form-control"
                                id="main_contact_no_{{ $vehicle->vehicleOwner->main_contact }}"
                                name="main_contact_no[{{ $vehicle->vehicleOwner->main_contact }}]"
                                value="{{ $vehicle->vehicleOwner->main_contact }}" placeholder="" disabled>
                        </div>
                        <div class="col-md-4 p-2">
                            <label for="secondary_contact_no_{{ $vehicle->vehicleOwner->secondary_contact }}"
                                class="form-label"><b>Secondary
                                    Contact No.</b></label>
                            <input type="text" class="form-control"
                                id="secondary_contact_no_{{ $vehicle->vehicleOwner->secondary_contact }}"
                                name="secondary_contact_no[{{ $vehicle->vehicleOwner->secondary_contact }}]"
                                value="{{ $vehicle->vehicleOwner->secondary_contact }}" placeholder="" disabled>
                        </div>
                        <div class="col-md-4 p-2">
                            <label for="tertiary_contact_no_{{ $vehicle->vehicleOwner->tertiary_contact }}"
                                class="form-label"><b>Tertiary
                                    Contact No.</b></label>
                            <input type="text" class="form-control"
                                id="tertiary_contact_no_{{ $vehicle->vehicleOwner->tertiary_contact }}"
                                name="tertiary_contact_no[{{ $vehicle->vehicleOwner->tertiary_contact }}]"
                                value="{{ $vehicle->vehicleOwner->tertiary_contact }}" placeholder="" disabled>
                        </div>
                    </div>
                </div>

                {{-- Vehicle Address  Info --}}
                <br>
                <h5>Address Information :</h5>

                {{--  Get Vehicle Owner ID --}}
                <div class="col-12 col-md-3 vehicle_address_input" style="display: none;">
                    <div class="form-floating">
                        <input type="text" class="form-control form-control-sm" id=""
                            name="vehicle_address_input[{{ $vehicle->vehicleAddress->id }}]">
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6 p-2">
                        <label for="vehicle_address_{{ $vehicle->vehicleAddress->id }}"
                            class="form-label"><b>Address</b></label>
                        <select class="form-select" name="vehicle_address[{{ $vehicle->vehicleAddress->id }}]"
                            id="category" disabled>
                            <option value="" style="color: grey;"
                                {{ is_null($vehicle->vehicleAddress->id) ? 'selected' : '' }}>
                                Please select Address
                            </option>

                            @foreach ($crm->CRMXIaddress as $address)
                                <option value="{{ $address->id }}"
                                    {{ $address->id == $vehicle->vehicleAddress->id ? 'selected' : '' }}>
                                    {{ $address->building_name ?? 'Unknown Address' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 p-2">
                        <label for="vehicle_ownership_status_{{ $loop->index }}" class="form-label"><b>Vehicle
                                Ownership Status</b></label>
                        <input type="text" class="form-control"
                            value="{{ $vehicle->vehicleOwnershipStatus->name }}" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 p-2">
                        <label for="category_{{ $loop->index }}" class="form-label"><b>Category</b></label>
                        <input type="text" class="form-control"
                            value="{{ $vehicle->vehicleAddress->CRMXIcategory->name }}" disabled>

                    </div>

                    <div class="col-md-4 p-2">
                        <label for="sub_category_{{ $loop->index }}" class="form-label"><b>Sub Category</b></label>
                        <input type="text" class="form-control"
                            value="{{ $vehicle->vehicleAddress->CRMXIsubCategory->name }}" disabled>
                    </div>
                    <div class="col-md-4 p-2">
                            <label for="hoa_{{ $loop->index }}" class="form-label"><b>HOA</b></label>
                            <input type="text" class="form-control"
                                value="{{ $vehicle->vehicleAddress->CRMXIhoa->name }}" disabled>    
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 p-2">
                        
                        <label for="member_type_{{ $loop->index }}" class="form-label"><b>Member Type</b></label>
                        <input type="text" class="form-control"
                            value="{{ $vehicle->vehicleAddress->CRMXIhoaType->name }}" disabled>   
                    </div>
                    <div class="col-md-3 p-2">
                        <label for="block_{{ $loop->index }}" class="form-label"><b>Block</b></label>
                        <input type="text" class="form-control"
                            value="{{ $vehicle->vehicleAddress->block }}" disabled>   
                    </div>
                    <div class="col-md-3 p-2">
                        <label for="lot_{{ $loop->index }}" class="form-label"><b>Lot</b></label>
                        <input type="text" class="form-control"
                            value="{{ $vehicle->vehicleAddress->lot }}" disabled>   
                    </div>
                    <div class="col-md-3 p-2">
                        <label for="house_number_{{ $loop->index }}" class="form-label"><b>House Number</b></label>
                        <input type="text" class="form-control"
                            value="{{ $vehicle->vehicleAddress->house_number }}" disabled>   
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 p-2">
                        <label for="street_{{ $loop->index }}" class="form-label"><b>Street</b></label>
                        <input type="text" class="form-control"
                            value="{{ $vehicle->vehicleAddress->street }}" disabled>   
                    </div>
                    <div class="col-md-4 p-2">
                        <label for="building_{{ $loop->index }}" class="form-label"><b>Building / Apartment /
                                Condo</b></label>
                                <input type="text" class="form-control"
                                value="{{ $vehicle->vehicleAddress->building_name }}" disabled>   
                    </div>
                    <div class="col-md-4 p-2">
                        <label for="subdivision_{{ $loop->index }}" class="form-label"><b>Subdivision /
                                Village</b></label>
                                <input type="text" class="form-control"
                                value="{{ $vehicle->vehicleAddress->subdivision_village }}" disabled>   
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 p-2">
                        <label for="city_{{ $loop->index }}" class="form-label"><b>City</b></label>
                        <input type="text" class="form-control"
                            value="{{ $vehicle->vehicleAddress->crmxiAccount->cities->description }}" disabled>   
                    </div>
                    <div class="col-md-6 p-2">
                        <label for="zipcode_{{ $loop->index }}" class="form-label"><b>Zipcode</b></label>
                        <input type="text" class="form-control"
                            value="{{ $vehicle->vehicleAddress->zipcode }}" disabled>   
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
