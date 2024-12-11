@extends('layouts.guest')

@section('title', 'Sticker Application Request - Renewal')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

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
            @if ($vehicleAddressIds->count() > 0)
                <div class="alert alert-warning text-center p-3" role="alert" style="font-size: 1.25rem;">
                    <strong>This account has no Homeowners Association (HOA) in the system.</strong>
                    <br>
                    <strong>Update your HOA</strong>
                </div>

                {{-- Dropdown filter for address_id --}}
                <form action="{{ route('request.v3.user-renewal.Updateprocess') }}" id="renewal_update_form"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-2">
                    <div class="row px-2 px-md-0 mb-4 mt-4">
                        <div class="col-md-6">
                            <h5>Select your address to Update</h5>
                            <select id="addressToUpdate" class="form-select" name="addressToUpdate" required>
                                @foreach ($vehicleAddressIds as $addressId)
                                    <option value="{{ $addressId->id }}">{{ $addressId->block }}, {{ $addressId->lot }}, {{ $addressId->house_number }}, {{ $addressId->street }}, {{ $addressId->subdivision_village }}, {{ $addressId->city }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <h5>Select your Homeowners Association (HOA)</h5>
                            <select id="hoaToUpdate" class="form-select" name ="hoaToUpdate" required>
                                @foreach ($hoaMembers as $dataHoa)
                                    <option value="{{ $dataHoa->id }}">{{ $dataHoa->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="crm_id_update" id="crm_id_update" value="{{ $crmId }}">
                    <center>
                    <button type="submit" id="request_submit_btn"
                    class="btn btn-primary mt-3">Update Account</button>
                    </center>
                </div>
                
                </form>
            @else
                <div class="container justify-content-center align-items-center">
                    <div class="px-md-4 mt-3 mb-3">
                        <form action="{{ route('request.v3.user-renewal.process') }}" id="renewal_request_form"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @if ($didWeUseMainEmail)
                                <div class="p-2">
                                    <div class="px-2 px-md-0 mb-4 mt-5">
                                        <h5>Account Information</h5>
                                        {{-- <div class="row p-2">
                                        <b>{{ $crm->category->name . ' / ' . $crm->subCategory->name }}</b>
                                    </div> --}}
                                    </div>

                                    <div class="row p-2 g-0">
                                        {{-- <div class="col-md-2 me-2">
                                        <label for="account_type" class="form-label"><b>Account Type</b></label>
                                        <select class="form-select form-select-md" name="account_type" id="account_type"
                                            disabled>
                                            <option value="0" style="color: grey;"
                                                {{ $crm->account_type == 0 ? 'selected' : '' }}>Individual</option>
                                            <option value="1" {{ $crm->account_type == 1 ? 'selected' : '' }}>Company
                                            </option>
                                        </select>
                                    </div> --}}
                                        <div class="col-md-2 me-2">
                                            <label for="account_type" class="form-label"><b>Account Type</b></label>
                                            <input type="text" class="form-control form-control-md" name="account_type"
                                                id="account_type"
                                                value="{{ $crm->account_type == 0 ? 'Individual' : 'Company' }}" disabled>
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
                                            <div class="" id="company-representative-container"
                                                style="display: none;">
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
                                            <input type="text" class="form-control" value="{{ $crm->account_id }}"
                                                disabled>
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
                                    <br>


                                </div>
                            @endif



                            {{-- Dropdown filter for address_id --}}
                            <div class="p-2">
                                <div class="px-2 px-md-0 mb-4 mt-4">
                                    <h5>Select Only 1 HOA</h5>
                                    <select id="hoaFilter" class="form-select" required>
                                        @foreach ($crm->CRMXIvehicles->filter(fn($vehicle) => $vehicle->vehicleAddress->category_id != 2 && in_array($vehicle->vehicleAddress->sub_category_id, [1, 4]))->pluck('vehicleAddress.CRMXIhoa.name')->unique() as $hoaName)
                                            <option value="{{ $hoaName }}">{{ $hoaName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="address_id" id="address_id" value="">
                            {{-- For Renewal (Table) --}}
                            <div class="p-2">
                                <div class="px-2 px-md-0 mb-4 mt-4">
                                    <h5>For Renewal</h5>

                                    <h6><b>Note : Uncheck if you do not wish to renew the vehicle.</b></h6>
                                </div>
                                <div class="row p-2 g-0">
                                    <div class="col-md-12">
                                        <!-- Button outside the table -->
                                        <!-- Vehicles Table -->
                                        <table class="table table-bordered table-hover mt-2">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Plate Number</th>
                                                    <th>Vehicle Ownership Type</th> <!-- New column added -->
                                                    <th>Brand, Series</th>
                                                    <th>Category</th>
                                                    <th>Sub Category</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="vehicleTableBody">
                                                @if ($crm->CRMXIvehicles->filter(fn($vehicle) => $vehicle->vehicleAddress->category_id != 2 && in_array($vehicle->vehicleAddress->sub_category_id, [1, 4]) && ($vehicle->vehicle_ownership_status_id == 1 || is_null($vehicle->vehicle_ownership_status_id)))->isEmpty())
                                                    <tr id="no-vehicles-row">
                                                        <td colspan="7" class="text-center">No vehicles available</td> <!-- Updated colspan to 7 -->
                                                    </tr>
                                                @else
                                                    @foreach ($crm->CRMXIvehicles->filter(fn($vehicle) => $vehicle->vehicleAddress->category_id != 2 && in_array($vehicle->vehicleAddress->sub_category_id, [1, 4]) && ($vehicle->vehicle_ownership_status_id == 1 || is_null($vehicle->vehicle_ownership_status_id))) as $vehicle)
                                                        <tr id="vehicle-row-{{ $vehicle->id }}"
                                                            data-hoa-name="{{ $vehicle->vehicleAddress->CRMXIhoa->name ?? 'N/A' }}"
                                                            data-address-id="{{ $vehicle->vehicleAddress->id }}">
                                                            <td>
                                                                <input type="checkbox" name="renewalVehicles[]"
                                                                    value="{{ $vehicle->id }}" checked>
                                                            </td>
                                                            <td>{{ $vehicle->plate_no ?? 'N/A' }}</td>
                                                            <td>
                                                                <select class="form-select" name="vehicle_ownership_type[{{ $vehicle->id }}]">
                                                                    @foreach ($vehicleOwnershipTypes as $type)
                                                                        <option value="{{ $type->id }}" {{ ($vehicle->vehicleOwnershipStatus->id ?? $vehicleOwnershipTypes->first()->id) == $type->id ? 'selected' : '' }}>
                                                                            {{ $type->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td> <!-- New column for Vehicle Ownership Type -->
                                                            <td>{{ $vehicle->brand ?? 'N/A' }},
                                                                {{ $vehicle->series ?? 'N/A' }}</td>
                                                            <td>{{ $vehicle->vehicleAddress->CRMXIcategory->name ?? 'N/A' }}</td>
                                                            <td>{{ $vehicle->vehicleAddress->CRMXIsubCategory->name ?? 'N/A' }}</td>
                                                            <td style="white-space: nowrap;">
                                                                <button type="button" class="btn btn-primary btn-sm"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#viewDetailsModal-{{ $vehicle->id }}">
                                                                    <i class="fa fa-eye"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm btn-remove"
                                                                    data-id="{{ $vehicle->id }}">
                                                                    Don't Renew
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{-- Modal for View/Update Details --}}
                            @foreach ($crm->CRMXIvehicles->filter(fn($vehicle) => $vehicle->vehicleAddress->category_id != 2) as $vehicle)
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

                            <div class="p-2">
                                <div class="px-2 px-md-0 mb-4 mt-5">
                                    <h5>File Attachment</h5>
                                </div>
                                <div class="px-2 mt-2">
                                    <div id="requirements_list">
                                        @foreach ($requirements as $requirement)
                                            <div class="mb-4">
                                                <!-- Requirement description above the file input -->
                                                <label class="form-label"><b>Upload Valid Goverment ID with
                                                        Address</b></label>
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
                @endif
            </div>
        </div>

        {{-- For Counting Vehicles --}}

        <input type="hidden" id="list-of-vehicles" name="list_of_vehicles" value="[]">
    @endsection

    @section('links_js')
        {{-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> --}}
        <script src="{{ asset('js/12srur0123.js') }}"></script>
        {{-- <script src="{{ asset('js/srs3renewal1.js') }}"></script> --}}
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
                            listOfVehicles = listOfVehicles.filter(id => id !=
                                vehicleId); // Remove the vehicle ID from the array
                        }
                    }
                    // If the user clicks 'No', the row won't be removed

                    // Update the hidden input field with the modified array
                    document.getElementById('list-of-vehicles').value = JSON.stringify(listOfVehicles);
                });
            });
        </script>

        {{-- <script>
            // JavaScript to toggle all checkboxes
            $('#checkAll').on('click', function() {
                $('input[name="renewalVehicles[]"]').prop('checked', this.checked);
            });
        </script>    --}}

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var hoaFilter = document.getElementById('hoaFilter');
                var rows = document.querySelectorAll('#vehicleTableBody tr');
                var addressIdInput = document.getElementById('address_id');

                function filterRows() {
                    var selectedHoaName = hoaFilter.value;
                    var hasVisibleRows = false;
                    var addressId = null;

                    rows.forEach(function(row) {
                        var checkbox = row.querySelector('input[name="renewalVehicles[]"]');
                        if (row.getAttribute('data-hoa-name') === selectedHoaName) {
                            row.style.display = '';
                            checkbox.checked = true; // Check the checkbox if the row is visible
                            hasVisibleRows = true;
                            addressId = row.getAttribute('data-address-id'); // Get the address_id from the row
                        } else {
                            row.style.display = 'none';
                            checkbox.checked = false; // Uncheck the checkbox if the row is hidden
                        }
                    });

                    // Show or hide the "No vehicles available" message
                    var noVehiclesRow = document.getElementById('no-vehicles-row');
                    if (noVehiclesRow) {
                        noVehiclesRow.style.display = hasVisibleRows ? 'none' : '';
                    }

                    // Update the hidden input field with the address_id
                    addressIdInput.value = addressId;
                }

                // Apply filter on page load
                filterRows();

                // Apply filter on dropdown change
                hoaFilter.addEventListener('change', filterRows);

                // Ensure only selected vehicles are submitted
                document.getElementById('renewal_request_form').addEventListener('submit', function() {
                    var checkboxes = document.querySelectorAll('input[name="renewalVehicles[]"]');
                    checkboxes.forEach(function(checkbox) {
                        if (!checkbox.checked) {
                            checkbox.disabled = true;
                        }
                    });
                });
            });
        </script>

        <script>
            document.getElementById('renewal_update_form').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent the form from submitting immediately

                // Display a confirmation dialog
                var userConfirmed = confirm('Are you sure you want to update the account?');

                // If the user clicked "Yes", submit the form
                if (userConfirmed) {
                    this.submit();
                }
            });
        </script>
    @endsection

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"
        integrity="sha512-6sSYJqDreZRZGkJ3b+YfdhB3MzmuP9R7X1QZ6g5aIXhRvR1Y/N/P47jmnkENm7YL3oqsmI6AK+V6AD99uWDnIw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
