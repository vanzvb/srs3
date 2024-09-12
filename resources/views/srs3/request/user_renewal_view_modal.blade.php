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
                    <div class="col-md-4 p-2">
                        <label for="first_name_{{ $loop->index }}"
                            class="form-label"><b>First Name</b></label>

                        <!-- Visible text input -->
                        <input type="text" class="form-control"
                            id="first_name_{{ $loop->index }}"
                            name="first_name[{{ $loop->index }}]"
                            placeholder="Enter First Name"
                            value="{{ old('first_name.' . $loop->index) }}">

                    </div>
                    <div class="col-md-4 p-2">
                        <label for="middle_name_{{ $loop->index }}"
                            class="form-label"><b>Middle Name</b></label>

                        <!-- Visible text input -->
                        <input type="text" class="form-control"
                            id="middle_name_{{ $loop->index }}"
                            name="middle_name[{{ $loop->index }}]"
                            placeholder="Enter Middle Name"
                            value="{{ old('middle_name.' . $loop->index) }}">

                    </div>

                    <div class="col-md-4 p-2">
                        <label for="last_name_{{ $loop->index }}"
                            class="form-label"><b>Last Name</b></label>

                        <!-- Visible text input for last name -->
                        <input type="text" class="form-control"
                            id="last_name_{{ $loop->index }}"
                            name="last_name[{{ $loop->index }}]"
                            placeholder="Enter Last Name"
                            value="{{ old('last_name.' . $loop->index) }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1 p-2">
                        <label for="block_{{ $loop->index }}"
                            class="form-label"><b>Block</b></label>

                        <!-- Visible text input for block, restricted to numbers only -->
                        <input type="number" class="form-control"
                            id="block_{{ $loop->index }}"
                            name="block[{{ $loop->index }}]" placeholder="..."
                            inputmode="numeric" min="1">
                    </div>

                    <div class="col-md-1 p-2">
                        <label for="lot_{{ $loop->index }}"
                            class="form-label"><b>Lot</b></label>

                        <!-- Visible text input for lot, restricted to numbers only -->
                        <input type="number" class="form-control"
                            id="lot_{{ $loop->index }}" name="lot[{{ $loop->index }}]"
                            placeholder="..." inputmode="numeric" min="1">
                    </div>

                    <div class="col-md-1 p-2">
                        <label for="house_no_{{ $loop->index }}" class="form-label"
                            style="white-space: nowrap;"><b>House No.</b></label>

                        <!-- Visible text input for house number, restricted to numbers only -->
                        <input type="number" class="form-control"
                            id="house_no_{{ $loop->index }}"
                            name="house_no[{{ $loop->index }}]" placeholder="..."
                            inputmode="numeric" min="1">
                    </div>

                    <div class="col-md-5 p-2">
                        <label for="street_{{ $loop->index }}"
                            class="form-label"><b>Street</b></label>

                        <!-- Visible text input for street -->
                        <input type="text" class="form-control"
                            id="street_{{ $loop->index }}"
                            name="street[{{ $loop->index }}]" placeholder="...">
                    </div>
                    <div class="col-md-4 p-2">
                        <label for="building_apartment_condo_{{ $loop->index }}"
                            class="form-label" style="white-space: nowrap;"><b>Building /
                                Apartment / Condo</b></label>

                        <!-- Visible text input for building / apartment / condo -->
                        <input type="text" class="form-control"
                            id="building_apartment_condo_{{ $loop->index }}"
                            name="building_apartment_condo[{{ $loop->index }}]"
                            placeholder="...">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 p-2">
                        <label for="city_{{ $loop->index }}" class="form-label"
                            style="white-space: nowrap;"><b>City</b></label>
                        <!-- Visible text input for city -->
                        <input type="text" class="form-control"
                            id="city_{{ $loop->index }}"
                            name="city[{{ $loop->index }}]" placeholder="...">
                    </div>
                    <div class="col-md-2 p-2">
                        <label for="zip_code_{{ $loop->index }}" class="form-label"
                            style="white-space: nowrap;"><b>Zip Code</b></label>
                        <!-- Visible text input for zip code -->
                        <input type="text" class="form-control"
                            id="zip_code_{{ $loop->index }}"
                            name="zip_code[{{ $loop->index }}]" placeholder="...">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 p-2">
                        <label for="category_select_{{ $loop->index }}" class="form-label"><b>Category</b></label>
                        <select class="form-select" name="new_category[{{ $loop->index }}]" id="category_select_{{ $loop->index }}">
                            <option value="" style="color: grey;">Please select Category</option>
                            @foreach ($srsCategories as $srsCategory)
                                <option value="{{ $srsCategory->id }}"
                                    {{ old('new_category.' . $loop->index) == $srsCategory->id ? 'selected' : '' }}>
                                    {{ $srsCategory->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 p-2">
                        <label for="sub_category_select_{{ $loop->index }}" class="form-label"><b>Sub Category</b></label>
                        <select class="form-select" name="sub_category_select[{{ $loop->index }}]" id="sub_category_select_{{ $loop->index }}">
                            <option value="" style="color: grey;">Please select Sub Category</option>
                            <option value="1">Sample Sub Cat1</option>
                            <option value="2">Sample Sub Cat2</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 p-2">
                        <label for="selected_hoa_{{ $loop->index }}" class="form-label"><b>HOA</b></label>
                        <select class="form-select" name="selected_hoa[{{ $loop->index }}]" id="selected_hoa_{{ $loop->index }}">
                            <option value="" style="color: grey;">Please select HOA</option>
                            @foreach ($hoas as $hoa)
                                <option value="{{ $hoa->id }}">
                                    {{ $hoa->name }}
                                </option>
                            @endforeach
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
                <div class="row">
                    <div class="col-md-6 p-2">
                        <label for="vehicle_ownership_status_{{ $loop->index }}" class="form-label"><b>Vehicle Ownership Status</b></label>
                        <select class="form-select" name="vehicle_ownership_status[{{ $loop->index }}]" id="vehicle_ownership_status_{{ $loop->index }}">
                            <option value="" style="color: grey;">Please Select Ownership Status...</option>
                            <option value="1">Sample Vehicle Ownership1</option>
                            <option value="2">Sample Vehicle Ownership2</option>
                        </select>                                                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 p-2">
                        <label for="main_contact_{{ $loop->index }}" class="form-label"><b>Main Contact No.</b></label>
                        <input type="text" class="form-control" id="main_contact_{{ $loop->index }}"
                            name="main_contact[{{ $loop->index }}]" placeholder="Enter Contact No.">
                    </div>
                    <div class="col-md-3 p-2">
                        <label for="second_contact_{{ $loop->index }}" class="form-label"><b>Secondary Contact No.</b></label>
                        <input type="text" class="form-control" id="second_contact_{{ $loop->index }}"
                            name="second_contact[{{ $loop->index }}]" placeholder="Enter Secondary Contact No.">
                    </div>
                    <div class="col-md-3 p-2">
                        <label for="tertiary_contact_{{ $loop->index }}" class="form-label"><b>Tertiary Contact No.</b></label>
                        <input type="text" class="form-control" id="tertiary_contact_{{ $loop->index }}"
                            name="tertiary_contact[{{ $loop->index }}]" placeholder="Enter Tertiary Contact No.">
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