@extends('layouts.main-app')

@section('title', 'SRS Report')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
@section('content')
<div class="container-fluid mt-2  mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card myCard">
                <div class="card-body p-3">
                    <div class="mt-4">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-md">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th scope="col" class="text-center">Report Name</th>
                                        <th scope="col" class="text-center">Period</th>
                                        <th scope="col" class="text-center">Filters</th>
                                        <th scope="col" class="text-center">Download</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr valign="middle">
                                        <th class="text-center" scope="row">SRS HOA</th>
                                        <td class="">
                                            <div>
                                                <label for="" class="form-label">From: </label>
                                                <input name="from" type="date" value="<?php echo date('Y-m-d'); ?>" id="request_hoa_from" class="form-control">
                                            </div>
                                            <div class=" mt-3">
                                                <label for="" class="form-label">To: </label>
                                                <input name="to" type="date" value="<?php echo date('Y-m-d'); ?>" id="request_hoa_to" class="form-control">
                                            </div>
                                        </td>
                                        <td>
                                        <td class="text-center" style="cursor:pointer">
                                            <a role="button" id="srs_hoa_export_btn">
                                                <i class="far fa-file-excel fa-2x" style="color:#808080"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr valign="middle">
                                        <th class="text-center" scope="row">SRS View Tickets</th>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center" style="cursor:pointer">
                                            <form id="srs_view_tickets_form" action="/srs/export/view_tickets" method="POST">
                                                @csrf
                                            </form>
                                            <a role="button" id="srs_view_tickets_export_btn">
                                                <i class="fa-solid fa-file-csv fa-2x" style="color: #11753f"></i>
                                            </a>
                                        </td>
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
                                                <option selected value="0">All Cashier</option>
                                                @foreach($users as $user)
                                                @if($user->name != 'Admin' && $user->name != 'Cashier')
                                                <option  value="{{$user->id}}">{{$user->name}}</option>
                                                @endif
                                                @endforeach

                                            </select>
                                        </td>
                                        <td class="text-center" style="cursor:pointer">
                                            <a role="button" id="sticker_request_export_btn"> <i class='bx bxs-file-pdf icon' style="font-size: 2em; color: #f40f00;"></i></a>

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
                                                <button id="pdfBtn" type="button" style="border: none;">
                                                    <i class='bx bxs-file-pdf icon' style="font-size: 2em; color: #f40f00;"></i>
                                                </button>
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

                                    <!-- New patch 21-03-2023 by: Joshua Vino -->
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
                                     <!--End  New patch 21-03-2023 by: Joshua Vino -->

                                    <tr valign="middle">
                                        <form action="/price_export" method="POST">
                                            @csrf
                                            <th class="text-center" scope="row">Pricing Report</th>
                                            <td class="">

                                            </td>
                                            <td>

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
<script>
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

</script>

@endsection