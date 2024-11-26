@php
    $count = 0;
@endphp

<div class="d-flex justify-content-end ">
    <button id="btnShow" class="btn btn-primary btn-md mt-3 mb-3"> <i class="fas fa-plus-circle"></i> Create New
        Billing 3.0</button>
</div>

<div class="card mt-4" id="new_invoice" style="display:none">
    <div class="card-header  p-3" style="background-color: #1e81b0;">
        <div class="d-flex justify-content-between">
            <div class="h6 fw-bold text-white"><i class="fas fa-hand-holding-usd"></i> Billing 3.0
            </div>
            <div class="h6 text-white">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" checked>
                    <label class="form-check-label" for="flexCheckIndeterminate">
                        Bulk
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">


        <form id="invoice_submit" onsubmit="" action="{{ route('scp_invoice3') }}" method="POST">
            @csrf
            <input type="hidden" value="<?= $crms_account[0]->crm_id ?>" name="crm_id">
            <input type="hidden" value="<?= $crms_account[0]->account_id ?>" name="customer_id">
            <input type="hidden" value="<?= $crms_account[0]->category_id ?>" name="category_id">

            <input type="hidden" name="billed_to" value="" id="billedTo">

            <div class="row p-4">

                <div class="col-md-12">


                    <div class="card myCard1">

                        <div class="card-body">
                            <div class="h5 p-2 "><i class="fas fa-hand-holding-usd"></i> Billing 3.0
                            </div>
                            <hr>
                            <div id="onBulk">
                                <div class="row">
                                    <div class="col-md-3">
                                        <a style="text-decoration: underline; cursor:pointer"
                                            class="fw-bold text-primary" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal7">
                                            Renewed Vehicle Stickers:
                                        </a>
                                    </div>
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3"></div>


                                    <!-- <div class="col-md-3">

          <label for="Sub Category " class="fw-bold">Amounts: </label>

          <select name="discount" id="amount" class="form-select mt-2" aria-label="Default select example" required>

            <option value="0" selected>Non-Tax</option>
         <option value="1">Exclusive Tax</option> -->
                                    </select>
                                </div>
                            </div>
                            @if (auth()->user()->email == 'test@test.com')
                                <div class="d-flex justify-content-end mt-4 mb-3">
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Billing Options
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item"
                                                    href="/spc/view-scp-v2/<?= $crmid ?>/<?= $customer_id ?>">SPC
                                                    V2 BILLING</a></li>

                                        </ul>
                                    </div>
                                </div>
                            @endif


                            <div class="row">
                                <input type="hidden" id="renewed_count" value="<?= count($renewedCar) ?>">
                                <input type="hidden" id="renewed_count_motor" value="<?= count($renewedMotor) ?>">

                                <input type="hidden" id="current_crm" name="current_crm"
                                    value="{{ $crms_account[0]->crm_id }}">
                                <input type="hidden" id="crm_id1" name="crm_id "
                                    value="<?= $crms_account[0]->account_id ?>">
                                @if (!$req)
                                    <input type="hidden" name="srs_request_id" value="{{ $req }}">
                                @endif
                                <div class="table-responsive p-4 ">
                                    <div class="d-flex">
                                        <div class="h5 fw-bold mb-4">Request No: </div> <span
                                            class="ms-2">{{ $req }}</span>
                                    </div>
                                    <table class="table table-bordered table-hover " style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th class="text-center">Plate No.</th>
                                                <th class="text-center">Count</th>
                                                <th class="text-center">Car Details</th>
                                                <th class="text-center">Old Sticker</th>
                                                <th class="text-center text-nowrap">New Sticker
                                                </th>
                                                <th class="text-center">Selling Price</th>
                                                <th class="text-center">Base Price</th>
                                                <th class="text-center">Security</th>
                                                <th class="text-center">RMF | EPF</th>
                                                <th class="text-center">Category</th>
                                            </tr>
                                        </thead>

                                        <tbody id="row-count">

                                            {{-- @if ($req)
        <?php
        $x = 0;
        $amount = 0;
        $amounts = [];
        $count = 0;
        ?>
        @foreach ($vehicles as $vehicle) <tr id="row-1">
          @if ($req == $vehicle->srs_request_id)
          <?php

          $x++;
          $count++;
          ?>

          <td class="vehicle-rows" id="row-{{$x}}-count">
            <?= $count ?>
            <?php
            array_push($amounts, \App\Http\Controllers\CRMController::getAmount($crms[0]->customer_id, $x + count($renewed), $vehicle->type));
            $amount = array_sum($amounts);
            ?>
          </td>
          <td id="row-{{$x}}-option">
            {{$vehicle->plate_no}}

          </td>
          <td id="row-{{$x}}-details"> {{$vehicle->brand}} , {{$vehicle->series}} , {{$vehicle->year_model}} , {{$vehicle->color}} , {{$vehicle->type}}</td>
          <td id="row-{{$x}}-old-sticker">{{$vehicle->old_sticker_no}}</td>
          <td id=" row-{{$x}}-new-sticker"><input type="text" class="form-control" name="new_sticker[]" maxlength="10" required=""></td>
          <td id="row-{{$x}}-amount"> {{ \App\Http\Controllers\CrmController::getAmount($crms[0]->customer_id, $x + count($renewed), $vehicle->type)}}</td>
          <input type="hidden" id="vehicle_id_{{$x}}" value="<?= $vehicle->id ?>" name="vehicle_id[]" multiple="multiple">
          <input type="hidden" id="vehicle_price_{{$x}}" name="price[]" value="{{ \App\Http\Controllers\CrmController::getAmount($crms[0]->customer_id, $x + count($renewed), $vehicle->type)}}" multiple="multiple">
          <input type="hidden" value="{{$vehicle->srs_request_id}}" name="srs_request_id">
        </tr>

          @endif
        @endforeach

        @else --}}

                                            <tr id="row-1">
                                                <td class="vehicle-rows" id="row-1-count">

                                                </td>
                                                <td id="row-1-option">
                                                    <select class="form-select form-select-sm plate-options"
                                                        aria-label=".form-select-sm example">
                                                    </select>
                                                </td>
                                                <td id="row-1-vehicle-count"></td>
                                                <td id="row-1-details"></td>
                                                <td id="row-1-old-sticker"></td>
                                                <td id="row-1-new-sticker"></td>
                                                <td class="text-center" id="row-1-sellingPrice">
                                                </td>
                                                <td class="text-center" id="row-1-basePrice"></td>
                                                <td class="text-center" id="row-1-secF"></td>
                                                <td class="text-center" id="row-1-rmF"></td>
                                                <td id="row-1-cat"></td>
                                                <input type="hidden" id="vehicle_id_1" value=""
                                                    name="vehicle_id[]" multiple="multiple">
                                                <input type="hidden" name="price[]" id="vehicle_price_1"
                                                    value="" multiple="multiple">
                                                <input type="hidden" name="basePrice[]" id="base_price_1"
                                                    value="" multiple="multiple">
                                                <input type="hidden" name="security[]" id="security_1"
                                                    value="" multiple="multiple">
                                                <input type="hidden" name="rmf[]" id="rmf_1" value=""
                                                    multiple="multiple">
                                                <input type="hidden" name="row_vat_sales[]" id="row_vat_sales1"
                                                    value="" multiple="multiple">
                                                <input type="hidden" name="row_vat[]" id="row_vat1" value=""
                                                    multiple="multiple">
                                                <input type="hidden" id="vehicle_type_1" value=""
                                                    name="vehicle_type" multiple="multiple">

                                            </tr>
                                            <?php
        for ($i = 2; $i <= 5; $i++) {
        ?>



                                            <tr id="row-<?= $i ?>">
                                                <td class="vehicle-rows" id="row-<?= $i ?>-count">

                                                </td>
                                                <td id="row-<?= $i ?>-option">

                                                </td>
                                                <td id="row-<?= $i ?>-vehicle-count"></td>
                                                <td id="row-<?= $i ?>-details"></td>
                                                <td id="row-<?= $i ?>-old-sticker"></td>
                                                <td id="row-<?= $i ?>-new-sticker"></td>
                                                <td class="text-center" id="row-<?= $i ?>-sellingPrice"></td>
                                                <td class="text-center" id="row-<?= $i ?>-basePrice"></td>
                                                <td class="text-center" id="row-<?= $i ?>-secF">
                                                </td>
                                                <td class="text-center" id="row-<?= $i ?>-rmF">
                                                </td>
                                                <td id="row-<?= $i ?>-cat"></td>
                                                <input type="hidden" id="vehicle_id_<?= $i ?>" value=""
                                                    name="vehicle_id[]" multiple="multiple">
                                                <input type="hidden" name="price[]" id="vehicle_price_<?= $i ?>"
                                                    value="" multiple="multiple">
                                                <input type="hidden" name="basePrice[]" id="base_price_<?= $i ?>"
                                                    value="" multiple="multiple">
                                                <input type="hidden" name="security[]" id="security_<?= $i ?>"
                                                    value="" multiple="multiple">
                                                <input type="hidden" name="rmf[]" id="rmf_<?= $i ?>"
                                                    value="" multiple="multiple">
                                                <input type="hidden" name="row_vat_sales[]"
                                                    id="row_vat_sales<?= $i ?>" value="" multiple="multiple">
                                                <input type="hidden" name="row_vat[]" id="row_vat<?= $i ?>"
                                                    value="" multiple="multiple">
                                                <input type="hidden" id="vehicle_type_<?= $i ?>" value=""
                                                    name="" multiple="multiple">

                                            </tr>

                                            <?php
        }

        ?>
                                            {{-- @endif --}}
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="card myCard2" style="border-radius: 10px;">
                                                <div class="card-body">
                                                    <div class=" h5">
                                                        <span class="text-muted fw-bold h5">Account
                                                            ID: </span> <span
                                                            class="text-muted">{{ $crms_account[0]->account_id }}</span>
                                                    </div>
                                                    <hr>
                                                    <div>
                                                        <span class="text-muted  "><span class="fw-bold"><i
                                                                    class="far fa-user"></i> Name:
                                                            </span>

                                                            {{-- For Patch 11/21/24 --}}
                                                            <select class="form-select mt-3" id="vehicleOwner" name="vehicleOwner">
                                                                <option value="">Owner</option>
                                                                @foreach ($vehicle_owners as $vehicle_owner)
                                                                    <option value="{{ $vehicle_owner->firstname }} {{ $vehicle_owner->middlename }} {{ $vehicle_owner->lastname }}">
                                                                        {{ $vehicle_owner->firstname }} {{ $vehicle_owner->middlename }} {{ $vehicle_owner->lastname }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            {{-- End For Patch --}}
                                                    </div>
                                                    <div class="h6 mt-3">
                                                        <span class="fw-bold text-muted">Account
                                                            Status:

                                                            <?php
                if ($crms_account[0]->status == 1) {
                ?>
                                                            <span class="badge text-bg-success">Active</span>
                                                            <?php
                } elseif ($crms_account[0]->status == 2) {
                ?>
                                                            <span class="badge text-bg-warning">Inactive</span>
                                                            <?php
                } elseif ($crms_account[0]->status == 3) {
                ?>
                                                            <span class="badge text-bg-danger">Suspended</span>
                                                            <?php
                } elseif ($crms_account[0]->status == 4) {
                ?>
                                                            <span class="badge text-bg-danger">Banned</span>
                                                            <?php
                }
                ?>
                                                        </span>
                                                    </div>
                                                    <div class="h6 mt-3 text-muted">
                                                        <span class="fw-bold"><i class="fas fa-calendar"></i> Date
                                                            Registered:</span><?= ' [' .
                                                            date(
                                                            'd M,
                                                            Y (D) h:i a',
                                                            strtotime($crms_account[0]->created_at),
                                                            ) .
                                                            ']' ?>
                                                    </div>
                                                    <div class="h6 mt-3 text-muted">
                                                        <span class="fw-bold"><i class="fa fa-tag"></i> Red Tag
                                                            Information:</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="card myCard2" style="border-radius: 10px;">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-end">
                                                        <span class="fw-bold ms-3">Total: </span>
                                                        <span id="totalAmm" class="ms-2">
                                                            <?= $amount ?? 0.0 ?></span>
                                                    </div>

                                                    <div class="d-flex justify-content-between mt-3">
                                                        <div class="input-group input-group-sm"
                                                            style="max-width: 240px;">
                                                            <span class="input-group-text" id="inputGroup-sizing-sm">
                                                                <small>Discount</small>
                                                            </span>
                                                            <button type="button"
                                                                class="btn btn-outline-secondary dropdown-toggle-split"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <span class="dropdown-text">%</span>
                                                                <span class="visually-hidden">Toggle
                                                                    Dropdown</span>
                                                            </button>
                                                            <ul class="dropdown-menu" id="switch_discount">
                                                                <li><a class="dropdown-item disabled" type="button"
                                                                        id="set_percent">Percent
                                                                </li>
                                                                <li><a class="dropdown-item" type="button"
                                                                        id="set_amount">Amount</a>
                                                                </li>
                                                            </ul>
                                                            <select id="discountP" class="form-select form-select-sm">
                                                                <option value="0">0%</option>
                                                                <option value="10">10%</option>
                                                                <option value="30">30%</option>
                                                                <option value="50">50%</option>
                                                            </select>
                                                            <input type="number" id="discountAmm" name="discountAmm"
                                                                class="form-control form-control-sm d-none"
                                                                value="0">
                                                            <button id="discountApply" type="button"
                                                                class="btn btn-primary btn-sm ms-1 d-none">Apply</button>
                                                        </div>

                                                        <div class="d-flex justify-content-end">
                                                            <span class="fw-bold ms-2 text-danger">Less:
                                                            </span> <span id="totalLess" class=" ms-2 text-danger">
                                                                <?= $amount ?? -0.0 ?></span>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-between mt-3">
                                                        <div class="d-flex justify-content-end align-items-center">
                                                            <span class="fw-bold me-3">With Holding
                                                                Tax: </span>
                                                            <select id="whTax" name="whTax"
                                                                class="form-select form-select-sm"
                                                                style="width: 100px;">
                                                                <option value="0.00">---</option>
                                                                <option value="0.01">1%</option>
                                                                <option value="0.02">2%</option>
                                                                <!-- <option>3%</option> -->
                                                            </select>
                                                        </div>
                                                        <div class="d-flex justify-content-end">
                                                            <span class="fw-bold ms-2 ">WH Tax:
                                                            </span> <span id="totalWHT1" class=" ms-2 ">
                                                                <?= $amount ?? 0.0 ?></span>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-end align-items-center">
                                                        <span class="fw-bold" style="font-size: 20px;">PAY AMOUNT
                                                            DUE: </span> <span id="totalDue" class="ms-2"
                                                            style="font-size: 30px;"><?= $amount ?? 0.0 ?></span>
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <label id="or_cr" class="fw-bold"
                                                            style="font-size: 14px;">OR No :
                                                        </label>
                                                        <input type="text" name="or_number"
                                                            class="form-control form-control-sm ms-3"
                                                            style="width: 140px;" required="">
                                                    </div>

                                                    <div class="d-flex justify-content-end mt-2">
                                                        <label id="tin_no" class="fw-bold"
                                                            style="font-size: 14px;">TIN NO :
                                                        </label>
                                                        <input type="text" name="tin_no"
                                                            class="form-control form-control-sm ms-3"
                                                            style="width: 140px;">
                                                    </div>

                                                    <div id="display-1">
                                                        <div class="d-flex justify-content-end">
                                                            <span class="fw-bold fst-italic">Total
                                                                Base Price:</span> <span id="totalBasePrice"
                                                                class="ms-2"> 0.00</span>
                                                        </div>
                                                    </div>

                                                    <div id="display-1">
                                                        <div class="d-flex justify-content-end">
                                                            <span class="fw-bold fst-italic">WH
                                                                Tax:</span> <span id="totalWHT2" class="ms-2">
                                                                0.00</span>
                                                        </div>
                                                    </div>
                                                    <div id="display-1">
                                                        <div class="d-flex justify-content-end">
                                                            <span class="fw-bold fst-italic">Security
                                                                Fee:</span> <span id="secFee" class="ms-2">
                                                                0.00</span>
                                                        </div>
                                                    </div>
                                                    <div id="display-1">
                                                        <div class="d-flex justify-content-end">
                                                            <span class="fw-bold fst-italic">EPF /
                                                                RMF:</span> <span id="epFrmF" class="ms-2">
                                                                0.00</span>
                                                        </div>
                                                    </div>
                                                    <div class=" d-flex justify-content-end">
                                                        - - - - - - - - - - - - - - - - - - - - - -
                                                        - - - - - - -
                                                    </div>
                                                    <div id="display-1">
                                                        <div class="d-flex justify-content-end">
                                                            <span class="fst-italic ">Vatable
                                                                Sales:</span> <span id="vatSales" class="ms-2 ">
                                                                0.00</span>
                                                        </div>
                                                    </div>
                                                    <div id="display-2">
                                                        <div class="d-flex justify-content-end">
                                                            <span class="  fst-italic ">Vat (12%)
                                                                Tax: </span> <span id="vat" class="ms-2 ">
                                                                0.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <!-- <div class="d-flex justify-content-end">
          <span class="fw-bold"><i class="fas fa-edit" style="cursor:pointer"></i> Tax: <span id="totalTax"></span></span>
        </div> -->

                                    <div class="col-md-12  ">
                                        <label for="" class="form-label">Remarks: </label>
                                        <div class="form-floating">
                                            <textarea name="remarks" class="form-control" placeholder="Leave a comment here" id="floatingTextarea"
                                                style="min-height:150px"></textarea>
                                            <label for="floatingTextarea">Post Billing 3.0
                                                Comments</label>
                                        </div>
                                    </div>
                                    <input type="hidden" name="totalCount" id="totalCount4"
                                        value="{{ $count }}">
                                    <input type="hidden" name="total_amount" id="total_amount">
                                    <input type="hidden" name="total_less" id="total_less">
                                    <input type="hidden" name="sub_amount" id="sub_amount">
                                    <input type="hidden" name="total_baseprice" id="total_baseprice">
                                    <input type="hidden" name="total_security" id="total_security">
                                    <input type="hidden" name="total_rmf" id="total_rmf">
                                    <input type="hidden" name="total_vatsales" id="total_vatsales">
                                    <input type="hidden" name="total_vat" id="total_vat">
                                    <input type="hidden" name="total_wht" id="total_wht">
                                </div>
                            </div>
                            <div class="d-flex mt-4 justify-content-end">
                                <div>
                                    <button id="invoice_submit_btn" type="submit"
                                        class="btn btn-success btn-md me-2">Submit</button>
                                </div>
                                <div>
                                    <a href="/crmxi" class="btn btn-danger btn-md me-2">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
    </div>
</div>
</form>

<div class="card mt-4">
    <div class="card-header  p-3" style="background-color: #1e81b0;">
        <div class="h6 fw-bold text-white"><i class="fas fa-hand-holding-usd"></i> Billing 3.0 History</div>
    </div>
    <div class="card-body">
        @if ($status_red == 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th scope="col">Billing #</th>
                        <th scope="col">OR|CR#</th>
                        <th scope="col">Action By</th>
                        <th scope="col">Creation Date</th>
                        <th scope="col">Status</th>
                        <th scope="col">Remarks</th>
                        <th scope="col">Action</th>


                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $index => $invoice)
                        <tr>
                            <td>{{ $index + 1 }} .</td>
                            <td>{{ $invoice->invoice_no }}</td>
                            <td>{{ $invoice->or_number }}</td>
                            <td>{{ $invoice->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($invoice->created_at, 'UTC')->setTimezone('Asia/Manila')->format('Y-m-d H:i:s') }}
                            </td>
                            <td>{{ $invoice->isCancel == 1 ? $invoice->reason_or_cancel : 'OPEN' }}</td>
                            <td>{{ $invoice->remarks }}</td>
                            @if ($invoice->isCancel != 1)
                                <td>
                                    <a style="cursor: pointer;" target="_blank"
                                        href="{{ route('generate.billing3', ['id' => $invoice->invoice_no, 'crm_id' => $invoice->c_id]) }}"
                                        class="me-3">
                                        <i class="fas fa-print text-success"></i>
                                    </a>
                                    <a target="_blank" style="cursor: pointer;"
                                        href="{{ route('scp_invoice3.edit', ['crm_id' => $invoice->crm_id, 'invoice_no' => $invoice->invoice_no]) }}"
                                        class="me-3"><i class="fas fa-edit text-primary"></i></a>
                                    @if (auth()->user()->can('access_cancel_billing_3'))
                                        <a style="cursor: pointer;" onclick="cancelOR(<?= $invoice->invoice_no ?>)"
                                            class="me-3" data-bs-toggle="modal" data-bs-target="#exampleModal20">
                                            <i class="fas fa-ban text-danger"></i>
                                        </a>
                                    @endif
                                </td>
                            @else
                                <td></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center fw-bold text-danger h4">
                SRS is unable to Create Invoice of this Account since it is currently red tagged. Please coordinate to
                SRS Administrator or Manager to override or update<br> RED TAG Status.
            </div>
        @endif
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal20" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Reason for : </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/crm3/spc/cancelOr" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="inv-id" name="invoice_no">
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" name="reason_or_cancel"
                            style="height: 100px" required=""></textarea>
                        <label for="floatingTextarea2">Reason for cancel: </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal7" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Renewed Vehicle Stickers</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Plate no.</th>
                            <th scope="col">Vehicle Description</th>
                            <th>New Sticker no.</th>
                            <th>Account ID</th>
                            <th>Category</th>
                            <th>Subcategory</th>
                            <th>Vehicle Ownership Type</th>
                            <th>Type</th>
                            <th>HOA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($count = 0)
                        @forelse ($renewed as $list)
                            @php($count++)
                            <tr>
                                <th scope="row"><?= $count ?></th>
                                <td><?= $list->plate_no ?></td>
                                <td> <?= $list->brand . ' , ' . $list->series . ' , ' . $list->year_model . ' , ' . $list->color . ' , ' . $list->type ?>
                                </td>
                                <td> <?= $list->new_sticker_no ?></td>
                                <td>{{ $list->account_id }}</td>
                                <td>{{ $list->category_name }}</td>
                                <td>{{ $list->sub_category_name }}</td>
                                <td>{{ $list->vehicle_ownership_status }}</td>
                                <td>{{ $list->type }}</td>
                                <td>{{ $list->hoa_name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">
                                    <div class="text-center text-danger">No renewed sticker yet for this year!</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>

<!-- Modal -->
{{-- <div class="modal fade" id="exampleModal7" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Renewed Vehicle Stickers</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group">

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Description</th>
                                <th scope="col">Last</th>
                                <th scope="col">Handle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($count = 0)
                            @foreach ($renewed as $list)
                                @php($count++)
                                <tr>
                                    <th scope="row"><?= $count ?></th>
                                    <td> <?= $list->plate_no ?>
                                        <?= $list->brand . ' , ' . $list->series . ' , ' . $list->year_model . ' , ' . $list->color . ' , ' . $list->type . ' <br>' . 'New Sticker:' . $list->new_sticker_no ?>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </ul>
            </div>

        </div>
    </div>
</div> --}}
<!-- The Modal - Comment out for meantime JY, comment it back once done 11/7/24 -->
{{-- <div id="myModal1" class="modal1">
    <span class="btn-close"></span>
    <img class="modal-content1" id="img01">
    <div id="caption1"></div>
</div> --}}

<!-- Modal -->
<div class="modal fade" id="editTag" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Red Tag</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('update-red-tag') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="id_tag" name="red_tag_id">
                    <label for="" class="form-label">Red Tag Remarks: </label>
                    <div class="form-floating">
                        <textarea name="red_tag_remarks" class="form-control red_tag_remarks" placeholder="Leave a comment here"
                            id="floatingTextarea" style="min-height:150px" required></textarea>
                        <label for="floatingTextarea">Remarks</label>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="getVehicles" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Vehicle</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
            {{-- <form action="{{ route('edit-vehicle') }}" method="POST" enctype="multipart/form-data"> --}}
                @csrf
                <div class="modal-body">

                    <div class="card " style="border-radius: 15px;">
                        <div class="card-header bg-primary">
                            <div class="h5 fw-bold text-white">
                                <i class="fas fa-car"></i> Vehicle Information
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="card mt-4" style="border-radius: 15px;">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between">
                                        <div class="h6 mb-3 text-muted"> <i class="fas fa-car"></i> Vehicle </div>
                                    </div>

                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Plate no: </label>
                                                <input type="text" id="plate2" name="plate"
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Select Brand:</label>
                                            <select id="brand2" name="brand" class="form-select"
                                                aria-label="Default select example">
                                                <option selected>---</option>
                                                <option value="Abarth">Abarth</option>
                                                <option value="Alfa Romeo">Alfa Romeo</option>
                                                <option value="Aston Martin">Aston Martin</option>
                                                <option value="Audi">Audi</option>
                                                <option value="Bentley">Bentley</option>
                                                <option value="BMW">BMW</option>
                                                <option value="BYD">BYD</option>
                                                <option value="Changan">Changan</option>
                                                <option value="Changhe">Changhe</option>
                                                <option value="Chery">Chery</option>
                                                <option value="Chevrolet">Chevrolet</option>
                                                <option value="Chrysler">Chrysler</option>
                                                <option value="Dodge">Dodge</option>
                                                <option value="FAW">FAW</option>
                                                <option value="Ferrari">Ferrari</option>
                                                <option value="Fiat">Fiat</option>
                                                <option value="Ford">Ford</option>
                                                <option value="Foton">Foton</option>
                                                <option value="GAC">GAC</option>
                                                <option value="GAZ">GAZ</option>
                                                <option value="Geely">Geely</option>
                                                <option value="Great Wall">Great Wall</option>
                                                <option value="Haima">Haima</option>
                                                <option value="Honda">Honda</option>
                                                <option value="Hyundai">Hyundai</option>
                                                <option value="Isuzu">Isuzu</option>
                                                <option value="JAC">JAC</option>
                                                <option value="Jaguar">Jaguar</option>
                                                <option value="Jeep">Jeep</option>
                                                <option value="JMC">JMC</option>
                                                <option value="Kaicene">Kaicene</option>
                                                <option value="Kia">Kia</option>
                                                <option value="King Long">King Long</option>
                                                <option value="Lamborghini">Lamborghini</option>
                                                <option value="Land Rover">Land Rover</option>
                                                <option value="Lexus">Lexus</option>
                                                <option value="Lifan">Lifan</option>
                                                <option value="Lotus">Lotus</option>
                                                <option value="Mahindra">Mahindra</option>
                                                <option value="Maserati">Maserati</option>
                                                <option value="Maxus">Maxus</option>
                                                <option value="Mazda">Mazda</option>
                                                <option value="Mercedes-Benz">Mercedes-Benz</option>
                                                <option value="MG">MG</option>
                                                <option value="MINI">MINI</option>
                                                <option value="Mitsubishi">Mitsubishi</option>
                                                <option value="Morgan">Morgan</option>
                                                <option value="Nissan">Nissan</option>
                                                <option value="Peugeot">Peugeot</option>
                                                <option value="Porsche">Porsche</option>
                                                <option value="RAM">RAM</option>
                                                <option value="Rolls-Royce">Rolls-Royce</option>
                                                <option value="SsangYong">SsangYong</option>
                                                <option value="Subaru">Subaru</option>
                                                <option value="Suzuki">Suzuki</option>
                                                <option value="Tata">Tata</option>
                                                <option value="Toyota">Toyota</option>
                                                <option value="Volkswagen">Volkswagen</option>
                                                <option value="Volvo">Volvo</option>
                                                <option value="Others">Others</option>
                                            </select></label>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Series </label>
                                                <input id="vehicle_series2" type="text" name="vehicle_series"
                                                    class="form-control">
                                            </div>
                                        </div>


                                        <div class="col-md-3">
                                            <label class="form-label">Year/Model</label>
                                            <select id="year_model2" name="year_model" class="form-select"
                                                aria-label="Default select example">
                                                <option selected>---</option>
                                                <?php
                          $current_year = date('Y');
                          for ($i = 2010; $i <= $current_year + 1; $i++) { ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                <?php } ?>
                                            </select></label>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Color</label>
                                            <select id="color2" name="color" class="form-select"
                                                aria-label="Default select example">
                                                <option selected>---</option>
                                                <option value="White">White</option>
                                                <option value="Black">Black</option>
                                                <option value="Gray">Gray</option>
                                                <option value="Silver">Silver</option>
                                                <option value="Blue">Blue</option>
                                                <option value="Red">Red</option>
                                                <option value="Brown">Brown</option>
                                                <option value="Green">Green</option>
                                                <option value="Orange">Orange</option>
                                                <option value="Beige">Beige</option>
                                                <option value="Purple">Purple</option>
                                                <option value="Gold">Gold</option>
                                                <option value="Yellow">Yellow</option>
                                                <option value="Others">Others</option>
                                            </select></label>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Type</label>
                                            <select id="type2" name="type" class="form-select"
                                                aria-label="Default select example">
                                                <option selected>---</option>
                                                <option value="Car">Car</option>
                                                <option value="Motorcycle">Motorcycle</option>

                                            </select></label>
                                        </div>


                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <div class="mb-3">
                                                    <label for="formFile" class="form-label">OR</label>
                                                    <input class="form-control" id="or" type="file"
                                                        name="or" id="formFile">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <div class="mb-3">
                                                    <label for="formFile" class="form-label">CR</label>
                                                    <input class="form-control" id="cr" type="file"
                                                        name="cr" id="formFile">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <div class="mb-3">
                                                    <label for="formFile" class="form-label">Vehicle Picture</label>
                                                    <input class="form-control" id="vehicle_pic" type="file"
                                                        name="vehicle_pic" id="formFile">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="vehicle_id" id="vehicle_id">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
</script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
</script>
<script>
    function notAvail() {
        Swal.fire({
            title: 'Invoice editing is not possible if Month End had been completed..',
            icon: 'error',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Okay'
        })
    }
    $(document).ready(function() {
        $('#table_id').DataTable();
    });
    $(document).ready(function() {
        $('#table_id2').DataTable();
    });

    var table_row = 1;
    var selectedVehicles = [];
    loadVehicles($('#vehicleOwner').val());

    function loadVehicles(accountName = '') {
        $.ajax({
            url: '/crm3/loadVehicle',
            type: 'post',
            data: {
                _token: '{{ csrf_token() }}',
                id: $("#crm_id1").val(),
                selectedVehicles: selectedVehicles,
                accountName: accountName
            },
            dataType: 'json',
            success: function(response) {
                // //console.log(response);
                $(".plate-options").html('');
                $(".plate-options").append(`<option value="">Select Vehicle</option>`);
                response.forEach(function(v) {
                    if (v.category_name) {
                        $(".plate-options").append(
                            `<option value="${ v.id }" data-vehicle-type="${v.type}" data-vehicle-cat="${v.category_name}" data-vehicle-subcat="${v.sub_category_name}">${v.plate_no} (${v.category_name} - ${v.sub_category_name} - ${v.vot_name} - ${v.type})</option>`
                        );
                    } else {
                        $(".plate-options").append(
                            `<option value="${ v.id }" data-vehicle-type="${v.type}" data-vehicle-cat="${v.category_name}" data-vehicle-subcat="${v.sub_category_name}">${v.plate_no} (${v.category_name} - ${v.sub_category_name} - ${v.vot_name} - ${v.type})</option>`
                        );
                    }
                });
            }
        })
    }
    // var price = 0;
    // var subTotal = <?= $amount ?? 0 ?>;
    // var grandTotal = <?= $amount ?? 0 ?>;
    // var discount = 0;
    var totalAmmCopy = 0;
    var totalDueCopy = 0;
    var totalAmm = 0;
    var totalLess = 0;
    var totalDue = 0;
    var secFee = 0;
    var secFeeCopy = 0;
    var epFrmF = 0;
    var epFrmFCopy = 0;
    var vatIncome = 0;
    var vat = 0;
    var totalBaseprice = 0;
    var totalBasePriceCopy = 0;
    var motor_price = 0;
    var car_price = 0;
    var totalVat = 0;
    var totalVatSales = 0;
    var totalVatCopy = 0;
    var totalVatSalesCopy = 0;
    var whTax = 0;
    var tax = 0;
    var checker = 0;

    $(document).on('change', '.plate-options', function() {
        table_row++;
        if (table_row >= 10) {
            $('#row-count').append(
                `<tr id="row-${table_row}">
     <td class="vehicle-rows" id="row-${table_row}-count">
     </td>
         <td id="row-${table_row}-option">
    </td>
    <td id="row-${table_row}-vehicle-count"></td>
    <td id="row-${table_row}-details"></td>
    <td  id="row-${table_row}-old-sticker"></td>
    <td id="row-${table_row}-new-sticker"></td>
    <td class="text-center" id="row-${table_row}-sellingPrice"></td>
    <td class="text-center" id="row-${table_row}-basePrice"></td>
    <td class="text-center" id="row-${table_row}-secF"></td>
    <td class="text-center" id="row-${table_row}-rmF"></td>
    <td id="row-${table_row}-cat"></td>
    <input type="hidden" id="vehicle_id_${table_row}" value="" name="vehicle_id[]" multiple="multiple">
    <input type="hidden" name="price[]" id="vehicle_price_${table_row}" value="" multiple="multiple">
    <input type="hidden" name="basePrice[]" id="base_price_${table_row}" value="" multiple="multiple">
    <input type="hidden" name="security[]" id="security_${table_row}" value="" multiple="multiple">
    <input type="hidden" name="rmf[]" id="rmf_${table_row}" value="" multiple="multiple">
    <input type="text" name="row_vat_sales[]" id="row_vat_sales${table_row}" value="" multiple="multiple">
    <input type="text" name="row_vat[]" id="row_vat${table_row}" value="" multiple="multiple">
     </tr>`)
        }
        const numberFormatter = Intl.NumberFormat('en-US');

        selectedVehicles.push([
            [$(this).val()],
            [$(this).find(':selected').data('vehicle-type')],
            [
                $(this).find(':selected').data('vehicle-cat'),
                $(this).find(':selected').data('vehicle-subcat')
            ],
        ]);

        var selectMotors = selectedVehicles.filter(function(v) {
            return v[1] == 'Motorcycle';
        });

        // console.log(selectMotors);

        var count = selectedVehicles.length + parseInt($("#renewed_count").val());
        var countMotor = selectMotors.length + parseInt($("#renewed_count_motor").val());
        var e = `<select class="form-select form-select-sm plate-options" aria-label=".form-select-sm example">
                                                                                </select>`;
        $('.plate-options').remove();

        $.ajax({

            url: '/crm3/fetchVehicleDetails',
            type: 'post',
            data: {
                _token: '{{ csrf_token() }}',
                id: $(this).val()
            },
            dataType: 'json',
            success: function(response) {
                let vehicleCount = 0;

                if (response[0].type == "Motorcycle") {
                    vehicleCount = selectMotors.length + parseInt($("#renewed_count_motor").val());
                } else {
                    vehicleCount = selectedVehicles.length + parseInt($("#renewed_count").val());
                }

                $.ajax({
                    url: '/crm3/loadComputation',
                    type: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: $("#crm_id").val(),
                        selectedVehicles: selectedVehicles,
                        vehicle_id: response[0].id, // vehicle_id
                        vehicle_type: response[0].type, // vehicle_type
                        vehicle_hoa_type: response[0].hoa_type, // vehicle_hoa_type
                        vehicle_category: response[0].category_id, // vehicle_category
                        vehicle_subcategory: response[0]
                        .sub_category_id, // vehicle_subcategory
                        vehicle_ownership_status_id: response[0]
                            .vehicle_ownership_status_id, // vehicle_ownership_status_id
                        vehicleCount: selectedVehicles.length + parseInt($("#renewed_count")
                            .val()) // vehicleCount
                    },
                    dataType: 'json',
                    success: function(response) {
                        // console.log(response[0].category_id);
                        totalAmmCopy = totalAmm + response[0].sellingPrice;
                        totalDueCopy = totalDue + response[0].sellingPrice;
                        totalAmm = totalAmm + response[0].sellingPrice;
                        totalDue = totalDue + response[0].sellingPrice;
                        totalBaseprice = totalBaseprice + response[0].basePrice;
                        totalBasePriceCopy = totalBaseprice;

                        if (response[0].security !== null) {
                            secFee += response[0].security;
                            secFeeCopy = secFee;
                        }

                        if (response[0].roadMaintFee !== null) {
                            epFrmF += response[0].roadMaintFee;
                            epFrmFCopy = epFrmF;
                        }


                        if (response[0].vatSales !== null) {
                            totalVatSales += parseFloat(response[0].vatSales);

                            totalVatSalesCopy += parseFloat(response[0].vatSales);
                        }

                        if (response[0].vatAmount !== null) {
                            totalVat += parseFloat(response[0].vatAmount);
                            totalVatCopy += parseFloat(response[0].vatAmount);
                        }


                        const numberFormatOptions = {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                        };

                        // console.log(totalVatSales);


                        const formattedTotalAmm = new Intl.NumberFormat('en-US',
                            numberFormatOptions).format(totalAmm);
                        const formattedTotalDue = new Intl.NumberFormat('en-US',
                            numberFormatOptions).format(totalDue);
                        const formattedTotalBasePrice = new Intl.NumberFormat('en-US',
                            numberFormatOptions).format(totalBaseprice);
                        const formattedSecFee = new Intl.NumberFormat('en-US',
                            numberFormatOptions).format(secFee);
                        const formattedEpFrmF = new Intl.NumberFormat('en-US',
                            numberFormatOptions).format(epFrmF);
                        const formattedTotalVatSales = new Intl.NumberFormat('en-US',
                            numberFormatOptions).format(totalVatSales);
                        const formattedTotalVat = new Intl.NumberFormat('en-US',
                            numberFormatOptions).format(totalVat);


                        $('#totalAmm').text(formattedTotalAmm);
                        $('#totalDue').text(formattedTotalDue);
                        $('#totalBasePrice').text(formattedTotalBasePrice);
                        $('#secFee').text(formattedSecFee);
                        $('#epFrmF').text(formattedEpFrmF);
                        $('#vatSales').text(formattedTotalVatSales);
                        $('#vat').text(formattedTotalVat);


                        $('#total_amount').val(formattedTotalAmm);
                        $('#sub_amount').val(formattedTotalDue);
                        $('#total_baseprice').val(formattedTotalBasePrice);
                        $('#total_security').val(formattedSecFee);
                        $('#total_rmf').val(formattedEpFrmF);
                        $('#total_vatsales').val(formattedTotalVatSales);
                        $('#total_vat').val(formattedTotalVat);

                        $(`#row-${(table_row - 1)}-sellingPrice`).html(response[0]
                            .sellingPrice.toLocaleString('en-US',
                                numberFormatOptions));
                        $(`#row-${(table_row - 1)}-basePrice`).html(response[0]
                            .basePrice.toLocaleString('en-US', numberFormatOptions));
                        $(`#row-${(table_row - 1)}-secF`).html(response[0].security
                            .toLocaleString('en-US', numberFormatOptions));
                        $(`#row-${(table_row - 1)}-rmF`).html(response[0].roadMaintFee
                            .toLocaleString('en-US', numberFormatOptions));
                        $(`#vehicle_price_${(table_row - 1)}`).val(response[0]
                            .sellingPrice.toLocaleString('en-US',
                                numberFormatOptions));
                        $(`#base_price_${(table_row - 1)}`).val(response[0].basePrice
                            .toLocaleString('en-US', numberFormatOptions));
                        $(`#security_${(table_row - 1)}`).val(response[0].security
                            .toLocaleString('en-US', numberFormatOptions));
                        $(`#rmf_${(table_row - 1)}`).val(response[0].roadMaintFee
                            .toLocaleString('en-US', numberFormatOptions));
                        $(`#row_vat${(table_row - 1)}`).val(response[0].vatAmount
                            .toLocaleString('en-US', numberFormatOptions));
                        $(`#row_vat_sales${(table_row - 1)}`).val(response[0].vatSales
                            .toLocaleString('en-US', numberFormatOptions));

                        // comment this to revert to old.
                        $(`#row-${(table_row - 1)}-new-sticker`).html(response[
                                'stickerSelect'])
                            .addClass('w-25')
                        $(`#row-${(table_row - 1)}-vehicle-count`).html(response[
                            'vehicleCount']);
                    }
                })

                $(`#row-${(table_row - 1)}-count`).html(count);

                if (response[0].category_name != null || response[0].category_name != '') {
                    $(`#row-${(table_row - 1)}-option`).html(`
                ${response[0].plate_no} <br>
                <span class="badge bg-primary">${response[0].category_name}</span>
                <span class="badge bg-primary">${response[0].sub_category_name}</span>
                <span class="badge bg-primary">${response[0].vot_name}</span>
                <span class="badge bg-primary">${response[0].type}</span>
              `);
                } else {
                    $(`#row-${(table_row - 1)}-option`).html(response[0].plate_no);
                }

                $(`#row-${(table_row - 1)}-details`).html(response[0].brand + ' , ' +
                    response[0].series + ' , ' + response[0].year_model + ' , ' + response[0]
                    .color + ' , ' + response[0].type);
                $(`#row-${(table_row - 1)}-old-sticker`).html(response[0].old_sticker_no);
                $(`#vehicle_id_${(table_row - 1)}`).val(response[0].id);
                $(`#vehicle_type_${(table_row - 1)}`).val(response[0].type);
                $(`#row-${(table_row - 1)}-cat`).html(`<select name="cat_identifier" class="form-select" aria-label="Default select example">
            <option value="1" ${response[0].category_id == 1 ? 'selected' : ''}>Resident</option>
            <option value="2" ${response[0].category_id == 2 ? 'selected' : ''}>Non-Resident</option>
            </select>`);
                $('#totalCount4').val(table_row - 1);

                // uncomment to revert to old.
                // $(`#row-${(table_row - 1)}-new-sticker`).html(`<input placeholder="New Sticker" type="text" class="form-control" onkeydown="return /[0-9]/i.test(event.key) || event.key === 'Backspace' || event.key === 'Delete'"  name="new_sticker[]" multiple="multiple" maxlength="10" required>`);

                count++;
            }
        })
        $(`#row-${table_row}-option`).html(e);
        loadVehicles($('#vehicleOwner').val());
    })

    var active_discount_type = 'percent';

    $('#set_percent').on('click', function() {
        $(this).addClass('disabled');
        $('#set_amount').removeClass('disabled');
        $('.dropdown-text').html('%');
        active_discount_type = 'percent';

        $('#discountAmm').addClass('d-none');
        $('#discountApply').addClass('d-none');
        $('#discountP').removeClass('d-none');
    });

    $('#set_amount').on('click', function() {
        $(this).addClass('disabled');
        $('#set_percent').removeClass('disabled');
        $('.dropdown-text').html('₱');
        active_discount_type = 'amount';

        $('#discountAmm').removeClass('d-none');
        $('#discountApply').removeClass('d-none');
        $('#discountP').addClass('d-none');
    });

    $(document).ready(function() {
        var discountA = 0;

        $('#discountApply').on('click', function(e) {
            var discountValue = $('#discountAmm').val(); // this is now a number, not a percentage

            // convert the discount value to a percentage by dividing it with
            // the total amount and multiplying by 100
            var discountPercentage = (discountValue / totalAmm) * 100;

            var discountToDeductFromEPF = 0;
            var discountToDeductFromSecFee = 0;

            var discountedTotalAmm = totalAmm * (100 - discountPercentage) / 100;
            var discountedTotalDue = totalDue * (100 - discountPercentage) / 100;
            var discountedTotalVatSales = totalVatSales * (100 - discountPercentage) / 100;
            totalVatSales = discountedTotalVatSales;
            var discountedTotalVat = totalVat * (100 - discountPercentage) / 100;

            if (checker > 0 && discountPercentage != "0") {
                return Swal.fire({
                    title: 'Please set to 0 before applying another discount.',
                    icon: 'error',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Okay'
                })
            }

            if (discountPercentage == "0") {
                secFee = secFeeCopy;
                epFrmF = epFrmFCopy;
                totalBaseprice = totalBasePriceCopy;
                totalDue = totalDueCopy;
                discountA = 0;
                checker = 0;

                $('#total_security').val(formatDecimal(secFee, 2));
                $('#total_rmf').val(formatDecimal(epFrmF, 2));
                $('#total_baseprice').val(formatDecimal(totalBaseprice, 2));
                $('#totalDue').text(formatDecimal(totalDue, 2));
                $('#sub_amount').val(formatDecimal(totalDue, 2));
                $('#totalBasePrice').text(formatDecimal(totalBaseprice, 2));
                $('#secFee').text(formatDecimal(secFee, 2));
                $('#epFrmF').text(formatDecimal(epFrmF, 2));

                $('#total_less').val(discountA);
                $('#totalLess').text(' - ' + discountA);

                $('#vatSales').text(totalVatSalesCopy);
                $('#vat').text(totalVatCopy);
                $('#total_vatsales').val(totalVatSalesCopy);
                $('#total_vat').val(totalVatCopy);

                return;
            }

            // Calculate the discount amount
            var discountAmount = totalAmm - discountedTotalAmm;

            discountA = totalAmm - discountedTotalAmm;

            // Deduct the discount from 'EPF/RMF' (if any)
            if (epFrmF > 0) {
                discountToDeductFromEPF = Math.min(epFrmF, discountAmount);
                epFrmF -= discountToDeductFromEPF;
                discountAmount -= discountToDeductFromEPF;
            }

            // Deduct the remaining discount from 'Security Fee' (if any)
            if (discountAmount > 0 && secFee > 0) {
                discountToDeductFromSecFee = Math.min(secFee, discountAmount);
                secFee -= discountToDeductFromSecFee;
                discountAmount -= discountToDeductFromSecFee;
            }

            // If there's still a discount remaining, subtract it from 'Total Base Price'
            if (discountAmount > 0) {
                totalBaseprice -= discountAmount;
            }

            // Format the calculated values
            var formattedDiscountedTotalDue = formatDecimal(discountedTotalDue, 2);
            var formattedDiscountedTotalVatSales = formatDecimal(discountedTotalVatSales, 2);
            var formattedDiscountedTotalVat = formatDecimal(discountedTotalVat, 2);
            var formattedTotalLess = formatDecimal(discountA, 2);

            // Update displayed values and input fields
            $('#totalDue').text(formattedDiscountedTotalDue);
            $('#vatSales').text(formattedDiscountedTotalVatSales);
            $('#vat').text(formattedDiscountedTotalVat);
            $('#totalLess').text(' - ' + formattedTotalLess);

            $('#sub_amount').val(formattedDiscountedTotalDue);
            $('#total_vatsales').val(formattedDiscountedTotalVatSales);
            $('#total_vat').val(formattedDiscountedTotalVat);
            $('#total_less').val(formattedTotalLess);

            $('#total_security').val(formatDecimal(secFee, 2));
            $('#total_rmf').val(formatDecimal(epFrmF, 2));
            $('#total_baseprice').val(formatDecimal(totalBaseprice, 2));

            $('#totalBasePrice').text(formatDecimal(totalBaseprice, 2));
            $('#secFee').text(formatDecimal(secFee, 2));
            $('#epFrmF').text(formatDecimal(epFrmF, 2));

            checker++;
        });

        $('#discountP').on('change', function(e) {
            var discountPercentage = parseInt($(this).val().replace('%', ''));

            var discountToDeductFromEPF = 0;
            var discountToDeductFromSecFee = 0;

            var discountedTotalAmm = totalAmm * (100 - discountPercentage) / 100;
            var discountedTotalDue = totalDue * (100 - discountPercentage) / 100;
            var discountedTotalVatSales = totalVatSales * (100 - discountPercentage) / 100;
            totalVatSales = discountedTotalVatSales;
            var discountedTotalVat = totalVat * (100 - discountPercentage) / 100;


            if (checker > 0 && discountPercentage != "0") {
                return Swal.fire({
                    title: 'Please set discount to 0 first before applying another discount.',
                    icon: 'error',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Okay'
                })
            }

            if (discountPercentage == "0") {
                secFee = secFeeCopy;
                epFrmF = epFrmFCopy;
                totalBaseprice = totalBasePriceCopy;
                totalDue = totalDueCopy;
                discountA = 0;
                checker = 0;

                $('#total_security').val(formatDecimal(secFee, 2));
                $('#total_rmf').val(formatDecimal(epFrmF, 2));
                $('#total_baseprice').val(formatDecimal(totalBaseprice, 2));
                $('#totalDue').text(formatDecimal(totalDue, 2));
                $('#sub_amount').val(formatDecimal(totalDue, 2));
                $('#totalBasePrice').text(formatDecimal(totalBaseprice, 2));
                $('#secFee').text(formatDecimal(secFee, 2));
                $('#epFrmF').text(formatDecimal(epFrmF, 2));

                $('#total_less').val(discountA);
                $('#totalLess').text(' - ' + discountA);

                $('#vatSales').text(totalVatSalesCopy);
                $('#vat').text(totalVatCopy);
                $('#total_vatsales').val(totalVatSalesCopy);
                $('#total_vat').val(totalVatCopy);

                return;
            }

            // Calculate the discount amount
            var discountAmount = totalAmm - discountedTotalAmm;

            discountA = totalAmm - discountedTotalAmm;

            // Deduct the discount from 'EPF/RMF' (if any)
            if (epFrmF > 0) {
                discountToDeductFromEPF = Math.min(epFrmF, discountAmount);
                epFrmF -= discountToDeductFromEPF;
                discountAmount -= discountToDeductFromEPF;
            }

            // Deduct the remaining discount from 'Security Fee' (if any)
            if (discountAmount > 0 && secFee > 0) {
                discountToDeductFromSecFee = Math.min(secFee, discountAmount);
                secFee -= discountToDeductFromSecFee;
                discountAmount -= discountToDeductFromSecFee;
            }

            // If there's still a discount remaining, subtract it from 'Total Base Price'
            if (discountAmount > 0) {
                totalBaseprice -= discountAmount;
            }

            // Format the calculated values
            var formattedDiscountedTotalDue = formatDecimal(discountedTotalDue, 2);
            var formattedDiscountedTotalVatSales = formatDecimal(discountedTotalVatSales, 2);
            var formattedDiscountedTotalVat = formatDecimal(discountedTotalVat, 2);
            var formattedTotalLess = formatDecimal(discountA, 2);

            // Update displayed values and input fields
            $('#totalDue').text(formattedDiscountedTotalDue);
            $('#vatSales').text(formattedDiscountedTotalVatSales);
            $('#vat').text(formattedDiscountedTotalVat);
            $('#totalLess').text(' - ' + formattedTotalLess);

            $('#sub_amount').val(formattedDiscountedTotalDue);
            $('#total_vatsales').val(formattedDiscountedTotalVatSales);
            $('#total_vat').val(formattedDiscountedTotalVat);
            $('#total_less').val(formattedTotalLess);

            $('#total_security').val(formatDecimal(secFee, 2));
            $('#total_rmf').val(formatDecimal(epFrmF, 2));
            $('#total_baseprice').val(formatDecimal(totalBaseprice, 2));

            $('#totalBasePrice').text(formatDecimal(totalBaseprice, 2));
            $('#secFee').text(formatDecimal(secFee, 2));
            $('#epFrmF').text(formatDecimal(epFrmF, 2));

            checker++;
        });
    });

    $('#whTax').change(function() {
        var selectedTaxRate = parseFloat($(this).val());
        // var updateVatSales = $('#total_vatsales').val()
        // console.log(totalVatSales, totalVatSalesCopy, updateVatSales);

        var tax = totalVatSales * selectedTaxRate;

        // check which #pills-tab is active
        var activeTab = active_discount_type;

        // check if active tab is pill-ammount-tab
        // if yes, then convert discount ammount to percentage
        if (activeTab == 'amount') {
            var discountAmmount = $('#discountAmm').val();
            var discount = (discountAmmount / totalAmm) * 100;
        } else {
            var discount = $('#discountP').val();
        }

        // var discount = $('#discountP').val();

        // check if discount is

        //var due = totalDue - tax;

        var due = (totalDue * ((100 - discount) / 100)) - tax;

        // vatSalesWH = due / 1.12;
        var vatSalesWH = totalVatSales - tax;
        //var vatWH = vatSalesWH * 0.12;

        // Formatting to two decimal places
        //vatSalesWH = vatSalesWH.toFixed(2);
        //vatWH = vatWH.toFixed(2);

        //$('#vatSales').text(vatSalesWH);
        //$('#vat').text(vatWH);

        //$('#total_vatsales').val(vatSalesWH);

        var formattedDue = formatDecimal(due, 2);

        var formattedTax = formatDecimal(tax, 2);

        $('#sub_amount').val(formattedDue);
        $('#total_wht').val(formattedTax);
        $('#totalDue').text(formattedDue);
        $('#totalWHT1').text(formattedTax);
        $('#totalWHT2').text(formattedTax);
    });

    function formatDecimal(number, decimalPlaces) {
        var formattedNumber = parseFloat(number).toFixed(decimalPlaces);
        return formattedNumber.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    //Calculation
    function calculateVAT(vatType, newPrice) {
        var vat = 0;
        var totalTax = 0;

        switch (vatType) {
            case "1":
                totalTax += newPrice / 1.12;
                vat = totalTax * 0.12;
                break;
            case "2":
                vat = 0;
                totalTax = vat;
                break;
            default:
                vat = 0;
                totalTax = vat;
        }
        return vat;
    }

    function delete_bulk(e) {
        let yow = e.attr('data-id');
        var vehicleID = e.attr('data-vehicle');


        const index = selectedVehicles.indexOf(vehicleID);

        const x = selectedVehicles.splice(index, 1);

        loadVehicles($('#vehicleOwner').val());
        var counters = document.querySelectorAll('.vehicle-rows');
        var start = parseInt($("#renewed_count").val());
        counters.forEach(function(e) {
            if (start > selectedVehicles.length) {
                e.innerHTML = "";
            } else {
                e.innerHTML = ++start;
            }
        })

        let subT = parseInt($('#totalSub2').val()) - parseInt($('#row-' + yow + '-amount').text());

        // console.log(parseInt($('#totalSub2').val()) + ' - ' + parseInt($('#row-' + yow + '-amount').text()));
        $('#totalSub').text(subT);
        $('#totalSub2').val(subT);

        $('#totalCount').text(subT);
        $('#totalAmount2').val(subT);
        $('#totalCount4').val($(this).val() - 1);
        $('#row-' + yow).remove();
    }

    // For Patch 11/21/24
    $(document).on('change', '#vehicleOwner', function () {
        let accountName = $(this).val(); // Get Account Name

        $('#billedTo').val(accountName);

        // Load Vehicles
        loadVehicles(accountName);
    });
    // End of Patch
</script>
<?php
  if (session()->has('success')) {
  ?>

<script>
    Swal.fire({
        title: '<?php echo session()->get('success'); ?>',
        icon: 'success',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Okay'
    })
</script>
<?php
  }


  ?>

<?php
  if (session()->has('error')) {
  ?>

<script>
    Swal.fire({
        title: '<?php echo session()->get('error'); ?>',
        icon: 'error',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Okay'
    })
</script>
<?php
  }


  ?>

<script>
    function categoryChange(id) {
        $('#sub_category').html('');
        $('#sub_category').append(`<option value="">--- </option>`);
        $.get('/getSubCategories/' + id,
            function(response) {
                response.forEach(function(a) {
                    var e = `<option value="${a.id}">${a.name}</option>`;
                    $('#sub_category').append(e);

                });


            });

    }
    // sub_categoryChange($('#sub_category').val());

    $(document).ready(function() {
        $("#btnShow").click(function() {
            $("#new_invoice").toggle('slide');
        });
    });

    $(document).ready(function() {
        $("#btnShow1").click(function() {
            $("#new_invoice").toggle('slide');
        });
    });

    function print_novat(crm_id, invoice_no) {
        Swal.fire({
            title: 'Is Invoice ready for printing?',
            text: "Printing Invoice with OR/CR will make this invoice uneditable later.  Pls confirm if this is final.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, print it'

        }).then((result) => {
            if (result.isConfirmed) {
                $.get("/update_isprint_novat/" + invoice_no, {
                        invoice_no: invoice_no
                    },
                    function() {
                        Swal.fire({
                            title: 'Ready to Print',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Okay'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.open(`/invoice_vat/${crm_id}/${invoice_no}`, '_blank');

                            }
                        })
                    });
            }
        })

    }

    function print_withvat(crm_id, invoice_no) {
        Swal.fire({
            title: 'Is Invoice ready for printing?',
            text: "Printing Invoice with OR/CR will make this invoice uneditable later.  Pls confirm if this is final.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, print it'

        }).then((result) => {
            if (result.isConfirmed) {
                $.get("/update_isprint_withvat/" + invoice_no, {
                        invoice_no: invoice_no
                    },
                    function() {
                        Swal.fire({
                            title: 'Ready to Print',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Okay'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.open(`/invoice_with_vat/${crm_id}/${invoice_no}`, '_blank');
                            }
                        })
                    });
            }
        })
    }

    function sub_categoryChange(id) {
        $('#no_cars').html('');
        $('#no_cars').append(`<option value="">--- </option>`);
        var e = '';
        $.get('/getSubCategoriesChanges/' + id,
            function(response) {
                response.forEach(function(a) {
                    var e = `<option value="${a.prices}">${a.rate_name} ${'for - ₱'} ${a.prices} </option>`;
                    $('#no_cars').append(e);

                });
            });
    }

    // getPrice($('#no_cars').val());

    function getPrice(id) {
        $.get('/add-price/' + id, function(price) {
            $('#price_id').val(price.id);
            $('#category_id1').val(price.category_id);
            $('#sub_category1').val(price.sub_category_id);
            $('#price1').val(price.prices);
        })
    }
    // Get the modal - Comment out for meantime JY, comment it back once done 11/7/24
    // var modal = document.getElementById('myModal1');


    // var img = document.getElementsByClassName('myImg');
    // var modalImg = document.getElementById("img01");
    // var captionText = document.getElementById("caption");

    // var showModal = function() {
    //     modal.style.display = "block";
    //     modalImg.src = this.src;
    //     captionText.innerHTML = this.alt;
    // }

    // for (var i = 0; i < img.length; i++) {
    //     img[i].addEventListener('click', showModal);
    // }


    // var span = document.getElementsByClassName("close")[0];


    // span.onclick = function() {
    //     modal.style.display = "none";
    // }

    var x = 2;

    function addVehicle() {
        var a = `<div id="myInputs-` + x +
            `" class="card mt-4" style="border-radius: 15px;">
                              <div class="card-header">
                                  <div class="d-flex justify-content-between">
                                      <div class="h6 mb-3 text-muted"> <i class="fas fa-car"></i> Vehicle ` + x +
            `</div>
                                      <div><button onclick="minusInput(` + x +
            `)"  type="button" class="btn btn-danger btn-md"><i class="fas fa-trash"></i></button></div>
                                  </div>

                              </div>
                              <div class="card-body">
                                  <div class="row">
                                      <div class="col-md-3">
                                          <div class="mb-3">
                                              <label class="form-label">Plate no: </label>
                                              <input type="text" name="plate[]" class="form-control">
                                          </div>
                                      </div>

                                      <div class="col-md-3">
                                          <label class="form-label">Select Brand:</label>
                                          <select name="brand[]" class="form-select" aria-label="Default select example">
                                              <option selected>---</option>
                                              <option value="Abarth">Abarth</option>
                                              <option value="Alfa Romeo">Alfa Romeo</option>
                                              <option value="Aston Martin">Aston Martin</option>
                                              <option value="Audi">Audi</option>
                                              <option value="Bentley">Bentley</option>
                                              <option value="BMW">BMW</option>
                                              <option value="BYD">BYD</option>
                                              <option value="Changan">Changan</option>
                                              <option value="Changhe">Changhe</option>
                                              <option value="Chery">Chery</option>
                                              <option value="Chevrolet">Chevrolet</option>
                                              <option value="Chrysler">Chrysler</option>
                                              <option value="Dodge">Dodge</option>
                                              <option value="FAW">FAW</option>
                                              <option value="Ferrari">Ferrari</option>
                                              <option value="Fiat">Fiat</option>
                                              <option value="Ford">Ford</option>
                                              <option value="Foton">Foton</option>
                                              <option value="GAC">GAC</option>
                                              <option value="GAZ">GAZ</option>
                                              <option value="Geely">Geely</option>
                                              <option value="Great Wall">Great Wall</option>
                                              <option value="Haima">Haima</option>
                                              <option value="Honda">Honda</option>
                                              <option value="Hyundai">Hyundai</option>
                                              <option value="Isuzu">Isuzu</option>
                                              <option value="JAC">JAC</option>
                                              <option value="Jaguar">Jaguar</option>
                                              <option value="Jeep">Jeep</option>
                                              <option value="JMC">JMC</option>
                                              <option value="Kaicene">Kaicene</option>
                                              <option value="Kia">Kia</option>
                                              <option value="King Long">King Long</option>
                                              <option value="Lamborghini">Lamborghini</option>
                                              <option value="Land Rover">Land Rover</option>
                                              <option value="Lexus">Lexus</option>
                                              <option value="Lifan">Lifan</option>
                                              <option value="Lotus">Lotus</option>
                                              <option value="Mahindra">Mahindra</option>
                                              <option value="Maserati">Maserati</option>
                                              <option value="Maxus">Maxus</option>
                                              <option value="Mazda">Mazda</option>
                                              <option value="Mercedes-Benz">Mercedes-Benz</option>
                                              <option value="MG">MG</option>
                                              <option value="MINI">MINI</option>
                                              <option value="Mitsubishi">Mitsubishi</option>
                                              <option value="Morgan">Morgan</option>
                                              <option value="Nissan">Nissan</option>
                                              <option value="Peugeot">Peugeot</option>
                                              <option value="Porsche">Porsche</option>
                                              <option value="RAM">RAM</option>
                                              <option value="Rolls-Royce">Rolls-Royce</option>
                                              <option value="SsangYong">SsangYong</option>
                                              <option value="Subaru">Subaru</option>
                                              <option value="Suzuki">Suzuki</option>
                                              <option value="Tata">Tata</option>
                                              <option value="Toyota">Toyota</option>
                                              <option value="Volkswagen">Volkswagen</option>
                                              <option value="Volvo">Volvo</option>
                                              <option value="Others">Others</option>
                                          </select></label>
                                      </div>

                                      <div class="col-md-3">
                                          <div class="mb-3">
                                              <label class="form-label">Series </label>
                                              <input type="text" name="vehicle_series[]" class="form-control">
                                          </div>
                                      </div>


                                      <div class="col-md-3">
                                          <label class="form-label">Year/Model</label>
                                          <select name="year_model[]" class="form-select" aria-label="Default select example">
                                              <option selected>---</option>
                                              <?php
                                              $current_year = date('Y');
                                              for ($i = 2010; $i <= $current_year + 1; $i++) { ?>
                                                          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                      <?php } ?>
                                          </select></label>
                                      </div>

                                      <div class="col-md-3">
                                          <label class="form-label">Color</label>
                                          <select name="color[]" class="form-select" aria-label="Default select example">
                                              <option selected>---</option>
                                              <option value="White">White</option>
                                              <option value="Black">Black</option>
                                              <option value="Gray">Gray</option>
                                              <option value="Silver">Silver</option>
                                              <option value="Blue">Blue</option>
                                              <option value="Red">Red</option>
                                              <option value="Brown">Brown</option>
                                              <option value="Green">Green</option>
                                              <option value="Orange">Orange</option>
                                              <option value="Beige">Beige</option>
                                              <option value="Purple">Purple</option>
                                              <option value="Gold">Gold</option>
                                              <option value="Yellow">Yellow</option>
                                              <option value="Others">Others</option>
                                          </select></label>
                                      </div>

                                      <div class="col-md-3">
                                          <label class="form-label">Type</label>
                                          <select name="type[]" class="form-select" aria-label="Default select example">
                                              <option selected>---</option>
                                              <option value="Car">Car</option>
                                              <option value="Motorcycle">Motorcycle</option>

                                          </select></label>
                                      </div>

                                      <div class="col-md-6">
                                          <label class="form-label">Sticker no.</label>
                                          <input type="text" name="sticker_no[]" class="form-control">
                                      </div>

                                      <div class="col-md-3">
                                                                                      <label class="form-label">Sticker Year</label>
                                                                                      <select id="sticker_year" name="sticker_year[]" class="form-select" aria-label="Default select example">
                                                                                          <option selected>---</option>
                                                                                          <?php
                                                                                          $current_year = date('Y');
                                                                                          for ($i = 2010; $i <= $current_year + 1; $i++) { ?>
                                                          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                      <?php } ?>
                                                                                      </select></label>
                                                                                  </div>
                                  </div>

                                  <div class="row mt-3">
                                      <div class="col-md-6">
                                          <div class="mb-3">
                                              <div class="mb-3">
                                                  <label for="formFile" class="form-label">OR</label>
                                                  <input class="form-control" type="file" name="or[]" id="formFile" >
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="mb-3">
                                              <div class="mb-3">
                                                  <label for="formFile" class="form-label">CR</label>
                                                  <input class="form-control" type="file" name="cr[]" id="formFile" >
                                              </div>
                                          </div>
                                      </div>


                                      <div class="col-md-6">
                                              <div class="mb-3">
                                                  <div class="mb-3">
                                                      <label for="formFile" class="form-label">Vehicle Picture</label>
                                                      <input class="form-control" type="file" name="vehicle_pic[]" id="formFile" >
                                                  </div>
                                              </div>
                                          </div>
                                  </div>
                              </div>
                          </div>`;

        Swal.fire({
            title: 'Are you sure to add another vehicle input',
            text: "Click ok if you need to add",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, add it!'

        }).then((result) => {
            if (result.isConfirmed) {
                $('#input_vehicle').append(a);

            }
        })

        x++;
    }

    function minusInput(x) {
        var id = x;

        Swal.fire({
            title: 'Are you sure to delete this vehicle Input',
            text: "Add again if you need an input",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'

        }).then((result) => {
            if (result.isConfirmed) {
                $('#myInputs-' + x).remove();
            }
        })


    }


    function changeBulk() {
        var x = document.getElementById("checkBulk");
        var y = document.getElementById("onBulk");

        if (x.checked == true) {
            y.style.display = "block";
        } else {
            y.style.display = "none";
        }
    }

    function deleteTag(id) {
        Swal.fire({
            title: 'Are you sure to delete this details?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'

        }).then((result) => {
            if (result.isConfirmed) {
                $.post("/deleteTag", {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    function() {
                        Swal.fire({
                            title: 'Deleted Successfully',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Okay'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        })
                    });
            }
        })
    }

    function cancelOR(invoice_no) {
        $.post("/crm3/spc/cancel_or_display", {
                _token: '{{ csrf_token() }}',
                invoice_no: invoice_no,
            },
            function(data) {
                // console.log(data);
                $('#inv-id').val(data.invoice_no);
            });
    }

    function getTag(id) {
        $.get('/edit-red-tags/' + id, function(red) {
            $('#id_tag').val(red[0].id);
            $('.red_tag_remarks').val(red[0].description);

        })
    }


    function getVehicle(id) {
        $.get('/edit_vehicles/' + id, function(response) {
            $('#vehicle_id').val(response[0].id);
            $('#plate2').val(response[0].plate_no);
            $('#type2').val(response[0].type);
            $('#color2').val(response[0].color);
            $('#year_model2').val(response[0].year_model);
            $('#vehicle_series2').val(response[0].series);
            $('#brand2').val(response[0].brand);


        })
    }

    status_changed = (status, v_id) => {
        Swal.fire({
            title: 'Are you sure to update the status of this details?',
            text: "Please, click yes if we proceed",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, updated it!'

        }).then((result) => {
            if (result.isConfirmed) {
                $.get("/update_status_vehicle/" + status + "/" + v_id, {
                        v_id: v_id,
                        status: status,
                    },
                    function() {
                        Swal.fire({
                            title: 'Updated Successfully',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Okay'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        })
                    });
            }
        })

    }

    function done_print() {
        Swal.fire({
            title: 'Printing Complete; cannot be printed again',
            icon: 'error',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Okay'
        })
    }

    $('#invoice_submit').on('submit', function() {
        $('#invoice_submit_btn').prop('disabled', true);
        $('#invoice_submit_btn').html(`<div class="spinner-border spinner-border-sm" role="status">
            </div>
            <br>
          Invoicing.....`);
    })
</script>

