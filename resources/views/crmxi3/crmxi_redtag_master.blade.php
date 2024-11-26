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
                <div class="h3 text-primary fw-bold"> CRMXI 3.0 RED TAG MASTER</div>
                <hr>

                <div class=" d-flex justify-content-end">
                    <button type="button" class="btn btn-success mt-3 btn-sm" data-bs-toggle="modal"
                        data-bs-target="#exampleModal" data-title="ADD RED TAG ITEM" onclick="">
                        <i class="fas fa-plus-circle"></i> Add
                    </button>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered " id="red_tag_table">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="col text-nowrap" scope="col" style="font-size:12pxmin-width: 10px;max-width: 10px;padding-right: 0;">#</th>
                                <th class="col text-center text-nowrap" scope="col" style="font-size:12px;min-width: 200px;max-width: 200px;">Description</th>
                                <th class="col text-center text-nowrap" scope="col" style="font-size:12px;min-width: 30px;max-width: 30px;padding-right: 0">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($redtag_master as $i => $redtag)
                                <tr>
                                    <td class="col text-center text-nowrap" scope="col" style="font-size:12px;min-width: 10px;max-width: 10px;">
                                        {{ $i + 1 }}
                                    </td>
                                    <td class="col text-center text-nowrap" scope="col" style="font-size:12px;min-width: 200px;max-width: 200px;">
                                        {{ $redtag->reason }}
                                    </td>
                                    <td style="min-width: 30px;max-width: 30px;text-align:center;">
                                        <button class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal" data-title="EDIT RED TAG ITEM" onclick="editRegTag({{ json_encode($redtag) }})">
                                            <i class="fa-solid fa-edit" style="color:#000000; font-size:20px"></i>
                                        </button>
                                        <button class="btn btn-sm" onclick="deletedRedTag({{ json_encode($redtag) }})">
                                            <i class="fa-solid fa-trash" style="color:#000000; font-size:20px"></i>
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

    <!-- ADD MODAL -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    {{-- <h1 class="modal-title fs-5" id="exampleModalLabel"><i class="far fa-square-plus"></i> Add Prices</h1> --}}
                    <h1 class="modal-title fs-5" id="exampleModalLabel"> RED TAG</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <form action="/insert_redtag_item" method="POST" id="hoa_group_form">
                        @csrf
                        <input type="hidden" name="current_id" id="current_id">
                        <div class="card" style="border-radius: 10px;">
                            <div class="card-header bg-primary text-white">RED TAG LIST</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label mb-2">Description : </label>
                                        <textarea class="form-control" name="description" placeholder="Leave a comment here" id="description"
                                                style="height:120px ; width: 100%;" required=""></textarea>
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

        $('#exampleModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var title = button.data('title'); // Extract info from data-* attributes
            var modal = $(this);
            modal.find('.modal-title').text(title);

        });
        $(document).ready(function() {
            var table = $('#red_tag_table').DataTable();
        });

        function editRegTag(data){
            $('#current_id').val(data.id)
            $('#description').val(data.reason)
        };

        function deletedRedTag(data){
            Swal.fire({
                title: 'Delete item',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log('Okay clicked'); 
                    var id = data.id;
                    $.ajax({
                        url: '{{ route("delete-redtag-item") }}?id=' + id, // Your update route here
                        type: 'GET', // The HTTP method for updating
                        // data: id, // The form data to send
                        success: function(response) {
                            location.reload()
                            Swal.fire({
                                title: 'Item Deleted',
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Okay'
                            })
                            // console.log('Update successful:', response);
                        },
                        // error: function(xhr) {
                        //     // Handle errors
                        //     console.error('Update failed:', xhr.responseText);
                        // }
                    });
                            
                } 
            });
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
