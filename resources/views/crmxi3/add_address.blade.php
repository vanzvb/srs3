
<link rel="stylesheet" href="{{ asset('css/crmxi-modal-style.css') }}">

<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" >
        <div class="modal-content modalContent" >
            <div class="modal-header py-2 modalHeader">
                <div style="display:flex; justify-content:space-between;width: 100%">
                    <h1 class="modal-title fs-3 " id="addAddressModalLabel" > ADD ACCOUNT</h1>
                    <button type="button" class="btn btn-lg " data-bs-dismiss="modal" aria-label="Close" style="color:white;">
                        <i class="fa-solid fa-times fa-xl" ></i>
                    </button>
                </div>
            </div>
            <div>
                <form action="{{ url('insert_crm_account_address') }}" id="accountAddressForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body modalBody" >
                        <div style="width: 100%;height: 30px" class="d-flex justify-start mt-2 mb-2">
                            <div style="width: 100%;text-align: end;">
                                <button type="button" id="addAddressBtn" class="addBtn btn btn-sm text-white" onclick="addressForm()">
                                    <span>Add Address</span>
                                </button>
                            </div>

                            {{-- <div style="width: 50%;text-align: end;">
                                <button type="button" class="addBtn btn btn-sm text-white" data-bs-toggle="" data-bs-target="">
                                    <span >Search Existing Account</span>
                                </button>
                            </div> --}}
                        </div>
                        {{-- current account_id for edit account 'hidden' --}}
                        <input style="border: 1px solid black;" type="hidden" id="current_account_id" name="current_account_id" class="form-control" >
                        <div class="card" style="border: 1px solid black">
                            <div class="card-header">
                                <div class="h5 fw-bold">
                                    <i class="fa-regular fa-address-book me-1"></i>
                                    <span>Address Information</span>
                                </div>
                            </div>
                            <div class="card-body pt-1 ps-4 pe-4">
                                <div class="row">
                                    <div class="mb-2">
                                        <span style="font-weight:700;">Address (1)</span>
                                        {{-- current id of address --}}
                                        <input style="border: 1px solid black;" type="hidden" id="current_id1" name="toPass[0][current_id]" class="form-control" >
                                        <div class="row ms-0" style="border: 1px solid gray;width: 100%;">
                                            <div class="col-md-4 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">Category : </label>
                                                    <select style="border: 1px solid black;" id="category_id1" name="toPass[0][category_id]" onchange="categoryChange($(this).val(),1)" class="form-select " aria-label=" example" required>
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
                                                    <select style="border: 1px solid black;" name="toPass[0][sub_category]"  id="sub_category_id1" onchange="sub_categoryChange($(this).val(),1)" class="form-select " aria-label="Default select example" disabled required>
                                                    {{-- <select style="border: 1px solid black;" name="toPass[0][sub_category]"  id="sub_category_id1"  class="form-select " aria-label="Default select example" disabled required> --}}
                                                        <option value="">---</option>
                                                        @foreach ($subcats as $subcat)
                                                            <option value="<?= $subcat->id ?>">{{ $subcat->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3" id="hoa_slot1">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">HOA : </label>
                                                    <select style="border: 1px solid black;" id="hoa1" name="toPass[0][hoa]" class="form-select" aria-label="Default select example" onchange="hoaChange($(this).val(),1)" disabled>
                                                        <option value="-1">---</option>
                                                        @foreach ($hoas as $hoa)
                                                        <option value="<?= $hoa->id ?>">{{ $hoa->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">Member Type : </label>
                                                    <select style="border: 1px solid black;" id="hoa_type1"
                                                        name="toPass[0][hoa_type]" class="form-select" aria-label="Default select example" disabled>
                                                        <option value="">---</option>
                                                        @foreach ($hoatypes as $hoatype)
                                                            <option value="<?= "$hoatype->id"
                                                                ?>">{{ $hoatype->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">Block : </label>
                                                    <input style="border: 1px solid black;" type="number" id="block1" name="toPass[0][block]" class="form-control" >
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">Lot : </label>
                                                    <input style="border: 1px solid black;" type="number" id="lot1" name="toPass[0][lot]" class="form-control" >
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">House Number : </label>
                                                    <input style="border: 1px solid black;" type="number" id="house_number1" name="toPass[0][house_number]" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">Street : </label>
                                                    <input style="border: 1px solid black;" type="text" id="street1" name="toPass[0][street]" class="form-control" required>
                                                </div>
                                            </div>
        
                                            <div class="col-md-4 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2" style="font-size: 14px;">Building / Apartment / Condo : </label>
                                                    <input style="border: 1px solid black;" type="text" id="building_name1" name="toPass[0][building_name]" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">Subdivision / Village : </label>
                                                    <input style="border: 1px solid black;" type="text" id="subdivision1" name="toPass[0][subdivision]" class="form-control">
                                                </div>
                                            </div>
        
                                            <div class="col-md-3 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">City : </label>
                                                    <select style="border: 1px solid black;" name="toPass[0][city]" id="city1" class="form-select" aria-label="Default select example" onchange="cityChange($(this).val(),1)">
                                                        <option selected>---</option>
                                                        @foreach($cities as $city)
                                                        <option value="{{$city->bl_id}}">{{$city->description}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">Zip Code : </label>
                                                    <select style="border: 1px solid black;" name="toPass[0][zip_code]"  id="zip_code1" class="form-select " aria-label="Default select example">
                                                        <option value="">---</option>
            
                                                    </select>
                                                    {{-- <input style="border: 1px solid black;" type="number" id="zip_code" name="zip_code" class="form-control"> --}}
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div id="input_address"></div>
                                                                      
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
    let addressCount = 1;
    var subcats = {!! json_encode($subcats) !!};
    var hoatypes = {!! json_encode($hoatypes) !!};
    var hoas = {!! json_encode($hoas) !!};

    function addressForm() {
        ++addressCount;
        var form = `<div class="mb-2">
                        <span style="font-weight:700;">Address (${addressCount})</span>
                        {{-- current id of address --}}
                        <input style="border: 1px solid black;" type="hidden" id="current_id${addressCount}" name="toPass[${addressCount-1}][current_id]" class="form-control" >
                        <div class="row ms-0" style="border: 1px solid gray;width: 100%;">
                            <div class="col-md-4 mb-3">
                                <div class="mb-1">
                                    <label class="form-label mb-2">Category : </label>
                                    <select style="border: 1px solid black;" id="category_id${addressCount}" name="toPass[${addressCount-1}][category_id]" onchange="categoryChange($(this).val(),${addressCount})" class="form-select " aria-label=" example" required>
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
                                    <select style="border: 1px solid black;" name="toPass[${addressCount-1}][sub_category]"  id="sub_category_id${addressCount}" onchange="sub_categoryChange($(this).val(),${addressCount})" class="form-select " aria-label="Default select example" disabled required>
                                    {{-- <select style="border: 1px solid black;" name="toPass[${addressCount-1}][sub_category]"  id="sub_category_id${addressCount}" class="form-select " aria-label="Default select example" disabled required> --}}
                                        <option value="">---</option>
                                        @foreach ($subcats as $subcat)
                                            <option value="<?= $subcat->id ?>">{{ $subcat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3" id="hoa_slot1">
                                <div class="mb-1">
                                    <label class="form-label mb-2">HOA : </label>
                                    <select style="border: 1px solid black;" id="hoa${addressCount}" name="toPass[${addressCount-1}][hoa]" class="form-select" aria-label="Default select example" onchange="hoaChange($(this).val(),${addressCount})" disabled>
                                        <option value="-1">---</option>
                                        @foreach ($hoas as $hoa)
                                        <option value="<?= $hoa->id ?>">{{ $hoa->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="mb-1">
                                    <label class="form-label mb-2">Member Type : </label>
                                    <select style="border: 1px solid black;" id="hoa_type${addressCount}"
                                        name="toPass[${addressCount-1}][hoa_type]" class="form-select" aria-label="Default select example" disabled>
                                        <option value="">---</option>
                                        @foreach ($hoatypes as $hoatype)
                                            <option value="<?= "$hoatype->id"
                                                ?>">{{ $hoatype->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="mb-1">
                                    <label class="form-label mb-2">Block : </label>
                                    <input style="border: 1px solid black;" type="number" id="block${addressCount}" name="toPass[${addressCount-1}][block]" class="form-control" >
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="mb-1">
                                    <label class="form-label mb-2">Lot : </label>
                                    <input style="border: 1px solid black;" type="number" id="lot${addressCount}" name="toPass[${addressCount-1}][lot]" class="form-control" >
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="mb-1">
                                    <label class="form-label mb-2">House Number : </label>
                                    <input style="border: 1px solid black;" type="number" id="house_number${addressCount}" name="toPass[${addressCount-1}][house_number]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="mb-1">
                                    <label class="form-label mb-2">Street : </label>
                                    <input style="border: 1px solid black;" type="text" id="street${addressCount}" name="toPass[${addressCount-1}][street]" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="mb-1">
                                    <label class="form-label mb-2" style="font-size: 14px;">Building / Apartment / Condo : </label>
                                    <input style="border: 1px solid black;" type="text" id="building_name${addressCount}" name="toPass[${addressCount-1}][building_name]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="mb-1">
                                    <label class="form-label mb-2">Subdivision / Village : </label>
                                    <input style="border: 1px solid black;" type="text" id="subdivision${addressCount}" name="toPass[${addressCount-1}][subdivision]" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="mb-1">
                                    <label class="form-label mb-2">City : </label>
                                    <select style="border: 1px solid black;" name="toPass[${addressCount-1}][city]" id="city${addressCount}" class="form-select" aria-label="Default select example" onchange="cityChange($(this).val(),${addressCount})">
                                        <option selected>---</option>
                                        @foreach($cities as $city)
                                        <option value="{{$city->bl_id}}">{{$city->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="mb-1">
                                    <label class="form-label mb-2">Zip Code : </label>
                                    <select style="border: 1px solid black;" name="toPass[${addressCount-1}][zip_code]"  id="zip_code${addressCount}" class="form-select " aria-label="Default select example" required>
                                        <option value="">---</option>

                                    </select>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                `
        $('#input_address').append(form);
        $('select').addClass('highlightSelect');
    }
    
    function categoryChange(id, counter, selectedSubCategoryId = null) {
        $('#accountAddressForm ' + `#hoa${counter == addressCount ? addressCount : counter} option[value="-1"]`)
                        .prop('selected', true)
        $('#accountAddressForm ' + '#subdivision'  + counter).prop('disabled', false);
        $('#accountAddressForm ' + '#hoa'  + counter).prop('disabled', true);
        let toCheckSelected = $('#accountAddressForm ' +  `#sub_category_id${counter == addressCount ? addressCount : counter}`).val()
        let toCheckSelectedHoa = $('#accountAddressForm ' +  `#hoa_type${counter == addressCount ? addressCount : counter}`).val()
        if (toCheckSelected) {
            $('#accountAddressForm ' +  `#sub_category_id${counter == addressCount ? addressCount : counter}`).val('')
        }
        if (toCheckSelectedHoa) {
            $('#accountAddressForm ' +  `#hoa_type${counter == addressCount ? addressCount : counter}`).val('')
        }

        if (counter == addressCount) {
            $('#accountAddressForm ' +  '#sub_category_id' + addressCount).prop('disabled', false);
        } else {
            $('#accountAddressForm ' +  '#sub_category_id' + counter).prop('disabled', false);
        }

        subcats.forEach(data => {
            if (+data.category_id == id) {
                $('#accountAddressForm ' +  `#sub_category_id${counter == addressCount ? addressCount : counter} option[value="${data.id}"]`)
                    .show();
            } else {
                $('#accountAddressForm ' +  `#sub_category_id${counter == addressCount ? addressCount : counter} option[value="${data.id}"]`)
                    .hide();
            }
        })
        // $('#accountAddressForm ' +  `#hoa${counter == addressCount ? addressCount : counter}`).prop('disabled', true)

        if (selectedSubCategoryId) {
            $('#accountAddressForm ' +  '#sub_category_id' + counter).val(selectedSubCategoryId);
        }  
    };
    function sub_categoryChange(id, counter, selectedHoaTypeId = null) {
        $('#accountAddressForm ' +  `#hoa${counter == addressCount ? addressCount : counter} option[value="-1"]`)
                        .prop('selected', true)
        if(!selectedHoaTypeId){
            $('#accountAddressForm ' +  '#hoa' + counter).prop('disabled', false);
        }
        let toCheckSelected = $('#accountAddressForm ' +  `#hoa_type${counter == addressCount ? addressCount : counter}`).val()

        if (toCheckSelected) {
            $('#accountAddressForm ' +  `#hoa_type${counter == addressCount ? addressCount : counter}`).val('')
            
        }

        if (counter == addressCount) {
            $('#accountAddressForm ' +  '#hoa_type' + addressCount).prop('disabled', false);
            // $('#accountAddressForm ' +  '#hoa' + addressCount).prop('disabled', false);
        } else {
            $('#accountAddressForm ' +  '#hoa_type' + counter).prop('disabled', false);
            // $('#accountAddressForm ' +  '#hoa' + counter).prop('disabled', false);
        }
        let getCat = $('#accountAddressForm ' +  `#category_id${counter == addressCount ? addressCount : counter}`).val();
        let getHoa = $('#accountAddressForm ' +  `#hoa${counter == addressCount ? addressCount : counter}`).val();
        let filHoa =  hoas.filter(rec=> {
            return rec.id == getHoa
        })[0]?.type
        if(getCat == 2 ){
            if(id == 48){
                $('#accountAddressForm ' +  `#hoa${counter == addressCount ? addressCount : counter}`).prop('disabled', false);
            }else{
                $('#accountAddressForm ' +  `#hoa${counter == addressCount ? addressCount : counter}`).prop('disabled', true);
            }
        }else{
            $('#accountAddressForm ' +  `#hoa_slot${counter == addressCount ? addressCount : counter}`).prop('disabled', false)

        }
        if(getHoa == -1 && !(id == 48)){
            $('#accountAddressForm ' +  `#hoa_type${counter == addressCount ? addressCount : counter} option[value="2"]`)
                        .prop('selected', true)
        }
        hoas.map(rec=>{
            if(rec.type == 0 || rec.type == 1){
                getCat == 1 ? $('#accountAddressForm ' +  `#hoa${counter == addressCount ? addressCount : counter} option[value="${rec.id}"]`)
                                .show()
                            : $('#accountAddressForm ' +  `#hoa${counter == addressCount ? addressCount : counter} option[value="${rec.id}"]`)
                                .hide()

            }else{
                getCat == 2 ? $('#accountAddressForm ' +  `#hoa${counter == addressCount ? addressCount : counter} option[value="${rec.id}"]`)
                                .show()
                            : $('#accountAddressForm ' +  `#hoa${counter == addressCount ? addressCount : counter} option[value="${rec.id}"]`)
                                .hide()
            }                   
        })
        hoatypes.map(data => {
            let idx = hoatypes.indexOf(data)
            if(data.sub_category_id == id){
                $('#accountAddressForm ' +  `#hoa_type${counter == addressCount ? addressCount : counter} option:eq(${idx + 1})`)
                    .show();
                if (id == 40 || id == 44 || id == 50) {
                    $('#accountAddressForm ' +  `#hoa_type${counter == addressCount ? addressCount : counter} option[value="${data.id}"]`)
                        .prop('selected', true)
                }

            }else{
                $('#accountAddressForm ' +  `#hoa_type${counter == addressCount ? addressCount : counter} option:eq(${idx + 1})`)
                    .hide();
            }
            if (data.id == selectedHoaTypeId) {
                $('#accountAddressForm ' +  `#hoa_type${counter == addressCount ? addressCount : counter} option[value="${data.id}"]`)
                .prop('selected', true)
            }
        })
               
    };
    function hoaChange(id, counter, selectedHoa = null) {
        $('#accountAddressForm ' +  `#subdivision${counter == addressCount ? addressCount : counter}`).val('')
        let toCheckSelected = $('#accountAddressForm ' +  `#hoa_type${counter == addressCount ? addressCount : counter}`).val()

        if (toCheckSelected) {
            $('#accountAddressForm ' +  `#hoa_type${counter == addressCount ? addressCount : counter}`).val('')
        }

        if (counter == addressCount) {
            $('#accountAddressForm ' +  '#hoa_type' + addressCount).prop('disabled', false);
        } else {
            $('#accountAddressForm ' +  '#hoa_type' + counter).prop('disabled', false);
        }
        // let getHoa = $('#accountAddressForm ' +  `#hoa${counter == addressCount ? addressCount : counter}`).val();
        let filHoa =  hoas.filter(rec=> {
            return rec.id == id
        })[0]?.type
        hoatypes.map(data => {
            let idx = hoatypes.indexOf(data)
            if(!(id == -1)){
                $('#accountAddressForm ' +  '#subdivision'  + counter).prop('disabled', true);
            }else{
                $('#accountAddressForm ' +  '#subdivision'  + counter).prop('disabled', false);
            }
            if(id == -1){
                $('#accountAddressForm ' +  `#hoa_type${counter == addressCount ? addressCount : counter} option[value="2"]`)
                        .prop('selected', true)
                        return
            }   
            if(filHoa == data.id){
                $('#accountAddressForm ' +  `#hoa_type${counter == addressCount ? addressCount : counter} option[value="${data.id}"]`)
                        .prop('selected', true)
            }
            
                
        })  
          
    };
    function cityChange(id, counter, selectedZipcode = null) {
        $('#accountAddressForm ' + '#zip_code' + counter).html('');
        $('#accountAddressForm ' + '#zip_code' + counter).append(`<option value="">--- </option>`);

        $.get("{{ url('crmxi_getZipcode') }}" + `/${id}`, function(response) {

            response.forEach(function(a) {
                var e = `<option value="${a.zip_code}">${a.zip_code}</option>`;
                $('#accountAddressForm ' + '#zip_code' + counter).append(e);
                $('#accountAddressForm ' + `#zip_code${counter} option[value="${a.zip_code}"]`).prop('selected', true)
            });

            // Set the selected subcategory if provided
            if (selectedZipcode) {
                $('#accountAddressForm ' + '#zip_code' + counter).val(selectedZipcode);
            }
        });
    }
    
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

    $('#addAddressModal').on('hidden.bs.modal', function (){
        $('#accountAddressForm')[0].reset();
    })

</script>


<style>
    

</style>