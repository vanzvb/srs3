@extends('layouts.guest')

@section('title', 'Sticker Application Appointment')

<style>
.img-modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto !important; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9) !important; /* Black w/ opacity */
}

/* Modal Content (image) */
.modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}


/* Add Animation */
.modal-content, #caption {  
  -webkit-animation-name: zoom;
  -webkit-animation-duration: 0.6s;
  animation-name: zoom;
  animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)} 
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)} 
  to {transform:scale(1)}
}

/* The Close Button */
.closeImgModal {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1 !important;
  font-size: 40px !important;
  font-weight: bold !important;
  transition: 0.3s;
}

.closeImgModal:hover,
.closeImgModal:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .modal-content {
    width: 100%;
  }
}
</style>

@section('content')
<div class="container px-md-5">
    <div class=" px-md-5 mb-3">
        <div class="mt-3">
            <div id="request_load" style="display: none;">
                <div class="alert alert-success" role="alert">
                    <div class="col-12 text-center">
                        <strong id="request_load_msg">Approving Request</strong>
                    </div>
                    <div class="col-12 text-center mt-3">
                        <img src="{{ asset('css/loading.gif') }}" height="25" width="25">
                    </div>
                </div>
            </div>
            <div id="request_msg">
            </div>
        </div>
        <div class="card mt-3 shadow mb-5 bg-body rounded">
            <div class="card-header text-center bg-primary" style="color: white;">
                <img src="{{ asset('images/bflogo.png') }}" height="60" width="60" alt="">
                <h5>BFFHAI</h5>
                <h5>Sticker Application
                <h5>
                    SRS #{{ $srsRequest->request_id }}
                </h5>
                <h5>
                    HOA Approval
                </h5>
            </div>
            <div class="container justify-content-center align-items-center">
                <div class="p-md-4 mt-4 mt-md-1 mb-3">
                    <div class="row">
                        <div class="col-md-4 col-6">
                            <p style="font-weight: 600;">Category</p>    
                            <p>{{ $srsRequest->category->name }}</p>
                        </div>
                        <div class="col-md-4 col-6">
                            <p style="font-weight: 600;">Sub-Category</p>    
                            <p>{{ $srsRequest->subCategory->name }}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4 col-6">
                            <p style="font-weight: 600;">First Name</p>    
                            <p>{{ $srsRequest->first_name }}</p>
                        </div>
                        <div class="col-md-4 col-6">
                            <p style="font-weight: 600;">Last Name</p>    
                            <p>{{ $srsRequest->last_name }}</p>
                        </div>
                        <div class="col-md-4 col-6">
                            <p style="font-weight: 600;">Middle Name</p>    
                            <p>{{ $srsRequest->middle_name }}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4 col-6">
                            <p style="font-weight: 600;">House No.</p>    
                            <p>{{ $srsRequest->house_no }}</p>
                        </div>
                        <div class="col-md-4 col-6">
                            <p style="font-weight: 600;">Street</p>    
                            <p>{{ $srsRequest->street }}</p>
                        </div>
                        <div class="col-md-4 col-6">
                            <p style="font-weight: 600;">HOA</p>    
                            <p>{{ $srsRequest->hoa->name }}</p>
                        </div>
                    </div>

                    <div>
                        @foreach ($srsRequest->vehicles as $vehicle)
                            <div class="mt-3">
                                <div class="px-md-0 mb-4 mt-5">
                                    <strong><h5>Vehicle #{{ $loop->index + 1 }}</h5></strong>
                                </div>
                                <div class="px-md-3">
                                    <div class="row">
                                        <div class="col-md-6 col-6">
                                            <div class="row g-md-4 g-0">
                                                <div class="col-md-4" style="font-weight: 600;">
                                                    Request Type
                                                </div>
                                                <div class="col-md-8">
                                                    {{ $vehicle->req_type ? 'Renewal' : 'New'}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6 col-6">
                                            <div class="row g-md-4 g-0">
                                                <div class="col-md-4" style="font-weight: 600;">
                                                    Plate No.
                                                </div>
                                                <div class="col-md-8">
                                                    {{ $vehicle->plate_no }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6">
                                            <div class="row">
                                                <div class="col-md-4" style="font-weight: 600;">
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
                                                <div class="col-md-4" style="font-weight: 600;">
                                                    Brand
                                                </div>
                                                <div class="col-md-8">
                                                    {{ $vehicle->brand }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6">
                                            <div class="row">
                                                <div class="col-md-4" style="font-weight: 600;">
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
                                                <div class="col-md-4" style="font-weight: 600;">
                                                    Series
                                                </div>
                                                <div class="col-md-8">
                                                    {{ $vehicle->series }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6">
                                            <div class="row">
                                                <div class="col-md-4" style="font-weight: 600;">
                                                    Sticker No.
                                                </div>
                                                <div class="col-md-8">
                                                    {{ $vehicle->old_sticker_no }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        @if($vehicle->or_path)
                                        <div class="col-md-6 col-6">
                                            <div class="row">
                                                <div class="col-md-4" style="font-weight: 600;">
                                                    OR
                                                </div>
                                                <div class="col-md-8">
                                                    {{--  <a data-value="/sticker/requests/uploads/{{$vehicle->req1}}/{{$srsRequest->created_at->format('Y-m-d')}}/{{$srsRequest->first_name.'_'.$srsRequest->last_name}}/{{($srsRequest->hoa_id ?: '0')}}/{{$srsRequest->category_id}}" data-type="{{ (explode('.', $vehicle->req1)[1] == 'pdf' ? 'pdf' : 'img')}}" href="#" class="modal_img">OR</a> --}}
                                                    {{-- <a data-value="/sticker/requests/uploads/{{ $vehicle->or_path }}" data-type="{{ (explode('.', $vehicle->req1)[1] == 'pdf' ? 'pdf' : 'img')}}" href="#" class="modal_img">OR</a>       --}}
                                                    <a data-value="/sticker/requests/uploads/{{ $vehicle->or_path ?? '' }}" 
                                                        data-type="{{ isset($vehicle->req1) && (explode('.', $vehicle->req1)[1] == 'pdf' ? 'pdf' : 'img') }}" 
                                                        href="#" class="modal_img">OR</a>                               
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($vehicle->cr_path)
                                        <div class="col-md-6 col-6">
                                            <div class="row">
                                                <div class="col-md-4" style="font-weight: 600;">
                                                    CR
                                                </div>
                                                <div class="col-md-8">
                                                    <a data-value="{{ $vehicle->cr_from_crm ? '/crm_model/cr/' . $vehicle->cr : '/sticker/requests/uploads/' . $vehicle->cr_path }}" 
                                                       data-type="{{ $vehicle->cr ? (explode('.', $vehicle->cr)[1] == 'pdf' ? 'pdf' : 'img') : 'img' }}" 
                                                       href="#" class="modal_img">CR</a>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="row px-md-3 mt-3">
                            <div class="col-md-6 col-6">
                                <div class="row" style="font-weight: 600;">
                                    Mandatory Requirements
                                </div>
                                <div class="col-md-8 px-md-3">
                                    @foreach ($srsRequest->files as $file)
                                        <div class="row">
                                            <a data-value="/sticker/requests/uploads/{{$file->name}}/{{$srsRequest->created_at->format('Y-m-d')}}/{{$srsRequest->first_name.'_'.$srsRequest->last_name}}/{{($srsRequest->hoa_id ?: '0')}}/{{$srsRequest->category_id}}" data-type="{{ (explode('.', $file->name)[1] == 'pdf' ? 'pdf' : 'img')}}" href="#" class="modal_img">{{ $file->requirement->description }}</a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="request_action" class="mt-5 row text-center">
                        <div class="mx-auto">
                            <a href="#" id="approve_btn" class="btn btn-primary mx-2" data-value="{{ $srsRequest->request_id }}">Approve</a>
                            <a href="#" id="reject_btn" class="btn btn-danger mx-2" data-value="{{ $srsRequest->request_id }}">Reject</a>
                        </div>
                    </div>
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

<div id="imgModal" class="modal img-modal">
    <span class="closeImgModal">&times;</span>
    <img class="modal-content" id="img01">
    <div class="row text-center">
        <div class="col-md-10 mx-auto">
            <embed class="img-responsive" id="embed01" width="1000" height="500" style="display: none;">
        </div>
    </div>
    <div id="caption"></div>
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
            url: '{{ route("requests.v3.hoa.approved") }}',
            type: 'POST',
            data: {
                // req_id: $(this).data('value'),
                req_id: '{{ $srsRequest->request_id }}',
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
            url: '{{ route("request.v3.destroy", [$srsRequest->request_id]) }}',
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}',
                reason: reason,
            },
            success: function (data) {
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

    $(document).on('click', '.modal_img', function (e) {
        e.preventDefault();
        if ($(this).attr('data-type') == 'pdf') {
            $('#embed01').attr('src', $(this).attr('data-value'));
            $('#img01').hide();
            $('#embed01').show();
        } else {
            $('#img01').attr('src', $(this).attr('data-value'));
        }
        $('#imgModal').show();
    });

    $('.closeImgModal').on('click', function () {
        $('#imgModal').hide();
        $('#embed01').hide();
        $('#embed01').attr('src', '');
        $('#img01').show();
    });
});
</script>
@endsection