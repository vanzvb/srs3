@extends('layouts.main-app')

@section('title', 'CRMXi')

@section('links_css')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="{{ asset('css/crmxi.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap');

    body {
        font-family: 'Nunito', sans-serif;
    }

    .no-header-border {
        border-top: 1px solid #ccc; /* Add top border to table body */
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
@endsection

@section('content')

<div class="container-fluid mt-4">
    <div class="card myCard">
        <div class="card-body p-5">
            <div class="text-muted h1  fw-bold">
                <i class="fas fa-users"></i> CRMXi
            </div>
            <hr>
            <div class="d-flex justify-content-between">
                <div>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search..." id="searchField" name="searchField">
                        <button type="button" class="btn btn-primary" id="searchBtn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal" data-title="+ ADD ACCOUNT">
                        <i class="fas fa-plus" "></i> Add Customer
                    </button>
                    
                </div>
            </div>
            <div class="table-responsive mt-4">
                <table id="crms_table" class="table table-bordered w-100">
                    <thead>
                        <tr>
                            <th>Account ID <br> (Account Type)</th>
                            {{-- <th>Account Type</th> --}}
                            <th>Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Vehicles</th>
                            <th>Vehicle Count</th>
                            {{-- <th>Status</th> --}}
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>

                </table>
            </div>
            
        </div>
    </div>
    {{-- ADD CUSTOMER MODAL -- RG   --}}
    @include('crmxi3.add_account')
    {{-- *END* ADD CUSTOMER MODAL -- RG   --}}

</div>

@endsection

@section('links_js')
<script
  src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
  crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


<script>

  
    $('#addAccountModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget); // Button that triggered the modal
      var title = button.data('title'); // Extract info from data-* attributes
      var modal = $(this);
      modal.find('.modal-title').text(title);

    //   $('#addAccountModal').on('hidden.bs.modal', function() {
    //       $('input[name="account_type"]').off('change', toggleChangeInput);
    //   });

    });
    $('#addAccountModal').on('hidden.bs.modal', function (){
        $('#accountForm')[0].reset();
    })
    
    let table = null;
    $(document).ready(function() {
        $('#searchBtn').click(function () {
            let searchField = $('#searchField');

            // Assuming you have a text input field for the search query with id "searchField"
            let searchQuery = searchField.val();

            // Assuming your DataTable AJAX URL accepts a query parameter named "search"
            // Using Blade syntax to generate the URL dynamically
            let ajaxUrl = "{{ route('getcrmxi') }}" + "?to_search=" + searchQuery;

            // Reload the DataTable with the new AJAX URL
            table.ajax.url(ajaxUrl).load();
        });
        
    });
    function loadCrms(){
        table = $('#crms_table').DataTable({
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
                url: "{{ route('getcrmxi') }}"
            },
            "order": [[ 1, "desc" ]],
            columns: [
                // {
                //     data: 'account_id'
                // },
                // {
                //     data: 'account_type_name'
                // },
                {
                    render: function (data, type, row){
                        return `<div>
                                ${row.account_id} 
                                <br>
                                ( ${row.account_type_name} )
                                <br>
                                <br>
                                ${row.status} 
                            </div>`;
                    }
                },
                {
                    data: 'cname'
                },
                {
                    data: 'email'
                },
                // {
                //     data: 'address'
                // },
                {
                    // data: 'acc_address'
                    render : function (data, type, row) {
                        let addresses = JSON.parse(row.acc_address);
                        return addresses.map(function (address, i) {
                            if (address.street) {
                                return '<b>' + 'Address ' + (i + 1) + ':</b> ' + 
                                    (address.block ? 'Blk ' + address.block : (address.blk_lot ? address.blk_lot : '')) + 
                                    (address.lot && !address.blk_lot ? ' Lot ' + address.lot : '') + 
                                    (address.house_number ? ', ' + address.house_number + ', ' : '') + ' ' + 
                                    address.street + 
                                    (address.building_name ? ', ' + address.building_name : '') + 
                                    (address.hoa_name ? '' : (address.subdivision_village ? ', ' + address.subdivision_village : '')) + 
                                    (address.hoa_name ? ', ' + address.hoa_name : '') + 
                                    (address.city_name ? ', ' + address.city_name : '') + 
                                    (address.zipcode ? ', ' + address.zipcode : '');
                            } else {
                                return address.address;
                            }
                        }).join('<br><br>');
                    }
                },
                {
                    data: 'vehicles',
                    name: 'vehicles.plate_no',

                },
                {
                    data: 'vehicle_count'
                },
                // {
                //     data: 'status'
                // },
                {
                    data: 'creator',
                },
                {
                    render : function(data, type, row) {
                        return `<div class="d-flex justify-content-center">
                            <a href="/crmxi/crms_view_account/${row.account_id}" class="me-3">
                                <i class="fa-solid fa-eye" style="color:#000000; font-size:20px"></i>
                            </a>

                            <button type="button" style="border: none;"  data-bs-toggle="modal" data-bs-target="#addAccountModal" data-title="EDIT ACCOUNT" onclick='editAccount(${JSON.stringify(row)})'>
                                <i class="fa-solid fa-edit" style="color:#000000; font-size:20px"></i>
                            </button> 
                        </div>`;
                    },
                    orderable: false,
                    searchable: false
                }
            ],

            order: [
                [3, 'desc']
            ],
            language: {
                emptyTable: "Please enter your search parameters..."
            },
            drawCallback: function () {
                // Check if table is empty and show/hide header accordingly
                if (table.rows().count() === 0) {
                    $('#crms_table thead').hide();
                    $('#crms_table tbody').find('tr:first td:first').addClass('border-0');
                } else {
                    $('#crms_table thead').show();
                    $('#crms_table tbody').find('tr:first td:first').removeClass('border-0');
                }
            }
        });
    }
    loadCrms();

    // function categoryChange(id) {
    // // $('#category_id').change(function (){
    //     $('#sub_category').html('');
    //     $('#sub_category').append(`<option value="">--- </option>`);
    //     $('#hoa').html('');
    //     $('#hoa').append(`<option value="0">--- </option>`);
    //     $.get( "{{ url('srs3_getSubCat') }}" + `/${id}`,
    //         function(response) {
    //             console.log(response,'response')
    //             // response.forEach(function(a) {
    //             //     var e = `<option value="${a.id}">${a.name}</option>`;
    //             //     $('#sub_category').append(e);

    //             // });
                
    //             response.forEach(function(a) {
    //                 var e = `<option value="${a.id}">${a.name}</option>`;
    //                 $('#sub_category').append(e);

    //             });

    //             // response[1].forEach(function(a) {
    //             //     var e = `<option value="${a.name}">${a.name}</option>`;
    //             //     $('#hoa').append(e);
    //             // });
    //         });
    // // })
        

    // };
    // function sub_categoryChange(id) {
    // // $('#category_id').change(function (){
    //     $('#hoa').html('');
    //     $('#hoa').append(`<option value="">--- </option>`);
    //     $.get( "{{ url('srs3_getHoas') }}" + `/${id}`,
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
    //                 $('#hoa').append(e);

    //             });

    //             // response[1].forEach(function(a) {
    //             //     var e = `<option value="${a.name}">${a.name}</option>`;
    //             //     $('#hoa').append(e);
    //             // });
    //         });
    // // })
        
    // }

    function hoa_change(id) {
        var splitID = id.split('-')
        $('#vehicle_ownership_status').html('');
        $('#vehicle_ownership_status').append(`<option value="">--- </option>`);
        var subcat_hoas = $('subcat_hoas_id')
        $.get( "{{ url('srs3_getVehicleOwnershipStatus') }}" + `/${splitID[1]}`,
            function(response) {                
                response.forEach(function(a) {
                    var e = `<option value="${a.id}">${a.name}</option>`;
                    $('#vehicle_ownership_status').append(e);

                });

            });
    };
    function editAccount(data) {
        console.log(data,'acc data')
        // Define a mapping for fields to set values
        const fields = {
            '#current_account_id': data.account_id,
            '#company_name': data.firstname,
            '#representative_name': data.name,
            '#first_name': data.firstname,
            '#middle_name': data.middlename,
            '#last_name': data.lastname,
            // '#block': data.block,
            // '#lot': data.lot,
            // '#house_number': data.house_number,
            // '#street': data.street,
            // '#building_name': data.building_name,
            // '#subdivision': data.subdivision_village,
            // '#city': data.city,
            // '#zip_code': data.zipcode,
            '#tin_no': data.tin,
            '#civil_status': data.civil_status,
            '#nationality': data.nationality,
            '#email': data.email,
            '#emailComp': data.email,
            // '#category_id': data.category_id,
            // '#sub_category_id': data.sub_category_id,
            // '#hoa': data.hoa,
            '#main_contact': data.main_contact,
            '#secondary_contact': data.secondary_contact,
            '#tertiary_contact': data.tertiary_contact
        };
    
        // Set values for the fields
        $.each(fields, function(selector, value) {
            $(selector).val(value);
        });

        let address = JSON.parse(data.acc_address)

        let addressFields = {}
        address.map((rec,i)=> {
            if(i !== 0){
                addressForm()
            }
            let holdAddress = {
                [`#current_id${i+1}`]: rec.id,
                [`#category_id${i+1}`]: rec.category_id,
                [`#sub_category_id${i+1}`]: rec.sub_category_id,
                [`#hoa${i+1}`]: rec.hoa,
                [`#hoa_type${i+1}`]: rec.hoa_type,
                [`#block${i+1}`]: rec.block,
                [`#lot${i+1}`]: rec.lot,
                [`#house_number${i+1}`]: rec.house_number,
                [`#street${i+1}`]: rec.street,
                [`#building_name${i+1}`]: rec.building_name,
                [`#subdivision${i+1}`]: rec.subdivision_village,
                [`#city${i+1}`]: rec.city,
                [`#zip_code${i+1}`]: rec.zipcode
            }
            categoryChange(rec.category_id, i+1, rec.sub_category_id);
            sub_categoryChange(rec.sub_category_id, i+1, rec.hoa_type);
            cityChange(rec.city,i+1,rec.zipcode)
            addressFields = {...addressFields, ...holdAddress}

        })
        console.log(addressFields,'addressFields')
        $.each(addressFields, function(selector, value) {
            $(selector).val(value);
        });
        // Handle radio buttons
        if (data.account_type === 1) {
            $('#account_type_individual').prop('checked', false);
            $('#account_type_company').prop('checked', true);
            $('.compInputs').show();
            $('.indiInputs').hide();
            $('#representative_name').attr('required', true);
            $('#first_name').attr('required', false);
        } else {
            $('#account_type_individual').prop('checked', true);
            $('#account_type_company').prop('checked', false);
            $('.compInputs').hide();
            $('.indiInputs').show();
            $('#first_name').attr('required', true);
            $('#representative_name').attr('required', false);
        }
    
        
    }

    
</script>
<?php
if (session()->has('success')) {
?>

    <script>
        Swal.fire({
            title: '<?php echo  session()->get('success');  ?>',
            icon: 'success',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Okay'
        })
        
    </script>
<?php
}


?>

<?php
if (session()->has('error')) {
?>

    <script>
        Swal.fire({
            title: '<?php echo  session()->get('error');  ?>',
            icon: 'error',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Okay'
        })
    </script>
<?php
}


?>

@endsection