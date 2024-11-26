<link rel="stylesheet" href="{{ asset('css/crmxi-modal-style.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<div class="modal fade" id="addRedTagModal" tabindex="-1" aria-labelledby="addRedTagModalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content modalContent">
            <div class="modal-header py-2 modalHeader">
                <div style="display:flex; justify-content:space-between;width: 100%;">
                    <h1 class="modal-title fs-3 " id="addRedTagModalLabel"> ADD RED TAG</h1>
                    <button type="button" class="btn btn-lg " data-bs-dismiss="modal" aria-label="Close"
                        style="color:white;">
                        <i class="fa-solid fa-times fa-xl"></i>
                    </button>
                </div>
            </div>
            <div>
                <form id="redTagForm"
                    action="{{ url('insert_redtag') . '?account_id=' . urlencode($crms_account[0]->account_id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body ">
                        <div style="width: 100%;height: 30px;" class="d-flex justify-start mt-2 mb-2">
                            <div style="width: 100%;text-align: start;">
                                <span class="h7 fw-bold text-black me-2">Red Tag Type:</span>
                                <input class="form-check-input me-2" type="radio" name="redtag_type"
                                    id="redtag_type_account" value="0" checked>
                                <label class="form-check-label me-2" for="redtag_type_account">
                                    Account
                                </label>
                                <input class="form-check-input me-2" type="radio" name="redtag_type"
                                    id="redtag_type_vehicle" value="1">
                                <label class="form-check-label me-2" for="redtag_type_vehicle">
                                    Vehicle
                                </label>
                                {{-- <button type="button" id="addVehicleBtn" class="addBtn btn btn-sm text-white" onclick="vehicleForm()">
                                    <span>Add Vehicle</span>
                                </button> --}}
                            </div>
                        </div>
                        <div class="card mb-4" style="border: 1px solid black;">
                            <div class="infoHeader card-header">
                                <div class="h5 fw-bold " id="formTitle">
                                    {{-- <i class="fas fa-car me-1"></i>
                                    <span>Account</span> --}}
                                </div>
                            </div>
                            {{-- <input type="hidden" id="current_vehicle_id1" name="toPass[0][current_vehicle_id]" class="form-control">
                            <input type="hidden" id="current_owner_id1" name="toPass[0][current_owner_id]" class="form-control"> --}}

                            {{-- form for red tag account --}}
                            <div class="card-body pt-1 ps-4 pe-4" id="redTagAccontForm">
                                <div class="row">
                                    <div class="col-md-12 mt-3 ">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Reason of Red Tag : </label>
                                            {{-- <textarea class="form-control" name="reason_of_tag" placeholder="Leave a comment here" id="floatingTextarea"
                                                style="height:200px; width: 100%;" required></textarea> --}}
                                            {{-- <select name="reason" id="reason"
                                                class="form-select form-select-sm  mb-3"
                                                aria-label=".form-select form-select-sm-lg example" >
                                                <option value="">---</option>
                                                @foreach ($redtag_list as $redtag_item)
                                                    <option value="{{ $redtag_item->reason }}">{{ $redtag_item->reason }}</option>
                                                @endforeach
                                            </select> --}}

                                            <select class="form-select" name="reason" id="reason">
                                                <option value="" selected>-----</option>
                                                @foreach ($redtag_list as $redtag_item)
                                                    <option value="{{ $redtag_item->reason }}">
                                                        {{ $redtag_item->reason }}</option>
                                                @endforeach
                                                <option value="Others">Others</option>
                                            </select>
                                            <input hidden type="text" name="other_reason" class="form-control"
                                                id="other_reason" placeholder="Add Reason here"  />
                                        </div>
                                    </div>


                                </div>
                            </div>

                            {{-- form for red tag vehicle --}}
                            <div class="card-body pt-1 ps-4 pe-4" id="redTagVehicleForm">
                                <div class="row">
                                    <div class="col-md-12 mt-3 ">
                                        <div class="mb-1">
                                            <input type="hidden" name="redtag_vehicles[]" id="redtag_vehicles" />
                                            <table id="redtag_vehicle_table"
                                                class="table table-bordered w-100 bg-white">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Plate no.</th>
                                                        <th>Owner Name</th>
                                                        <th>Address</th>
                                                        <th>
                                                            Brand
                                                            <br>
                                                            Series
                                                            <br>
                                                            Year/Model
                                                            <br>
                                                            Color
                                                            <br>
                                                            Type
                                                            <br>
                                                            Old Sticker no.
                                                        </th>
                                                        <th>Latest Stricker No. <br> (Sticker Year)</th>
                                                        <th>Category / Sub Category</th>
                                                        <th>HOA</th>
                                                        <th>Member Type / Vehicle Ownership Status</th>
                                                        <th>Reason of Red Tag</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="submit_vehicle" class="btn btn-primary">Submit</button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

<script>
    $(document).ready(function() {
        function toggleChangeInput() {
            // Remove the previously appended elements (icon and span)
            $('#formTitle .dynamic-icon').remove();
            $('#formTitle .dynamic-text').remove();

            if ($('#redtag_type_vehicle').is(':checked')) {
                // Append vehicle icon and text
                $('#formTitle').append('<i class="fas fa-car me-1 dynamic-icon"></i>');
                $('#formTitle').append('<span class="dynamic-text">Vehicle</span>');
                $('#redTagAccontForm').hide()
                $('#redTagVehicleForm').show()
            } else {
                // Append account icon and text
                $('#formTitle').append('<i class="fas fa-user-alt me-1 dynamic-icon"></i>');
                $('#formTitle').append('<span class="dynamic-text">Account</span>');
                $('#redTagAccontForm').show()
                $('#redTagVehicleForm').hide()
            }
        }

        // Bind the change event to the radio buttons
        $('input[name="redtag_type"]').on('change', toggleChangeInput);

        // Initial call to set the correct display based on the default selected radio button
        toggleChangeInput();

        // $('#reason').select2({
        //     theme: "bootstrap-5",   
        //     // width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
        //     //     'style',
        //     // placeholder: $(this).data('placeholder'),
        //     closeOnSelect: true,
        // });
    });

    function loadVehicleForRedTag() {
        table = $('#redtag_vehicle_table').DataTable({
            // scrollX: true,
            processing: true,
            pageLength: 15,
            serverSide: true,
            searching: false,
            destroy: true,
            lengthMenu: [
                [15, 30, 50, 100],
                ['15', '30', '50', '100']
            ],
            ajax: {
                url: "{{ url('/crmxi/vehicles/' . $crms_account[0]->account_id) }}"

            },
            order: [
                [1, "desc"]
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    render: function(data, type, row) {
                        let plate = `
                            ${row.plate_no}
                        `
                        let status = ''
                        if (row.red_tag == 1) {
                            status =
                                `
                            <div class="statusRedTag" style="height: 15px !important; width:50px !important;font-size: 10px !important;">Red Tag</div>`
                        } else if (row.status == 0) {
                            status =
                                `<div class="statusInactive" style="height: 15px !important; width:50px !important;font-size: 10px !important;">Inactive</div>`
                        } else {
                            status =
                                `<div class="statusActive" style="height: 15px !important; width:50px !important;font-size: 10px !important;" >Active</div>`
                        }
                        return plate + '<br>' + '<br>' + status;
                    }
                },
                {
                    render: function(data, type, row) {
                        return row.firstname + (row.middlename ? ' ' + row.middlename + ' ' : '') + (row
                            .lastname ? ' ' + row.lastname + ' ' : '');
                    },
                },
                {
                    data: 'address'
                },
                {
                    render: function(data, type, row) {
                        return `
                                ${row.brand ?? ''},
                                ${row.series ?? ''},
                                ${row.year_model ?? ''},
                                ${row.color ?? ''},
                                ${row.type ?? ''},
                                ${row.old_sticker_no ?? ''}
                            
                            `
                    }
                },
                {
                    render: function(data, type, row) {
                        if (row.new_sticker_no) {
                            return `${row.new_sticker_no} 
                                    (${row.sticker_date 
                                        ? row.sticker_date 
                                        : row.old_sticker_year ? row.old_sticker_year : ''})
                                    `;
                        } else {
                            return row.old_sticker_no ? `${row.old_sticker_no} 
                                    (${row.sticker_date 
                                        ? row.sticker_date 
                                        : row.old_sticker_year ? row.old_sticker_year : ''})
                                    ` : '';
                        };
                    },
                },
                {
                    render: function(data, type, row) {
                        return `${row.category_name ? row.category_name : ''} / ${row.subcat_name ? row.subcat_name : ''}`
                    }
                },
                {
                    data: 'hoa_name'
                },
                {
                    render: function(data, type, row) {
                        return `${row.hoa_type_name ? row.hoa_type_name : ''}  ${row.vos_name ? ' / ' + row.vos_name : ''}`
                    }
                },
                {
                    render: function(data, type, row, meta) {
                        // return `<textarea class="form-control" name="reason_of_tag" placeholder="Leave a comment here" id="floatingTextarea"
                        //                         style="height:120px ; width: 100%;font-size:10px !important;" required=""></textarea>`
                        const index = meta.row
                        return `
                            <select class="form-select" name="reason${index}" id="reason${index}" style="font-size:10px">
                                <option style="font-size:10px" value="" selected>-----</option>
                                @foreach ($redtag_list as $redtag_item)
                                    <option style="font-size:10px" value="{{ $redtag_item->reason }}">{{ $redtag_item->reason }}</option>
                                @endforeach
                                <option style="font-size:10px" value="Others">Others</option>
                            </select>
                            <input hidden type="text" name="other_reason${index}" class="form-control" id="other_reason${index}" placeholder="Add Reason here" style="font-size:10px" />

                        `
                    }
                },
                {
                    render: function(data, type, row, meta) {
                        const index = meta.row; // Get the row index
                        return `
                            <div class="d-flex justify-content-center">
                                <input type="checkbox" name="isRedtag${index}" id="isRedtag${index}" class="isRedtag" data-row='${JSON.stringify(row)}'  /> 
                            </div>
                        `;
                    },
                    orderable: false,
                    searchable: false
                }
            ],
            language: {
                emptyTable: "No registered vehicle..."
            },
            rowCallback: function(row, data) {
                // Check if the row should be shown based on red_tag value
                if (data.red_tag != 0 && data.red_tag != null) {
                    $(row).hide(); // Hide rows where red_tag is not 0 or null
                }
            },
            drawCallback: function() {
                // Check if table is empty and show/hide header accordingly
                if (table.rows().count() === 0) {
                    $('#vehicle_table thead').hide();
                    $('#vehicle_table tbody').find('tr:first td:first').addClass('border-0');
                } else {
                    $('#vehicle_table thead').show();
                    $('#vehicle_table tbody').find('tr:first td:first').removeClass('border-0');
                }
            }

        });
    }
    loadVehicleForRedTag();

    $(document).on("change", "#reason", function() {
        if (this.value === "Others") {

            // Hide the dropdown
            $(this).hide();

            // Enable and show the input field
            $('#other_reason').show().removeAttr('hidden');
            $('#other_reason').attr('required', true);
        } else {
            // Optionally hide the input and show the dropdown again if another option is selected
            $('#other_reason').hide().attr('hidden', true);
            $('#other_reason').attr('required', false);
            $(this).show();
        }
    });
    $(document).on("blur", "#other_reason", function() {
        if (this.value == "") {
            console.log(this.value, 'this.value');

            // Hide the dropdown
            $(this).hide();

            // Enable and show the input field
            $('#reason').val('')
            $('#reason').show().removeAttr('hidden');
            $('#reason').show().attr('required', true);
        } else {
            // Optionally hide the input and show the dropdown again if another option is selected
            $('#reason').hide().attr('hidden', true);
            $('#reason').show().attr('required', false);
            $(this).show();
        }
    });

    // Event handler for the select dropdown
    $(document).on("change", "#redtag_vehicle_table [id^='reason']", function() {
        const index = this.id.replace('reason', ''); // Extract the index from the ID
        const otherReasonInput = $(`#other_reason${index}`); // Select the corresponding input field

        if (this.value === "Others") {
            // Hide the dropdown
            $(this).hide();
            // Show and enable the input field
            otherReasonInput.show().removeAttr('hidden').prop('disabled', false);
            otherReasonInput.attr('required', true);
        } else {
            // Hide the input and show the dropdown again if another option is selected
            otherReasonInput.hide().attr('hidden', true).prop('disabled', true);
            otherReasonInput.attr('required', false);
            $(this).show();
        }
    });

    // Event handler for the other reason input field
    $(document).on("blur", "#redtag_vehicle_table [id^='other_reason']", function() {
        const index = this.id.replace('other_reason', ''); // Extract the index from the ID
        const reasonSelect = $(`#reason${index}`); // Select the corresponding dropdown

        if (this.value === "") {
            console.log(this.value, 'this.value');

            // Hide the input field
            $(this).hide().attr('hidden', true).prop('disabled', true);
            $(this).attr('required', false);
            // Reset the dropdown and show it
            reasonSelect.val('').show().removeAttr('hidden').prop('disabled', false);
            reasonSelect.attr('required', true);
        } else {
            // Optionally keep the input field visible
            // Or, if you want to handle specific logic, you can add it here
        }
    });


    $(document).on('change',"#redtag_vehicle_table [id^='isRedtag']",function(){
        // Create an array to hold the checked data
        
        // Loop through all checked checkboxes with class 'isRedtag'
        $(".isRedtag:checked").each(function() {
            const checkedData = [];
            const rowData = $(this).data('row'); // Assuming row data is stored in a data attribute
            const index = this.id.replace('isRedtag', ''); // Extract index from I

            // Retrieve the row data associated with the checkbox
            let redtagReason = $(`#reason${index}`).val();
            let redtagOtherReason = $(`#other_reason${index}`).val();
            let holdDetail = {
                'vehicle': rowData.vehicle_id,
                'reason' : redtagReason,
                'other_reason' : redtagOtherReason
            }
            // Add row data and index to the array
            checkedData.push(holdDetail);

        });
        $('#redtag_vehicles').val(JSON.stringify(checkedData));
        console.log($('#redtag_vehicles').val(),'add row')
    })

    // // Handle the unchecked event
    // $(document).on('change', "#redtag_vehicle_table [id^='isRedtag']", function() {
    //     const index = this.id.replace('isRedtag', ''); // Extract index from ID

    //     // If the checkbox is unchecked
    //     if (!$(this).is(':checked')) {
    //         // Remove the reason and other_reason inputs' required attribute
    //         // $(`#reason${index}`).removeAttr('required');
    //         // $(`#other_reason${index}`).removeAttr('required');

    //         // Update the hidden input to remove the unchecked item from checkedData
    //         let currentData = JSON.parse($('#redtag_vehicles').val());

    //         // Filter out the unchecked checkbox's data
    //         currentData = currentData.filter(item => item.vehicle !== $(this).data('row').vehicle_id);

    //         // Update the hidden input with the new checkedData
    //         $('#redtag_vehicles').val(JSON.stringify(currentData));
    //     console.log($('#redtag_vehicles').val(),'delete row')
    // }
    // });
</script>
<style>
    #redtag_vehicle_table th,
    #redtag_vehicle_table td {
        font-size: 10px !important;
    }

    #redtag_vehicle_table td:nth-child(5) ul {
        padding-left: 1rem;
    }

    #redtag_vehicle_table th:nth-child(10),
    #redtag_vehicle_table td:nth-child(10) {
        min-width: 150px;
        max-width: 150px;
    }

    #addVehicleRedTagBtn {
        width: 80px;
        height: 25px;
    }

    #redtag_vehicle_table>td:nth-last-child {
        align-content: center !important;
    }
</style>
