@extends('layouts.main-app')

@section('title', 'Sticker Application Request')

@section('content')
<div class="container px-md-5">
    <div class=" px-md-5 mb-3">
        <div id="request_load" style="display: none;">
            <div class="alert alert-success" role="alert">
                <div class="col-12 text-center">
                    <strong id="request_load_msg">Approving Request</strong>
                </div>
                <div class="col-12 text-center">
                    <strong>Sending Email Notification</strong>
                </div>
                <div class="col-12 text-center mt-3">
                    <img src="{{ asset('css/loading.gif') }}" height="25" width="25">
                </div>
            </div>
        </div>
        <div id="request_msg">
            <div class="alert alert-success" role="alert">
                <div class="col-12 text-center">
                    @if ($srsRequest->status == 2)
                        <strong>Approved by Admin</strong>
                    @elseif ($srsRequest->status == 1)
                        <strong>Approved by Enclave President</strong>
                    @else
                        <strong>Pending</strong>
                    @endif
                </div>
            </div>
        </div>
        <div class="card mt-3 shadow mb-5 bg-body rounded p-md-3">
            <div class="row">
                <div class="col-md-4 col-6">
                    <p style="font-weight: bold;">First Name</p>    
                    <p>{{ $srsRequest->first_name }}</p>
                </div>
                <div class="col-md-4 col-6">
                    <p style="font-weight: bold;">Last Name</p>    
                    <p>{{ $srsRequest->last_name }}</p>
                </div>
                <div class="col-md-4 col-6">
                    <p style="font-weight: bold;">Middle Initial</p>    
                    <p>{{ $srsRequest->middle_initial }}</p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4 col-6">
                    <p style="font-weight: bold;">House No.</p>    
                    <p>{{ $srsRequest->house_no }}</p>
                </div>
                <div class="col-md-4 col-6">
                    <p style="font-weight: bold;">Street</p>    
                    <p>{{ $srsRequest->street }}</p>
                </div>
                <div class="col-md-4 col-6">
                    <p style="font-weight: bold;">HOA</p>    
                    <p>{{ $srsRequest->hoa ? $srsRequest->hoa->name : 'N\A' }}</p>
                </div>
            </div>

            <div>
                @foreach ($srsRequest->files as $file)
                    <div class="mt-4">
                        <p style="font-weight: bold;">{{ $file->requirement->description }}</p>
                        <div class="row">
                            <img class="img-responsive" src="/srs/uploads/{{ $file->name.'/'.$srsRequest->created_at->format('Y-m-d').'/'.$srsRequest->first_name.'_'.$srsRequest->last_name.'/'.($srsRequest->hoa_id ?: '0').'/'.$srsRequest->category_id}}" style="max-height: 90vh;">
                        </div>
                    </div>
                @endforeach
            </div>

            <div>
                @foreach ($srsRequest->vehicles as $vehicle)
                    <div class="mt-3">
                        <div class="px-md-0 mb-4 mt-5">
                            <strong><h5 style="font-weight: bold;">Vehicle #{{ $loop->index + 1 }}</h5></strong>
                        </div>
                        <div class="px-md-3">
                            <div class="row">
                                <div class="col-md-6 col-6">
                                    <div class="row g-md-4 g-0">
                                        <div class="col-md-4" style="font-weight: bold;">
                                            Plate No.
                                        </div>
                                        <div class="col-md-8">
                                            {{ $vehicle->plate_no }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-6">
                                    <div class="row">
                                        <div class="col-md-4" style="font-weight: bold;">
                                            Type
                                        </div>
                                        <div class="col-md-8">
                                            {{ $vehicle->type }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6 col-6">
                                    <div class="row">
                                        <div class="col-md-4" style="font-weight: bold;">
                                            Brand
                                        </div>
                                        <div class="col-md-8">
                                            {{ $vehicle->brand }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-6">
                                    <div class="row">
                                        <div class="col-md-4" style="font-weight: bold;">
                                            Color
                                        </div>
                                        <div class="col-md-8">
                                            {{ $vehicle->color }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6 col-6">
                                    <div class="row">
                                        <div class="col-md-4" style="font-weight: bold;">
                                            Series
                                        </div>
                                        <div class="col-md-8">
                                            {{ $vehicle->series }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-6">
                                    <div class="row">
                                        <div class="col-md-4" style="font-weight: bold;">
                                            Sticker No.
                                        </div>
                                        <div class="col-md-8">
                                            {{ $vehicle->sticker_no ? $vehicle->sticker_no : 'N\A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6 col-6">
                                    <div class="row">
                                        <div class="col-md-4" style="font-weight: bold;">
                                            Year/Model
                                        </div>
                                        <div class="col-md-8">
                                            {{ $vehicle->year_model }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                @if ($vehicle->req1)
                                    <p style="font-weight: bold;">OR</p>
                                    <div class="row">
                                        @if (explode('.', $vehicle->req1)[1] == 'pdf')
                                            <embed class="img-responsive" src="/srs/uploads/{{ $vehicle->req1.'/'.$vehicle->created_at->format('Y-m-d').'/'.$srsRequest->first_name.'_'.$srsRequest->last_name.'/'.($srsRequest->hoa_id ?: '0').'/'.$srsRequest->category_id}}" width="600" height="500">
                                        @else
                                            <img class="img-responsive" src="/srs/uploads/{{ $vehicle->req1.'/'.$vehicle->created_at->format('Y-m-d').'/'.$srsRequest->first_name.'_'.$srsRequest->last_name.'/'.($srsRequest->hoa_id ?: '0').'/'.$srsRequest->category_id}}" style="max-height: 90vh;">
                                        @endif
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-4" style="font-weight: bold;">
                                                    OR
                                                </div>
                                                <div class="col-8">
                                                    No OR Submitted
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-3">
                                @if ($vehicle->cr)
                                    <p style="font-weight: bold;">CR</p>
                                    <div class="row">
                                        @if (explode('.', $vehicle->cr)[1] == 'pdf')
                                            <embed class="img-responsive" src="/srs/uploads/{{ $vehicle->cr.'/'.$vehicle->created_at->format('Y-m-d').'/'.$srsRequest->first_name.'_'.$srsRequest->last_name.'/'.($srsRequest->hoa_id ?: '0').'/'.$srsRequest->category_id}}" width="600" height="500">
                                        @else
                                            <img class="img-responsive mb-3" src="/srs/uploads/{{ $vehicle->cr.'/'.$vehicle->created_at->format('Y-m-d').'/'.$srsRequest->first_name.'_'.$srsRequest->last_name.'/'.($srsRequest->hoa_id ?: '0').'/'.$srsRequest->category_id}}" style="max-height: 90vh;">
                                        @endif
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-4" style="font-weight: bold;">
                                                    CR
                                                </div>
                                                <div class="col-8">
                                                    No CR Submitted
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- <img src="/srs/uploads/{{ $q->item3.'/'.$q->created_at->format('Y-m-d').'/EN001' }}" alt=""> --}}
            <div id="request_action" class="mt-3 row text-center">
                <div class="col-md-12">
                    @if ($srsRequest->status == 2)
                         <strong>Approved</strong>
                    @else
                        <a href="#" id="approve_btn" class="btn btn-primary" data-value="{{ $srsRequest->request_id }}">Approve</a>
                        <a href="#" id="reject_btn" class="btn btn-danger">Reject</a>
                    @endif
                </div>
            </div>
                
        </div>
    </div>
</div>

<div class="modal fade" id="rejectRequestModal" tabindex="-1" aria-labelledby="rejectRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-center w-100" id="rejectRequestModalLabel">Reject Request?</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="my-3">
                    <p>Do you really want to reject this sticker application request?</p>
                </div>
                <div class="px-1 mt-4">
                    <textarea class="form-control" name="reject_reason" id="reject_reason" rows="5" placeholder="Please indicate reason of rejection" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <form id="rejectRequestModalForm" action="" method="POST">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('links_js')
<script>
$(document).ready(function () {
    $('#approve_btn').on('click', function (e) {
        e.preventDefault();
        window.scrollTo(0, 0);
        $('#request_load').show();
        $.ajax({
            url: '{{ route("requests.approve") }}',
            type: 'POST',
            data: {
                req_id: $(this).data('value'),
                _token: '{{ csrf_token() }}'
            },
            success: function (data) {
                var html = '';
                if (data.status == 1) {
                    html += `<div class="alert alert-success" role="alert">
                                <div class="col-12 text-center">
                                    <strong>${data.msg}</strong>
                                </div>
                            </div>`;
                }
                $('#request_load').hide();
                $('#request_msg').html(html);
                $('#request_action').html(`<div class="col-md-12">
                                            <strong>Approved</strong>
                                        </div>`);
            }
        });
    });

    $('#reject_btn').on('click', function (e) {
        e.preventDefault();
        $('#rejectRequestModal').modal('show');
    });

    $('#rejectRequestModalForm').on('submit', function (e) {
        e.preventDefault();
        var reason = $('#reject_reason').val();
        
        if (!reason || reason == '') {
            alert('Please enter reason of rejection');
            return;
        }
        
        $('#request_load #request_load_msg').text('Rejecting Request');
        $('#rejectRequestModal').modal('hide');
        window.scrollTo(0, 0);
        $('#request_load').show();
        $.ajax({
            url: '{{ route("request.destroy", [$srsRequest->request_id]) }}',
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}',
                reason: reason,
            },
            success: function (data) {
                // $('#rejectRequestModal').modal('hide');
                $('#request_load').hide();
                var html = '';
                if (data.status == 1) {
                    html += `<div class="alert alert-success" role="alert">
                                <div class="col-12 text-center">
                                    <strong>${data.msg}</strong>
                                </div>
                            </div>`;
                    $('#request_msg').html(html);
                    $('#request_action').html(`<div class="col-md-12">
                                            <strong>Request Rejected</strong>
                                        </div>`);
                }
            }
        });
    });

});
</script>
@endsection
