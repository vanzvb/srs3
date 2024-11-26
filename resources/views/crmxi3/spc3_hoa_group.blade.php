@extends('layouts.main-app')

@section('title', 'HOA GROUPS - BFFHAI')

@section('links_css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/crmxi-modal-style.css') }}">

    <!-- JavaScript -->
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"></script>
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="{{ asset('css/spc3-style.css') }}">
    <!--  -->

    <!-- Scripts -->

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap');

        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
@endsection

@section('content')

    <div class="container mt-4 mb-3">

        <div class="card shadow shadow-1" style="border-radius: 15px;">
            <div class="card-body p-5">
                <div class="h3 text-primary fw-bold"> SPC V3 HOA GROUP MASTER</div>
                <hr>

                <div class=" d-flex justify-content-end">
                    <button type="button" class="btn btn-success mt-3 btn-sm" data-bs-toggle="modal"
                        data-bs-target="#exampleModal" data-title="ADD HOA GROUP" onclick="">
                        <i class="fas fa-plus-circle"></i> Add Prices
                    </button>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered " id="hoa_group_table">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="col text-nowrap" scope="col"
                                    style="font-size:12pxmin-width: 20px;max-width: 20px;padding-right: 0;">#</th>
                                <th class="col text-center text-nowrap" scope="col"
                                    style="font-size:12px;min-width: 80px;max-width: 80px;">Name</th>
                                <th class="col text-center" scope="col"
                                    style="font-size:12px;min-width: 200px;max-width: 200px;">HOA</th>
                                <th class="col text-center text-nowrap" scope="col"
                                    style="font-size:12px;min-width: 30px;max-width: 30px;padding-right: 0">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hoa_groups as $i => $hoa_group)
                                <tr>
                                    <td class="col text-center text-nowrap" scope="col"
                                        style="font-size:12px;min-width: 20px;max-width: 20px;">
                                        {{ $i + 1 }}
                                    </td>
                                    <td class="col text-center text-nowrap" scope="col"
                                        style="font-size:12px;min-width: 80px;max-width: 80px;">
                                        {{ $hoa_group['name'] }}
                                    </td>
                                    <td class="col text-center" scope="col"
                                        style="font-size:12px;min-width: 200px;max-width: 200px;">
                                        {{ implode(', ', array_column($hoa_group['hoa'], 'hoa_name')) }}
                                    </td>
                                    <td style="min-width: 30px;max-width: 30px;text-align:center;">
                                        <button class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                            data-title="EDIT HOA GROUP"
                                            onclick="editHoaGroup({{ json_encode($hoa_group) }})">
                                            <i class="fa-solid fa-edit" style="color:#000000; font-size:20px"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ADD MODAL For Patch 11/11/24 -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    {{-- <h1 class="modal-title fs-5" id="exampleModalLabel"><i class="far fa-square-plus"></i> Add Prices</h1> --}}
                    <h1 class="modal-title fs-5" id="exampleModalLabel"> HOA GROUP</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modalBody">
                    <form action="/hoa-group-insert" method="POST" id="hoa_group_form">
                        @csrf
                        <input type="hidden" name="assigned_hoa_list[]" id="assigned_hoa_hidden">
                        <div class="card" style="border-radius: 10px;">
                            <div class="card-header bg-primary text-white">HOA GROUP DETAIL</div>
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show">
                                        <i class="fa fa-info-circle"></i> <strong>Validation Error</strong>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif
                                <input type="hidden" name="current_hoa_group_id" id="current_hoa_group_id">
                                {{-- <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label mb-2">Group Name : </label>
                                        <input type="text" id="group_name" name="group_name" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">HOA: </label>
                                        <select name="hoa[]" id="hoa"
                                            class="form-select  mb-3 hoa-group-add"
                                            aria-label=".form-select form-select-sm-lg example" multiple>
                                            <option value="">---</option>
                                            @foreach ($hoas as $hoa)
                                                <option value="{{ $hoa->id }}">{{ $hoa->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="row">
                                    <div class="col-md-8">
                                        <label class="form-label mb-2">Group Name : </label>
                                        <input type="text" id="group_name" name="group_name" class="form-control-sm"
                                            required>
                                    </div>
                                    <div class="col-md-12 container mt-2">
                                        {{-- <h3 class="text-center">Assign hoaList</h3> --}}
                                        <p class="text-center" style="color:rgb(160, 150, 150)">Select HOA from "HOA List"
                                            Column then click `>>` to add them to the assigned HOA list.</p>
                                        <div class="col-md-3 mt-2 mb-2  pe-0 ">
                                            <label>Member Type</label>
                                            <select id="hoatype_filter" class="form-select form-select-sm"
                                                aria-label="Default select example" onchange="filterHoa($(this).val())">
                                                <option value="all" selected>All</option>
                                                @foreach ($hoatypes as $hoatype)
                                                    @if ($hoatype->id == 0 || $hoatype->id == 1 || $hoatype->id == 3 || $hoatype->id == 7)
                                                        <option value="{{ $hoatype->id }}">{{ $hoatype->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="list-box-container"
                                            style="display: flex;justify-content: flex-start;">
                                            <!-- hoaList List -->
                                            <div style=" width: 40%;">
                                                <h6>HOA LIST:</h6>
                                                <select multiple style="height: 35vh;" class="form-control list-box"
                                                    id="hoaList">
                                                    @foreach ($hoas as $hoa)
                                                        <option value="{{ $hoa->id }}">{{ $hoa->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Move Buttons -->
                                            <div class="buttons"
                                                style=" width: 20%;align-content:center;text-align: center;">
                                                <button class="btn btn-primary" id="add" type="button">
                                                    >>
                                                </button>
                                                <button class="btn btn-primary" id="remove" type="button">
                                                    << </button>
                                            </div>

                                            <!-- Assigned hoaList List -->
                                            <div style=" width: 40%;">
                                                <h6>ASSIGNED HOA LIST:</h6>
                                                <select multiple style="height: 35vh;" class="form-control list-box"
                                                    name="assigned_hoa_list[]" id="assignedHoa">

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save </button>
                </div>
                </form>
            </div>
        </div>
    </div>



@endsection

@section('links_js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

    <script>
        // $(document).ready(function() {
        //     $('.hoa-add').select2({
        //         theme: "bootstrap-5",
        //         width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
        //             'style',
        //         placeholder: $(this).data('placeholder'),
        //         closeOnSelect: false,
        //     });
        // });

        $('#exampleModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var title = button.data('title'); // Extract info from data-* attributes
            var modal = $(this);
            modal.find('.modal-title').text(title);

            // $(document).ready(function() {
            //     $('.hoa-group-add').select2({
            //         theme: "bootstrap-5",
            //         width: $(this).data('width') ? $(this).data('width') : $(this).hasClass(
            //             'w-100') ? '100%' : 'style',
            //         placeholder: $(this).data('placeholder'),
            //         closeOnSelect: false,
            //     });
            // });
        });
        $(document).ready(function() {
            var table = $('#hoa_group_table').DataTable();
            // Move selected items from 'hoaList' to 'assignedHoa'
            $('#add').click(function() {
                $('#hoaList option:selected').each(function() {
                    var optionValue = $(this)
                        .val(); // Get the value of the selected option
                    var isDuplicate = false;

                    // Check if the option already exists in 'assignedHoa'
                    $('#assignedHoa option').each(function() {
                        if ($(this).val() == optionValue) {
                            isDuplicate = true;
                            return false; // Exit the loop once a duplicate is found
                        }
                    });

                    // Append the option only if it's not a duplicate
                    if (!isDuplicate) {
                        $(this).appendTo('#assignedHoa');
                    }
                    updateAssignedHoaList();
                });
            });

            // Move selected items from 'assignedHoa' back to 'hoaList'
            $('#remove').click(function() {
                $('#assignedHoa option:selected').appendTo('#hoaList');
                updateAssignedHoaList();
            });
        });

        function updateAssignedHoaList() {
            // Clear all existing hidden inputs for the assigned HOA list
            $('input[name="assigned_hoa_list[]"]').remove();

            // Add new hidden inputs for each selected HOA
            $('#assignedHoa option').each(function() {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'assigned_hoa_list[]',
                    value: $(this).val()
                }).appendTo('#hoa_group_form');
            });
        }

        var hoas = {!! json_encode($hoas) !!};

        function filterHoa(id) {
            $(`#hoaList option`).hide()
            if (id == 'all') {
                $(`#hoaList option`).show()
                return
            }
            hoas.map(rec => {
                if (rec.type == id) {
                    $(`#hoaList option[value="${rec.id}"]`).show()
                } else {
                    $(`#hoaList option[value="${rec.id}"]`).hide()
                }
            })
        }

        function editHoaGroup(group) {
            // console.log(group,'group')
            // let groupDetails = {
            //     '#group_name': group.name,
            //     '#current_hoa_group_id': group.group_id,
            //     '#assigned_hoa_list[]' : group.hoa.map(rec=>{return rec.hoa_id})
            // }
            $('#group_name').val(group.name)
            $('#current_hoa_group_id').val(group.group_id)
            group.hoa.map(rec => {
                // var e = `<option value="${rec.hoa_id}">${rec.hoa_name}</option>`
                // $('#assignedHoa').append()
                $('#hoaList option').each(function() {
                    if ($(this).val() == rec.hoa_id) {
                        $(this).prop('selected', true)
                    }
                })
                $('#hoaList option:selected').each(function() {
                    var optionValue = $(this)
                        .val(); // Get the value of the selected option
                    var isDuplicate = false;

                    // Check if the option already exists in 'assignedHoa'
                    $('#assignedHoa option').each(function() {
                        if ($(this).val() == optionValue) {
                            isDuplicate = true;
                            return false; // Exit the loop once a duplicate is found
                        }
                    });

                    // Append the option only if it's not a duplicate
                    if (!isDuplicate) {
                        $(this).appendTo('#assignedHoa');
                    }
                    $(this).prop('selected', false)
                    updateAssignedHoaList();
                });

            })
            // console.log($('select[name="assigned_hoa_list[]"]').val(),'assigned_hoa_list')

            // $('#assignedHoa').
        }
    </script>

    <?php
    if (session()->has('success')) {
    ?>

    <script>
        Swal.fire({
            title: '<?php echo session()->get('success'); ?>',
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
            title: '<?php echo session()->get('error'); ?>',
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

    <style>

    </style>

@endsection
