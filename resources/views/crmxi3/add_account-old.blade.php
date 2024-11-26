
<link rel="stylesheet" href="{{ asset('css/crmxi-modal-style.css') }}">

<div class="modal fade" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" >
        <div class="modal-content modalContent" >
            <div class="modal-header py-2 modalHeader">
                <div style="display:flex; justify-content:space-between;width: 100%">
                    <h1 class="modal-title fs-3 " id="addAccountModalLabel" > ADD ACCOUNT</h1>
                    <button type="button" class="btn btn-lg " data-bs-dismiss="modal" aria-label="Close" style="color:white;">
                        <i class="fa-solid fa-times fa-xl" ></i>
                    </button>
                </div>
            </div>
            <div>
                <form action="{{ url('insert_crm_account') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body modalBody" >
                        <div style="width: 100%;height: 30px" class="d-flex justify-start mt-2 mb-2">
                            <div class="accountTypeContainer" style="align-self: center">
                                {{-- current account_id for edit account 'hidden' --}}
                                <input style="border: 1px solid black;" type="hidden" id="current_account_id" name="current_account_id" class="form-control" >

                                <span class="h7 fw-bold text-black me-2">Account Type:</span>
                                <input class="form-check-input me-2" type="radio" name="account_type" id="account_type_individual" value="0" checked>
                                <label class="form-check-label me-2" for="account_type_individual">
                                    Individual
                                </label>
                                <input class="form-check-input me-2" type="radio" name="account_type" id="account_type_company" value="1">
                                <label class="form-check-label me-2" for="account_type_company">
                                    Company
                                </label>
                            </div>
                            {{-- <div style="width: 50%;text-align: end;">
                                <button type="button" class="addBtn btn btn-sm text-white" data-bs-toggle="" data-bs-target="">
                                    <span >Search Existing Account</span>
                                </button>
                            </div> --}}
                        </div>
                        <div class="card" style="border: 1px solid black">
                            <div class="infoHeader card-header">
                                <div class="h5 fw-bold indiInputs ">
                                    <i class="fas fa-user-alt me-1"></i>
                                    <span>Personal Information</span>
                                </div>
                                <div class="h5 fw-bold compInputs ">
                                    <i class="fas fa-building me-1"></i>
                                    <span>Company Information</span>
                                </div>
                            </div>
                            <div class="card-body pt-1 ps-4 pe-4">
                                <div class="row">
                                    <div class="col-md-6 compInputs mt-3 mb-3" >
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Company name: </label>
                                            <input style="border: 1px solid black;" type="text" id="company_name" name="company_name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6 compInputs  mt-3 mb-3" >
                                        <div class="mb-1">
                                            <label class="form-label mb-2" >Company Representative: </label>
                                            <input style="border: 1px solid black;" type="text" id="representative_name" name="representative_name" class="form-control" >
                                        </div>
                                    </div>

                                    <div class="col-md-4 indiInputs mt-3 mb-3" >
                                        <div class="mb-1">
                                            <label class="form-label mb-2" >First name: </label>
                                            <input style="border: 1px solid black;" type="text" id="first_name" name="first_name" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-md-4 indiInputs mt-3 mb-3" >
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Middle name: </label>
                                            <input style="border: 1px solid black;" type="text" id="middle_name" name="middle_name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4 indiInputs mt-3 mb-3" >
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Last name: </label>
                                            <input style="border: 1px solid black;" type="text" id="last_name" name="last_name" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Block : </label>
                                            <input style="border: 1px solid black;" type="number" id="block" name="block" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Lot : </label>
                                            <input style="border: 1px solid black;" type="number" id="lot" name="lot" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">House Number : </label>
                                            <input style="border: 1px solid black;" type="number" id="house_number" name="house_number" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Street : </label>
                                            <input style="border: 1px solid black;" type="text" id="street" name="street" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Building / Apartment / Condo : </label>
                                            <input style="border: 1px solid black;" type="text" id="building_name" name="building_name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Subdivision / Village : </label>
                                            <input style="border: 1px solid black;" type="text" id="subdivision" name="subdivision" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">City : </label>
                                            <select style="border: 1px solid black;" name="city" id="city" class="form-select" aria-label="Default select example" onchange="cityChange($(this).val())">
                                                <option selected>---</option>
                                                @foreach($cities as $city)
                                                <option value="{{$city->bl_id}}">{{$city->description}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Zip Code : </label>
                                            <select style="border: 1px solid black;" name="zip_code"  id="zip_code" class="form-select " aria-label="Default select example" required>
                                                <option value="">---</option>
    
                                            </select>
                                            {{-- <input style="border: 1px solid black;" type="number" id="zip_code" name="zip_code" class="form-control"> --}}
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">TIN NO : </label>
                                            <input style="border: 1px solid black;" type="text" id="tin_no" name="tin_no" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-4 indiInputs mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Civil Status : </label>
                                            <select style="border: 1px solid black;" id="civil_status" name="civil_status" class="form-select" aria-label="Default select example">
                                                <option selected>---</option>
                                                @foreach($civil_status as $c_status)
                                                <option value="{{$c_status->id}}">{{$c_status->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 indiInputs mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Nationality : </label>
                                            <select style="border: 1px solid black;" id="nationality" name="nationality" class="form-select" aria-label="Default select example" required>
                                                <option selected>---</option>
                                                @foreach($nationalities as $nationality)
                                                <option value="{{$nationality->id}}">{{$nationality->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 indiInputs mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Email : </label>
                                            <input style="border: 1px solid black;" id="email" type="email" name="email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Category : </label>
                                            <select style="border: 1px solid black;" id="category_id" name="category_id" onchange="categoryChange($(this).val())" class="form-select " aria-label=" example" required>
                                                <option value="">---</option>
                                                @foreach ($categories as $category)
                                                <option value="<?= $category->id ?>">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Sub Category : </label>
                                            <select style="border: 1px solid black;" name="sub_category_id"  id="sub_category" class="form-select " aria-label="Default select example" required>
                                                <option value="">---</option>
    
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">HOA : </label>
                                            <select style="border: 1px solid black;" id="hoa" name="hoa" class="form-select" aria-label="Default select example">
                                                <option value="0">---</option>
                                                @foreach ($hoas as $hoa)
                                                <option value="<?= $hoa->id ?>">{{ $hoa->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4 compInputs mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Email : </label>
                                            <input style="border: 1px solid black;" id="emailComp" type="email" name="email" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Main Contact no. : </label>
                                            <input style="border: 1px solid black;" type="number" id="main_contact" name="main_contact" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Secondary Contact no. : </label>
                                            <input style="border: 1px solid black;" type="number" id="secondary_contact" name="secondary_contact" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Tertiary Contact no. : </label>
                                            <input style="border: 1px solid black;" type="number" id="tertiary_contact" name="tertiary_contact" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="card mt-2" style="border: 1px solid black">
                            <div class="infoHeader card-header">
                                <div class="h5 fw-bold">
                                    <i class="fas fa-car me-1"></i>
                                    <span>Vehicle/s </span>
                                </div>
                            </div>
                            <div class="card-body pt-1">
                                <div style="width: 100%;height: 30px" class="d-flex justify-start">
                                    <div style="width: 100%;text-align: end;">
                                        <button type="button" class="addBtn btn btn-sm text-white" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                                            <i class="fas fa-car me-1"></i>
                                            <span >Add Vehicle/s</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="vehicleDiv" style="width: 100%">
                                    <table class="vehicleTable" >
                                        <thead>
                                            <tr>
                                                <th >Plate no.</th>
                                                <th >Brand</th>
                                                <th >Series</th>
                                                <th >Year/Model</th>
                                                <th >Color</th>
                                                <th >Type</th>
                                                <th >Owner Name</th>
                                                <th >Owner Address</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="submit_crm" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- ADD VEHICLE MODAL -- RG   --}}
{{-- @include('crmxi3.add_vehicle') --}}
{{-- *END* ADD VEHICLE MODAL -- RG   --}}

<script
  src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
  crossorigin="anonymous">
</script>

<script>
    $(document).ready(function() {
        function toggleChangeInput() {
            if ($('#account_type_company').is(':checked')) {
                $('.compInputs').show();
                $('.indiInputs').hide();
                $('#representative_name').attr('required', true);
                $('#first_name').attr('required', false);
            } else {
                $('.compInputs').hide();
                $('.indiInputs').show();
                $('#first_name').attr('required', true);
                $('#representative_name').attr('required', false);
            }
        }

        $('input[name="account_type"]').on('change', toggleChangeInput);

        // Initial call to set the correct display based on the default selected radio button
        toggleChangeInput();
    });

    function categoryChange(id, selectedSubCategoryId = null) {
        $('#sub_category').html('');
        $('#sub_category').append(`<option value="">--- </option>`);
        
        $.get("{{ url('crmxi_getSubCat') }}" + `/${id}`, function(response) {
            
            response.forEach(function(a) {
                var e = `<option value="${a.id}">${a.name}</option>`;
                $('#sub_category').append(e);
            });
        
            // Set the selected subcategory if provided
            if (selectedSubCategoryId) {
                $('#sub_category').val(selectedSubCategoryId);
            }
        });
    }

    function cityChange(id, selectedZipcode = null) {
        $('#zip_code').html('');
        $('#zip_code').append(`<option value="">--- </option>`);
        
        $.get("{{ url('crmxi_getZipcode') }}" + `/${id}`, function(response) {
            
            response.forEach(function(a) {
                var e = `<option value="${a.zip_code}">${a.zip_code}</option>`;
                $('#zip_code').append(e);
                $(`#zip_code option[value="${a.zip_code}"]`).prop('selected', true)
            });
        
            // Set the selected subcategory if provided
            if (selectedZipcode) {
                $('#zip_code').val(selectedZipcode);
            }
        });
    }

    // function sub_categoryChange(id) {
    // // $('#category_id').change(function (){
    //     $('#hoa_type').html('');
    //     $('#hoa_type').append(`<option value="">--- </option>`);
    //     $.get( "{{ url('crmxi_getHoas') }}" + `/${id}`,
    //         function(response) {
    //             console.log(response,'response')
    //             // response.forEach(function(a) {
    //             //     var e = `<option value="${a.id}">${a.name}</option>`;
    //             //     $('#sub_category').append(e);

    //             // });
                
    //             response.forEach(function(a) {
    //                 var e = `
    //                     <option value="${a.id}-${a.subcat_hoa_type_id}">${a.name}</option>
    //                 `;
    //                 $('#hoa_type').append(e);

    //             });

    //             // response[1].forEach(function(a) {
    //             //     var e = `<option value="${a.name}">${a.name}</option>`;
    //             //     $('#hoa').append(e);
    //             // });
    //         });
    // // })
        
    // }
    $(document).on('submit', '#submit_crm', function() {
        $(this).prop('disabled', true);
        $(this).html(
            `
                <div class="spinner-border spinner-border-sm" role="status"></div>
                <br>
                Processing
            `);
    });

    $('select').addClass('highlightSelect');

</script>


<style>
    

</style>