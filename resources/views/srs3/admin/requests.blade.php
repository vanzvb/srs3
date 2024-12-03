@extends('layouts.main-app')

@section('title', 'Sticker Application Requests')

{{-- <link rel="stylesheet" href="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.4/themes/excite-bike/jquery-ui.css"> --}}
{{-- <link rel="stylesheet" href="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.4/themes/flick/jquery-ui.css"> --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

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
.img-modal-content, #caption {  
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
<div class="container">
    <div class="card p-2 mb-2 mb-md-4 shadow-sm">
		<div class="row g-0">
			<div class="col-md-2 col-sm-4 col-xs-8" style="padding: 0px;">
				<div class="dropdown" id="srs_inbox_select">
					<button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
						@can('accessOpen', App\Models\SrsRequest::class)
							<i class='bx bxs-inbox icon'></i> Srs Inbox
						@else
							<i class='bx bxs-user-check icon'></i> For Approval
						@endcan
					</button>
					<ul class="dropdown-menu inbox_menu">

						{{-- @can('accessOpen', App\Models\SrsRequest::class) --}}
							<li id="srs_select_inbox">
								<a class="dropdown-item" href="#">
									<i class='bx bxs-inbox icon'></i> SRS Inbox
								</a>
							</li>
						{{-- @endcan --}}

						<li id="srs_select_for_approval">
							<a class="dropdown-item" href="#">
								<i class='bx bxs-user-check icon'></i> For Approval
							</a>
						</li>

						{{-- @can('accessClosed', App\Models\SrsRequest::class) --}}
							<li id="srs_select_closed"><a class="dropdown-item" href="#"><i class='bx bx-check-square icon'></i> Closed</a></li>
						{{-- @endcan --}}

						{{-- @can('accessRejected', App\Models\SrsRequest::class) --}}
							<li id="srs_select_rejected">
								<a class="dropdown-item" href="#">
									<i class='bx bx-task-x icon'></i>
									Rejected
								</a>
							</li>
						{{-- @endcan --}}

						{{-- @can('accessArchive', App\Models\SrsRequest::class) --}}
							<li id="srs_select_archive"><a class="dropdown-item" href="#"><i class='bx bx-archive-out icon'></i> Archive</a></li>
						{{-- @endcan --}}

					</ul>
				</div>
			</div>
			<div class="col-md-5" id="archive_year" style="display: none;">
				<div class="row align-items-center g-3">
					<div class="col-auto">
						<label for="">Year:</label>
					</div>
					<div class="col-auto">
						<select class="form-select" name="" id="archive_year_select">
						</select>
					</div>
				</div>				
			</div>
		</div>
	</div>
    <div class="table-responsive">
        <table class="table table-light table-hover table-bordered w-100" id="requests_table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Requestor</th>
                    <th>Request Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                {{-- @foreach ($requests as $request)
					<tr>
						<td><a data-id="{{ $request->request_id}}" class="view_request" href="#">{{ $request->request_id }}</a></td>
						<td>{{ $request->first_name.' '.$request->last_name }}</td>
						<td data-sort="{{ $request->created_at }}">{{ $request->created_at->format('M d, Y h:i A') }}</td>
						@if ($request->status == 1)
							@if($request->statuses->where('name', 'Approval - Admin')->isNotEmpty())
								<td>Approved by Admin</td>
							@else
								<td>Approved by Enclave President</td>
							@endif
						@elseif ($request->status == 2)
							<td>Approved by Admin & Enclave President</td>
						@else
							<td>Pending</td>
						@endif
					</tr>
				@endforeach --}}
            </tbody>
        </table>
    </div>
</div>

<section class="content mb-5" style="margin-top: 50px; box-shadow: rgb(216, 216, 216) 0px 5px 15px 0px; background: rgb(255, 255, 255) !important; display: none;" id="section_request_details">
	<div class="row p-4">
		<div class="col-md-12 col-sm-12 col-12 row" id="div_details_portion">
				<div class="col-md-8 col-sm-9 col-9" style="text-align: left;">
					<p style="font-weight: bold; color: grey; font-size: 17px;">SRS NO: <span style="color: #00b3db; margin-left: 10px;" id="details_requestId"></span></p>
					<h3 style="font-weight: bold;margin-top: 0px !important;">SRS Approval for <span id="details_subject"></span></h3>
				</div>

				<div class="col-md-4 col-sm-3 col-3 px-0">
					<div style="text-align: right;padding-right: 0px !important;">
						<button class="btn" id="btn_exit_request_details" style="background-color:#00b3db;color: white;"><span class="bx bx-x"></span></button>
					</div>
					<div class="mt-md-3" id="red_tag_action">
						
					</div>
				</div>
		</div>
		<div class="row">
			{{-- <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: left;border-bottom: 1px solid #c7ced0;  margin-bottom: 20px;">
				<p style="font-size: 14px !important;"><span>Requestor: </span><span id="details_requestor"></span>        [<span id="details_creation_date"></span>]</p>
				<p style="font-size: 14px !important;">Address: <span id="details_address"></span></p>
			</div> --}}
			<div class="row" style="border-bottom: 1px solid #c7ced0; margin-bottom: 10px;">
				<div class="col-md-8 col-sm-12 col-xs-12" style="text-align: left;">
					<p style="font-size: 14px !important;"><span>Requestor: </span><span id="details_requestor"></span>        [<span id="details_creation_date"></span>]</p>
					<p style="font-size: 14px !important;">Address: <span id="details_address"></span></p>
					@can('access', App\Models\CrmMain::class)	
						<p style="font-size: 14px !important;">Email: <span id="details_email"></span></p>
						<p style="font-size: 14px !important;">Contact No.: <span id="details_contact_no"></span></p>
					@endcan

				</div>
				<div class="col-md-4">
					<div id="red_tag_textarea" class="mb-2">

					</div>
					<div class="" id="red_tag_list">

					</div>
				</div>
			</div>
		</div>
		<div class="row justify-content-end mb-2">
			{{-- <a role="button"><i class='bx bx-refresh' style="height: 30px; width: 30px;"></i></a> --}}
			<div class="col-md-2">
				<div style="text-align: right;" id="refresh_btn">
					
				</div>
			</div>
		</div>
		<div class="col-md-6 col-sm-6 col-12">
			<div class="row g-0" style="text-align: left;">
				<div class="col-md-4 col-sm-4 col-xs-4">
					<label>Account ID :</label>
				</div>
				<div class="col-md-7 col-sm-6 col-xs-6">
					{{-- <input type="text" name="" id=""> --}}
					<div class="input-group mb-3">
						<input type="text" id="details_account_id" class="form-control" placeholder="Link account here" readonly>
						<button data-fname="" data-mname="" data-lname="" class="btn btn-outline-secondary" id="search_account_btn" type="button"><i class='bx bx-search-alt-2 icon'></i></button>
					</div>
				</div>
				{{-- <div class="col-md-2 col-sm-2 col-xs-2">
					<a href="#"><i class='bx bx-search-alt-2 icon'></i></a>
				</div> --}}
				<div class="text-center" id="search_acc_msg">
				</div>
			</div>
			<div class="row mb-2 justify-content-end" id="gen_invoice_action">

			</div>
			<div class="row" style="text-align: left;">
				<div class="col-md-3 col-sm-4 col-xs-5">
					<label>Status :</label>
				</div>
				<div class="col-md-9 col-sm-8 col-xs-7">
					<p style="color: #00b3db;font-weight: bold;" id="details_status">Initiated</p>
				</div>
			</div>
			<div class="row" style="text-align: left;">
				<div class="col-md-3 col-sm-4 col-xs-5">
					<label>Service :</label>
				</div>
				<div class="col-md-9 col-sm-8 col-xs-7">
					<p><span id="details_service"></span></p>
				</div>
			</div>
			<div class="row d-none" style="text-align: left;">
				<div class="col-md-3 col-sm-4 col-xs-5">
					<label>New Service :</label>
				</div>
				<div class="col-md-9 col-sm-8 col-xs-7">
					<p><span id="details_service3"></span></p>
				</div>
			</div>
			<div class="row" style="text-align: left;">
				<div class="col-md-3 col-sm-4 col-xs-5">
					<label>HOA :</label>
				</div>
				<div class="col-md-9 col-sm-8 col-xs-7">
					<p><span id="details_hoa"></span></p>
				</div>
			</div>
			<div class="row d-none" style="text-align: left;">
				<div class="col-md-3 col-sm-4 col-xs-5">
					<label>NEW HOA :</label>
				</div>
				<div class="col-md-9 col-sm-8 col-xs-7">
					<p><span id="details_hoa3"></span></p>
				</div>
			</div>
			<div class="row mt-md-3" style="text-align: left;">
				<label class="text-muted" style="font-size: 14px;">SRS SYSTEM Notification</label>				
				<div class="col-md-12">
					<textarea name="" class="form-control text-muted rounded-0" id="system_notes" style="font-size: 14px;" disabled></textarea>
				</div>	
			</div>
			<div class="row mt-md-2" style="text-align: left;">
				<fieldset @if(auth()->user()->cannot('addAdminRemarks', App\Models\SrsRequest::class)) disabled @endif>
					<label class="text-muted" style="font-size: 14px;">ADMIN REMARKS</label>
					<div class="col-md-12">
						<textarea name="" class="form-control text-muted rounded-0 disabled" id="admin_notes" maxlength="300"></textarea>
						<div style="float: right; padding: 0.1rem 0 0 0; font-size: 0.875rem;">
							<span id="current">0</span> / 300
						</div>
					</div>
				</fieldset>
			</div>
			<div class="row justify-content-center">
				<div id="save_info_msg" class="col-md-6">
				</div>
			</div>
			<div class="row mt-md-2 justify-content-end">
				<div class="col-md-4 text-end">
					@can('addAdminRemarks', App\Models\SrsRequest::class)
						<button class="btn btn-sm btn-secondary px-3" id="save_info_btn" style="color: black;">SAVE INFO</button>
					@endcan
				</div>
			</div>
		</div>
		<div class="col-md-6 col-sm-6 col-12">
            <div class="text-center" id="table_top_msg">
			</div>

			<div id="resend_hoa_notif_action">
			</div>

			<div id="resend_approval_action">
			</div>

			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li id="lisdroutes" class="nav-item">
						<a class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabsdroutes">Routes</a>
					</li>
					<li id="lisdmsgd" class="nav-item">
						<a class="nav-link" data-bs-toggle="tab" data-bs-target="#tabsvehicles">Vehicles</a>
					</li>
					<li id="lisdfiles" class="nav-item">
						<a class="nav-link" data-bs-toggle="tab" data-bs-target="#tabsfiles">Files</a>
					</li>
					<li id="lisreject" class="nav-item">
						<a class="nav-link" data-bs-toggle="tab" data-bs-target="#tabsRejected">Rejected Vehicles</a>
					</li>
					{{-- <li id="lisdinvoice" class="nav-item">
						<a class="nav-link" data-bs-toggle="tab" data-bs-target="#tabsinvoice">Invoice</a>
					</li> --}}
				</ul>
				<div class="tab-content" style="min-height: 250px;max-height: 400px;box-shadow: -1px -5px 35px 0px #d8d8d8;background: #fdfdfd;">
					<div class="tab-pane active" id="tabsdroutes">
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="col-md-12 col-sm-12 col-xs-12 table-responsive" style="max-height: 310px;">
									<table class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" id="tbl_routelog" style="overflow-y: scroll;">
										<thead style=""> 
											<tr style="font-size: 13px;">
												<th style="text-align: center;background: #b1b7b9;color: white;">ROUTE</th>
												{{-- <th style="text-align: center;background: #b1b7b9;color: white;">WHO</th> --}}
												<th style="text-align: center;background: #b1b7b9;color: white;">DATE</th>
												<th style="text-align: center;background: #b1b7b9;color: white;">REMARKS/REASON</th>
											</tr> 
										</thead> 
										<tbody id="routelog_tbody" style="font-size: 12px;">
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tabsvehicles">
						<div class="container">
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="col-md-12 col-sm-12 col-xs-12 table-responsive" style="max-height: 300px;">
										<table class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" id="tbl_vehicles" style="overflow-y: scroll;">
											<thead style=""> 
												<tr style="font-size: 13px;">
													<th style="text-align: center;background: #b1b7b9;color: white;">#</th>
													<th style="text-align: center;background: #b1b7b9;color: white;">REQUEST TYPE</th>
													<th style="text-align: center;background: #b1b7b9;color: white;">STICKER NO.</th>
													<th style="text-align: center;background: #b1b7b9;color: white;">TYPE</th>
													<th style="text-align: center;background: #b1b7b9;color: white;">PLATE NO.</th>
													<th style="text-align: center;background: #b1b7b9;color: white;">BRAND</th>
													<th style="text-align: center;background: #b1b7b9;color: white;">SERIES</th>
													<th style="text-align: center;background: #b1b7b9;color: white;">YEAR/MODEL</th>
													<th style="text-align: center;background: #b1b7b9;color: white;">COLOR</th>
													<th style="text-align: center;background: #b1b7b9;color: white;">OR/CR/VOT</th>
												</tr> 
											</thead> 
											<tbody id="vehicleslog_tbody" style="font-size: 12px;">
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tabsfiles">
						<div class="container">

						</div>
					</div>
					<div class="tab-pane" id="tabsRejected">
						<div class="container">
							<div class="container">
								<div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<div class="col-md-12 col-sm-12 col-xs-12 table-responsive" style="max-height: 300px;">
											<table class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" id="tbl_rejected_veh" style="overflow-y: scroll;">
												<thead style=""> 
													<tr style="font-size: 13px;">
														<th style="text-align: center;background: #b1b7b9;color: white;">#</th>
														<th style="text-align: center;background: #b1b7b9;color: white;">REQUEST TYPE</th>
														<th style="text-align: center;background: #b1b7b9;color: white;">REJECTION REMARKS</th>
														<th style="text-align: center;background: #b1b7b9;color: white;">STICKER NO.</th>
														<th style="text-align: center;background: #b1b7b9;color: white;">TYPE</th>
														<th style="text-align: center;background: #b1b7b9;color: white;">PLATE NO.</th>
														<th style="text-align: center;background: #b1b7b9;color: white;">BRAND</th>
														<th style="text-align: center;background: #b1b7b9;color: white;">SERIES</th>
														<th style="text-align: center;background: #b1b7b9;color: white;">YEAR/MODEL</th>
														<th style="text-align: center;background: #b1b7b9;color: white;">COLOR</th>
														<th style="text-align: center;background: #b1b7b9;color: white;">OR/CR/VOT</th>
													</tr> 
												</thead> 
												<tbody id="tbl_rejected_veh" style="font-size: 12px;">
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					{{-- <div class="tab-pane" id="tabsinvoice">
						<div class="container">
							<div id="invoice_action">

							</div>
						</div>
					</div> --}}
				</div>
			</div>
			<div id="request_load" style="display: none;">
				<div class="col-12 text-center mt-4">
					<img src="{{ asset('css/loading.gif') }}" height="20" width="20">
				</div>
				<div class="col-12 text-center">
					<small id="request_load_msg">Approving Request</small>
				</div>
			</div>
			<div class="text-center mt-3" id="request_approve_msg">
			</div>
			<div id="request_action" class="mt-4 row text-center">
			</div>
			<div class="text-center" id="request_close_msg">
			</div>
			<div id="invoice_action" class="text-end">
			</div>
		</div>
	</div>
</section>

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
					<input type="hidden" id="request_id" name="request_id">
                    <button type="submit" class="btn btn-danger">Reject</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="searchAccountModal" tabindex="-1" aria-labelledby="searchAccountModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header text-center">
				<h1 class="modal-title fs-5 w-100" id="searchAccountModalLabel">Link SRS to CRM Account</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="container p-2 px-md-4">
				<div class="row">
					<div class="col-md-2">
						Name:
					</div>
					<div class="col-md-10">
						<span id="searchAccountName"></span>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						Address:
					</div>
					<div class="col-md-10">
						<span id="searchAccountAddress"></span>
					</div>
				</div>
			</div>

			<div class="modal-body" style="max-width: 100%; overflow-x: auto;">
				
			</div>
			<div class="modal-footer justify-content-center">
				<button type="button" id="link_account_btn" class="btn btn-sm btn-primary px-3">LINK ACCOUNT</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="apptResetModal" tabindex="-1" aria-labelledby="apptResetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-center w-100" id="apptResetModalLabel">Reset Request Appointment?</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="my-3">
                    <p>SRS Reset Appointment will email customer and prompt them to enter new preferred schedule</p>
					<p>Please click OK to confirm</p>
                </div>
            </div>
            <div class="modal-footer">
                <form id="apptResetModalForm" action="" method="POST">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<input type="hidden" id="reset_appt_request_id" name="request_id">
                    <button type="submit" class="btn btn-primary">OK</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="apptResendModal" tabindex="-1" aria-labelledby="apptResendModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-center w-100" id="apptResendModalLabel">Resend Appointment Email?</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="my-3">
                    <p>SRS will send requestor another appointment email with a new link for scheduling appointment</p>
                </div>
            </div>
            <div class="modal-footer">
                <form id="apptResendModalForm" action="" method="POST">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<input type="hidden" id="resend_appt_request_id" name="request_id">
                    <button type="submit" class="btn btn-primary">Resend</button>
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
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {

    var srsNo = '{{ session()->get("srsNo") }}';

	var today = new Date();

	let yearDropdown = document.getElementById('archive_year_select');
	let currentYear = today.getFullYear();

	let allYearOption = document.createElement('option');
	allYearOption.text = 'ALL';      
	allYearOption.value = 1; 
	yearDropdown.add(allYearOption);

	while(currentYear >= 2022) {
		let yearOption = document.createElement('option');
		yearOption.text = currentYear;      
		yearOption.value = currentYear;        
		yearDropdown.add(yearOption);  
		currentYear -= 1;
	}


	// $('#archive_year_select').val(today.getFullYear() - 1);

    // <td>
    //     <a href="#" class="btn btn-primary approve_btn" data-value="${item.request_id }">Approve</a>
    //     <a href="#" class="btn btn-danger reject_btn">Reject</a>
    // </td>
    function loadSrsRequest(type) {
		let year = null;

		if (type == 4) {
			$('#archive_year').show();
			year = $('#archive_year_select').val();
		} else {
			$('#archive_year').hide();
		}

		var table = $('#requests_table').DataTable({
			processing: true,
			serverSide: true,
			pageLength: 15,
			lengthMenu: [
				[15, 30, 50, 100],
				['15', '30', '50', '100']
			],
			destroy: true,
			ajax: {
				url: "{{ route('getRequests.v3') }}",
				data: {
					type: type,					
					year: year
				},
			},
			columns: [
				{
                    data: 'request_id',
                    name: 'request_id',
                },
				{
                    data: 'requestor',
                    name: 'requestor',
                },
				{
					data: 'created_at',
                    name: 'created_at',
					type: 'date'
				},
				{
                    data: 'status',
                    name: 'status',
                    searchable: false
                },
			],
			order: [[2, 'desc']]
		});
	}


	if ($('#srs_select_inbox').length) {
		loadSrsRequest(0);
	} else {
		loadSrsRequest(3);
	}

    
    getStatus = (status) => {
        if (status == 0) {
            return 'Pending';
        } else if (status == 1) {
            return 'Approved by Enclave President'
        }
    }

    showRequest = (request, archiveYear = '') => {
		let archive_year = null;
		let type = 1;

		if ($("#srs_inbox_select .btn").text() == ' Archive') {
			type = 0;
			// archive_year = $('#archive_year_select').val();
			archive_year = archiveYear;
		}

		$.ajax({
			url: '{{ route("getRequest.v3") }}',
			type: 'POST',
			data: {
				_token: '{{ csrf_token() }}',
				srs: request,
				type: type,
				archive_year: archive_year
			},
			success: function (data) {
				// var name = data.srs.fn+' '+data.srs.ln;;
				$('#details_subject').text(`${data.srs.fn} ${data.srs.ln}`);
				if (data.srs.redTagged) {
					$('#details_subject').css('color', 'red');
				} else {
					$('#details_subject').css('color', '');
				}
				
				$('#details_requestId').text(data.srs.id);
				// $('#details_subject').text(`SRS Approval for ${data.srs.fn} ${data.srs.ln}`);
				// $('#details_subject').html(`SRS Approval for ${name}`);
				$('#details_requestor').text(`${data.srs.fn} ${data.srs.mid} ${data.srs.ln}`);
				$('#search_account_btn').attr({
												'data-fname': data.srs.fn,
												'data-mname': data.srs.mid,
												'data-lname': data.srs.ln,
												'data-blk_lot': data.srs.blk_lot,
												'data-street': data.srs.street
											});
				$('#details_address').text(data.srs.address);
				
				if ($('#details_email').length) {
					$('#details_email').text(data.srs.email);
					$('#details_contact_no').text(data.srs.contact_no);
				}

				$('#details_creation_date').text(data.srs.creationDate);
				if (data.srs.cid) {
					$('#details_account_id').val(data.srs.cid);
					$('#details_account_id').prop('readonly', true);
					$('#details_account_id').css('font-weight', 'bold');
					$('#gen_invoice_action').html(data.srs.genInvoiceAction);
				} else {
					$('#details_account_id').val('');
					$('#details_account_id').css('font-weight', '');
					$('#gen_invoice_action').html('');
				}
				$('#red_tag_action').html(data.srs.redTagAction);
				$('#red_tag_list').html(data.srs.redTagNotes);
				$('#red_tag_textarea').html(data.srs.redTagTextarea);
				$('#details_status').text(data.srs.status);
				$('#details_service').text(data.srs.service);
				$('#details_hoa').text(data.srs.hoa);
				// New Fields SRS3 
				$('#details_service3').text(data.srs.new_service);
				$('#details_hoa3').text(data.srs.new_hoa_id);
				var html = '';
				$.each(data.srs.routes, function (index, value) {
					html += value;
				});
				var vehicles = '';
				$.each(data.srs.vehicles, function (index, value) {
					vehicles += value;
				});
				var files = '';
				$.each(data.srs.files, function (index, value) {
					files += value;
					files += '<br>';
				});
				var rejected_vehs = '';
				$.each(data.srs.rejected_veh, function (index, value) {
					rejected_vehs += value;
				});

				if (data.srs.adminApproved) {
					$('#request_action').html('');
				} else {
					if (data.srs.requestAction) {
						$('#request_action').html(data.srs.requestAction);
					} else {
						$('#request_action').html('');
					}
				}
				$('#tabsdroutes table tbody').html(html);
				// $('#tabsdroutes').html(`
				// 	Admin - ${data.srs.adminApproval}
				// 	Enclave President - ${data.srs.hoaApproval}
				// `);
				// $('#tabsdroutes').html(`
					
				// `);
				// $('#tabsvehicles .container').html(vehicles);
				$('#tabsvehicles table tbody').html(vehicles);
				$('#tabsRejected table tbody').html(rejected_vehs);
				$('#tabsfiles .container').html(files);
				$('#invoice_action').html(data.srs.paymentAction);
				$('#system_notes').val(data.srs.systemNotes);
				$('#admin_notes').val(data.srs.adminNotes);
				$('#current').text($('#admin_notes').val().length);
				$('#requests_table_wrapper').hide();
				$('#section_request_details').show();
				$('#refresh_btn').html(data.srs.refreshBtn);
                $('#resend_approval_action').html(data.srs.resendApptBtn);
				$('#resend_hoa_notif_action').html(data.srs.resendHoaNotifBtn);

				if (data.srs.rejected) {
					$('#details_status').css('color', 'red');
				} else {
					$('#details_status').css('color', '#00b3db');
				}

				if (data.srs.isOpen) {
					$('#search_account_btn').prop('disabled', false);
					$('#details_account_id').prop('disabled', false);
					$('#admin_notes').prop('disabled', false);
					$('#save_info_btn').prop('disabled', false);
				} else {
					$('#search_account_btn').prop('disabled', true);
					$('#details_account_id').prop('disabled', true);
					$('#admin_notes').prop('disabled', true);
					$('#save_info_btn').prop('disabled', true);
				}
			}
		});
	}

    if (srsNo) {
		showRequest(srsNo);
	}

    getRequests = () => {
        $.ajax({
            url: '{{ route("getRequests.v3") }}',
            success: function(data) {
                var html = '';
                $.each(data, function (index, item) {
                    html += `<tr>
                                <td><a href="/v3/srs/request/${item.request_id}" target="_blank">${item.request_id }</a></td>
                                <td>${item.first_name} ${item.last_name}</td>
                                <td>${item.created_at}</td>
                                <td>${getStatus(item.status)}</td>
                            </tr>`;
                });
                $('#requests_table tbody').html(html);
            }
        });
    }

    // getRequests();

    // $(document).on('click', '.approve_btn', function (e) {
    //     e.preventDefault();
    //     $.ajax({
    //         url: '{{ route("requests.approve") }}',
    //         type: 'POST',
    //         data: {
    //             req_id: $(this).data('value'),
    //             _token: '{{ csrf_token() }}'
    //         },
    //         success: function () {
    //             //getRequests();
    //         }
    //     });
    // });

    $(document).on('click', '.view_request', function (e) {
		e.preventDefault();
		var archive_year = '';
		var srs = e.target.attributes[0].value;
		if ($(this).data('archive')) {
			archive_year = $(this).data('archive');
		}

		showRequest(srs, archive_year);
	});

	$('#btn_exit_request_details').on('click', function () {
		$('#section_request_details').hide();
		$('#requests_table_wrapper').show();
		$('#request_approve_msg').html('');
		$('#search_acc_msg').html('');
		$('#request_close_msg').html('');
		$('#save_info_msg').html('');
		$('#details_subject').css('color', '');
	});

	$(document).on('click', '#approve_btn', function (e) {
      	e.preventDefault();

		Swal.fire({
		    title: 'Are you sure?',
		    text: "You are about to approve this request!",
		    icon: 'warning',
		    showCancelButton: true,
		    confirmButtonColor: '#3085d6',
		    cancelButtonColor: '#d33',
		    confirmButtonText: 'Yes, approve it!'
     	}).then((result) => {
	        if (result.isConfirmed) {
	            $('#approve_btn').prop('disabled', true);
				$('#request_action').html('');
				$('#request_load').show();

				$.ajax({
					url: '{{ route("requests.approve.v3") }}',
					type: 'POST',
					data: {
						req_id: $(this).data('value'),
						_token: '{{ csrf_token() }}'
					},
					success: function (data) {
						var html = '';
						$('#request_load').hide();
						if (data) {
							if (data.status == 1) {
								var msg = $(`<div class="col-md-8 mx-auto alert alert-info alert-dismissible fade show text-center p-2" role="alert">
									<strong style="font-size: 12px;">Request Approved</strong>
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="width: 2px; height: 2px; padding: 10px 10px 0 0;"></button>
								</div>`);
								$('#request_approve_msg').html(msg);
								msg.delay(5000).slideUp(200, function() {
									$(this).alert('close');
								});
								// $('#request_action').html(`<div class="col-md-12">
								//                         <strong>Approved</strong>
								//                     </div>`);
								
							} else if (data.status == 0) {
								var msg = $(`<div class="col-md-10 mx-auto alert alert-warning alert-dismissible fade show text-center p-2" role="alert">
									<div>
										<p style="font-size: 12px;"><strong>Request Was Recently Approved</strong></p>
									</div>
									<div>
										<p style="font-size: 12px;"><strong>Approved ${data.approvedBy}</strong></p>
									</div>
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="width: 2px; height: 2px; padding: 10px 10px 0 0;"></button>
								</div>`);
								$('#request_approve_msg').html(msg);
								msg.delay(10000).slideUp(200, function() {
									$(this).alert('close');
								});
							}
								
							showRequest(data.srs);				
						}
					}
				});
	        }
      	});
   	});

	$(document).on('click', '#reject_btn', function (e) {
        e.preventDefault();
		$('#rejectRequestModalForm').find('#request_id').val($(this).data('value'));
        $('#rejectRequestModal').modal('show');
    });

    $('#rejectRequestModalForm').on('submit', function (e) {
        e.preventDefault();
		$('#approve_btn').prop('disabled', true);
		$('#reject_btn').prop('disabled', true);
		$('#request_action').html('');
        var reason = $('#reject_reason').val();
		var request_id = $('#rejectRequestModalForm').find('#request_id').val();
        
        if (!reason || reason == '') {
            alert('Please enter reason of rejection');
            return;
        }
        
        $('#request_load #request_load_msg').text('Rejecting Request');
        $('#rejectRequestModal').modal('hide');
		
        $('#request_load').show();
        $.ajax({
            url: '{{ route("request.delete.v3", "") }}'+'/'+request_id,
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
					var msg = $(`<div class="col-md-8 mx-auto alert alert-info alert-dismissible fade show text-center p-2" role="alert">
							<strong style="font-size: 12px;">Request Rejected</strong>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="width: 2px; height: 2px; padding: 10px 10px 0 0;"></button>
						</div>`);
					$('#request_approve_msg').html(msg);
					msg.delay(5000).slideUp(200, function() {
						$(this).alert('close');
					});
                    // $('#request_action').html(`<div class="col-md-12">
                    //                         <strong>Request Rejected</strong>
                    //                     </div>`);
					// $('#request_action').show();
                } else if (data.status == 0) {
					var msg = $(`<div class="col-md-10 mx-auto alert alert-warning alert-dismissible fade show text-center p-2" role="alert">
							<div>
								<p style="font-size: 12px;"><strong>Request Was Recently Rejected</strong></p>
							</div>
							<div>
								<p style="font-size: 12px;"><strong>Rejected ${data.rejectedBy}</strong></p>
							</div>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="width: 2px; height: 2px; padding: 10px 10px 0 0;"></button>
						</div>`);
					$('#request_approve_msg').html(msg);
					msg.delay(10000).slideUp(200, function() {
						$(this).alert('close');
					});
				}
            }
        });
    });

	$('#srs_select_inbox').on('click', function (e) {
		e.preventDefault();
		$('#section_request_details').hide();
		loadSrsRequest(0);
		$("#srs_inbox_select .btn").html('<i class="bx bxs-inbox icon"></i> Srs Inbox');
	});

	$('#srs_select_for_approval').on('click', function (e) {
		e.preventDefault();
		$('#section_request_details').hide();
		loadSrsRequest(3);
		$("#srs_inbox_select .btn").html('<i class="bx bxs-user-check icon"></i> For Approval');
	});
	
	$('#srs_select_closed').on('click', function (e) {
		e.preventDefault();
		$('#section_request_details').hide();
		loadSrsRequest(1);
		$("#srs_inbox_select .btn").html('<i class="bx bx-check-square icon"></i> Closed</a></li>');
	});

	$('#srs_select_rejected').on('click', function (e) {
		e.preventDefault();
		$('#section_request_details').hide();
		loadSrsRequest(2);
		$("#srs_inbox_select .btn").html('<i class="bx bx-task-x icon"></i> Rejected</a></li>');
	});

	$('#srs_select_archive').on('click', function (e) {
		e.preventDefault();
		$('#section_request_details').hide();
		loadSrsRequest(4);
		$("#srs_inbox_select .btn").html('<i class="bx bx-archive-out icon"></i> Archive</a></li>');
	});

	$('#archive_year_select').on('change', function () {
		if ($("#srs_inbox_select .btn").text() == ' Archive') {
			loadSrsRequest(4);
		}
	});
	
	
	$('#admin_notes').on('keyup', function () {
		// console.log($(this).next().find('#current').text());
		$(this).next().find('#current').text($(this).val().length);
	});

	$(document).on('click', '#search_account_btn', function () {
		var fname = $(this).attr('data-fname');
		var mname = $(this).attr('data-mname');
		var lname = $(this).attr('data-lname');
		var blk_lot = $(this).attr('data-blk_lot');
		var street = $(this).attr('data-street');
		var address = $('#details_address').text();
		$.ajax({
			url: '{{ route("srs.v3.search_account") }}',
			type: 'POST',
			data: {
				_token: '{{ csrf_token() }}',
				fname: fname,
				mname: mname,
				lname: lname,
				blk_lot: blk_lot,
				street: street
			},
			success: function (data) {
				$('#searchAccountModal .modal-body').html(data.html);
				$('#searchAccountModal #searchAccountName').html(lname + ', ' + fname + ' ' + mname);
				$('#searchAccountModal #searchAccountAddress').html(address);
				$("#searchAccountModal").modal('show');
			}
		});
		
	});

	$('#save_info_btn').on('click', function () {
		$(this).html(`<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only"></span></div>`);
		var red_tag = 0;
		var red_tag_notes = '';

		if ($('#red_tag_btn').length) {
			if ($('#red_tag_btn').is(':checked')) {
				red_tag = 1;
				red_tag_notes = $('#red_tag_notes').val();
			}
		}

		$.ajax({
			url: '{{ route("request.edit_info") }}',
			type: 'POST',
			data: {
				_token: '{{ csrf_token() }}',
				req_id: $("#details_requestId").text(),
				notes: $('#admin_notes').val(),
				acc: $('#details_account_id').val(),
				red_tag: red_tag,
				red_tag_notes: red_tag_notes
			},
			success: function (data) {
				if (data.status == 1) {
					var msg = $(`<div class="alert alert-info alert-dismissible fade show text-center p-2" role="alert">
						<strong style="font-size: 12px;">Information Saved</strong>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="width: 2px; height: 2px; padding: 10px 10px 0 0;"></button>
					</div>`);
					$('#save_info_msg').html(msg);
					msg.delay(5000).slideUp(200, function() {
						$(this).alert('close');
					});
					$('#save_info_btn').text('SAVE INFO');
					showRequest(data.srs);
				}
			}
		});
	});
	
	$('#link_account_btn').on('click', function ()  {
		var acc = $('input[type="radio"][name="accountRadio"]:checked').val();
		
		if (!acc || acc == '') {
			alert('No selected account');
            return;
		}

		$(this).prop('disabled', true);
		$(this).html(`<div class="spinner-border spinner-border-sm" role="status">
					</div>
					<br>
					Linking Account`);
		
		$.ajax({
			url: '{{ route("request.v3.edit_accID") }}',
			type: 'POST',
			data: {
				_token: '{{ csrf_token() }}',
				acc: acc,
				req_id: $("#details_requestId").text(),
			},
			success: function (data) {
				$('#link_account_btn').prop('disabled', false);
				$('#link_account_btn').html('LINK ACCOUNT');
				if (data.status == 1) {
					var msg = $(`<div class="col-md-8 mx-auto alert alert-info alert-dismissible fade show text-center p-2" role="alert">
						<strong style="font-size: 12px;">Account Linked</strong>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="width: 2px; height: 2px; padding: 10px 10px 0 0;"></button>
					</div>`);
					$('#search_acc_msg').html(msg);
					msg.delay(5000).slideUp(200, function() {
						$(this).alert('close');
					});
					$('#searchAccountModal').modal('hide');
					showRequest(data.srs);
				}
			}     
		});
	});

	// $('#searchAccountModal').on('hidden.bs.modal', function () {
	// 	// $('#search_acc_msg').text('');
	// });

	$(document).on('click', '#invoice_payment_btn', function () {
		$(this).prop('disabled', true);
		$(this).html(`<div class="spinner-border spinner-border-sm" role="status">
					</div>
					<br>
					Processing`);
		$.ajax({
			url: '{{ route("request.close") }}',
			type: 'POST',
			data: {
				_token: '{{ csrf_token() }}',
				req_id: $(this).data('value'),
			},
			success: function (data) {
				if (data.status == 1) {
					var msg = $(`<div class="col-md-8 mx-auto alert alert-info alert-dismissible fade show text-center p-2" role="alert">
							<strong style="font-size: 12px;">Ticket Closed</strong>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="width: 2px; height: 2px; padding: 10px 10px 0 0;"></button>
						</div>`);
					$('#request_close_msg').html(msg);
					msg.delay(5000).slideUp(200, function() {
						$(this).alert('close');
					});
				} else if (data.status == 0) {
					var msg = $(`<div class="col-md-10 mx-auto alert alert-warning alert-dismissible fade show text-center p-2" role="alert">
							<div>
								<p style="font-size: 12px;"><strong>SRS Request Was Recently Closed</strong></p>
							</div>
							<div>
								<p style="font-size: 12px;"><strong>Closed ${data.closedBy}</strong></p>
							</div>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="width: 2px; height: 2px; padding: 10px 10px 0 0;"></button>
						</div>`);
					$('#request_close_msg').html(msg);
					msg.delay(10000).slideUp(200, function() {
						$(this).alert('close');
					});
				}
				showRequest(data.srs);
			}
		});
	});

	$(document).on('click', '#create_customer_btn', function () {
		$(this).prop('disabled', true);
		$(this).html(`<div class="spinner-border spinner-border-sm" role="status">
					</div>
					<br>
					Creating customer record`);
		$.ajax({
			url: '{{ route("crm.v3.store") }}',
			type: 'POST',
			data: {
				_token: '{{ csrf_token() }}',
				req_id: $("#details_requestId").text(),
			},
			success: function (data) {
				$('create_customer_btn').html('');
				if (data.status == 1) {
					var msg = $(`<div class="col-md-8 mx-auto alert alert-info alert-dismissible fade show text-center p-2" role="alert">
						<strong style="font-size: 12px;">Customer Record Created<br>Account Linked</strong>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="width: 2px; height: 2px; padding: 10px 10px 0 0;"></button>
					</div>`);
					$('#search_acc_msg').html(msg);
					msg.delay(5000).slideUp(200, function() {
						$(this).alert('close');
					});
					$('#searchAccountModal').modal('hide');
					showRequest(data.srs);
				} else if (data.status == 0) {
					var msg = $(`<div class="col-md-10 mx-auto alert alert-warning alert-dismissible fade show text-center p-2" role="alert">
						<div>
							<p style="font-size: 12px;"><strong>Customer Record Was Recently Created</strong></p>
						</div>
						<div>
							<p style="font-size: 11px;"><strong>Created ${data.createdBy}<strong></p>
						</div>
						
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="width: 2px; height: 2px; padding: 10px 10px 0 0;"></button>
					</div>`);
					// <div>
					// 		<p style="font-size: 11px;"><strong>Account should have been linked<strong></p>
					// 	</div>
					// 	<div>
					// 		<p style="font-size: 11px;"><strong>If no Account ID please try to link account again</strong></p>
					// 	</div>
					$('#search_acc_msg').html(msg);
					msg.delay(20000).slideUp(200, function() {
						$(this).alert('close');
					});
					$('#searchAccountModal').modal('hide');
					showRequest(data.srs);
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

	$(document).on('click', '#btn_refresh_request_details', function (e) {
		e.preventDefault();
		$(this).css({'pointer-events': 'none', 'cursor': 'default'});
		$(this).html(`<div class="spinner-border spinner-border-sm" role="status">
					</div>`);
		showRequest($(this).attr('data-id'));
	});

	$(document).on('change', '#red_tag_btn', function () {
		if ($(this).is(':checked')) {
			$('#red_tag_notes').show();
		} else {
			$('#red_tag_notes').hide();
		}
	});

	$('#searchAccountModal').on('hide.bs.modal', function () {
		$(this).find('#searchAccountName').html('');
		$(this).find('#searchAccountAddress').html('');
	});

    $(document).on('click', '#reset_appt_btn', function (e) {
        e.preventDefault();
		$('#apptResetModalForm').find('#reset_appt_request_id').val($(this).data('value'));
        $('#apptResetModal').modal('show');
    });

	$('#apptResetModalForm').on('submit', function (e) {
        e.preventDefault();
		var request_id = $('#apptResetModalForm').find('#reset_appt_request_id').val();
		$(this).find('button[type="submit"]').prop('disabled', true);
        $('#reset_appt_btn').css({'pointer-events': 'none', 'cursor': 'default'});
		$('#apptResetModal').modal('hide');
		$('#table_top_msg').html(`<div class="col-md-8 mx-auto alert alert-info text-center p-2" role="alert">
					<strong style="font-size: 12px;">
						<div class="spinner-border spinner-border-sm" role="status"></div>
						<br>
						Resetting Appointment
					</strong>
				</div>`);
		$.ajax({
			url: '{{ route("appointment.reset") }}',
			type: 'POST',
			data: {
				_token: '{{ csrf_token() }}',
				request_id: request_id
			},
			success: function (data) {
				if (data.status == 1) {
					var msg = $(`<div class="col-md-8 mx-auto alert alert-info alert-dismissible fade show text-center p-2" role="alert">
								<strong style="font-size: 12px;">Appointment Reset</strong>
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="width: 2px; height: 2px; padding: 10px 10px 0 0;"></button>
							</div>`);
					$('#table_top_msg').html(msg);
					msg.delay(5000).slideUp(200, function() {
						$(this).alert('close');
					});
					showRequest(data.srs);
				} else if (data.status == 0) {
					var msg = $(`<div class="col-md-8 mx-auto alert alert-danger alert-dismissible fade show text-center p-2" role="alert">
								<strong style="font-size: 12px;">${data.error_msg}</strong>
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="width: 2px; height: 2px; padding: 10px 10px 0 0;"></button>
							</div>`);
					$('#table_top_msg').html(msg);
					msg.delay(15000).slideUp(200, function() {
						$(this).alert('close');
					});
                    $('#reset_appt_btn').css({'pointer-events': 'auto', 'cursor': 'pointer'});
				} else if (data.status == 2) {
					var msg = $(`<div class="col-md-8 mx-auto alert alert-warning alert-dismissible fade show text-center p-2" role="alert">
								<div>
									<p style="font-size: 12px;"><strong>Appointment Was Recently Reset</strong></p>
								</div>
								<div>
									<p style="font-size: 12px;"><strong>${data.msg}</strong></p>
								</div>
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="width: 2px; height: 2px; padding: 10px 10px 0 0;"></button>
							</div>`);
					$('#table_top_msg').html(msg);
					msg.delay(15000).slideUp(200, function() {
						$(this).alert('close');
					});
					showRequest(data.srs);
				}
			}
		});
	});

	$('#apptResetModal').on('hide.bs.modal', function () {
		$('#apptResetModalForm').find('#reset_appt_request_id').val('');
		$('#apptResetModalForm').find('button[type="submit"]').prop('disabled', false);
	});
    

    $(document).on('click', '#resend_approval_btn', function (e) {
		e.preventDefault();
		$('#apptResendModalForm').find('#resend_appt_request_id').val($(this).data('value'));
        $('#apptResendModal').modal('show');
	});

	$('#apptResendModalForm').on('submit', function (e) {
        e.preventDefault();
		var request_id = $('#apptResendModalForm').find('#resend_appt_request_id').val();
		$(this).find('button[type="submit"]').prop('disabled', true);
		$('#resend_approval_btn').prop('disabled', true);
		$('#apptResendModal').modal('hide');
		$('#table_top_msg').html(`<div class="col-md-8 mx-auto alert alert-info text-center p-2" role="alert">
					<strong style="font-size: 12px;">
						<div class="spinner-border spinner-border-sm" role="status"></div>
						<br>
						Resending Appointment Email
					</strong>
				</div>`);
		$.ajax({
			url: '{{ route("appointment.resend") }}',
			type: 'POST',
			data: {
				_token: '{{ csrf_token() }}',
				request_id: request_id
			},
			success: function (data) {
				if (data.status == 1) {
					var msg = $(`<div class="col-md-8 mx-auto alert alert-info alert-dismissible fade show text-center p-2" role="alert">
								<strong style="font-size: 12px;">Appointment Email Resent</strong>
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="width: 2px; height: 2px; padding: 10px 10px 0 0;"></button>
							</div>`);
					$('#table_top_msg').html(msg);
					msg.delay(5000).slideUp(200, function() {
						$(this).alert('close');
					});
					showRequest(data.srs);
				} else if (data.status == 0) {
					var msg = $(`<div class="col-md-8 mx-auto alert alert-danger alert-dismissible fade show text-center p-2" role="alert">
								<strong style="font-size: 12px;">${data.error_msg}</strong>
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="width: 2px; height: 2px; padding: 10px 10px 0 0;"></button>
							</div>`);
					$('#table_top_msg').html(msg);
					msg.delay(15000).slideUp(200, function() {
						$(this).alert('close');
					});
					$('#resend_approval_btn').prop('disabled', false);
				} else if (data.status == 2) {
					var msg = $(`<div class="col-md-8 mx-auto alert alert-warning alert-dismissible fade show text-center p-2" role="alert">
								<div>
									<p style="font-size: 12px;"><strong>Appointment Email Was Recently Resent</strong></p>
								</div>
								<div>
									<p style="font-size: 12px;"><strong>${data.msg}</strong></p>
								</div>
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="width: 2px; height: 2px; padding: 10px 10px 0 0;"></button>
							</div>`);
					$('#table_top_msg').html(msg);
					msg.delay(15000).slideUp(200, function() {
						$(this).alert('close');
					});
					showRequest(data.srs);
				}
			}
		});
	});

	$('#apptResendModal').on('hide.bs.modal', function () {
		$('#apptResendModalForm').find('#resend_appt_request_id').val('');
		$('#apptResendModalForm').find('button[type="submit"]').prop('disabled', false);
	});

	$(document).on('click', '#resend_hoa_notif_btn', function (e) {
		e.preventDefault();
		$(this).prop('disabled', true);
		$(this).html(`<div class="spinner-border spinner-border-sm" role="status">
					</div>
					<br>
					Processing`);
		var request_id = $(this).data('value');

		$.ajax({
			url: '/srs/hoa/resend',
			type: 'POST',
			data: {
				_token: '{{ csrf_token() }}',
				request_id: request_id
			},
			success: function (data) {
				if (data.status == 1) {
					var msg = $(`<div class="col-md-8 mx-auto alert alert-info alert-dismissible fade show text-center p-2" role="alert">
								<strong style="font-size: 12px;">HOA Notification Email Resent</strong>
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="width: 2px; height: 2px; padding: 10px 10px 0 0;"></button>
							</div>`);
					$('#table_top_msg').html(msg);
					msg.delay(5000).slideUp(200, function() {
						$(this).alert('close');
					});
					showRequest(data.srs);
				} else if (data.status == 0) {
					var msg = $(`<div class="col-md-8 mx-auto alert alert-danger alert-dismissible fade show text-center p-2" role="alert">
								<strong style="font-size: 12px;">${data.error_msg}</strong>
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="width: 2px; height: 2px; padding: 10px 10px 0 0;"></button>
							</div>`);
					$('#table_top_msg').html(msg);
					msg.delay(15000).slideUp(200, function() {
						$(this).alert('close');
					});
					$('#resend_hoa_notif_action').html('');
					
				}
			}
		});
	});
});
</script>
@endsection