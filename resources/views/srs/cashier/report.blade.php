@extends('layouts.main-app')

@section('title', 'Reports')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
@section('content')
<div class="container-fluid mt-2  mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card myCard">
                <div class="card-body p-5">
                    <div class="text-start h3 fw-bold"><i class="fas fa-file-excel"></i> Cashier Reports</div>
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
                                    <!-- <tr valign="middle">
                                        <form id="stickerForm2" action="/sticker_export_excel" method="POST">
                                            @csrf
                                            <th class="text-center" scope="row">Sticker Reports (SPC 2.0)</th>
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
                                                    <option selected disabled>Select Agent</option>
                                                    <option selected value="0">All Cashier</option>
                                                    @foreach($cashiers as $user)
                                                    @if($user->name != 'Admin' && $user->name != 'Cashier')
                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <select id="sticker_or_cr_dropdown" name="or_cr_select" class="form-select mt-1">
                                                    <option disabled>Select Receipt Type</option>
                                                    <option value="all" selected="">ALL (OR - CR)</option>
                                                    <option value="or">OR</option>
                                                    <option value="cr">CR</option>
                                                </select>
                                            </td>
                                            <td class="text-center" style="cursor:pointer">
                                                <button id="excelBtn2" type="submit" style="border: none;">
                                                    <i class="far fa-file-excel text-success fa-2x" style="color:#808080"></i>
                                                </button>
                                            </td>
                                        </form>
                                    </tr> -->

                                    <tr valign="middle">
                                       <form id="sticker_reports_form" action="/sticker_export_excel_2" method="POST">
                                          @csrf
                                          <th class="text-center" scope="row">Sticker Reports (SPC 2.0)</th>
                                          <td>
                                             <div class="row">
                                                <div class="col-12">
                                                   <select id="sticker_period_filter" name="sticker_period_filter" class="form-select" aria-label="Default select example">
                                                      <option selected value="">Select Period</option>
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
                                             <select id="user_filter" name="user_filter" class="form-select" aria-label="Default select example">
                                                <option selected value="">Select Cashier</option>
                                                @if(auth()->user()->role_id == '1')
                                                  <option value="{{ auth()->id() }}">{{ auth()->user()->name }}</option>
                                                @else
                                                <option value="0">All</option>
                                                @foreach($cashiers as $user)
                                                   <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                                @endif
                                             </select>
                                              <select id="sticker_or_cr_dropdown_2" name="or_cr_select" class="form-select mt-1">
                                                    <option disabled>Select Receipt Type</option>
                                                    <option value="all" selected="">ALL (OR - CR)</option>
                                                    <option value="or">OR</option>
                                                    <option value="cr">CR</option>
                                                </select>
                                          </td>
                                          <td class="text-center" style="cursor:pointer">
                                             @if(auth()->user()->role_id != 1 || $reportReady)
                                                <button type="submit"
                                                   style="border: none;"><i class="far fa-file-excel fa-2x"
                                                      style="color:#808080"></i>
                                                </button>
                                             @endif
                                          </td>
                                       </form>
                                    </tr>

                                    @unless(auth()->user()->role_id == '1')
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
                                    @endunless 

                                    <tr valign="middle">
                                       <form action="{{ route('sims.released_items_export') }}" method="POST">
                                          @csrf
                                          <th class="text-center" scope="row">Released Stickers (Only in 2.0)</th>
                                          <td>
                                             <div class="row">
                                                <div class="col-12">
                                                   <select id="release_period_filter" name="release_period_filter" class="form-select" aria-label="Default select example">
                                                      <option selected value="">Select Period</option>
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
                                                @if(auth()->user()->role_id == '1')
                                                  <option value="{{ auth()->id() }}">{{ auth()->user()->name }}</option>
                                                @else
                                                <option value="0">All</option>
                                                @foreach($cashiers as $user)
                                                   <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                                @endif
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
                                                      <option selected value="">Select Period</option>
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
                                                @if(auth()->user()->role_id == '1')
                                                <option value="{{ auth()->id() }}">{{ auth()->user()->name }}</option>
                                                @else
                                                <option value="0">All</option>
                                                @foreach($cashiers as $user)
                                                   <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                                @endif
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

                                        <th class="text-center" scope="row">Sticker Report</th>
                                        <td>
                                            <div class="row g-2">
                                                <div class="col-md">
                                                    <select class="form-select" id="sticker_period_dropdown" aria-label="Default select example">
                                                        <option value="1">Daily</option>
                                                        <option value="2">Weekly</option>
                                                        <option value="3">Monthly</option>
                                                        <option value="4">Quarterly</option>
                                                        <option value="5">Annual</option>
                                                    </select>
                                                </div>
                                                <div class="col-md">
                                                    <select class="form-select" id="sticker_year_dropdown">
                                                    </select>
                                                </div>
                                                <div id="sticker_sub_select">

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <select id="sticker_category_dropdown" name="agent_id" class="form-select" aria-label="Default select example">
                                                @if(auth()->user()->role_id != 1)
                                                <option selected disabled>Select Agent</option>
                                                @foreach($cashiers as $cashier)
                                                <option selected value="{{$cashier->id}}">{{$cashier->name}}</option>
                                                @endforeach
                                                @else
                                                <option selected value="{{ auth()->user()->id}}">{{auth()->user()->name}}</option>
                                                @endif
                                            </select>
                                        </td>
                                        <td class="text-center" style="cursor:pointer">
                                          <!--   <a role="button" id="sticker_request_export_btn"> <i class='bx bxs-file-pdf icon' style="font-size: 2em; color: #f40f00;"></i></a> -->

                                            <a role="button" id="sticker_request_export_btn_cv"><i class="far fa-file-excel text-success fa-2x" style="color:#808080"></i></a>
                                        </td>

                                    </tr>


                                    <tr valign="middle">
                                        <form id="stickerForm" method="POST">
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
                                            <td>
                                                <select id="sticker_category_dropdown" name="status" class="form-select" aria-label="Default select example">
                                                    @if(auth()->user()->role_id != 1)
                                                    <option selected disabled>Select Agent</option>
                                                    @foreach($cashiers as $cashier)
                                                    @if($cashier->name != 'Admin' && $cashier->name != 'Cashier')
                                                    <option selected value="{{$cashier->id}}">{{$cashier->name}}</option>
                                                    @endif
                                                    @endforeach
                                                    @else
                                                    <option selected value="{{ auth()->user()->id}}">{{auth()->user()->name}}</option>
                                                    @endif
                                                </select>
                                            </td>
                                            <td class="text-center" style="cursor:pointer">
                                              <!--   <button id="pdfBtn" type="button" style="border: none;">
                                                    <i class='bx bxs-file-pdf icon' style="font-size: 2em; color: #f40f00;"></i>
                                                </button> -->
                                                <button id="excelBtn" type="button" style="border: none;">
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
<script src="https://code.jquery.com/jquery-3.6.3.slim.min.js" integrity="sha256-ZwqZIVdD3iXNyGHbSYdsmWP//UBokj2FHAxKuSBKDSo=" crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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

<script>
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
</script>

<script>
    // $(document).ready(function() {
    //     // $('#pdfBtn').click(function() {
    //     //     $('#stickerForm').attr('action', '/sticker_export');
    //     //     $('#stickerForm').submit();
    //     // });

    //     $('#excelBtn2').click(function() {
    //         $('#stickerForm2').attr('action', '/sticker_export_excel');
    //         $('#stickerForm2').submit();
    //     });
    // });
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

{{-- @if($reportReady)
   <script>
      let today = '{{ $today }}';

      Swal.fire({
         title: 'Report is ready for download!',
         text: 'Your report for ' + today + ' is ready for download! Remember it can be downloaded only once daily.',
         icon: 'info',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Download Now',
         cancelButtonText: 'Download Later'
      }).then((result) => {
         if (result.isConfirmed) {
            $('#sticker_period_filter').val('daily').trigger('change');
            $('#user_filter').val('{{ auth()->id() }}').trigger('change');

            $('#sticker_reports_form').submit();
         }
      });
   </script>
@endif --}}

@endsection