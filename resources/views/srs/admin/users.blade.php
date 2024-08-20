@extends('layouts.main-app')

@section('title', 'SRS Users')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

@section('content')
<div class="container card p-0 mb-2 mb-md-4 shadow-sm">
    <div class="card-title text-center text-white bg-primary p-2 rounded-top">
        <h3>Users</h3>
    </div>
    
    @can('create', \App\Models\SrsUser::class)
        <div class="d-flex justify-content-end p-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                Add User
            </button>
            <button class="btn btn-primary ms-1" id="reset_all">Reset All</button>
        </div>
    @endcan

    <div class="card-body">
        @if ($errors->any())
            <div class="alert close-alert alert-danger alert-dismissible fade show text-center mt-3" role="alert">
                @foreach ($errors->all() as $message)
                    <strong>{{ $message }}</strong>
                    <br>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (Session::has('userEditSuccess') || Session::has('userAddSuccess'))
            <div class="alert close-alert alert-success alert-dismissible fade show text-center mt-3" role="alert">
                <div class="col-12 text-center">
                    <strong>{{ Session::get('userEditSuccess') }}</strong>
                    <strong>{{ Session::get('userAddSuccess') }}</strong>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row mb-3">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">LOGGED IN USERS</h5>
                        <hr>
                        <h4 id="logged_in_users">{{ $logged_in_users }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label for="login_status">Status</label>
                <select class="form-select" id="login_status" aria-label="Online">
                    <option value="all">All</option>
                    <option value="online" selected>Online</option>
                    <option value="offline">Offline</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="role_filter">Role</label>
                <select class="form-select" id="role_filter" aria-label="Role">
                    <option value="0">All</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-light table-hover w-100" id="users_table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Email</th>
                        @can('viewAny', \App\Models\SrsUser::class)
                            <th>Role</th>
                        @endcan
                        <th>HOA</th>
                        <th>Login Status</th>
                        <th>Time Login</th>
                        <th>Location</th>
                        <th>IP</th>
                        <th>Action</th>
                        {{-- @canany(['updateAny', 'deleteAny'], \App\Models\SrsUser::class)
                            <th>Actions</th>
                        @endcanany --}}
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            @canany(['updateAny', 'deleteAny'], \App\Models\SrsUser::class)
                                <td>
                                    @can('updateAny', \App\Models\SrsUser::class)
                                        <button class="btn btn-warning">Edit</button>
                                    @endcan
                                    
                                    <button class="btn btn-danger">Delete</button>
                                </td>
                            @endcanany
                        </tr>
                    @endforeach --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editUserModalLabel">Edit User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserModalForm" action="" method="POST">
                    @method('PATCH')
                    @csrf
                    
                    <div class="row p-2 justify-content-center">
                        <div class="col-6">
                            <label for="">Name</label>
                            <input type="text" class="form-control" id="user_name" name="user_name">
                        </div>
                    </div>

                    <div class="row p-2 justify-content-center">
                        <div class="col-6">
                            <label for="">Email</label>
                            <input type="text" class="form-control" id="user_email" name="user_email">
                        </div>
                    </div>

                    <div class="row  p-2 justify-content-center">
                        <div class="col-6 d-flex justify-content-start">
                            <input type="hidden" id="is_online_status_value" name="is_online_status_value">
                            <div class="form-check form-switch form-check-reverse">
                                <label class="form-check-label" for="is_online_status">Is Online</label>
                                <input class="form-check-input" type="checkbox" role="switch" id="is_online_status">
                            </div>
                        </div>
                    </div>
                    
                    @can('viewAny', \App\Models\SrsUser::class)
                    <div class="row p-2 justify-content-center">
                        <div class="col-6">
                            <label for="">Role</label>
                            <select name="user_role" id="user_role" class="form-select">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endcan

                    <div class="row p-2 justify-content-center" id="edit_hoa">
                        <div class="col-6">
                            <label for="">HOA</label>
                            <select name="user_hoa[]" id="user_hoa" class="form-select" multiple>
                                @foreach($hoas as $hoa)
                                    <option value="{{ $hoa->id }}">{{ $hoa->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row p-2 justify-content-center">
                        <div class="col-6">
                            <label for="">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="password">
                        </div>
                    </div>

                    <div class="row p-2 justify-content-center">
                        <div class="col-6">
                            <label for="">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_new_password" name="password_confirmation">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="editUserModalForm" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addUserModalLabel">Add User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUserModalForm" action="/admin/register" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="role" class="col-md-4 col-form-label text-md-end">Role</label>

                        <div class="col-md-6">
                            <select class="form-select @error('role') is-invalid @enderror" name="role" id="role" value="{{ old('role') }}" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>

                            @error('role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3" id="hoa_president">
                        <label for="role" class="col-md-4 col-form-label text-md-end">HOA</label>

                        <div class="col-md-6">
                            <select class="form-select @error('hoa') is-invalid @enderror" name="hoa[]" id="hoa" value="{{ old('hoa') }}" multiple>
                                {{-- <option value="">N/A</option> --}}
                                @foreach($hoas as $hoa)
                                    <option value="{{ $hoa->id }}">{{ $hoa->name }}</option>
                                @endforeach
                            </select>

                            @error('hoa')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="addUserModalForm" class="btn btn-primary">Create User</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('links_js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
<script>
$(document).ready(function () {
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    function loadUsers() {
        var table = $('#users_table').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            pageLength: 30,
            lengthMenu: [
                [1, 30, 50, 100, -1],
                ['1', '30', '50', '100', 'All']
            ],
            ajax: {
                url: '/srs/i/users',
                type: 'post',
                data: function (d) {
                    d.login_status = $('#login_status').val();
                    d.role_filter = $('#role_filter').val();
                },
            },
            createdRow: function (row, data, dataIndex) {
                $('td', row).eq(5).addClass('text-center');
                $('td', row).eq(9).addClass('text-center');
            },
            columns: [
                {
                    render: function (data, type, full, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'name'
                },
                {
                    data: 'email'
                },
                @can('viewAny', \App\Models\SrsUser::class)
                {
                    data: 'userRole',
                    name: 'userRole.name'
                },
                @endcan
                {
                    data: 'hoa',
                    name: 'hoa'
                },
                {
                    data: 'is_logged_in',
                    name: 'is_logged_in'
                },
                {
                    data: 'login_at',
                    name: 'login_at'
                },
                {
                    data: 'location',
                    name: 'location'
                },
                {
                    data: 'ip_address',
                    name: 'ip_address'
                },
                {
                    data: 'action',
                    searchable: false,
                    sortable: false
                }
            ],
        });
    }

    // $('#edit_hoa').show();

    // $('#user_role').on('change', function () {
    //     if ($('#user_role').val() == 7) {
    //         $('#edit_hoa').show();

    //         $('#user_hoa').select2( {
    //             theme: "bootstrap-5",
    //             width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    //             placeholder: $( this ).data( 'placeholder' ),
    //             closeOnSelect: false,
    //         });
    //     } else {
    //         $('#edit_hoa').hide();
    //     }
    // });

    getUser = (user) => {
        $.ajax({
            url: '/srs/i/user/'+user,
            type: 'post',
            success: function (data) {
                $('#editUserModalForm').attr('action', '/srs/i/user/'+data.user.user);
                $('#editUserModalForm').find('#user_name').val(data.user.name);
                $('#editUserModalForm').find('#user_email').val(data.user.email);
                $('#editUserModalForm').find('#user_role').val(data.user.role);
                $('#editUserModalForm').find('#is_online_status').prop('checked', data.user.is_logged_in);

                if(data.user.is_logged_in) {
                    $('#editUserModalForm').find('#is_online_status_value').val(1);
                } else {
                    $('#editUserModalForm').find('#is_online_status_value').val(0);
                }

                // if (data.user.role == 7) {
                //     $('#edit_hoa').show();

                //     $('#user_hoa').select2( {
                //         theme: "bootstrap-5",
                //         width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
                //         placeholder: $( this ).data( 'placeholder' ),
                //         closeOnSelect: false,
                //     });

                //     $('#user_hoa').val(data.user.hoa).trigger('change');
                // } else {
                //     $('#edit_hoa').hide();
                // }

                if(data.user.hoa) {
                    $('#user_hoa').select2( {
                        theme: "bootstrap-5",
                        width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
                        placeholder: $( this ).data( 'placeholder' ),
                        closeOnSelect: false,
                    });

                    $('#user_hoa').val(data.user.hoa).trigger('change');
                }

                $('#editUserModal').modal('show');
            }
        });
    }

    loadUsers();

    $(document).on('click', '.edit_user', function (e) {
        e.preventDefault();
        let user = $(this).data('value');
        getUser(user);
    });

    $(document).on('click', '#is_online_status', function (e) {
        // set the value of the checkbox to 1 if checked and 0 if not
        if ($(this).is(':checked')) {
            $('#is_online_status_value').val(1);
        } else {
            $('#is_online_status_value').val(0);
        }
    });

    $(document).on('change', '#login_status', function () {
        loadUsers();
    });

    $(document).on('change', '#role_filter', function () {
        loadUsers();
    });
}); 
</script>

<script>
    // $('#hoa_president').hide();
    
    // $('#role').change(function () {
    //     if ($('#role').val() == 7) {
    //         $('#hoa_president').show();

    //         $('#hoa').select2( {
    //             theme: "bootstrap-5",
    //             width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    //             placeholder: $( this ).data( 'placeholder' ),
    //             closeOnSelect: false,
    //         });
    //     } else {
    //         $('#hoa_president').hide();
    //     }
    // });

    $('#hoa_president').show();

    $('#hoa').select2( {
        theme: "bootstrap-5",
        width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
        placeholder: $( this ).data( 'placeholder' ),
        closeOnSelect: false,
    });
</script>

<script>
    $(document).on('change', '.toggle-login', function () {
        var user_id = $(this).data('id');
        var is_online = $(this).prop('checked');
        var is_online_value = 0;

        if (is_online) {
            is_online_value = 1;
        }

        $.ajax({
            url: '/srs/i/user/'+user_id+'/toggle-login',
            type: 'post',
            data: {
                is_online: is_online_value,
                user_id: user_id
            },
            success: function (data) {
                Swal.fire({
                    title: 'Success!',
                    text: data.success,
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    // reload the users table
                    $('#users_table').DataTable().ajax.reload();

                    // update the logged in users count
                    $('#logged_in_users').text(data.logged_in_users);
                });
            },   
            error: function (data) {
                Swal.fire({
                    title: 'Error!',
                    text: data.responseJSON.error,
                    icon: 'error',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                })
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('#reset_all').on('click', function () {
            Swal.fire({
                title: 'Are you sure?',
                text: "All users will be logged out.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reset it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/users/reset-all',
                        type: 'post',
                        success: function (data) {
                            Swal.fire({
                                title: 'Success!',
                                text: data.success,
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                location.reload();
                            });
                        },
                        error: function (data) {
                            Swal.fire({
                                title: 'Error!',
                                text: data.responseJSON.error,
                                icon: 'error',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            })
                        }
                    });
                }
            });
        });
    });
</script>
@endsection