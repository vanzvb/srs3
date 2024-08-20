@extends('layouts.main-app')

@section('title', 'Reports')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
@section('content')
<div class="container-fluid mt-2  mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card myCard">

                <div class="card-body p-5">
                    <div class="text-start h3 fw-bold"><i class="fas fa-file-excel"></i> MGMT Reports</div>
                    <div class="text-start fst-italic h6"><?= date("F j, Y, g:i a") ?></div>
                    <hr>
                    <div class="mt-4">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-md">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th scope="col" class="text-center p-3">Report Name</th>
                                        <th scope="col" class="text-center p-3">Period</th>
                                        <th scope="col" class="text-center p-3">Filters</th>
                                        <th scope="col" class="text-center p-3">Download</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr valign="middle">
                                        <form action="/sticker_export_excel_2" method="POST">
                                            @csrf
                                            <th class="text-center" scope="row">Sticker Reports (SPC 2.0)</th>
                                            <td>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <select id="sticker_period_filter" name="sticker_period_filter" class="form-select" aria-label="Default select example">
                                                        <option value="" disabled selected>Select Period</option>
                                                        <option value="daily">Today</option>
                                                        <option value="weekly">This Week</option>
                                                        <option value="monthly">This Month</option>
                                                        <option value="quarterly">This Quarter</option>
                                                        <option value="yearly">This Year</option>
                                                        <option value="custom">Custom</option>
                                                        </select>
                                                        <input type="hidden" name="period" id="sticker_period_2">
                                                    </div>
                                                </div>
                                                <div class="row mt-1 gx-1" id="sticker_form_2">
                                                    <div class="col-6">
                                                        <label for="from" class="form-label">From:</label>
                                                        <input type="date" class="form-control" name="from" id="sticker_from_2">
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="to" class="form-label">To:</label>
                                                        <input type="date" class="form-control" name="to" id="sticker_to_2">
                                                    </div>
                                                </div>
                                            </td>
                                            <td> 
                                                <select name="user_filter" class="form-select" aria-label="Default select example">
                                                    <option selected value="">Select Cashier</option>
                                                    <option value="0">All</option>
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                                <select id="sticker_or_cr_dropdown_2" name="or_cr_select" class="form-select mt-1">
                                                    <option disabled>Select Receipt Type</option>
                                                    <option value="all" selected="">ALL (OR - CR)</option>
                                                    <option value="or">OR</option>
                                                    <option value="cr">CR</option>
                                                </select>
                                            </td>
                                            <td class="text-center" style="cursor:pointer"> 
                                                <button type="submit"
                                                    style="border: none;"><i class="far fa-file-excel fa-2x"
                                                        style="color:#808080"></i>
                                                </button>
                                            </td>
                                        </form>
                                    </tr>

                                    <tr valign="middle">
                                        <form action="/revenue_report_2" method="POST">
                                            @csrf
                                            <th class="text-center" scope="row">Revenue Reports (Only in 2.0)</th>
                                            <td class="">
                                                <div>
                                                    <label for="" class="form-label">From: </label>
                                                    <input name="from" type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                                </div>
                                                <div class=" mt-3">
                                                    <label for="" class="form-label">To: </label>
                                                    <input name="to" type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                                </div>
                                            </td>
                                            <td>
                                                 <select id="sticker_category_dropdown" name="status" class="form-select" aria-label="Default select example">
                                                    <option disabled>Select Receipt Type</option>
                                                    <option value="all" selected="">ALL (OR - CR)</option>
                                                    <option value="or">OR</option>
                                                    <option value="cr">CR</option>
                                                 </select>
                                            </td>
                                            <td class="text-center" style="cursor:pointer">
                                                <button type="submit" style="border: none;">
                                                    <i class="far fa-file-excel text-success fa-2x" style="color:#808080"></i>
                                                </button>
                                            </td>
                                        </form>
                                    </tr>

                                    <tr valign="middle">
                                        <form action="{{ route('sims.released_items_export') }}" method="POST">
                                           @csrf
                                           <th class="text-center" scope="row">Released Stickers (Only in 2.0)</th>
                                           <td>
                                              <div class="row">
                                                 <div class="col-12">
                                                    <select id="release_period_filter" name="release_period_filter" class="form-select" aria-label="Default select example">
                                                       <option value="" disabled selected>Select Period</option>
                                                        <option value="daily">Today</option>
                                                        <option value="weekly">This Week</option>
                                                        <option value="monthly">This Month</option>
                                                        <option value="quarterly">This Quarter</option>
                                                        <option value="yearly">This Year</option>
                                                        <option value="custom">Custom</option>
                                                    </select>
                                                    <input type="hidden" name="period" id="release_period">
                                                 </div>
                                              </div>
                                              <div class="row mt-1 gx-1" id="release_form">
                                                 <div class="col-6">
                                                    <label for="from" class="form-label">From:</label>
                                                    <input type="date" class="form-control" name="from" id="release_from">
                                                 </div>
                                                 <div class="col-6">
                                                    <label for="to" class="form-label">To:</label>
                                                    <input type="date" class="form-control" name="to" id="release_to">
                                                 </div>
                                              </div>
                                           </td>
                                           <td> 
                                              <select name="user_filter" class="form-select" aria-label="Default select example">
                                                 <option selected value="">Select Cashier</option>
                                                 <option value="0">All</option>
                                                 @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                 @endforeach
                                              </select>
                                           </td>
                                           <td class="text-center" style="cursor:pointer"> 
                                              <button type="submit"
                                                 style="border: none;"><i class="far fa-file-excel fa-2x"
                                                    style="color:#808080"></i>
                                              </button>
                                           </td>
                                        </form>
                                    </tr>

                                    <tr valign="middle">
                                        <form action="{{ route('sims.detailed_cashier_export') }}" method="POST">
                                           @csrf
                                           <th class="text-center" scope="row">Detailed Cashier Sticker Report (Only in 2.0)</th>
                                           <td>
                                              <div class="row">
                                                 <div class="col-12">
                                                    <select id="detailed_period_filter" name="detailed_period_filter" class="form-select" aria-label="Default select example">
                                                       <option value="" disabled selected>Select Period</option>
                                                       <option value="daily">Today</option>
                                                       <option value="weekly">This Week</option>
                                                       <option value="monthly">This Month</option>
                                                       <option value="quarterly">This Quarter</option>
                                                       <option value="yearly">This Year</option>
                                                       <option value="custom">Custom</option>
                                                    </select>
                                                    <input type="hidden" name="period" id="detailed_period">
                                                 </div>
                                              </div>
                                              <div class="row mt-1 gx-1" id="detail_form">
                                                 <div class="col-6">
                                                    <label for="from" class="form-label">From:</label>
                                                    <input type="date" class="form-control" name="from" id="detail_from">
                                                 </div>
                                                 <div class="col-6">
                                                    <label for="to" class="form-label">To:</label>
                                                    <input type="date" class="form-control" name="to" id="detail_to">
                                                 </div>
                                              </div>
                                           </td>
                                           <td> 
                                              <select name="user_filter" class="form-select" aria-label="Default select example">
                                                 <option selected value="">Select Cashier</option>
                                                 <option value="0">All</option>
                                                 @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                 @endforeach
                                              </select>
                                           </td>
                                           <td class="text-center" style="cursor:pointer"> 
                                              <button type="submit"
                                                 style="border: none;"><i class="far fa-file-excel fa-2x"
                                                    style="color:#808080"></i>
                                              </button>
                                           </td>
                                        </form>
                                    </tr>

                                    <tr valign="middle">
                                        <th class="text-center" scope="row">CRM</th>
                                        <td>
                                            <div class="row g-2">
                                                <div class="col-md">
                                                    <select class="form-select form-select-sm" id="crm_period_dropdown" aria-label="Default select example">
                                                        <option value="1">Weekly</option>
                                                        <option value="2">Monthly</option>
                                                        <option value="3">Quarterly</option>
                                                        <option value="4">Annual</option>
                                                    </select>
                                                </div>
                                                <div class="col-md">
                                                    <select class="form-select form-select-sm" id="crm_year_dropdown">
                                                    </select>
                                                </div>
                                                <div id="crm_sub_select">

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-select" aria-label="Default select example" id="crm_category_dropdown">
                                                <option selected disabled>Select Category</option>
                                                <option value="0">All</option>
                                                <option value="1">Active</option>
                                                <option value="2">Inactive</option>
                                                <option value="3">Suspended</option>
                                                <option value="4">Banned</option>


                                            </select>
                                        </td>
                                        <!-- <td><input type="date" class="form-control"></td>
                                            <td><input type="date" class="form-control"></td> -->
                                        <td class="text-center" style="cursor:pointer">
                                            <a role="button" id="crm_request_export_btn"><i class="far fa-file-excel fa-2x" style="color:#808080"></i></a>
                                        </td>


                                    </tr>
                                    <tr>
                                        <th class="text-center" scope="row">SRS Tickets</th>
                                        <td>
                                            <div class="row g-2">
                                                <div class="col-md">
                                                    <select class="form-select form-select-sm" id="srs_period_dropdown" aria-label="Default select example">
                                                        <option value="1">Weekly</option>
                                                        <option value="2">Monthly</option>
                                                        <option value="3">Quarterly</option>
                                                        <option value="4">Annual</option>
                                                    </select>
                                                </div>
                                                <div class="col-md">
                                                    <select class="form-select form-select-sm" id="srs_year_dropdown">
                                                    </select>
                                                </div>
                                                <div id="srs_sub_select">

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row g-0">
                                                <div class="col-md-4">
                                                    Status:
                                                </div>
                                                <div class="col-md-8">
                                                    <select class="form-select form-select-sm" id="srs_status_dropdown" aria-label="Default select example">
                                                        <option value="1">All</option>
                                                        <option value="2">Open</option>
                                                        <option value="3">Close</option>
                                                        <option value="4">Rejected</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a role="button" id="request_export_btn">
                                                <i class="far fa-file-excel fa-2x" style="color:#808080"></i>
                                            </a>
                                            {{-- <a role="button" id="request_export_pdf">

                                                <i class='bx bxs-file-pdf icon' style="font-size: 2em; color: #f40f00;"></i>
                                            </a> --}}
                                        </td>
                                    </tr>
                                    <tr valign="middle">
                                        <th class="text-center" scope="row">Invoice</th>
                                        <td>
                                            <div class="row g-2">
                                                <div class="col-md">
                                                    <select class="form-select form-select-sm" id="invoice_period_dropdown" aria-label="Default select example">
                                                        <option value="1">Weekly</option>
                                                        <option value="2">Monthly</option>
                                                        <option value="3">Quarterly</option>
                                                        <option value="4">Annual</option>
                                                        <option value="5">Daily</option>
                                                    </select>
                                                </div>
                                                <div class="col-md">
                                                    <select class="form-select form-select-sm" id="invoice_year_dropdown">
                                                    </select>
                                                </div>
                                                <div id="invoice_sub_select">

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-select  form-select-sm" aria-label="Default select example" id="invoice_category_dropdown">
                                                <option selected disabled>Select Category</option>
                                                <option value="0">All</option>
                                                <option value="1">Residents</option>
                                                <option value="2">Non-Residents</option>
                                                <option value="3">Commercial</option>

                                            </select>
                                        </td>
                                        <!-- <td><input type="date" class="form-control"></td>
                                            <td><input type="date" class="form-control"></td> -->
                                        <td class="text-center" style="cursor:pointer">
                                            <a role="button" id="invoice_request_export_btn"><i class="far fa-file-excel fa-2x" style="color:#808080"></i></a>
                                        </td>


                                    </tr>
                                    <tr valign="middle">
                                        <form action="/invoice_export" method="POST">
                                            @csrf
                                            <th class="text-center" scope="row">Invoice</th>
                                            <td class="">
                                                <div>
                                                    <label for="" class="form-label">From: </label>
                                                    <input name="from" type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                                </div>
                                                <div class=" mt-3">
                                                    <label for="" class="form-label">To: </label>
                                                    <input name="to" type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                                </div>
                                            </td>
                                            <td> <select name="category_id" class="form-select" aria-label="Default select example">
                                                    <option selected disabled>Select Category</option>
                                                    <option value="0">All</option>
                                                    <option value="1">Residents</option>
                                                    <option value="2">Non-Residents</option>
                                                    <option value="3">Commercial</option>

                                                </select></td>
                                            <td class="text-center" style="cursor:pointer"> <button type="submit" style="border: none;"><i class="far fa-file-excel fa-2x" style="color:#808080"></i></button></td>
                                        </form>
                                    </tr>

                                    <tr valign="middle">


                                        <th class="text-center" scope="row">Sticker Report (ALL OR+CR)</th>
                                        <td>
                                            <div class="row g-2">
                                                <div class="col-md">
                                                    <select class="form-select form-select-sm" id="sticker_period_dropdown" aria-label="Default select example">
                                                        <option value="1">Daily</option>
                                                        <option value="2">Weekly</option>
                                                        <option value="3">Monthly</option>
                                                        <option value="4">Quarterly</option>
                                                        <option value="5">Annual</option>
                                                    </select>
                                                </div>
                                                <div class="col-md">
                                                    <select class="form-select form-select-sm" id="sticker_year_dropdown">
                                                    </select>
                                                </div>
                                                <div id="sticker_sub_select">

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <select id="sticker_category_dropdown" name="agent_id" class="form-select form-select-sm" aria-label="Default select example">
                                                <option selected disabled>Select Agent</option>
                                                <option value="0" selected>All Cashier</option>
                                                @foreach($users as $user)
                                                @if($user->name != 'Admin' && $user->name != 'Cashier')
                                                <option  value="{{$user->id}}">{{$user->name}}</option>
                                                @endif
                                                @endforeach

                                            </select>
                                        </td>
                                        <td class="text-center" style="cursor:pointer">
                                           <!--  <a role="button" id="sticker_request_export_btn"> <i class='bx bxs-file-pdf icon' style="font-size: 2em; color: #f40f00;"></i></a> -->

                                            <a role="button" id="sticker_request_export_btn_cv"><i class="far fa-file-excel text-success fa-2x" style="color:#808080"></i></a>
                                        </td>
                                    </tr>


                                     <tr valign="middle">
                                        <form id="stickerForm" method="POST">
                                            @csrf
                                            <th class="text-center" scope="row">Sticker Reports (DATE FILTER ALL OR+CR)</th>
                                            <td class="">
                                                <div>
                                                    <label for="" class="form-label">From: </label>
                                                    <input name="from" type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                                </div>
                                                <div class=" mt-3">
                                                    <label for="" class="form-label">To: </label>
                                                    <input name="to" type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                                </div>
                                            </td>
                                            <td>
                                                <select id="sticker_category_dropdown" name="status" class="form-select form-select-sm" aria-label="Default select example">
                                                    <option selected disabled>Select Agent</option>
                                                    <option value="0" selected>All Cashier</option>
                                                    @foreach($users as $user)
                                                    @if($user->name != 'Admin' && $user->name != 'Cashier')
                                                    <option  value="{{$user->id}}">{{$user->name}}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center" style="cursor:pointer">
                                               <!--  <button id="pdfBtn" type="button" style="border: none;">
                                                    <i class='bx bxs-file-pdf icon' style="font-size: 2em; color: #f40f00;"></i>
                                                </button> -->
                                                <button id="excelBtn" type="button" style="border: none;">
                                                    <i class="far fa-file-excel text-success fa-2x" style="color:#808080"></i>
                                                </button>
                                            </td>
                                        </form>
                                    </tr>

                                    <tr valign="middle">
                                        <form action="/revenue_report" method="POST">
                                            @csrf
                                            <th class="text-center" scope="row">Revenue Report


                                            </th>
                                            <td class="">
                                                <div>
                                                    <label for="" class="form-label">From: </label>
                                                    <input name="from" type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                                </div>
                                                <div class=" mt-3">
                                                    <label for="" class="form-label">To: </label>
                                                    <input name="to" type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                                </div>
                                            </td>
                                            <td>

                                                <center>
                                                    <select name="status" id="" class="form-select mt-4 form-select-sm">
                                                        <option value="0">All (OR+CR)</option>
                                                        <option value="2">CR Only</option>
                                                        <option value="1">OR Only</option>

                                                    </select>
                                                </center>
                                            </td>
                                            <td class="text-center" style="cursor:pointer">

                                                <button type="submit" style="border: none;">
                                                    <i class="far fa-file-excel text-success fa-2x" style="color:#808080"></i>
                                                </button>
                                            </td>
                                        </form>
                                    </tr>

                              <!--       <tr valign="middle">
                                        <th class="text-center" scope="row">Sticker Report (HOA)</th>
                                        <td>
                                            <div class="row g-2">
                                                <div class="col-md">
                                                    <select class="form-select form-select-sm" id="hoa_period_dropdown" aria-label="Default select example">
                                                        <option value="1">Daily</option>
                                                        <option value="2">Weekly</option>
                                                        <option value="3">Monthly</option>
                                                        <option value="4">Quarterly</option>
                                                        <option value="5">Annual</option>

                                                    </select>
                                                </div>
                                                <div class="col-md">
                                                    <select class="form-select form-select-sm" id="hoa_year_dropdown">
                                                    </select>
                                                </div>
                                                <div id="hoa_sub_select">

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-select  form-select-sm" aria-label="Default select example" id="hoa_category_dropdown">

                                                <option value="0">All HOA</option>

                                            </select>
                                        </td>
                                         <td><input type="date" class="form-control"></td>
                                            <td><input type="date" class="form-control"></td> 
                                        <td class="text-center" style="cursor:pointer">
                                            <a role="button" id="hoa_request_export_btn"> <i class='bx bxs-file-pdf icon' style="font-size: 2em; color: #f40f00;"></i></a> 
                                        </td>


                                    </tr> -->
                                    <tr valign="middle">
                                        <th class="text-center" scope="row">Red Tag</th>
                                        <td class="">
                                            <div>
                                                <label for="" class="form-label">From: </label>
                                                <input name="from" type="date" value="<?php echo date('Y-m-d'); ?>" id="redtag_from" class="form-control">
                                            </div>
                                            <div class=" mt-3">
                                                <label for="" class="form-label">To: </label>
                                                <input name="to" type="date" value="<?php echo date('Y-m-d'); ?>" id="redtag_to" class="form-control">
                                            </div>
                                        </td>
                                        <td>
                                        <td class="text-center" style="cursor:pointer">
                                            <a role="button" id="redtag_export_btn">
                                                <i class="far fa-file-excel fa-2x" style="color:#808080"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <tr valign="middle">
                                        <form action="/hoa_report_2" method="POST">
                                            @csrf
                                            <th class="text-center" scope="row">HOA Sticker Report (Only in 2.0)</th>
                                            <td class="">
                                                <div>
                                                    <label for="" class="form-label">From: </label>
                                                    <input name="from" type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                                </div>
                                                <div class=" mt-3">
                                                    <label for="" class="form-label">To: </label>
                                                    <input name="to" type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                                </div>
                                            </td>
                                            <td>
                                                <select id="sticker_category_dropdown" name="status" class="form-select form-select-sm" aria-label="Default select example">

                                                    <option value="0">All Cashier</option>

                                                </select>
                                            </td>
                                            <td class="text-center" style="cursor:pointer">

                                                <button type="submit" style="border: none;">
                                                    <i class="far fa-file-excel text-success fa-2x" style="color:#808080"></i>
                                                </button>
                                            </td>
                                        </form>
                                    </tr>

                                    <!-- JOSH PATCH 26-04-2023 HOA REPORT VIEW -->

                                    <tr valign="middle">
                                        <form action="/hoa_report" method="POST">
                                            @csrf
                                            <th class="text-center" scope="row">HOA Sticker Report</th>
                                            <td class="">
                                                <div>
                                                    <label for="" class="form-label">From: </label>
                                                    <input name="from" type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                                </div>
                                                <div class=" mt-3">
                                                    <label for="" class="form-label">To: </label>
                                                    <input name="to" type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                                </div>
                                            </td>
                                            <td>
                                                <select id="sticker_category_dropdown" name="status" class="form-select form-select-sm" aria-label="Default select example">

                                                    <option value="0">All Cashier</option>

                                                </select>
                                            </td>
                                            <td class="text-center" style="cursor:pointer">

                                                <button type="submit" style="border: none;">
                                                    <i class="far fa-file-excel text-success fa-2x" style="color:#808080"></i>
                                                </button>
                                            </td>
                                        </form>
                                    </tr>
                                    <!-- JOSH PATCH 26-04-2023 HOA REPORT VIEW -->

                                    <!-- JOSH PATCH 27-04-2023 HOA MEMBERS REPORT VIEW -->
                                 <tr valign="middle">
                                        <form action="/hoa_member_report" method="POST">
                                            @csrf
                                            <th class="text-center" scope="row">HOA to Member Sticker Report</th>
                                            <td class="">
                                                <div>
                                                    <label for="" class="form-label">From: </label>
                                                    <input name="from" type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                                </div>
                                                <div class=" mt-3">
                                                    <label for="" class="form-label">To: </label>
                                                    <input name="to" type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                                </div>
                                            </td>
                                            <td>
                                                <select id="sticker_category_dropdown" name="status" class="form-select form-select-sm" aria-label="Default select example">
                                                    <option value="0">All HOA</option>
                                                    @foreach($hoas as $hoa)
                                                    <option value="{{$hoa->name}}">{{$hoa->name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center" style="cursor:pointer">

                                                <button type="submit" style="border: none;">
                                                    <i class="far fa-file-excel text-success fa-2x" style="color:#808080"></i>
                                                </button>
                                            </td>
                                        </form>
                                    </tr>

                                    <!-- END JOSH PATCH 27-04-2023 -->

                                    <!-- JOSH PATCH 18-04-2023 -->
                                    
                                    <!-- END JOSH PATCH 18-04-2023 -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('links_js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        let currentDate = new Date();
        let yearDropdown = document.getElementById('srs_year_dropdown');
        let currentYear = currentDate.getFullYear();

        while (currentYear >= 2022) {
            let yearOption = document.createElement('option');
            yearOption.text = currentYear;
            yearOption.value = currentYear;
            yearDropdown.add(yearOption);
            currentYear -= 1;
        }

        toggleSubSelect = () => {
            var period = $('#srs_period_dropdown').val();
            html = '';

            if (period == 2) {
                html += `<select class="form-select form-select-sm" id="srs_sub_select_dropdown" aria-label="Default select example">
                        <option value="1">Jan</option>
                        <option value="2">Feb</option>
                        <option value="3">Mar</option>
                        <option value="4">Apr</option>
                        <option value="5">May</option>
                        <option value="6">Jun</option>
                        <option value="7">Jul</option>
                        <option value="8">Aug</option>
                        <option value="9">Sep</option>
                        <option value="10">Oct</option>
                        <option value="11">Nov</option>
                        <option value="12">Dec</option>
                    </select>`;

                $('#srs_sub_select').addClass('col-md');
            } else if (period == 3) {
                html += `<select class="form-select form-select-sm" id="srs_sub_select_dropdown" aria-label="Default select example">
                        <option value="1">Q1</option>
                        <option value="2">Q2</option>
                        <option value="3">Q3</option>
                        <option value="4">Q4</option>
                    </select>`;

                $('#srs_sub_select').addClass('col-md');
            } else {
                $('#srs_sub_select').removeClass('col-md');
            }

            if (period == 1) {
                $('#srs_year_dropdown').parent().removeClass('col-md');
                $('#srs_year_dropdown').hide();
            } else {
                $('#srs_year_dropdown').parent().addClass('col-md');
                $('#srs_year_dropdown').show();
            }

            $('#srs_sub_select').html(html);

            if (period == 2) {
                $('#srs_sub_select_dropdown').val(currentDate.getMonth() + 1);
            }
        }

        toggleSubSelect();

        $('#request_export_btn').on('click', function() {
            var sub_select = 0;

            if ($('#srs_sub_select_dropdown').length) {
                sub_select = $('#srs_sub_select_dropdown').val()
            }

            var query = {
                year: $('#srs_year_dropdown').val(),
                period: $('#srs_period_dropdown').val(),
                status: $('#srs_status_dropdown').val(),
                sub_select: sub_select
            };

            var url = "/srs/export/requests?" + $.param(query)

            window.location = url;
        });

        $('#request_export_pdf').on('click', function() {
            var sub_select = 0;

            if ($('#srs_sub_select_dropdown').length) {
                sub_select = $('#srs_sub_select_dropdown').val()
            }

            var query = {
                year: $('#srs_year_dropdown').val(),
                period: $('#srs_period_dropdown').val(),
                status: $('#srs_status_dropdown').val(),
                sub_select: sub_select
            };

            var url = '/srs/export-pdf/requests?' + $.param(query);

            // window.location = url;
            window.open(url, '_blank');
        });

        $('#redtag_export_btn').on('click', function () {
            var from = $('#redtag_from').val();
            var to = $('#redtag_to').val();

            if (from > to) {
                alert('Date field To cannot be less than the date field From');
                return;
            }

            var query = {
                from: from,
                to: to,
            };
            
            var url = "/srs/export/redtag?" + $.param(query)

            window.location = url;
        });

        $('#srs_period_dropdown').on('change', function() {
            toggleSubSelect();
        });
    });
</script>
<script>
    $(document).ready(function() {
        let currentDate = new Date();
        let yearDropdown = document.getElementById('invoice_year_dropdown');
        let currentYear = currentDate.getFullYear();

        while (currentYear >= 2022) {
            let yearOption = document.createElement('option');
            yearOption.text = currentYear;
            yearOption.value = currentYear;
            yearDropdown.add(yearOption);
            currentYear -= 1;
        }

        toggleInvoiceSubSelect = () => {
            var period = $('#invoice_period_dropdown').val();
            html = '';

            if (period == 2) {
                html += `<select class="form-select form-select-sm" id="invoice_sub_select_dropdown" aria-label="Default select example">
                            <option value="1">Jan</option>
                            <option value="2">Feb</option>
                            <option value="3">Mar</option>
                            <option value="4">Apr</option>
                            <option value="5">May</option>
                            <option value="6">Jun</option>
                            <option value="7">Jul</option>
                            <option value="8">Aug</option>
                            <option value="9">Sep</option>
                            <option value="10">Oct</option>
                            <option value="11">Nov</option>
                            <option value="12">Dec</option>
                        </select>`;

                $('#invoice_sub_select').addClass('col-md');
            } else if (period == 3) {
                html += `<select class="form-select form-select-sm" id="invoice_sub_select_dropdown" aria-label="Default select example">
                            <option value="1">Q1</option>
                            <option value="2">Q2</option>
                            <option value="3">Q3</option>
                            <option value="4">Q4</option>
                        </select>`;

                $('#invoice_sub_select').addClass('col-md');
            } else {
                $('#invoice_sub_select').removeClass('col-md');
            }

            if (period == 1 || period == 5) {
                $('#invoice_year_dropdown').parent().removeClass('col-md');
                $('#invoice_year_dropdown').hide();
            } else {
                $('#invoice_year_dropdown').parent().addClass('col-md');
                $('#invoice_year_dropdown').show();
            }



            $('#invoice_sub_select').html(html);
        }

        toggleInvoiceSubSelect();

        $('#invoice_request_export_btn').on('click', function() {

            var sub_select = 0;

            if ($('#invoice_sub_select_dropdown').length) {
                sub_select = $('#invoice_sub_select_dropdown').val()
            }

            var query = {
                year: $('#invoice_year_dropdown').val(),
                period: $('#invoice_period_dropdown').val(),
                status: $('#invoice_category_dropdown').val(),
                sub_select: sub_select
            };
            console.log(query);
            var url = "/invoice_report_filter?" + $.param(query);
            window.location = url;
        });

        $('#invoice_period_dropdown').on('change', function() {
            toggleInvoiceSubSelect();
        });
    });

    $(document).ready(function() {
        let currentDate = new Date();
        let yearDropdown = document.getElementById('crm_year_dropdown');
        let currentYear = currentDate.getFullYear();

        while (currentYear >= 2022) {
            let yearOption = document.createElement('option');
            yearOption.text = currentYear;
            yearOption.value = currentYear;
            yearDropdown.add(yearOption);
            currentYear -= 1;
        }

        toggleCrmSubSelect = () => {
            var period = $('#crm_period_dropdown').val();
            html = '';

            if (period == 2) {
                html += `<select class="form-select form-select-sm" id="crm_sub_select_dropdown" aria-label="Default select example">
                            <option value="1">Jan</option>
                            <option value="2">Feb</option>
                            <option value="3">Mar</option>
                            <option value="4">Apr</option>
                            <option value="5">May</option>
                            <option value="6">Jun</option>
                            <option value="7">Jul</option>
                            <option value="8">Aug</option>
                            <option value="9">Sep</option>
                            <option value="10">Oct</option>
                            <option value="11">Nov</option>
                            <option value="12">Dec</option>
                        </select>`;

                $('#crm_sub_select').addClass('col-md');
            } else if (period == 3) {
                html += `<select class="form-select form-select-sm" id="crm_sub_select_dropdown" aria-label="Default select example">
                            <option value="1">Q1</option>
                            <option value="2">Q2</option>
                            <option value="3">Q3</option>
                            <option value="4">Q4</option>
                        </select>`;

                $('#crm_sub_select').addClass('col-md');
            } else {
                $('#crm_sub_select').removeClass('col-md');
            }

            if (period == 1) {
                $('#crm_year_dropdown').parent().removeClass('col-md');
                $('#crm_year_dropdown').hide();
            } else {
                $('#crm_year_dropdown').parent().addClass('col-md');
                $('#crm_year_dropdown').show();
            }

            $('#crm_sub_select').html(html);
        }

        toggleCrmSubSelect();

        $('#crm_request_export_btn').on('click', function() {

            var sub_select = 0;

            if ($('#crm_sub_select_dropdown').length) {
                sub_select = $('#crm_sub_select_dropdown').val()
            }

            var query = {
                year: $('#crm_year_dropdown').val(),
                period: $('#crm_period_dropdown').val(),
                status: $('#crm_category_dropdown').val(),
                sub_select: sub_select
            };
            console.log(query);
            var url = "/crm_access_report?" + $.param(query);
            window.location = url;
        });

        $('#crm_period_dropdown').on('change', function() {
            toggleCrmSubSelect();
        });
    });

    $(document).ready(function () {
    $('#srs_hoa_export_btn').on('click', function () {
        var from = $('#request_hoa_from').val();
        var to = $('#request_hoa_to').val();

        if (from > to) {
            alert('Date field To cannot be less than the date field From');
            return;
        }

        var query = {
            from: from,
            to: to,
        };
        
        var url = "/srs/export/requests/hoa?" + $.param(query)

        window.location = url;
    });

    $('#srs_view_tickets_export_btn').on('click', function () {
        $('#srs_view_tickets_form').submit();
    });
});

$(document).ready(function() {
        $('#pdfBtn').click(function() {
            $('#stickerForm').attr('action', '/sticker_export');
            $('#stickerForm').submit();
        });

        $('#excelBtn').click(function() {
            $('#stickerForm').attr('action', '/sticker_export_excel');
            $('#stickerForm').submit();
        });
    });


$(document).ready(function() {
        let currentDate = new Date();
        let yearDropdown = document.getElementById('sticker_year_dropdown');
        let currentYear = currentDate.getFullYear();

        while (currentYear >= 2022) {
            let yearOption = document.createElement('option');
            yearOption.text = currentYear;
            yearOption.value = currentYear;
            yearDropdown.add(yearOption);
            currentYear -= 1;
        }

        toggleStickerSubSelect = () => {
            var period = $('#sticker_period_dropdown').val();
            html = '';

            if (period == 3) {
                html += `<select class="form-select form-select-sm" id="sticker_sub_select_dropdown" aria-label="Default select example">
                            <option value="1">Jan</option>
                            <option value="2">Feb</option>
                            <option value="3">Mar</option>
                            <option value="4">Apr</option>
                            <option value="5">May</option>
                            <option value="6">Jun</option>
                            <option value="7">Jul</option>
                            <option value="8">Aug</option>
                            <option value="9">Sep</option>
                            <option value="10">Oct</option>
                            <option value="11">Nov</option>
                            <option value="12">Dec</option>
                        </select>`;

                $('#sticker_sub_select').addClass('col-md');
            } else if (period == 4) {
                html += `<select class="form-select form-select-sm" id="sticker_sub_select_dropdown" aria-label="Default select example">
                            <option value="1">Q1</option>
                            <option value="2">Q2</option>
                            <option value="3">Q3</option>
                            <option value="4">Q4</option>
                        </select>`;

                $('#sticker_sub_select').addClass('col-md');
            } else {
                $('#sticker_sub_select').removeClass('col-md');
            }

            if (period == 1 || period == 2) {
                $('#sticker_year_dropdown').parent().removeClass('col-md');
                $('#sticker_year_dropdown').hide();
            } else {
                $('#sticker_year_dropdown').parent().addClass('col-md');
                $('#sticker_year_dropdown').show();
            }

            $('#sticker_sub_select').html(html);
        }

        toggleStickerSubSelect();

        $('#sticker_request_export_btn').on('click', function() {

            var sub_select = 0;

            if ($('#sticker_sub_select_dropdown').length) {
                sub_select = $('#sticker_sub_select_dropdown').val()
            }

            var query = {
                year: $('#sticker_year_dropdown').val(),
                period: $('#sticker_period_dropdown').val(),
                status: $('#sticker_category_dropdown').val(),
                sub_select: sub_select
            };
            console.log(query);
            var url = "/sticker_access_report?" + $.param(query);
            window.location = url;
        });

        $('#sticker_request_export_btn_cv').on('click', function() {

            var sub_select = 0;

            if ($('#sticker_sub_select_dropdown').length) {
                sub_select = $('#sticker_sub_select_dropdown').val()
            }

            var query = {
                year: $('#sticker_year_dropdown').val(),
                period: $('#sticker_period_dropdown').val(),
                status: $('#sticker_category_dropdown').val(),
                sub_select: sub_select
            };
            console.log(query);
            var url = "/sticker_access_report_cv?" + $.param(query);
            window.location = url;
        });

        $('#sticker_period_dropdown').on('change', function() {
            toggleStickerSubSelect();
        });
    });

  $(document).ready(function() {
        $('#pdfBtn').click(function() {
            $('#stickerForm').attr('action', '/sticker_export');
            $('#stickerForm').submit();
        });

        $('#excelBtn').click(function() {
            $('#stickerForm').attr('action', '/sticker_export_excel');
            $('#stickerForm').submit();
        });
    });

    $(document).ready(function() {
        let currentDate = new Date();
        let yearDropdown = document.getElementById('hoa_year_dropdown');
        let currentYear = currentDate.getFullYear();

        while (currentYear >= 2022) {
            let yearOption = document.createElement('option');
            yearOption.text = currentYear;
            yearOption.value = currentYear;
            yearDropdown.add(yearOption);
            currentYear -= 1;
        }

        toggleHoaSubSelect = () => {
            var period = $('#hoa_period_dropdown').val();
            html = '';

            if (period == 3) {
                html += `<select class="form-select form-select-sm" id="hoa_sub_select_dropdown" aria-label="Default select example">
                            <option value="1">Jan</option>
                            <option value="2">Feb</option>
                            <option value="3">Mar</option>
                            <option value="4">Apr</option>
                            <option value="5">May</option>
                            <option value="6">Jun</option>
                            <option value="7">Jul</option>
                            <option value="8">Aug</option>
                            <option value="9">Sep</option>
                            <option value="10">Oct</option>
                            <option value="11">Nov</option>
                            <option value="12">Dec</option>
                        </select>`;

                $('#hoa_sub_select').addClass('col-md');
            } else if (period == 4) {
                html += `<select class="form-select form-select-sm" id="hoa_sub_select_dropdown" aria-label="Default select example">
                            <option value="1">Q1</option>
                            <option value="2">Q2</option>
                            <option value="3">Q3</option>
                            <option value="4">Q4</option>
                        </select>`;

                $('#hoa_sub_select').addClass('col-md');
            } else {
                $('#hoa_sub_select').removeClass('col-md');
            }

            if (period == 1 || period == 2) {
                $('#hoa_year_dropdown').parent().removeClass('col-md');
                $('#hoa_year_dropdown').hide();
            } else {
                $('#hoa_year_dropdown').parent().addClass('col-md');
                $('#hoa_year_dropdown').show();
            }

            $('#hoa_sub_select').html(html);
        }

        toggleHoaSubSelect();

        $('#hoa_request_export_btn').on('click', function() {

            var sub_select = 0;

            if ($('#hoa_sub_select_dropdown').length) {
                sub_select = $('#hoa_sub_select_dropdown').val()
            }

            var query = {
                year: $('#hoa_year_dropdown').val(),
                period: $('#hoa_period_dropdown').val(),
                status: $('#hoa_category_dropdown').val(),
                sub_select: sub_select
            };
            console.log(query);
            var url = "/hoa_access_report?" + $.param(query);
            window.location = url;
        });

        $('#hoa_period_dropdown').on('change', function() {
            toggleHoaSubSelect();
        });
    });
</script>

<script>
    $(document).ready(function() {
       // hide #inventory_form
       $('#sticker_form_2').hide();
 
       $('#sticker_period_filter').change(function() {
          $('#sticker_form_2').hide();
 
          // set the value of hidden form with id of period to the value of period_filter
          $('#sticker_period_2').val($(this).val());
          
          if ($(this).val() == 'daily') {
             // set from and to to current date
             let from = $('#sticker_from_2').val('{{ now()->toDateString() }}');
             let to = $('#sticker_to_2').val('{{ now()->toDateString() }}');
 
             return;
          }
 
          if ($(this).val() == 'weekly') {
             // set from and to to current to current week
             let from = $('#sticker_from_2').val('{{ now()->startOfWeek()->toDateString() }}');
             let to = $('#sticker_to_2').val('{{ now()->endOfWeek()->toDateString() }}');
 
             return;
          }
 
          if ($(this).val() == 'monthly') {
             // set from and to to current to current month
             let from = $('#sticker_from_2').val('{{ now()->startOfMonth()->toDateString() }}');
             let to = $('#sticker_to_2').val('{{ now()->endOfMonth()->toDateString() }}');
 
             return;
          }
 
          if ($(this).val() == 'quarterly') {
             // set from and to to current to current quarter
             let from = $('#sticker_from_2').val('{{ now()->startOfQuarter()->toDateString() }}');
             let to = $('#sticker_to_2').val('{{ now()->endOfQuarter()->toDateString() }}');
 
             return;
          }
 
          if ($(this).val() == 'yearly') {
             // set from and to to current to current year
             let from = $('#sticker_from_2').val('{{ now()->startOfYear()->toDateString() }}');
             let to = $('#sticker_to_2').val('{{ now()->endOfYear()->toDateString() }}');
 
             return;
          }
 
          if ($(this).val() == 'custom') {
             // show #inventory_form
             $('#sticker_form_2').show();
 
             return;
          }
       })
    });
</script>
 
<script>
    $(document).ready(function() {
       // hide #inventory_form
       $('#release_form').hide();
 
       $('#release_period_filter').change(function() {
          $('#release_form').hide();
 
          // set the value of hidden form with id of period to the value of period_filter
          $('#release_period').val($(this).val());
          
          if ($(this).val() == 'daily') {
             // set from and to to current date
             let from = $('#release_from').val('{{ now()->toDateString() }}');
             let to = $('#release_to').val('{{ now()->toDateString() }}');
 
             return;
          }
 
          if ($(this).val() == 'weekly') {
             // set from and to to current to current week
             let from = $('#release_from').val('{{ now()->startOfWeek()->toDateString() }}');
             let to = $('#release_to').val('{{ now()->endOfWeek()->toDateString() }}');
 
             return;
          }
 
          if ($(this).val() == 'monthly') {
             // set from and to to current to current month
             let from = $('#release_from').val('{{ now()->startOfMonth()->toDateString() }}');
             let to = $('#release_to').val('{{ now()->endOfMonth()->toDateString() }}');
 
             return;
          }
 
          if ($(this).val() == 'quarterly') {
             // set from and to to current to current quarter
             let from = $('#release_from').val('{{ now()->startOfQuarter()->toDateString() }}');
             let to = $('#release_to').val('{{ now()->endOfQuarter()->toDateString() }}');
 
             return;
          }
 
          if ($(this).val() == 'yearly') {
             // set from and to to current to current year
             let from = $('#release_from').val('{{ now()->startOfYear()->toDateString() }}');
             let to = $('#release_to').val('{{ now()->endOfYear()->toDateString() }}');
 
             return;
          }
 
          if ($(this).val() == 'custom') {
             // show #inventory_form
             $('#release_form').show();
 
             return;
          }
       })
    });
</script>

<script>
    $(document).ready(function() {
       // hide #inventory_form
       $('#detail_form').hide();
 
       $('#detailed_period_filter').change(function() {
          $('#detail_form').hide();
 
          // set the value of hidden form with id of period to the value of period_filter
          $('#detailed_period').val($(this).val());
          
          if ($(this).val() == 'daily') {
             // set from and to to current date
             let from = $('#detail_from').val('{{ now()->toDateString() }}');
             let to = $('#detail_to').val('{{ now()->toDateString() }}');
 
             return;
          }
 
          if ($(this).val() == 'weekly') {
             // set from and to to current to current week
             let from = $('#detail_from').val('{{ now()->startOfWeek()->toDateString() }}');
             let to = $('#detail_to').val('{{ now()->endOfWeek()->toDateString() }}');
 
             return;
          }
 
          if ($(this).val() == 'monthly') {
             // set from and to to current to current month
             let from = $('#detail_from').val('{{ now()->startOfMonth()->toDateString() }}');
             let to = $('#detail_to').val('{{ now()->endOfMonth()->toDateString() }}');
 
             return;
          }
 
          if ($(this).val() == 'quarterly') {
             // set from and to to current to current quarter
             let from = $('#detail_from').val('{{ now()->startOfQuarter()->toDateString() }}');
             let to = $('#detail_to').val('{{ now()->endOfQuarter()->toDateString() }}');
 
             return;
          }
 
          if ($(this).val() == 'yearly') {
             // set from and to to current to current year
             let from = $('#detail_from').val('{{ now()->startOfYear()->toDateString() }}');
             let to = $('#detail_to').val('{{ now()->endOfYear()->toDateString() }}');
 
             return;
          }
 
          if ($(this).val() == 'custom') {
             // show #inventory_form
             $('#detail_form').show();
 
             return;
          }
       })
    });
</script>

@if (session('danger'))
    <script>
        Swal.fire({
            title: "{{ session('danger') }}",
            icon: 'error',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Okay'
        });
    </script>
@endif

@endsection