@extends('layouts.main-app')

@section('title', 'SRS HOA - BFFHAI')

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css"> -->
{{-- Datatables Responsive and Datatables CSS Styles --}}
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.0/css/responsive.dataTables.min.css" />

{{-- Datatables Button CSS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.min.css" />

<style>
    /* Adjust spacing between thead and DataTable filter/search */
    #hoas_table {
        padding-top: 20px;
        /* Adjust this value as needed */
    }

    .dt-buttons {
        margin-top: 1rem !important;
        margin-bottom: 1rem !important;
    }

    .dt-search {
        margin-top: -3rem !important;
        margin-bottom: 1rem !important;
    }
</style>

@section('content')

<div class="container p-2">

    @if ($errors->any())
        <div class="alert close-alert alert-danger alert-dismissible fade show text-center mt-3" role="alert">
            @foreach ($errors->all() as $message)
                <strong>{{ $message }}</strong>
                <br>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (Session::has('hoaEditSuccess') || Session::has('hoaAddSuccess'))
        <div class="alert close-alert alert-success alert-dismissible fade show text-center mt-3" role="alert">
            <div class="col-12 text-center">
                <strong>{{ Session::get('hoaEditSuccess') }}</strong>
                <strong>{{ Session::get('hoaAddSuccess') }}</strong>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mt-2" style="border-radius: 15px;">
        <div class="card-body p-5">
            <div class="row">
                <div class="col-6">
                    <div class="text-muted h2 fw-bold">
                        SRS HOA
                    </div>
                </div>
                <div class="col-6 text-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addHoaModal">
                        Add HOA
                    </button>
                </div>
            </div>
            <hr>
            <div class="table-responsive mt-5">
                <table class="table table-hover" id="hoas_table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Emails</th>
                            <th>Type</th>
                            <th>Date Created</th>
                            <th>Created By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @foreach ($hoas as $hoa)
                            <tr>
                                <td>{{ $hoa->name }}</td>
                                <td>
                                    {{ $hoa->emailAdd1 }}
                                    @if ($hoa->emailAdd2)
                                        <br>
                                        {{ $hoa->emailAdd2 }}
                                    @endif
                                    @if ($hoa->emailAdd3)
                                        <br>
                                        {{ $hoa->emailAdd3 }}
                                    @endif
                                </td>
                                <td>
                                    @switch($hoa->type)
                                        @case(0)
                                            Regular
                                            @break
                                        @case(1)
                                            Non-member
                                            @break
                                        @case(2)
                                            No Hoa List
                                            @break
                                        @case(3)
                                            Special
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $hoa->created_at->format('M d, Y h:i A') }}</td>
                                <td>{{ $hoa->creator ? $hoa->creator->name : '' }}</td>
                                <td>
                                    <a data-value="{{ $hoa->id }}" class="edit_hoa" href="#" style="cursor: pointer;">
                                        <i class="far fa-edit" style="color:#B2BEB5; font-size:20px"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editHoaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit SRS HOA</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editHoaModalForm" action="" method="POST">
                    @method('PATCH')
                    @csrf
                    <div class="row p-2">
                        <div class="col-6">
                            <label for="">Name</label>
                            <input type="text" class="form-control" id="hoa_name" name="hoa_name">
                        </div>
                        <div class="col-6">
                            <label for="">Type</label>
                            <select name="hoa_type" class="form-select" id="hoa_type">
                                <option value="0">Regular</option>
                                <option value="1">Non-member</option>
                                <option value="3">Special</option>
                            </select>
                        </div>
                    </div>
                    <div class="row p-2">
                        <div class="col-6">
                            <label for="">Email 1</label>
                            <input type="text" class="form-control" id="hoa_email1" name="hoa_email1">
                        </div>
                        <div class="col-6">
                            <label for="">Email 2</label>
                            <input type="text" class="form-control" id=hoa_email2 name="hoa_email2">
                        </div>
                    </div>
                    <div class="row p-2">
                        <div class="col-6">
                            <label for="">Email 3</label>
                            <input type="text" class="form-control" id="hoa_email3" name="hoa_email3">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="editHoaModalForm" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addHoaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add SRS HOA</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addHoaModalForm" action="{{ route('hoas.store') }}" method="POST">
                    @csrf
                    <div class="row p-2">
                        <div class="col-6">
                            <label for="">Name</label>
                            <input type="text" class="form-control" name="hoa_name">
                        </div>
                        <div class="col-6">
                            <label for="">Type</label>
                            <select name="hoa_type" class="form-select">
                                <option value="0">Regular</option>
                                <option value="1">Non-member</option>
                                <option value="3">Special</option>
                            </select>
                        </div>
                    </div>
                    <div class="row p-2">
                        <div class="col-6">
                            <label for="">Email 1</label>
                            <input type="text" class="form-control" name="hoa_email1">
                        </div>
                        <div class="col-6">
                            <label for="">Email 2</label>
                            <input type="text" class="form-control" name="hoa_email2">
                        </div>
                    </div>
                    <div class="row p-2">
                        <div class="col-6">
                            <label for="">Email 3</label>
                            <input type="text" class="form-control" name="hoa_email3">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="addHoaModalForm" class="btn btn-primary">Create HOA</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('links_js')
<!-- <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script> -->
{{-- Datatables Responsive JS and Datatables JS --}}
<script src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.0/js/dataTables.responsive.min.js"></script>

<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"
    integrity="sha512-XMVd28F1oH/O71fzwBnV7HucLxVwtxf26XV8P4wPk26EDxuGZ91N8bsOttmnomcCD3CS5ZMRL50H0GgOHvegtg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.10/pdfmake.min.js"
    integrity="sha512-w61kvDEdEhJPJLSAJpuL+RWp1+zTBUUpgPaP+6pcqCk78wQkOaExjnGWrVbovojeisWGQS7XZKz+gr3L+GPYLg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
$(document).ready(function () {
    $('#hoas_table').DataTable({
        pageLength: 15,
        lengthMenu: [
            [15, 30, 50, 100],
            ['15', '30', '50', '100']
        ],
        buttons: [{
                extend: 'copy',
                text: 'Copy'
            },
            {
                extend: 'excel',
                text: 'Excel'
            },
            {
                extend: 'print',
                text: 'Print'
            }
        ],
        columnDefs: [
            {
                orderable: false,
                searchable: false,
                target: 5
            }
        ],
        dom: '<"top"lBf>rt<"bottom"ip><"clear">' // Define the layout explicitly
    });

    getHoa = (hoa) => {
        $.ajax({
            url: '/srs/i/hoa/'+hoa,
            success: function (data) {
                $('#editHoaModalForm').attr('action', '/hoa/'+data.hoa.hoa);
                $('#editHoaModalForm').find('#hoa_name').val(data.hoa.name);
                $('#editHoaModalForm').find('#hoa_type').val(data.hoa.type);

                if (data.hoa.email1) {
                    $('#editHoaModalForm').find('#hoa_email1').val(data.hoa.email1);
                }

                if (data.hoa.email2) {
                    $('#editHoaModalForm').find('#hoa_email2').val(data.hoa.email2);
                }

                if (data.hoa.email3) {
                    $('#editHoaModalForm').find('#hoa_email3').val(data.hoa.email3);
                }

                $('#editHoaModal').modal('show');
            }
        });
    }

    $(document).on('click', '.edit_hoa', function (e) {
        e.preventDefault();
        let hoa = $(this).data('value');
        getHoa(hoa);
    })

    $('#editHoaModal').on('hide.bs.modal', function () {
        $('#editHoaModalForm').attr('action', '');
        $('#editHoaModalForm').find('#hoa_name').val('');
        $('#editHoaModalForm').find('#hoa_type').val('');
        $('#editHoaModalForm').find('#hoa_email1').val('');
        $('#editHoaModalForm').find('#hoa_email2').val('');
        $('#editHoaModalForm').find('#hoa_email3').val('');
    });
});
</script>
@endsection