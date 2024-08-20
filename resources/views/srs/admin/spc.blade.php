@extends('layouts.main-app')

@section('title', 'SPCV2.0 - BFFHAI')

@section('links_css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">

<!-- JavaScript -->
<!-- CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"></script>
<!-- Styles -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<!--  -->

<!-- Scripts -->

<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap');

    body {
        font-family: 'Nunito', sans-serif;
    }
</style>
@endsection

@section('content')

<div class="container mt-4 mb-3">
    <div class="card shadow shadow-1" style="border-radius: 15px;">
        <div class="card-body p-5">

            <div class="h3 text-primary fw-bold"> STICKER PRICE CALCULATOR 2.0</div>
            <div class="smallTxt fst-italic">(SRS Prices Calculation V2)</div>
            <hr>
            <!-- <div class="d-flex align-items-center mt-5">
                <div>
                    <i class="fas fa-filter fa-2x text-muted"></i>
                </div>
                <div>
                    <select id="categoryFilter" class="form-select ms-2">
                        <option value="">All</option>
                        <option value="Resident">Resident</option>
                        <option value="Non-resident">Non-resident</option>

                    </select>
                </div>
            </div> -->

            <div class=" d-flex justify-content-between">
                <button class="btn btn-secondary mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    <i class="fa-solid fa-filter"></i> Filters
                </button>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="fas fa-plus-circle"></i> Add Prices
                </button>
            </div>

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif


            <div class="collapse mt-3" id="collapseExample">
                <div class="row g-3">
                    <div class="col-3">
                        <label>Category</label>
                        <select id="category_filter" class="form-select form-select-sm" aria-label="Default select example">
                            <option value="all" selected>All</option>
                            @foreach($categories as $category)
                                <option value="{{$category->name}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <label> Sub-category</label>
                        <select id="sub_category_filter" class="form-select form-select-sm" aria-label="Default select example">
                            <option value="all" selected>All</option>
                            @foreach($subs as $sub)
                                <option value="{{$sub->name}}">{{$sub->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <label> HOA</label>
                        <select id="hoa_filter" class="form-select form-select-sm" aria-label="Default select example">
                            <option value="all" selected>All</option>
                            @foreach($hoas as $hoa)
                                <option value="{{$hoa->name}}">{{$hoa->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <label> Vehicle Type</label>
                        <select id="vehicle_filter" class="form-select form-select-sm" aria-label="Default select example">
                            <option value="all">All</option>
                            <option value="Car">Car</option>
                            <option value="Motorcycle">Motorcycle</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="table-responsive mt-3">
                <table class="table table-bordered " id="myTable">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th scope="col text-nowrap" style="font-size:9px">#</th>
                            <th scope="col text-nowrap" style="font-size:9px">Category</th>
                            <th scope="col text-nowrap" style="font-size:9px">Sub-Category</th>
                             <th scope="col " style="font-size:9px">HOA</th>
                            <th class="col text-center text-nowrap" scope="col" style="font-size:9px">Vehicle Type</th>
                            <th class="col text-center text-nowrap" scope="col" style="font-size:9px">Count Range</th>
                            <th class="col text-center text-nowrap" scope="col" style="font-size:9px">Selling Price</th>
                            <th class="col text-center text-nowrap" scope="col" style="font-size:9px">Base Price</th>
                            <th class="col text-center text-nowrap" scope="col" style="font-size:9px">RMF | EMF</th>
                            <th class="col text-center text-nowrap" scope="col" style="font-size:9px">Security</th>
                            <th class="col text-center text-nowrap" scope="col" style="font-size:9px">Surcharge</th>
                            <th class="col text-center text-nowrap" scope="col" style="font-size:9px">Eff Date</th>
                            <th class="col text-center text-nowrap" scope="col" style="font-size:9px">Exp Date</th>
                            {{-- <th class="col text-center text-nowrap" scope="col" style="font-size:9px">WHO</th> --}}

                            <th class="col text-center text-nowrap" scope="col" style="font-size:9px">OR</th>
                            <th class="col text-center text-nowrap" scope="col" style="font-size:9px">Encoded By</th>
                            <th class="col text-center text-nowrap" scope="col" style="font-size:9px">Action</th>
                        </tr>
                    </thead>
                    <tbody id="table-1-rows">
                        @foreach($scps as $index => $scp)
                        <tr data-category="{{ $scp->category_name }}">
                            <td class="text-nowrap" style="font-size: 9px">{{ $index + 1 }}.</td>
                            <td class="text-nowrap" style="font-size: 9px">{{ $scp->category_name }}</td>
                            <td class="" style="font-size: 9px">{{ $scp->sub_name }}</td>
                            <td class=" text-center" style="font-size: 9px">{{ $scp->hoa != null ? $scp->hoa : '' }}</td>
                            <td class="text-nowrap text-center" style="font-size: 9px">{{ $scp->vehicleType }}</td>
                            <td class="text-nowrap text-center" style="font-size: 9px">{{ $scp->min != null ? $scp->min : '' }} - {{ $scp->max ? $scp->max : 'Above' }}</td>
                            <td class="text-nowrap text-center" style="font-size: 9px">{{ $scp->sellingPrice != null ? number_format($scp->sellingPrice, 2) : '' }}</td>
                            <td class="text-nowrap text-center" style="font-size: 9px">{{ $scp->basePrice != null ? number_format($scp->basePrice, 2) : '' }}</td>
                            <td class="text-nowrap text-center" style="font-size: 9px">{{ $scp->roadMaintFee != '0.00' ? number_format($scp->roadMaintFee, 2) : '' }}</td>
                            <td class="text-nowrap text-center" style="font-size: 9px">{{ $scp->security != '0.00' ? number_format($scp->security, 2) : '' }}</td>
                            <td class="text-nowrap text-center" style="font-size: 9px">{{ $scp->surcharge != null ? number_format($scp->surcharge, 2) : '' }}</td>
                            <td class="text-nowrap text-center" style="font-size: 9px">{{ $scp->surchargeEffDate != null ? $scp->surchargeEffDate : '' }}</td>
                            <td class="text-nowrap text-center" style="font-size: 9px">{{ $scp->surchargeExpDate != null ? $scp->surchargeExpDate : '' }}</td>
                            {{-- <td class="text-nowrap text-center" style="font-size: 9px">{{ $scp->who }}</td> --}}
                            <td class="text-nowrap text-center" style="font-size: 9px">{{ $scp->isOR == 1 ? 'YES' : '' }}</td>
                             <td class="text-nowrap text-center" style="font-size: 9px">{{ $scp->who }}</td>
                            <td class="text-center text-nowrap" style="font-size: 9px">
                                <button data-bs-toggle="modal" onclick="getPrice(<?= $scp->id ?>)" data-bs-target="#exampleModal2" class="btn open-confirm text-muted">
                                    <i class="far fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- ADD MODAL -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"><i class="far fa-square-plus"></i> Add Prices</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/scp-insert" method="POST">
                        @csrf
                        <div class="card" style="border-radius: 10px;">
                            <div class="card-header bg-primary text-white">SPCv2</div>
                            <div class="card-body">
                                <div class="text-uppercase h4 text-info fw-bold mt-4">Sticker Profile</div>
                                <hr>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Category: </label>
                                            <select id="category_id" name="category_id" onchange="categoryChange($(this).val())" class="form-select form-select-sm  mb-3" aria-label=".form-select form-select-sm-lg example" required>
                                                <option value="">---</option>
                                                @foreach ($categories as $category)
                                                <option value="<?= $category->id ?>">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Sub Category: </label>
                                            <select name="sub_category_id" id="sub_category" class="form-select form-select-sm  mb-3" aria-label=".form-select form-select-sm-lg example" required>
                                                <option value="">---</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="" class="form-label">HOA: </label>
                                            <select name="hoa[]" id="hoa" class="form-select form-select-sm  mb-3 hoa-add" aria-label=".form-select form-select-sm-lg example" multiple>
                                                <option value="">---</option>
                                                @foreach($hoas as $hoa)
                                                <option value="{{$hoa->name}}">{{$hoa->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>



                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Vehicle Type: </label>
                                            <select name="vehicleType" class="form-select form-select-sm  mb-3" aria-label=".form-select form-select-sm-lg example" required>
                                                <option value="">---</option>
                                                <option value="Car">Car/Bus/Trucks</option>
                                                <option value="Motorcycle">Motorcycle</option>
                                                <option value="Tricycle">Tricycle</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label">ITEM DATA: </label>
                                            <select name="item_data" id="item_data" class="form-select form-select-sm  mb-3" aria-label=".form-select form-select-sm-lg example">
                                                <option value="">---</option>
                                                @foreach($groups as $group)
                                                <option value="{{$group->id}}">{{$group->item_data_group_code}} - {{$group->item_data_group_desc}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Receipt Type: </label>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" value="1" type="radio" name="isOR" id="orType">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            OR
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" value="0" type="radio" name="isOR" id="crType">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            CR
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="" class="form-label">VAT-INCLUSIVE:</label>
                                            <div class="row">
                                                <div id="vat_yes">
                                                    <div class="col">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked disabled>
                                                            <label class="form-check-label" for="flexCheckDefault" id="vat_check">
                                                                YES
                                                            </label>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div id="vat_no" style="display: none;">
                                                    <div class="col">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked disabled>
                                                            <label class="form-check-label" for="flexCheckDefault" id="vat_check">
                                                                N/A
                                                            </label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Min Qty: </label>
                                            <input type="number" name="min" class="form-control form-control-sm" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Max Qty: </label>
                                            <input type="number" name="max" class="form-control form-control-sm">
                                        </div>
                                    </div>




                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="effDate" class="form-label">Effective On:</label>
                                                <input type="date" name="effDate" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="expDate" class="form-label">Expiry On:</label>
                                                <?php
                                                $expiryDate = date('Y-m-d', strtotime('+180 days'));
                                                ?>
                                                <input type="date" name="expDate" class="form-control form-control-sm" value="<?= $expiryDate ?>" min="<?= date('Y-m-d') ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                </div>


                                <div class="h4 text-uppercase mt-5 text-info fw-bold">Sticker Price</div>
                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3 d-flex align-items-center">
                                            <label for="sellingPrice" class="form-label col-md-4">Selling Price:</label>
                                            <div class="col-md-8">
                                                <input type="number" id="sellingPrice" name="sellingPrice" class="form-control form-control-sm" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3 d-flex align-items-center">
                                            <label for="totalC" class="form-label col-md-4">Total Checker:</label>
                                            <div class="col-md-8">
                                                <input type="number" id="totalC" class="form-control form-control-sm" readonly>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="mb-3 d-flex align-items-center">
                                            <label for="surcharge" class="form-label col-md-4">Surcharge:</label>
                                            <div class="col-md-8">
                                                <input id="surcharge" type="number" name="surcharge" class="form-control form-control-sm">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 d-flex align-items-center">
                                            <label for="stickerPrice" class="form-label col-md-4">Sticker Price:</label>
                                            <div class="col-md-8">
                                                <input type="number" id="stickerPrice" name="stickerPrice" class="form-control form-control-sm" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6"></div>
                                    <div class="col-md-6" id="emfShow">
                                        <div class="mb-3">
                                            <div class="d-flex">
                                                <label for="" class="form-label me-2">Surcharge: </label>
                                                <div> <span id="surchargeText"></span> </div>
                                            </div>
                                            <input type="hidden" class="form-control form-control-sm" readonly>

                                        </div>
                                    </div>

                                    <div class="col-md-6"></div>

                                    <div class="col-md-6" id="secShow">
                                        <div class="mb-3">
                                            <div class="d-flex">
                                                <label for="" class="form-label me-2">Security: </label>
                                                <div> <span id="secText"></span> </div>
                                            </div>
                                            <input type="hidden" id="secu" name="security" class="form-control form-control-sm" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6"></div>
                                    <div class="col-md-6" id="emfShow">
                                        <div class="mb-3">
                                            <div class="d-flex">
                                                <label for="" class="form-label me-2">Road Maint Fee | EMF: </label>
                                                <div> <span id="roadMaintText"></span> </div>
                                            </div>
                                            <input type="hidden" id="roadMaintFee" name="roadMaintFee" class="form-control form-control-sm" readonly>
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="col-md-6"></div>
                                    <div class="col-md-6" id="vatSalesShow">
                                        <div class="mb-3">
                                            <div class="d-flex">
                                                <label for="" class="form-label me-2 fst-italic">Vatable-Sales: </label>
                                                <div> <span id="vatSalesText"></span> </div>
                                            </div>
                                            <input type="hidden" id="vatSales" name="vatSales" step="0.01" class="form-control form-control-sm" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6" id="vatAmmShow">
                                        <div class="mb-3">
                                            <div class="d-flex">
                                                <label for="" class="form-label me-2 fst-italic">Vat (12%): </label>
                                                <div> <span id="vatAmmText"></span> </div>
                                            </div>
                                            <input type="hidden" id="vatAmount" name="vatAmount" step="0.01" class="form-control form-control-sm" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save </button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel2"><i class="far fa-square-plus"></i> Edit Prices</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="scpEditForm" action="/scp-edit" method="POST">
                        <fieldset id="sixHoursPassed">
                        @csrf
                        <div class="card" style="border-radius: 10px;">
                            <div class="card-header bg-primary text-white">SPCv2</div>
                            <div class="card-body">
                                <input type="hidden" name="rowID" id="spID">
                                <div class="text-uppercase h4 text-info fw-bold mt-4">Sticker Profile</div>
                                <h6 id="sixHoursPassedNote"></h6>
                                <hr>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Category: </label>
                                            <select id="category_id2" name="category_id" onchange="categoryChange($(this).val())" class="form-select form-select-sm  mb-3" aria-label=".form-select form-select-sm-lg example" required>
                                                <option value="">---</option>
                                                @foreach ($categories as $category)
                                                <option value="<?= $category->id ?>">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Sub Category: </label>
                                            <select name="sub_category_id" id="sub_category2" class="form-select form-select-sm  mb-3" aria-label=".form-select form-select-sm-lg example" required>
                                                <option value="">---</option>
                                                @foreach($subs as $sub)
                                                <option value="{{$sub->id}}">{{$sub->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                   <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="" class="form-label">HOA: </label>
                                            <select name="hoa" id="edit_hoa" class="form-select form-select-sm mb-3 hoa-edit" aria-label=".form-select form-select-sm-lg example">
                                                <option value="">---</option>
                                                @foreach($hoas as $hoa)
                                                <option value="{{$hoa->name}}">{{$hoa->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                      <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Vehicle Type: </label>
                                            <select name="vehicleType" id="vehicleType2" class="form-select form-select-sm  mb-3" aria-label=".form-select form-select-sm-lg example" required>
                                                <option value="">---</option>
                                                <option value="Car">Car/Bus/Trucks</option>
                                                <option value="Motorcycle">Motorcycle</option>
                                                <option value="Tricycle">Tricycle</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label">ITEM DATA: </label>
                                            <select name="item_data" id="item_data2" class="form-select form-select-sm  mb-3" aria-label=".form-select form-select-sm-lg example">
                                                <option value="">---</option>
                                                @foreach($groups as $group)
                                                <option value="{{$group->id}}">{{$group->item_data_group_code}} - {{$group->item_data_group_desc}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Receipt Type: </label>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" value="1" type="radio" name="isOR" id="orType2">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            OR
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" value="0" type="radio" name="isOR" id="crType2">
                                                        <label class="form-check-label" for="flexRadioDefault2">
                                                            CR
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="" class="form-label">VAT-INCLUSIVE:</label>
                                            <div class="row">
                                                <div id="vat_yes2">
                                                    <div class="col">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked disabled>
                                                            <label class="form-check-label" for="flexCheckDefault" id="vat_check">
                                                                YES
                                                            </label>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div id="vat_no2" style="display: none;">
                                                    <div class="col">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked disabled>
                                                            <label class="form-check-label" for="flexCheckDefault" id="vat_check">
                                                                N/A
                                                            </label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                  

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Min Qty: </label>
                                            <input type="number" id="min2" name="min" class="form-control form-control-sm" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Max Qty: </label>
                                            <input type="number" id="max2" name="max" class="form-control form-control-sm">
                                        </div>
                                    </div>




                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="effDate" class="form-label">Effective On:</label>
                                                <input type="date" id="effDate2" name="effDate" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="expDate" class="form-label">Expiry On:</label>
                                                <?php
                                                $expiryDate = date('Y-m-d', strtotime('+180 days'));
                                                ?>
                                                <input type="date" id="expDate2" name="expDate" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </div>

                                </div>


                                <div class="h4 text-uppercase mt-5 text-info fw-bold">Sticker Price</div>
                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3 d-flex align-items-center">
                                            <label for="sellingPrice" class="form-label col-md-4">Selling Price:</label>
                                            <div class="col-md-8">
                                                <input type="number" id="sellingPrice2" name="sellingPrice" class="form-control form-control-sm" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3 d-flex align-items-center">
                                            <label for="totalC" class="form-label col-md-4">Total Checker:</label>
                                            <div class="col-md-8">
                                                <input type="number" id="totalC2" class="form-control form-control-sm" readonly>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="mb-3 d-flex align-items-center">
                                            <label for="surcharge" class="form-label col-md-4">Surcharge:</label>
                                            <div class="col-md-8">
                                                <input id="surcharge2" type="number" name="surcharge" class="form-control form-control-sm">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 d-flex align-items-center">
                                            <label for="stickerPrice" class="form-label col-md-4">Sticker Price:</label>
                                            <div class="col-md-8">
                                                <input type="number" id="stickerPrice2" name="stickerPrice" class="form-control form-control-sm" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6"></div>
                                    <div class="col-md-6" id="emfShow">
                                        <div class="mb-3">
                                            <div class="d-flex">
                                                <label for="" class="form-label me-2">Surcharge: </label>
                                                <div> <span id="surchargeText2"></span> </div>
                                            </div>
                                            <input type="hidden" id="surcharge_val2" class="form-control form-control-sm" readonly>

                                        </div>
                                    </div>

                                    <div class="col-md-6"></div>

                                    <div class="col-md-6" id="secShow">
                                        <div class="mb-3">
                                            <div class="d-flex">
                                                <label for="" class="form-label me-2">Security: </label>
                                                <div> <span id="secText2"></span> </div>
                                            </div>
                                            <input type="hidden" id="secu2" name="security" class="form-control form-control-sm" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6"></div>
                                    <div class="col-md-6" id="emfShow">
                                        <div class="mb-3">
                                            <div class="d-flex">
                                                <label for="" class="form-label me-2">Road Maint Fee | EMF: </label>
                                                <div> <span id="roadMaintText2"></span> </div>
                                            </div>
                                            <input type="hidden" id="roadMaintFee2" name="roadMaintFee" class="form-control form-control-sm" readonly>
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="col-md-6"></div>
                                    <div class="col-md-6" id="vatSalesShow2">
                                        <div class="mb-3">
                                            <div class="d-flex">
                                                <label for="" class="form-label me-2 fst-italic">Vatable-Sales: </label>
                                                <div> <span id="vatSalesText2"></span> </div>
                                            </div>
                                            <input type="hidden" id="vatSales2" name="vatSales" step="0.01" class="form-control form-control-sm" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6" id="vatAmmShow">
                                        <div class="mb-3">
                                            <div class="d-flex">
                                                <label for="" class="form-label me-2 fst-italic">Vat (12%): </label>
                                                <div> <span id="vatAmmText2"></span> </div>
                                            </div>
                                            <input type="hidden" id="vatAmount2" name="vatAmount" step="0.01" class="form-control form-control-sm" readonly>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                       <button type="submit" class="btn btn-success sixHoursPassed">Save </button>
                   </div>
                   </fieldset>
                </form>
            </div>
        </div>
    </div>

    @endsection

    @section('links_js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>

   <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
    <script>
        function escapeRegExp(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
         }

         $(document).ready(function() {
            var table = $('#myTable').DataTable();

            $('#category_filter, #sub_category_filter, #hoa_filter, #vehicle_filter').on('change', function() {
               var category = $('#category_filter').val();
               var subCategory = $('#sub_category_filter').val();
               var hoa = $('#hoa_filter').val();
               var vehicleType = $('#vehicle_filter').val();

               table.columns(1).search(category !== 'all' ? '^' + escapeRegExp(category) + '$' : '', true, false)
                  .draw();
               table.columns(2).search(subCategory !== 'all' ? '^' + escapeRegExp(subCategory) + '$' : '', true,
                  false).draw();
               table.columns(3).search(hoa !== 'all' ? '^' + escapeRegExp(hoa) + '$' : '', true, false).draw();
               table.columns(4).search(vehicleType !== 'all' ? '^' + escapeRegExp(vehicleType) + '$' : '', true,
                  false).draw();
            });
         });

       $(document).ready(function(){
            $('.hoa-add').select2({
       theme: "bootstrap-5",
       width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
       placeholder: $( this ).data( 'placeholder' ),
       closeOnSelect: false,
            });
         });

       // $(document).ready(function(){
       //     $('.hoa-edit').select2({
       //  theme: "bootstrap-5",
       //  width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
       //  placeholder: $( this ).data( 'placeholder' ),
       //  closeOnSelect: false,
       //          });
       //  });
    </script>
    <script>
        function categoryChange(id) {
            $('#sub_category').html('');
            $('#sub_category').append(`<option value="">--- </option>`);

            // for edit modal
            $('#sub_category2').html('');
            $('#sub_category2').append(`<option value="">--- </option>`);

            $.get('/spcgetSubCategories/' + id, function(response) {
                var orType = document.getElementById("orType");
                var crType = document.getElementById("crType");


                // for edit modal
                var orType2 = document.getElementById("orType2");
                var crType2 = document.getElementById("crType2");

                if (id === "1") {
                    // If resident, disable CR and enable OR
                    // orType.disabled = true;
                    // crType.disabled = false;
                    orType.checked = false;
                    crType.checked = true;
                    $('#vat_no').show();
                    $('#vat_yes').hide();

                    // for edit modal
                    // orType2.disabled = true;
                    // crType2.disabled = false;
                    orType2.checked = false;
                    crType2.checked = true;
                    $('#vat_no2').show();
                    $('#vat_yes2').hide();


                } else if (id == "2") {
                    // If non-resident, enable CR and disable OR
                    // orType.disabled = false;
                    // crType.disabled = true;
                    orType.checked = true;
                    crType.checked = false;
                    $('#vat_yes').show();
                    $('#vat_no').hide();

                    // for edit modal
                    // orType2.disabled = false;
                    // crType2.disabled = true;
                    orType2.checked = true;
                    crType2.checked = false;
                    $('#vat_yes2').show();
                    $('#vat_no2').hide();
                }
                response.forEach(function(subCategory) {

                    var option = `<option value="${subCategory.id}">${subCategory.name}</option>`;
                    $('#sub_category').append(option);
                    $('#sub_category2').append(option);
                });
            });
        }


        function calculateVAT() {
            var sellingPrice = parseFloat($('#sellingPrice').val());
            var categoryId = $('#category_id').val();
            var vatSales = 0;
            var vatAmount = 0;
            var emf = 0;
            var security = 0;
            var surcharge = parseFloat($('#surcharge').val());

            if (categoryId == 1) {
                vatSales = 0;
                vatAmount = 0;
                emf = 0;
                security = 0;
                // $('#vatSalesShow, #vatAmmShow, #secShow ,#emfShow').hide();
                $('#orType').prop('checked', false);
                $('#crType').prop('checked', true);
                $('#isVat').val('NO VAT');

                $('#sellingPrice').on('keyup', function() {
                    var sellingPrice = parseFloat($(this).val());
                    var totalC = sellingPrice;
                    $('#totalC').val(totalC.toFixed(2));
                    recalculateVAT();
                });
            } else {

                vatSales = sellingPrice / 1.12;
                vatAmount = vatSales * 0.12;

                $('#vatSalesShow, #vatAmmShow , #secShow ,#emfShow').show();
                $('#orType').prop('checked', true);
                $('#crType').prop('checked', false);
                $('#isVat').val('YES');
            }

            $('#surcharge').on('input', function() {
                surcharge = parseFloat($(this).val());
                recalculateVAT();
            });

            $('#sellingPrice').on('input', function() {
                sellingPrice = parseFloat($(this).val());
                recalculateVAT();
            });

            function recalculateVAT() {
                var calculatedPrice = sellingPrice;
                if (!isNaN(surcharge)) {
                    calculatedPrice += surcharge;
                }

                if (categoryId != 1) {
                    vatSales = calculatedPrice / 1.12;
                    vatAmount = vatSales * 0.12;
                }


                var totalC = calculatedPrice;
                if (isNaN(surcharge) || surcharge === 0) {
                    totalC = sellingPrice;
                }

                $('#vatSales').val(vatSales.toFixed(2));
                $('#vatAmount').val(vatAmount.toFixed(2));
                $('#roadMaintFee').val(emf.toFixed(2));
                $('#secu').val(security.toFixed(2));
                $('#surcharge').val(surcharge.toFixed(2));
                $('#totalC').val(totalC.toFixed(2));

                $('#vatSalesText').text(vatSales.toFixed(2));
                $('#vatAmmText').text(vatAmount.toFixed(2));
                $('#roadMaintText').text(emf.toFixed(2));
                $('#secuText').text(security.toFixed(2));
                $('#surchargeText').text(surcharge.toFixed(2));
                $('#totalCText').text(totalC.toFixed(2));
            }

            recalculateVAT();
        }

        $(document).ready(function() {

            $('#sellingPrice').on('input', function() {
                calculateVAT();
            });

            $('#stickerPrice').on('input', function() {
                calculateVAT();
            });

        });

        $(document).ready(function() {
            $('#sellingPrice, #stickerPrice, #vatAmount, #surcharge').on('input', function() {
                var categoryId = $('#category_id').val();
                var sellingPrice = parseFloat($('#sellingPrice').val());
                var stickerPrice = parseFloat($('#stickerPrice').val());
                var vatAmount = parseFloat($('#vatAmount').val());
                var surcharge = parseFloat($('#surcharge').val());
                var security = 0;

                if (isNaN(sellingPrice) || isNaN(stickerPrice) || isNaN(vatAmount)) {
                    $('#secu').val('');
                    $('#roadMaintFee').val('');
                    $('#totalC').val('');
                    return;
                }

                if (sellingPrice === 0 || stickerPrice === 0) {
                    $('#secu').val('');
                    $('#roadMaintFee').val('');
                    $('#totalC').val('');
                    return;
                }

                if (isNaN(surcharge)) {
                    surcharge = 0;
                }

                var calculatedPrice = sellingPrice + surcharge;

                var totalAmount = calculatedPrice - stickerPrice;
                security = Math.min(totalAmount, 1000);
                var roadMaintFee = totalAmount - security;
                var totalC = stickerPrice + roadMaintFee + security;

                $('#secu').val(security.toFixed(2));
                $('#roadMaintFee').val(roadMaintFee.toFixed(2));
                $('#roadMaintText').text(roadMaintFee.toFixed(2));
                $('#secText').text(security.toFixed(2));
                $('#totalC').val(totalC.toFixed(2));
            });
        });


        function getPrice(id) {
            var orType2 = document.getElementById("orType2");
            var crType2 = document.getElementById("crType2");

            $.get('/get-price-scp/' + id, function(price) {

               let sixHoursPassed = price.sixHoursPassed;
                
                if(sixHoursPassed == 1){
                    $('#sixHoursPassed').prop('disabled', true);
                    $('#sixHoursPassedNote').text('This price is passed 6 hours. Cannot be edited').addClass('text-info');
                    $('.sixHoursPassed').prop('disabled', true);
                }

                $('#spID').val(price[0].id);
                $('#category_id2').val(price[0].category_id);
                $('#sub_category2').val(price[0].sub_category_id);
                $('#min2').val(price[0].min);
                $('#max2').val(price[0].max);
                $('#vehicleType2').val(price[0].vehicleType);
                $('#expDate2').val(price[0].surchargeExpDate);
                $('#effDate2').val(price[0].surchargeEffDate);
                $('#sellingPrice2').val(price[0].sellingPrice);
                $('#totalC2').val(price[0].sellingPrice);
                $('#surcharge2').val(price[0].surcharge);
                $('#stickerPrice2').val(price[0].basePrice);
                $('#surcharge_val2').val(price[0].surcharge);
                $('#surchargeText2').text(price[0].surcharge);
                $('#secu2').val(price[0].security);
                $('#secText2').text(price[0].security);
                $('#roadMaintFee2').val(price[0].roadMaintFee);
                $('#roadMaintText2').text(price[0].roadMaintFee);
                $('#vatSales2').val(price[0].vatSales);
                $('#vatSalesText2').text(price[0].vatSales);
                $('#vatAmount2').val(price[0].vatAmount);
                $('#vatAmmText2').text(price[0].vatAmount);
                $('#item_data2').val(price[0].spc_item_id);
                $('.hoa-edit').val(price[0].hoa.replace(/"/g, '')).trigger('change');
                console.log(price[0]);
                if (price[0].isOR == 0) {

                    // orType2.disabled = true;
                    // crType2.disabled = false;
                    orType2.checked = false;
                    crType2.checked = true;
                    $('#vat_no2').show();
                    $('#vat_yes2').hide();

                } else {
                    // orType2.disabled = false;
                    // crType2.disabled = true;
                    orType2.checked = true;
                    crType2.checked = false;
                    $('#vat_yes2').show();
                    $('#vat_no2').hide();
                }
            })
        }

        function calculateVAT2() {
            var sellingPrice2 = parseFloat($('#sellingPrice2').val());
            var categoryId2 = $('#category_id2').val();
            var vatSales2 = 0;
            var vatAmount2 = 0;
            var emf2 = 0;
            var security2 = 0;
            var surcharge2 = parseFloat($('#surcharge2').val());

            if (categoryId2 == 1) {
                vatSales2 = 0;
                vatAmount2 = 0;
                emf2 = 0;
                security2 = 0;
                // $('#vatSalesShow, #vatAmmShow, #secShow ,#emfShow').hide();
                $('#orType2').prop('checked', false);
                $('#crType2').prop('checked', true);
                $('#isVat2').val('NO VAT');

                $('#sellingPrice2').on('keyup', function() {
                    var sellingPrice2 = parseFloat($(this).val());
                    var totalC2 = sellingPrice2;
                    $('#totalC2').val(totalC2.toFixed(2));
                    recalculateVAT2();
                });
            } else {

                vatSales2 = sellingPrice2 / 1.12;
                vatAmount2 = vatSales2 * 0.12;

                $('#vatSalesShow2, #vatAmmShow2 , #secShow2 ,#emfShow2').show();
                $('#orType2').prop('checked', true);
                $('#crType2').prop('checked', false);
                $('#isVat2').val('YES');
            }

            $('#surcharge2').on('input', function() {
                surcharge2 = parseFloat($(this).val());
                recalculateVAT2();
            });

            $('#sellingPrice2').on('input', function() {
                sellingPrice2 = parseFloat($(this).val());
                recalculateVAT2();
            });

            function recalculateVAT2() {
                var calculatedPrice2 = sellingPrice2;
                if (!isNaN(surcharge2)) {
                    calculatedPrice2 += surcharge2;
                }

                if (categoryId2 != 1) {
                    vatSales2 = calculatedPrice2 / 1.12;
                    vatAmount2 = vatSales2 * 0.12;
                }


                var totalC2 = calculatedPrice2;
                if (isNaN(surcharge2) || surcharge2 === 0) {
                    totalC2 = sellingPrice2;
                }

                $('#vatSales2').val(vatSales2.toFixed(2));
                $('#vatAmount2').val(vatAmount2.toFixed(2));
                $('#roadMaintFee2').val(emf2.toFixed(2));
                $('#secu2').val(security2.toFixed(2));
                $('#surcharge2').val(surcharge2.toFixed(2));
                $('#totalC2').val(totalC2.toFixed(2));

                $('#vatSalesText2').text(vatSales2.toFixed(2));
                $('#vatAmmText2').text(vatAmount2.toFixed(2));
                $('#roadMaintText2').text(emf2.toFixed(2));
                $('#secuText2').text(security2.toFixed(2));
                $('#surchargeText2').text(surcharge2.toFixed(2));
                $('#totalCText2').text(totalC2.toFixed(2));
            }

            recalculateVAT2();
        }

        $(document).ready(function() {

            $('#sellingPrice2').on('input', function() {
                calculateVAT2();
            });

            $('#stickerPrice2').on('input', function() {
                calculateVAT2();
            });

        });

        $(document).ready(function() {
            $('#sellingPrice2, #stickerPrice2, #vatAmount2, #surcharge2').on('input', function() {
                var categoryId2 = $('#category_id2').val();
                var sellingPrice2 = parseFloat($('#sellingPrice2').val());
                var stickerPrice2 = parseFloat($('#stickerPrice2').val());
                var vatAmount2 = parseFloat($('#vatAmount2').val());
                var surcharge2 = parseFloat($('#surcharge2').val());
                var security2 = 0;

                if (isNaN(sellingPrice2) || isNaN(stickerPrice2) || isNaN(vatAmount2)) {
                    $('#secu2').val('');
                    $('#roadMaintFee2').val('');
                    $('#totalC2').val('');
                    return;
                }

                if (sellingPrice2 === 0 || stickerPrice2 === 0) {
                    $('#secu2').val('');
                    $('#roadMaintFee2').val('');
                    $('#totalC2').val('');
                    return;
                }

                if (isNaN(surcharge2)) {
                    surcharge2 = 0;
                }

                var calculatedPrice2 = sellingPrice2 + surcharge2;

                var totalAmount2 = calculatedPrice2 - stickerPrice2;
                security2 = Math.min(totalAmount2, 1000);
                var roadMaintFee2 = totalAmount2 - security2;
                var totalC2 = stickerPrice2 + roadMaintFee2 + security2;

                $('#secu2').val(security2.toFixed(2));
                $('#roadMaintFee2').val(roadMaintFee2.toFixed(2));
                $('#roadMaintText2').text(roadMaintFee2.toFixed(2));
                $('#secText2').text(security2.toFixed(2));
                $('#totalC2').val(totalC2.toFixed(2));
            });
        });


        $(document).ready(function() {
            $('#table_id').DataTable();
        });

        $(document).ready(function() {
            $('#table_id2').DataTable();
        });


    </script>

    <script>
        $('#exampleModal2').on('hide.bs.modal', function() {
            $('#sixHoursPassed').prop('disabled', false);
            $('#sixHoursPassedNote').text('').removeClass('text-info');
            $('.sixHoursPassed').prop('disabled', false);
        });
    </script>


    <?php
    if (session()->has('success')) {
    ?>

        <script>
            Swal.fire({
                title: '<?php echo  session()->get('success');  ?>',
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
                title: '<?php echo  session()->get('error');  ?>',
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



    @endsection