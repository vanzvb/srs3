@extends('layouts.main-app')

@section('links_css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.bootstrap5.min.css">

    <style>
        .img-modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1;
            /* Sit on top */
            padding-top: 100px;
            /* Location of the box */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto !important;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.9) !important;
            /* Black w/ opacity */
        }

        /* Modal Content (image) */
        .img-modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        /* Caption of Modal Image */
        /* #caption {
                    margin: auto;
                    display: block;
                    width: 80%;
                    max-width: 700px;
                    text-align: center;
                    color: #ccc;
                    padding: 10px 0;
                    height: 150px;
                  } */

        /* Add Animation */
        .img-modal-content,
        #caption {
            -webkit-animation-name: zoom;
            -webkit-animation-duration: 0.6s;
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        @-webkit-keyframes zoom {
            from {
                -webkit-transform: scale(0)
            }

            to {
                -webkit-transform: scale(1)
            }
        }

        @keyframes zoom {
            from {
                transform: scale(0)
            }

            to {
                transform: scale(1)
            }
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
        @media only screen and (max-width: 700px) {
            .modal-content {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid mt-4">
        <div class="card mt-3">
            <div class="card-body p-5">
                <div class="d-flex justify-content-between">
                    <input type="hidden" id="request_id" value="{{ $srsRequest->request_id }}">

                    <h6 class="fw-bold" style="font-size: 17px">
                        SRS NO: <span class="text-primary">{{ $srsRequest->request_id }}</span>
                    </h6>
                    <button id="back_button" class="btn btn-primary">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <h3 style="font-weight: bold;margin-top: 0px !important;">
                    SRS Approval for <span id="details_subject">{{ $srsRequest->fullname() }}</span>
                </h3>
                <div class="col-md-8 col-sm-12 col-xs-12" style="text-align: left;">
                    <p style="font-size: 14px !important;">
                        <span>Requestor: </span>
                        <span id="details_requestor">
                            {{ $srsRequest->first_name }} {{ $srsRequest->middle_name }} {{ $srsRequest->last_name }}
                        </span>
                        [<span id="details_creation_date">{{ $srsRequest->created_at->format('F d, Y h:i A') }}</span>]
                    </p>
                    <p style="font-size: 14px !important;">
                        Address:
                        <span id="details_address">
                            {{ $srsRequest->house_no . ' ' . $srsRequest->street . ($srsRequest->building_name ? ', ' . $srsRequest->building_name : '') . ($srsRequest->subdivision_village ? ', ' . $srsRequest->subdivision_village : '') . ($srsRequest->city ? ', ' . $srsRequest->city : '') }}
                        </span>
                    </p>

                    @can('access', App\Models\CrmMain::class)
                        <p style="font-size: 14px !important;">
                            Email: <span id="details_email">{{ $srsRequest->email }}</span>
                        </p>
                        <p style="font-size: 14px !important;">Contact No.:
                            <span id="details_contact_no">{{ $srsRequest->contact_no }}</span>
                        </p>
                    @endcan
                </div>
                <hr>

                <div class="row gx-3">
                    <div class="col-md-6">
                        <div class="row" style="text-align: left;">
                            <div class="col-md-3 col-sm-4 col-xs-5">
                                <label>Account Type :</label>
                            </div>
                            <div class="col-md-9 col-sm-8 col-xs-7">
                                <p>
                                    <span id="details_service">
                                        @if ($srsRequest->account_type == 1)
                                            <span>Company</span>
                                        @else
                                            <span>Individual</span>
                                        @endif
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="row mt-2" style="text-align: left;">
                            <div class="col-md-3 col-sm-4 col-xs-5">
                                <label>Status :</label>
                            </div>
                            <div class="col-md-9 col-sm-8 col-xs-7">
                                <p id="details_status" class="fw-bold <?php if ($status != 'Rejected') {
                                    echo 'text-info';
                                } else {
                                    echo 'text-danger';
                                } ?>">
                                    {{ $status }}
                                </p>
                            </div>
                        </div>

                        <div class="row" style="text-align: left;">
                            <div class="col-md-3 col-sm-4 col-xs-5">
                                <label>Service :</label>
                            </div>
                            <div class="col-md-9 col-sm-8 col-xs-7">
                                <p>
                                    <span id="details_service">{{ Str::upper($srsRequest->category3->name) }} /
                                        {{ $srsRequest->subCategory3->name ?? 'N/A' }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="row" style="text-align: left;">
                            <div class="col-md-3 col-sm-4 col-xs-5">
                                <label>HOA : </label>
                            </div>
                            <div class="col-md-9 col-sm-8 col-xs-7">
                                <p>
                                    <span id="details_hoa"> {{ $srsRequest->hoa3->name ?? 'Not Applicable' }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="row" style="text-align: left;">
                            <div class="col-md-3 col-sm-4 col-xs-5">
                                <label>Member Type : </label>
                            </div>
                            <div class="col-md-9 col-sm-8 col-xs-7">
                                <p>
                                    <span id="details_hoa">
                                        {{ $srsRequest->hoa3->hoaType->name ?? 'Not Applicable' }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                    @if (auth()->user()->role_id != 7)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="pills-routes-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-routes" type="button" role="tab"
                                                aria-controls="pills-routes" aria-selected="true">
                                                <i class="fa-solid fa-timeline"></i> Routes</button>
                                        </li>
                                    @endif
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link @if (auth()->user()->role_id == 7) active @endif"
                                            id="pills-vehicles-tab" data-bs-toggle="pill" data-bs-target="#pills-vehicles"
                                            type="button" role="tab" aria-controls="pills-vehicles"
                                            aria-selected="true">
                                            <i class="fa-solid fa-car"></i> Vehicles</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-requirements-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-requirements" type="button" role="tab"
                                            aria-controls="pills-requirements" aria-selected="false">
                                            <i class="fa-solid fa-file"></i> Mandatory Files</button>
                                    </li>
                                </ul>

                                <div id="refresh_button" style="cursor:pointer;">
                                    <i class="fa-solid fa-arrows-rotate icon"></i>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="pills-tabContent">
                                    @if (auth()->user()->role_id != 7)
                                        <div class="tab-pane fade show active table-responsive" id="pills-routes"
                                            role="tabpanel" aria-labelledby="pills-routes-tab" tabindex="0">
                                            <table class="table table-striped table-bordered dt-responsive"
                                                cellspacing="0" width="100%" id="tbl_routelog"
                                                style="overflow-y: scroll;">
                                                <thead style="">
                                                    <tr style="font-size: 13px;">
                                                        <th style="text-align: center;background: #b1b7b9;color: white;">
                                                            ROUTE</th>
                                                        {{-- <th style="text-align: center;background: #b1b7b9;color: white;" >WHO</th> --}}
                                                        <th style="text-align: center;background: #b1b7b9;color: white;">
                                                            DATE</th>
                                                        <th style="text-align: center;background: #b1b7b9;color: white;">
                                                            REMARKS/REASON</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="routelog_tbody" style="font-size: 12px;">

                                                    @foreach ($routes as $route)
                                                        {!! $route !!}
                                                    @endforeach

                                                    @if ($srsRequest->status == 61 || $srsRequest->status == 62)
                                                        <tr class="text-danger fw-bold">
                                                            <td>Rejected</td>
                                                            {{-- <td>{{ $srsRequest->rejected_by }}</td> --}}
                                                            <td>{{ $srsRequest->updated_at->format('m/d/Y h:i A') }}</td>
                                                            <td>{{ $srsRequest->reject_reason }}</td>
                                                        </tr>
                                                    @endif

                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                    <div class="tab-pane fade table-responsive @if (auth()->user()->role_id == 7) show active @endif"
                                        id="pills-vehicles" role="tabpanel" aria-labelledby="pills-vehicles-tab"
                                        tabindex="0">
                                        <table class="table table-sm table-bordered ">
                                            <thead>
                                                <tr>
                                                    <th>Approve ?</th>
                                                    <th>#</th>
                                                    <th>Request Type</th>
                                                    <th>Sticker No.</th>
                                                    <th>Type</th>
                                                    <th>Plate No.</th>
                                                    <th>Brand</th>
                                                    <th>Series</th>
                                                    <th>Color</th>
                                                    <th>OR/CR</th>
                                                    <th>Rejection Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($vehicles as $vehicle)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="selectedVehicles[]" value="{{ $vehicle['key'] }}">
                                                        </td>
                                                        <td>{{ $vehicle['key'] }}</td>
                                                        <td>{{ $vehicle['req_type'] }}</td>
                                                        <td>{{ $vehicle['old_sticker_no'] }}</td>
                                                        <td>{{ $vehicle['type'] }}</td>
                                                        <td>{{ $vehicle['plate_no'] }}</td>
                                                        <td>{{ $vehicle['brand'] }}</td>
                                                        <td>{{ $vehicle['series'] }}</td>
                                                        <td>{{ $vehicle['color'] }}</td>
                                                        <td>{!! $vehicle['or'] !!} <br> {!! $vehicle['cr'] !!}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="9" class="text-center text-danger">No data available</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="pills-requirements" role="tabpanel"
                                        aria-labelledby="pills-requirements-tab" tabindex="0">
                                        <ol class="list-group list-group-numbered list-group-flush">
                                            @forelse ($files as $file)
                                                <li class="list-group-item">
                                                    {!! $file !!}
                                                </li>
                                            @empty
                                                <li class="list-group">
                                                    No data available
                                                </li>
                                            @endforelse
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($status == 'Pending Approval' || $status == 'Approved by Admin')
                    <div class="mt-5 d-flex justify-content-center gap-3" id="request_btns">
                        {{-- <button class="btn btn-success" id="approve_btn">APPROVE</button>
                        <button class="btn btn-danger" id="reject_btn" data-bs-toggle="modal"
                            data-bs-target="#rejectRequestModal">REJECT</button> --}}
                            <button type="submit" class="btn btn-primary" id="submit_btn">SUBMIT</button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectRequestModal" tabindex="-1" aria-labelledby="rejectRequestModalLabel"
        aria-hidden="true">
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
                        <textarea class="form-control" name="reject_reason" id="reject_reason" rows="5"
                            placeholder="Please indicate reason of rejection" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <form id="rejectRequestModalForm">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <!-- <input type="hidden" id="request_id" name="request_id"> -->
                        <button type="submit" class="btn btn-danger" id="submit_reject_btn">Reject</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="imgModal" class="modal img-modal">
        <span class="closeImgModal">&times;</span>
        <img class="img-modal-content" id="img01">
        <div class="row text-center">
            <div class="col-md-10 mx-auto">
                <embed class="img-responsive" id="embed01" width="1000" height="500" style="display: none;">
            </div>
        </div>
        <div id="caption"></div>
    </div>
@endsection

@section('links_js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/11hps3.js') }}"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                title: '{{ session('success') }}',
                icon: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Okay'
            });
        </script>
    @endif
@endsection
