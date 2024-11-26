@extends('layouts.main-app')

@section('title', 'SPCV2.0 - BFFHAI')

@section('links_css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/crmxi-modal-style.css') }}">

    <!-- JavaScript -->
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"></script>
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="{{ asset('css/spc3-style.css') }}">
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
                <div class="h3 text-primary fw-bold"> STICKER PRICE CALCULATOR 3.0</div>
                <div class="smallTxt fst-italic">(SRS Prices Calculation V3)</div>
                <hr>

                <div class=" d-flex justify-content-end">
                    <button type="button" class="btn btn-success mt-3 btn-sm" data-bs-toggle="modal"
                        data-bs-target="#exampleModal" onclick="changeTitle('Add')">
                        <i class="fas fa-plus-circle"></i> Add Prices
                    </button>
                </div>

                <div>
                    <div class="filter-label">Filter</div>
                    <div class="row filter-container" style="border: 1px solid black;">
                        <div class="col-2 mt-3 mb-3  pe-0">
                            <label>Category</label>
                            <select id="category_filter" class="form-select form-select-sm"
                                aria-label="Default select example">
                                <option value="all" selected>All</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2 mt-3 mb-3  pe-0 ">
                            <label>Sub-category</label>
                            <select id="sub_category_filter" class="form-select form-select-sm"
                                aria-label="Default select example">
                                <option value="all" selected>All</option>
                                @foreach ($subcats as $subcat)
                                    <option value="{{ $subcat->name }}">{{ $subcat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2 mt-3 mb-3  pe-0 ">
                            <label>Member Type</label>
                            <select id="hoatype_filter" class="form-select form-select-sm"
                                aria-label="Default select example">
                                <option value="all" selected>All</option>
                                @foreach ($hoatypes as $hoatype)
                                    <option value="{{ $hoatype->name }}">{{ $hoatype->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2 mt-3 mb-3 pe-0 ">
                            <label>HOA</label>
                            <select id="hoa_filter" class="form-select form-select-sm" aria-label="Default select example">
                                <option value="all" selected>All</option>
                                @foreach ($hoas as $hoa)
                                    <option value="{{ $hoa->name }}">{{ $hoa->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-2 mt-3 mb-3  pe-0 ">
                            <label>Vehicle Ownership Status</label>
                            <select id="vehicle_ownership_filter" class="form-select form-select-sm"
                                aria-label="Default select example">
                                <option value="all" selected>All</option>
                                @foreach ($vehicle_ownership_status as $vos)
                                    <option value="{{ $vos->name }}">{{ $vos->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2 mt-3  mb-3  ">
                            <label>Vehicle Type</label>
                            <select id="vehicle_filter" class="form-select form-select-sm"
                                aria-label="Default select example">
                                <option value="all" selected>All</option>
                                <option value="Car">Car/Bus/Truck</option>
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
                                <th scope="col " style="font-size:9px">HOA Type</th>
                                <th class="col text-center text-nowrap" scope="col" style="font-size:9px">Vehicle
                                    Ownership Status</th>
                                <th class="col text-center text-nowrap" scope="col" style="font-size:9px">Vehicle Type
                                </th>
                                <th class="col text-center text-nowrap" scope="col" style="font-size:9px">Count Range
                                </th>
                                <th class="col text-center text-nowrap" scope="col" style="font-size:9px">Selling Price
                                </th>
                                <th class="col text-center text-nowrap" scope="col" style="font-size:9px">Base Price</th>
                                <th class="col text-center text-nowrap" scope="col" style="font-size:9px">RMF | EMF</th>
                                <th class="col text-center text-nowrap" scope="col" style="font-size:9px">Security
                                </th>
                                <th class="col text-center text-nowrap" scope="col" style="font-size:9px">Surcharge
                                </th>
                                <th class="col text-center text-nowrap" scope="col" style="font-size:9px">Eff Date
                                </th>
                                <th class="col text-center text-nowrap" scope="col" style="font-size:9px">Exp Date
                                </th>
                                {{-- <th class="col text-center text-nowrap" scope="col" style="font-size:9px">WHO</th> --}}

                                <th class="col text-center text-nowrap" scope="col" style="font-size:9px">OR</th>
                                <th class="col text-center text-nowrap" scope="col" style="font-size:9px">Encoded By
                                </th>
                                <th class="col text-center text-nowrap" scope="col" style="font-size:9px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sticker_prices_matrix as $index => $spc)
                                <tr data-category="{{ $spc->category_name }}">
                                    <td class="text-nowrap" style="font-size: 9px">{{ $index + 1 }}.</td>
                                    <td class="text-nowrap" style="font-size: 9px">{{ $spc->category_name }}</td>
                                    <td class="text-nowrap" style="font-size: 9px">{{ $spc->subcat_name }}</td>
                                    <td class="text-nowrap" style="font-size: 9px">{{ $spc->hoa_name ?? '' }}</td>
                                    <td class="text-nowrap" style="font-size: 9px">{{ $spc->hoatype_name }}</td>
                                    <td class="text-nowrap" style="font-size: 9px">{{ $spc->vos_name }}</td>
                                    <td class="text-nowrap text-center" style="font-size: 9px">{{ $spc->vehicleType }}
                                    </td>
                                    <td class="text-nowrap text-center" style="font-size: 9px">
                                        {{ $spc->min != null ? $spc->min : '' }} - {{ $spc->max ? $spc->max : 'Above' }}
                                    </td>
                                    <td class="text-nowrap text-center" style="font-size: 9px">
                                        {{ $spc->sellingPrice != null ? number_format($spc->sellingPrice, 2) : '' }}</td>
                                    <td class="text-nowrap text-center" style="font-size: 9px">
                                        {{ $spc->basePrice != null ? number_format($spc->basePrice, 2) : '' }}</td>
                                    <td class="text-nowrap text-center" style="font-size: 9px">
                                        {{ $spc->roadMaintFee != '0.00' ? number_format($spc->roadMaintFee, 2) : '' }}</td>
                                    <td class="text-nowrap text-center" style="font-size: 9px">
                                        {{ $spc->security != '0.00' ? number_format($spc->security, 2) : '' }}</td>
                                    <td class="text-nowrap text-center" style="font-size: 9px">
                                        {{ $spc->surcharge != null ? number_format($spc->surcharge, 2) : '' }}</td>
                                    <td class="text-nowrap text-center" style="font-size: 9px">
                                        {{ $spc->surchargeEffDate != null ? $spc->surchargeEffDate : '' }}</td>
                                    <td class="text-nowrap text-center" style="font-size: 9px">
                                        {{ $spc->surchargeExpDate != null ? $spc->surchargeExpDate : '' }}</td>
                                    {{-- <td class="text-nowrap text-center" style="font-size: 9px">{{ $spc->who }}</td> --}}
                                    <td class="text-nowrap text-center" style="font-size: 9px">
                                        {{ $spc->isOR == 1 ? 'YES' : '' }}</td>
                                    <td class="text-nowrap text-center" style="font-size: 9px">{{ $spc->who }}</td>
                                    <td class="text-center text-nowrap" style="font-size: 9px">
                                        <button data-bs-toggle="modal"
                                            onclick="getPrice(<?= $spc->id ?>),changeTitle('Edit')"
                                            data-bs-target="#exampleModal" class="btn open-confirm text-muted">
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
    </div>

    <!-- ADD MODAL -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    {{-- <h1 class="modal-title fs-5" id="exampleModalLabel"><i class="far fa-square-plus"></i> Add Prices</h1> --}}
                    <h1 class="modal-title fs-5" id="exampleModalLabel"> Add Prices</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modalBody">
                    <form action="/spc-insert" method="POST">
                        <fieldset id="sixHoursPassed">
                            @csrf
                            <div class="card" style="border-radius: 10px;">
                                <div class="card-header bg-primary text-white">SPC - V3</div>
                                <div class="card-body">
                                    <div class="text-uppercase h4 text-info fw-bold mt-2">Sticker Profile</div>
                                    <input type="hidden" name="rowID" id="spID">
                                    <h6 id="sixHoursPassedNote"></h6>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="" class="form-label">Category: </label>
                                                <select id="category_id" name="category_id"
                                                    onchange="categoryChange($(this).val())"
                                                    class="form-select form-select-sm  mb-3"
                                                    aria-label=".form-select form-select-sm-lg example" required>
                                                    <option value="">---</option>
                                                    @foreach ($categories as $category)
                                                        <option value="<?= $category->id ?>">{{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="" class="form-label">Sub Category: </label>
                                                <select name="sub_category_id" id="sub_category"
                                                    class="form-select form-select-sm  mb-3" disabled
                                                    aria-label=".form-select form-select-sm-lg example"
                                                    onchange="subcatChange($(this).val())" required>
                                                    <option value="">---</option>
                                                    @foreach ($subcats2 as $subcat)
                                                        <option value="{{ $subcat->id }}">{{ $subcat->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="" class="form-label">Member Type: </label>
                                                {{-- <select name="hoa[]" id="hoa" class="form-select form-select-sm  mb-3 hoa-add" aria-label=".form-select form-select-sm-lg example" multiple> --}}
                                                <select name="hoa_type" id="hoa_type"
                                                    onchange="hoatypeChange($(this).val())"
                                                    class="form-select form-select-sm  mb-3 " disabled
                                                    aria-label=".form-select form-select-sm-lg example">
                                                    <option value="">---</option>
                                                    {{-- @foreach ($hoas as $hoa)
                                            <option value="{{$hoa->name}}">{{$hoa->name}}</option>
                                            @endforeach --}}
                                                    @foreach ($hoatypes2 as $hoatype)
                                                        <option id="{{ $hoatype->subcat_hoa_type_id }}" value="{{ $hoatype->id }}">{{ $hoatype->name }}</option>
                                                    @endforeach
                                                </select>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="" class="form-label">HOA: </label>
                                                <select name="hoa[]" id="hoa"
                                                    class="form-select form-select-sm  mb-3 hoa-add"
                                                    aria-label=".form-select form-select-sm-lg example" multiple>
                                                    <option value="">---</option>
                                                    @foreach ($hoas as $hoa)
                                                        <option value="{{ $hoa->id }}">{{ $hoa->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>



                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="" class="form-label "
                                                    style="font-size: 12px;">Vehicle Ownership Type: </label>
                                                {{-- <select name="hoa[]" id="hoa" class="form-select form-select-sm  mb-3 hoa-add" aria-label=".form-select form-select-sm-lg example" multiple> --}}
                                                <select name="vos_type" id="vos_type"
                                                    class="form-select form-select-sm  mb-3 " disabled
                                                    aria-label=".form-select form-select-sm-lg example">
                                                    <option value="">---</option>
                                                    {{-- @foreach ($hoas as $hoa)
                                            <option value="{{$hoa->name}}">{{$hoa->name}}</option>
                                            @endforeach --}}
                                                    @foreach ($vehicle_ownership_status2 as $vos)
                                                        <option id="{{ $vos->subcat_hoatype_id }}" value="{{ $vos->id }}">{{ $vos->name }}</option>
                                                    @endforeach
                                                </select>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="" class="form-label">Vehicle Type: </label>
                                                <select name="vehicleType" id="vehicleType"
                                                    class="form-select form-select-sm  mb-3"
                                                    aria-label=".form-select form-select-sm-lg example" required>
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
                                                <select name="item_data" id="item_data"
                                                    class="form-select form-select-sm  mb-3"
                                                    aria-label=".form-select form-select-sm-lg example">
                                                    <option value="">---</option>
                                                    @foreach ($groups as $group)
                                                        <option value="{{ $group->id }}">
                                                            {{ $group->item_data_group_code }} -
                                                            {{ $group->item_data_group_desc }}</option>
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
                                                            <input class="form-check-input" value="1" type="radio"
                                                                name="isOR" id="orType">
                                                            <label class="form-check-label" for="flexRadioDefault1">
                                                                OR
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-check">
                                                            <input class="form-check-input" value="0" type="radio"
                                                                name="isOR" id="crType">
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
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="" id="flexCheckDefault" checked disabled>
                                                                <label class="form-check-label" for="flexCheckDefault"
                                                                    id="vat_check">
                                                                    YES
                                                                </label>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div id="vat_no" style="display: none;">
                                                        <div class="col">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="" id="flexCheckDefault" checked disabled>
                                                                <label class="form-check-label" for="flexCheckDefault"
                                                                    id="vat_check">
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
                                                <input type="number" name="min" id="minQty"
                                                    class="form-control form-control-sm" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="" class="form-label">Max Qty: </label>
                                                <input type="number" name="max" id="maxQty"
                                                    class="form-control form-control-sm">
                                            </div>
                                        </div>




                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="effDate" class="form-label">Effective On:</label>
                                                    <input type="date" name="effDate"
                                                        class="form-control form-control-sm" value="<?= date('Y-m-d') ?>"
                                                        min="<?= date('Y-m-d') ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="expDate" class="form-label">Expiry On:</label>
                                                    <?php
                                                    $expiryDate = date('Y-m-d', strtotime('+180 days'));
                                                    ?>
                                                    <input type="date" name="expDate"
                                                        class="form-control form-control-sm" value="<?= $expiryDate ?>"
                                                        min="<?= date('Y-m-d') ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="h4 text-uppercase mt-3 text-info fw-bold">Sticker Price</div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3 d-flex align-items-center">
                                                <label for="sellingPrice" class="form-label col-md-4">Selling
                                                    Price:</label>
                                                <div class="col-md-8">
                                                    <input type="number" id="sellingPrice" name="sellingPrice"
                                                        class="form-control form-control-sm" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3 d-flex align-items-center">
                                                <label for="totalC" class="form-label col-md-4">Total Checker:</label>
                                                <div class="col-md-8">
                                                    <input type="number" id="totalC"
                                                        class="form-control form-control-sm" readonly>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="mb-3 d-flex align-items-center">
                                                <label for="surcharge" class="form-label col-md-4">Surcharge:</label>
                                                <div class="col-md-8">
                                                    <input id="surcharge" type="number" name="surcharge"
                                                        class="form-control form-control-sm">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 d-flex align-items-center">
                                                <label for="stickerPrice" class="form-label col-md-4">Sticker
                                                    Price:</label>
                                                <div class="col-md-8">
                                                    <input type="number" id="stickerPrice" name="stickerPrice"
                                                        class="form-control form-control-sm" required>
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
                                                <input type="hidden" id="secu" name="security"
                                                    class="form-control form-control-sm" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6"></div>
                                        <div class="col-md-6" id="emfShow">
                                            <div class="mb-3">
                                                <div class="d-flex">
                                                    <label for="" class="form-label me-2">Road Maint Fee | EMF:
                                                    </label>
                                                    <div> <span id="roadMaintText"></span> </div>
                                                </div>
                                                <input type="hidden" id="roadMaintFee" name="roadMaintFee"
                                                    class="form-control form-control-sm" readonly>
                                                <hr>
                                            </div>
                                        </div>

                                        <div class="col-md-6"></div>
                                        <div class="col-md-6" id="vatSalesShow">
                                            <div class="mb-3">
                                                <div class="d-flex">
                                                    <label for=""
                                                        class="form-label me-2 fst-italic">Vatable-Sales: </label>
                                                    <div> <span id="vatSalesText"></span> </div>
                                                </div>
                                                <input type="hidden" id="vatSales" name="vatSales" step="0.01"
                                                    class="form-control form-control-sm" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6"></div>
                                        <div class="col-md-6" id="vatAmmShow">
                                            <div class="mb-3">
                                                <div class="d-flex">
                                                    <label for="" class="form-label me-2 fst-italic">Vat (12%):
                                                    </label>
                                                    <div> <span id="vatAmmTextvatAmmTextspan> </div>
                                                </div>
                                                <input type="hidden" id="vatAmount" name="vatAmount" step="0.01"
                                                    class="form-control form-control-sm" readonly>
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



@endsection

@section('links_js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

    <script>
        var subcats2 = {!! json_encode($subcats2) !!};
        var hoatypes2 = {!! json_encode($hoatypes2) !!};
        var vehicle_ownership_status2 = {!! json_encode($vehicle_ownership_status2) !!};

        $(document).ready(function() {
            var table = $('#myTable').DataTable();
        })

        function escapeRegExp(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }

        $(document).ready(function() {
            var table = $('#myTable').DataTable();

            $('#category_filter, #sub_category_filter, #hoa_filter, #hoatype_filter, #vehicle_ownership_filter, #vehicle_filter')
                .on('change', function() {
                    var category = $('#category_filter').val();
                    var subCategory = $('#sub_category_filter').val();
                    var hoa = $('#hoa_filter').val();
                    var hoatype = $('#hoatype_filter').val();
                    var vos = $('#vehicle_ownership_filter').val();
                    var vehicleType = $('#vehicle_filter').val();
                    // console.log(vehicleType,'test')
                    table.columns(1).search(category !== 'all' ? '^' + escapeRegExp(category) + '$' : '', true,
                            false)
                        .draw();
                    table.columns(2).search(subCategory !== 'all' ? '^' + escapeRegExp(subCategory) + '$' : '',
                        true,
                        false).draw();
                    table.columns(3).search(hoa !== 'all' ? '^' + escapeRegExp(hoa) + '$' : '', true,
                        false).draw();
                    table.columns(4).search(hoatype !== 'all' ? '^' + escapeRegExp(hoatype) + '$' : '', true,
                        false).draw();
                    table.columns(5).search(vos !== 'all' ? '^' + escapeRegExp(vos) + '$' : '', true, false)
                        .draw();
                    table.columns(6).search(vehicleType !== 'all' ? '^' + escapeRegExp(vehicleType) + '$' : '',
                        true,
                        false).draw();
                });
        });

        $(document).ready(function() {
            $('.hoa-add').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
                closeOnSelect: false,
            });
        });



        function categoryChange(id,selectedId) {
            // $('#sub_category').html('');
            // $('#sub_category').append(`<option value="">--- </option>`);

            // $.get('/spcgetSubCategories/' + id, function(response) {
            var orType = document.getElementById("orType");
            var crType = document.getElementById("crType");
            
            if (id === "1") {
                // If resident, disable CR and enable OR
                orType.checked = false;
                crType.checked = true;
                $('#vat_no').show();
                $('#vat_yes').hide();

            } else if (id == "2") {
                // If non-resident, enable CR and disable OR
                orType.checked = true;
                crType.checked = false;
                $('#vat_yes').show();
                $('#vat_no').hide();

            }
            // response.forEach(function(subCategory) {

            //     var option = `<option value="${subCategory.id}">${subCategory.name}</option>`;
            //     $('#sub_category').append(option);
            // });
            // });
            $(`#sub_category`).prop('disabled',false);
            $(`#sub_category`).val('');
            $(`#hoa_type`).prop('disabled',true);
            $(`#hoa_type`).val('');
            $(`#vos_type`).prop('disabled',true);
            $(`#vos_type`).val('');
            subcats2.forEach(data => {
                if (+data.category_id == id) {
                    $(`#sub_category option[value="${data.id}"]`)
                        .show();
                } else {
                    $(`#sub_category option[value="${data.id}"]`)
                        .hide();
                }
            })

        };

        function subcatChange(id) {
            // Reset all options to be hidden initially
            $('#hoa_type option').hide();
            $(`#hoa_type`).prop('disabled',false);
            $(`#hoa_type`).val('');
            $(`#vos_type`).prop('disabled',true);
            $(`#vos_type`).val('');
            // Show options that match the selected sub_category_id
            hoatypes2.forEach(data => {
                if (data.sub_category_id == id) {
                    $(`#hoa_type option[id="${data.subcat_hoa_type_id}"]`).show();
                }
            });
        }

        function hoatypeChange(id) {
            // Reset all options to be hidden initially
            $('#vos_type option').hide();
            $(`#vos_type`).prop('disabled',false);
            $(`#vos_type`).val('');
            var selectedOptionId = $('#hoa_type option:selected').attr('id');
            console.log(selectedOptionId,'selectedOptionId')
            // Show options that match the selected sub_category_id
            vehicle_ownership_status2.forEach(data => {
                if (data.subcat_hoatype_id == selectedOptionId) {
                    console.log(data,selectedOptionId,'test')
                    $(`#vos_type option[id="${data.subcat_hoatype_id}"]`).show();
                }
            });
        }




        function calculateVAT() {
            var sellingPrice = parseFloat($('#sellingPrice').val());
            var categoryId = $('#category_id').val();
            var subcatId = $('#sub_category').val();
            var hoatypeId = $('#hoa_type').val();
            var vosId = $('#vos_type').val();
            var minQty = $('#minQty').val();
            var maxQty = $('#maxQty').val();
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
                // console.log(maxQty,'maxQty')
                // console.log(minQty,'minQty')
                if (subcatId == 1 && (hoatypeId == 0 || hoatypeId == 1 || hoatypeId == 2)) {
                    if ((vosId == 3 && (minQty == 3 && !maxQty)) ||
                        (vosId == 5 && (minQty == 11 && !maxQty))) {
                        vatSales = sellingPrice / 1.12;
                        vatAmount = vatSales * 0.12;
                    }
                } else if (subcatId == 2 && (hoatypeId == 0 || hoatypeId == 1 || hoatypeId == 2)) {
                    if ((vosId == 1 || vosId == 3 || vosId == 4 || vosId == 5 || vosId == 6) && (minQty == 6 && !maxQty)) {
                        vatSales = sellingPrice / 1.12;
                        vatAmount = vatSales * 0.12;
                    }
                } else if ((subcatId == 3 || subcatId == 4) && (hoatypeId == 0 || hoatypeId == 1 || hoatypeId == 2)) {
                    if ((vosId == 1 || vosId == 3 || vosId == 4 || vosId == 5 || vosId == 6) && (minQty == 4 && !maxQty)) {
                        vatSales = sellingPrice / 1.12;
                        vatAmount = vatSales * 0.12;
                    }
                }
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
                var subcatId = $('#sub_category').val();
                var calculatedPrice = sellingPrice + surcharge;

                var totalAmount = calculatedPrice - stickerPrice;
                if (subcatId == 2 || subcatId == 3 || subcatId == 4) {
                    security = totalAmount
                } else {
                    security = Math.min(totalAmount, 1000);
                }
                // security = Math.min(totalAmount, 1000);
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
            // var orType = document.getElementById("orType");
            // var crType = document.getElementById("crType");
            $("#hoa").removeClass("hoa[]").addClass("hoa");
            $("#hoa").prop('multiple',false);
            $.get('/get-price-spc/' + id, function(price) {
                // console.log(price,'price')
                let sixHoursPassed = price.sixHoursPassed;

                if (sixHoursPassed == 1) {
                    $('#sixHoursPassed').prop('disabled', true);
                    $('#sixHoursPassedNote').text('This price is passed 6 hours. Cannot be edited').addClass(
                        'text-info');
                    $('.sixHoursPassed').prop('disabled', true);
                }

                $('#spID').val(price[0].id);
                $('#category_id').val(price[0].category_id);
                $('#sub_category').val(price[0].sub_category_id);
                $('#hoa').val(price[0].hoa);
                $('#hoa_type').val(price[0].hoa_type_id);
                $('#vos_type').val(price[0].vehicle_ownership_status_id);
                $('#minQty').val(price[0].min);
                $('#maxQty').val(price[0].max);
                $('#vehicleType').val(price[0].vehicleType);
                $('#expDate').val(price[0].surchargeExpDate);
                $('#effDate').val(price[0].surchargeEffDate);
                $('#sellingPrice').val(price[0].sellingPrice);
                $('#totalC').val(price[0].sellingPrice);
                $('#surcharge').val(price[0].surcharge);
                $('#stickerPrice').val(price[0].basePrice);
                $('#surcharge_val').val(price[0].surcharge);
                $('#surchargeText').text(price[0].surcharge);
                $('#secu').val(price[0].security);
                $('#secText').text(price[0].security);
                $('#roadMaintFee').val(price[0].roadMaintFee);
                $('#roadMaintText').text(price[0].roadMaintFee);
                $('#vatSales').val(price[0].vatSales);
                $('#vatSalesText').text(price[0].vatSales);
                $('#vatAmount').val(price[0].vatAmount);
                $('#vatAmmText').text(price[0].vatAmount);
                $('#item_data').val(price[0].spc_item_id);
                $('.hoa-add').val(price[0].hoa ? price[0].hoa.replace(/"/g, '') : []).trigger('change');
                // console.log(price[0]);
                if (price[0].isOR == 0) {
                    // console.log('test')
                    // orType.disabled = true;
                    // crType.disabled = false;
                    orType.checked = false;
                    crType.checked = true;
                    $('#vat_no').show();
                    $('#vat_yes').hide();

                } else {
                    // orType.disabled = false;
                    // crType.disabled = true;
                    orType.checked = true;
                    crType.checked = false;
                    $('#vat_yes').show();
                    $('#vat_no').hide();
                }

                $(`#sub_category`).prop('disabled',false);
                $(`#hoa_type`).prop('disabled',false);
                $(`#vos_type`).prop('disabled',false);
            })
        };

        function changeTitle(title) {
            $('#exampleModalLabel').text(`${title} Prices`)
        };


        $('#exampleModal').on('hide.bs.modal', function() {
            $('#sixHoursPassed').prop('disabled', false);
            $('#sixHoursPassedNote').text('').removeClass('text-info');
            $('.sixHoursPassed').prop('disabled', false);
            $("#hoa").removeClass("hoa").addClass("hoa[]");
        });
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

    <style>
        table.dataTable>thead>tr>th:nth-child(n+2):nth-child(-n+6) {
            /* width: 100px; */
            min-width: 100px !important;
            max-width: 100px !important;
        }

        table.dataTable>thead>tr>th:nth-child(n+2):nth-child(-n+6) {
            min-width: 100px !important;
            max-width: 100px !important;
            /* white-space: wrap !important; */
        }

        .text-nowrap {
            white-space: normal !important;
        }
    </style>

@endsection
