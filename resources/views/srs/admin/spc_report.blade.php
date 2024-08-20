@extends('layouts.main-app')

@section('title', 'SPC v2.0 Reports')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
@section('content')
<div class="container-fluid mt-2  mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card myCard">

                <div class="card-body p-5">
                    <div class="text-start h3 fw-bold"><i class="fas fa-file-excel"></i> SPC v2.0 Reports</div>
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
                                                <option selected value="0">All Cashier</option>
                                                <!-- <option value="1">CR</option>
                                                <option value="2">OR</option> -->
                                                @foreach($users as $user)
                                                @if($user->name != 'Admin' && $user->name != 'Cashier')
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                                @endif
                                                @endforeach

                                            </select>
                                        </td>
                                        <td class="text-center" style="cursor:pointer">
                                            <!-- <a role="button" id="sticker_request_export_btn"> <i class='bx bxs-file-pdf icon' style="font-size: 2em; color: #f40f00;"></i></a> -->

                                            <a role="button" id="sticker_request_export_btn_cv"><i class="far fa-file-excel text-success fa-2x" style="color:#808080"></i></a>
                                        </td>
                                    </tr>

                                    <tr valign="middle">


                                        <th class="text-center" scope="row">Sticker Report (OR Only)</th>
                                        <td>
                                            <div class="row g-2">
                                                <div class="col-md">
                                                    <select class="form-select form-select-sm" id="sticker_or_period_dropdown" aria-label="Default select example">
                                                        <option value="1">Daily</option>
                                                        <option value="2">Weekly</option>
                                                        <option value="3">Monthly</option>
                                                        <option value="4">Quarterly</option>
                                                        <option value="5">Annual</option>
                                                    </select>
                                                </div>
                                                <div class="col-md">
                                                    <select class="form-select form-select-sm" id="sticker_or_year_dropdown">
                                                    </select>
                                                </div>
                                                <div id="sticker_or_sub_select">

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <select id="sticker_or_category_dropdown" name="agent_id" class="form-select form-select-sm" aria-label="Default select example">
                                                <option selected disabled>Select Agent</option>
                                                <option selected value="0">All Cashier</option>
                                                <!-- <option value="1">CR</option>
                                                <option value="2">OR</option> -->
                                                @foreach($users as $user)
                                                @if($user->name != 'Admin' && $user->name != 'Cashier')
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                                @endif
                                                @endforeach

                                            </select>
                                        </td>
                                        <td class="text-center" style="cursor:pointer">

                                            <a role="button" id="sticker_or_request_export_btn_cv"><i class="far fa-file-excel text-success fa-2x" style="color:#808080"></i></a>
                                        </td>
                                    </tr>

                                    <tr valign="middle">


                                        <th class="text-center" scope="row">Sticker Report (CR Only)</th>
                                        <td>
                                            <div class="row g-2">
                                                <div class="col-md">
                                                    <select class="form-select form-select-sm" id="sticker_cr_period_dropdown" aria-label="Default select example">
                                                        <option value="1">Daily</option>
                                                        <option value="2">Weekly</option>
                                                        <option value="3">Monthly</option>
                                                        <option value="4">Quarterly</option>
                                                        <option value="5">Annual</option>
                                                    </select>
                                                </div>
                                                <div class="col-md">
                                                    <select class="form-select form-select-sm" id="sticker_cr_year_dropdown">
                                                    </select>
                                                </div>
                                                <div id="sticker_cr_sub_select">

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <select id="sticker_cr_category_dropdown" name="agent_id" class="form-select form-select-sm" aria-label="Default select example">
                                                <option selected disabled>Select Agent</option>
                                                <option selected value="0">All Cashier</option>
                                                <!-- <option value="1">CR</option>
                                                <option value="2">OR</option> -->
                                                @foreach($users as $user)
                                                @if($user->name != 'Admin' && $user->name != 'Cashier')
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                                @endif
                                                @endforeach

                                            </select>
                                        </td>
                                        <td class="text-center" style="cursor:pointer">
                                            <!-- <a role="button" id="sticker_request_export_btn"> <i class='bx bxs-file-pdf icon' style="font-size: 2em; color: #f40f00;"></i></a> -->

                                            <a role="button" id="sticker_cr_request_export_btn_cv"><i class="far fa-file-excel text-success fa-2x" style="color:#808080"></i></a>
                                        </td>
                                    </tr>
                                    <!-- 

                                    <tr valign="middle">
                                        <form action="/sticker_export" method="POST">
                                            @csrf
                                            <th class="text-center" scope="row">Sticker Reports</th>
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
                                            <td> <select id="sticker_category_dropdown" name="status" class="form-select form-select-sm" aria-label="Default select example">
                                                    <option selected disabled>Select Agent</option>
                                                    <option value="0">All Cashier</option>
                                                    @foreach($users as $user)
                                                    @if($user->name != 'Admin' && $user->name != 'Cashier')
                                                    <option selected value="{{$user->id}}">{{$user->name}}</option>
                                                    @endif
                                                    @endforeach

                                                </select></td>
                                            <td class="text-center" style="cursor:pointer"> <button type="submit" style="border: none;"> <i class='bx bxs-file-pdf icon' style="font-size: 2em; color: #f40f00;"></i></button></td>
                                        </form>
                                    </tr> -->


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
                                                    <option selected value="0">All Cashier</option>
                                                    @foreach($users as $user)
                                                    @if($user->name != 'Admin' && $user->name != 'Cashier')
                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center" style="cursor:pointer">
                                                <button id="excelBtn" type="button" style="border: none;">
                                                    <i class="far fa-file-excel text-success fa-2x" style="color:#808080"></i>
                                                </button>
                                            </td>
                                        </form>
                                    </tr>

                                    <tr valign="middle">
                                        <form action="/sticker_filter_cv_or" method="POST">
                                            @csrf
                                            <th class="text-center" scope="row">Sticker Reports (DATE FILTER OR ONLY)</th>
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
                                                    <option selected value="0">All Cashier</option>
                                                    @foreach($users as $user)
                                                    @if($user->name != 'Admin' && $user->name != 'Cashier')
                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                    @endif
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


                                    <tr valign="middle">
                                        <form action="/sticker_filter_cv_cr" method="POST">
                                            @csrf
                                            <th class="text-center" scope="row">Sticker Reports (DATE FILTER CR ONLY)</th>
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
                                                    <option selected value="0">All Cashier</option>
                                                    @foreach($users as $user)
                                                    @if($user->name != 'Admin' && $user->name != 'Cashier')
                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                    @endif
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


                                    <tr valign="middle">
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

                                        <td class="text-center" style="cursor:pointer">
                                            <a role="button" id="hoa_request_export_btn"> <i class='bx bxs-file-pdf icon' style="font-size: 2em; color: #f40f00;"></i></a>
                                        </td>


                                    </tr>
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
                                        <form action="/invoice_admin_export" method="POST">
                                            @csrf
                                            <th class="text-center" scope="row">Invoice (Admin)</th>
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


                                            </td>
                                            <td class="text-center" style="cursor:pointer"> <button type="submit" style="border: none;"><i class="far fa-file-excel fa-2x" style="color:#808080"></i></button></td>
                                        </form>
                                    </tr>


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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

            window.location = url;
        });

        $('#redtag_export_btn').on('click', function() {
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
            var url = "/sticker_access_report_spc_cv?" + $.param(query);
            window.location = url;
        });

        $('#sticker_period_dropdown').on('change', function() {
            toggleStickerSubSelect();
        });
    });

    $(document).ready(function() {
        let currentDate = new Date();
        let yearDropdown = document.getElementById('sticker_or_year_dropdown');
        let currentYear = currentDate.getFullYear();

        while (currentYear >= 2022) {
            let yearOption = document.createElement('option');
            yearOption.text = currentYear;
            yearOption.value = currentYear;
            yearDropdown.add(yearOption);
            currentYear -= 1;
        }

        toggleStickerOrSubSelect = () => {
            var period = $('#sticker_or_period_dropdown').val();
            html = '';

            if (period == 3) {
                html += `<select class="form-select form-select-sm" id="sticker_or_sub_select_dropdown" aria-label="Default select example">
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

                $('#sticker_or_sub_select').addClass('col-md');
            } else if (period == 4) {
                html += `<select class="form-select form-select-sm" id="sticker_or_sub_select_dropdown" aria-label="Default select example">
                            <option value="1">Q1</option>
                            <option value="2">Q2</option>
                            <option value="3">Q3</option>
                            <option value="4">Q4</option>
                        </select>`;

                $('#sticker_or_sub_select').addClass('col-md');
            } else {
                $('#sticker_or_sub_select').removeClass('col-md');
            }

            if (period == 1 || period == 2) {
                $('#sticker_or_year_dropdown').parent().removeClass('col-md');
                $('#sticker_or_year_dropdown').hide();
            } else {
                $('#sticker_or_year_dropdown').parent().addClass('col-md');
                $('#sticker_or_year_dropdown').show();
            }

            $('#sticker_or_sub_select').html(html);
        }

        toggleStickerOrSubSelect();

        $('#sticker_or_request_export_btn').on('click', function() {

            var sub_select = 0;

            if ($('#sticker_or_sub_select_dropdown').length) {
                sub_select = $('#sticker_or_sub_select_dropdown').val()
            }

            var query = {
                year: $('#sticker_or_year_dropdown').val(),
                period: $('#sticker_or_period_dropdown').val(),
                status: $('#sticker_or_category_dropdown').val(),
                sub_select: sub_select
            };
            console.log(query);
            var url = "/sticker_or_access_report?" + $.param(query);
            window.location = url;
        });

        $('#sticker_or_request_export_btn_cv').on('click', function() {

            var sub_select = 0;

            if ($('#sticker_or_sub_select_dropdown').length) {
                sub_select = $('#sticker_or_sub_select_dropdown').val()
            }

            var query = {
                year: $('#sticker_or_year_dropdown').val(),
                period: $('#sticker_or_period_dropdown').val(),
                status: $('#sticker_or_category_dropdown').val(),
                sub_select: sub_select
            };
            console.log(query);
            var url = "/sticker_or_access_report_cv?" + $.param(query);
            window.location = url;
        });

        $('#sticker_or_period_dropdown').on('change', function() {
            toggleStickerOrSubSelect();
        });
    });


    $(document).ready(function() {
        let currentDate = new Date();
        let yearDropdown = document.getElementById('sticker_cr_year_dropdown');
        let currentYear = currentDate.getFullYear();

        while (currentYear >= 2022) {
            let yearOption = document.createElement('option');
            yearOption.text = currentYear;
            yearOption.value = currentYear;
            yearDropdown.add(yearOption);
            currentYear -= 1;
        }

        toggleStickerCrSubSelect = () => {
            var period = $('#sticker_cr_period_dropdown').val();
            html = '';

            if (period == 3) {
                html += `<select class="form-select form-select-sm" id="sticker_cr_sub_select_dropdown" aria-label="Default select example">
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

                $('#sticker_cr_sub_select').addClass('col-md');
            } else if (period == 4) {
                html += `<select class="form-select form-select-sm" id="sticker_cr_sub_select_dropdown" aria-label="Default select example">
                            <option value="1">Q1</option>
                            <option value="2">Q2</option>
                            <option value="3">Q3</option>
                            <option value="4">Q4</option>
                        </select>`;

                $('#sticker_cr_sub_select').addClass('col-md');
            } else {
                $('#sticker_cr_sub_select').removeClass('col-md');
            }

            if (period == 1 || period == 2) {
                $('#sticker_cr_year_dropdown').parent().removeClass('col-md');
                $('#sticker_cr_year_dropdown').hide();
            } else {
                $('#sticker_cr_year_dropdown').parent().addClass('col-md');
                $('#sticker_cr_year_dropdown').show();
            }

            $('#sticker_cr_sub_select').html(html);
        }

        toggleStickerCrSubSelect();

        $('#sticker_cr_request_export_btn').on('click', function() {

            var sub_select = 0;

            if ($('#sticker_cr_sub_select_dropdown').length) {
                sub_select = $('#sticker_cr_sub_select_dropdown').val()
            }

            var query = {
                year: $('#sticker_cr_year_dropdown').val(),
                period: $('#sticker_cr_period_dropdown').val(),
                status: $('#sticker_cr_category_dropdown').val(),
                sub_select: sub_select
            };
            console.log(query);
            var url = "/sticker_cr_access_report?" + $.param(query);
            window.location = url;
        });

        $('#sticker_cr_request_export_btn_cv').on('click', function() {

            var sub_select = 0;

            if ($('#sticker_cr_sub_select_dropdown').length) {
                sub_select = $('#sticker_cr_sub_select_dropdown').val()
            }

            var query = {
                year: $('#sticker_cr_year_dropdown').val(),
                period: $('#sticker_cr_period_dropdown').val(),
                status: $('#sticker_cr_category_dropdown').val(),
                sub_select: sub_select
            };
            console.log(query);
            var url = "/sticker_cr_access_report_cv?" + $.param(query);
            window.location = url;
        });

        $('#sticker_cr_period_dropdown').on('change', function() {
            toggleStickerCrSubSelect();
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
        $('#pdfBtn').click(function() {
            $('#stickerForm').attr('action', '/sticker_export');
            $('#stickerForm').submit();
        });

        $('#excelBtn').click(function() {
            $('#stickerForm').attr('action', '/sticker_export_excel');
            $('#stickerForm').submit();
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script>
    $(document).ready(function() {
        // Hide the buttons initially
        $('#sticker_request_export_btn_cv').hide();
        $('#excelBtn').hide();

        // Check the time every second
        setInterval(function() {
            // Get the current hour
            var currentHour = new Date().getHours();

            // Check if the current time is between 7am-8am or 5pm-6pm
            if ((currentHour >= 7 && currentHour < 8) || (currentHour >= 17 && currentHour < 18)) {
                // Show the buttons
                $('#sticker_request_export_btn_cv').show();
                $('#excelBtn').show();
            } else {
                // Hide the buttons
                $('#sticker_request_export_btn_cv').hide();
                $('#excelBtn').hide();
            }
        }, 1000); // Repeat every second
    });
</script> -->

@endsection