<link rel="stylesheet" href="{{ asset('css/crmxi-modal-style.css') }}">

<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-labelledby="addVehicleModalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content modalContent">
            <div class="modal-header py-2 modalHeader">
                <div style="display:flex; justify-content:space-between;width: 100%;">
                    <h1 class="modal-title fs-3 " id="addVehicleModalLabel"> ADD VEHICLE/S</h1>
                    <button type="button" class="btn btn-lg " data-bs-dismiss="modal" aria-label="Close"
                        style="color:white;">
                        <i class="fa-solid fa-times fa-xl"></i>
                    </button>
                </div>
            </div>
            <div>
                <form id="vehicleForm"
                    action="{{ url('insert_vehicle') . '?account_id=' . urlencode($crms_account[0]->account_id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body modalBody">
                        <div style="width: 100%;height: 30px;" class="d-flex justify-start mt-2 mb-2">
                            <div style="width: 100%;text-align: end;">
                                <button type="button" id="addVehicleBtn" class="addBtn btn btn-sm text-white"
                                    onclick="vehicleForm()">
                                    <span>Add Vehicle</span>
                                </button>
                            </div>
                        </div>
                        <div class="card mb-4" style="border: 1px solid black">
                            <div class="infoHeader card-header">
                                <div class="h5 fw-bold indiInputs ">
                                    <i class="fas fa-car me-1"></i>
                                    <span>Vehicle Information (1)</span>
                                </div>
                            </div>
                            <input type="hidden" id="current_vehicle_id1" name="toPass[0][current_vehicle_id]"
                                class="form-control">
                            <input type="hidden" id="current_owner_id1" name="toPass[0][current_owner_id]"
                                class="form-control">
                            <div class="card-body pt-1 ps-4 pe-4">
                                <div class="row">
                                    <div class="col-md-3 mt-3 ">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Plate no. : </label>
                                            <input style="border: 1px solid black;" type="text" id="plate_no1"
                                                name="toPass[0][plate]" class="form-control"
                                                onchange="checkPlateNo($(this).val())">
                                            <span class="error-message-plate_no1"
                                                style="color:red; display:none;"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-3 ">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Brand : </label>
                                            <select style="border: 1px solid black;" name="toPass[0][brand]"
                                                id="brand1" class="form-select" aria-label="Default select example">
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
                                    </div>
                                    <div class="col-md-3 mt-3 ">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Series : </label>
                                            <input style="border: 1px solid black;" type="text"
                                                id="vehicle_series1" name="toPass[0][vehicle_series]"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-3 ">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Year / Model : </label>
                                            <select style="border: 1px solid black;" id="year_model1"
                                                name="toPass[0][year_model]" class="form-select"
                                                aria-label="Default select example">
                                                <option value="" selected>---</option>
                                                <?php
                                                $years = range(1975, strftime("%Y", time()));
                                                foreach ($years as $year) : ?>
                                                <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                                <?php endforeach; ?>
                                            </select></label>
                                        </div>
                                    </div>

                                    <div class="col-md-3 mt-3 ">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Color : </label>
                                            <select style="border: 1px solid black;" name="toPass[0][color]"
                                                id="color1" class="form-select"
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
                                    </div>
                                    <div class="col-md-3 mt-3 ">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Type : </label>
                                            <select style="border: 1px solid black;" name="toPass[0][type]"
                                                id="type1" class="form-select"
                                                aria-label="Default select example">
                                                <option selected>---</option>
                                                <option value="Car">Car</option>
                                                <option value="Motorcycle">Motorcycle</option>
                                            </select></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-3 ">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">OR ID : </label>
                                            <input style="border: 1px solid black;" type="text" id="orID1"
                                                name="toPass[0][orID]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-3 ">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">CR ID : </label>
                                            <input style="border: 1px solid black;" type="text" id="crID1"
                                                name="toPass[0][crID]" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-3 mt-3 ">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Old Sticker Year : </label>
                                            <input style="border: 1px solid black;" type="text" id="sticker_year1"
                                                name="toPass[0][sticker_year]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3 ">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Old Sticker No. : </label>
                                            <input style="border: 1px solid black;" type="text" id="sticker_no1"
                                                name="toPass[0][sticker_no]" class="form-control">
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-6 formFileDiv">
                                        <div class="mt-3">
                                            <label for="formFile" class="form-label">OR</label>
                                            <input style="border: 1px solid black;" class="form-control"
                                                type="file" name="toPass[0][or]" id="formFile">
                                        </div>
                                    </div>
                                    <div class="col-md-6 formFileDiv">
                                        <div class="mt-3">
                                            <label for="formFile" class="form-label">CR</label>
                                            <input style="border: 1px solid black;" class="form-control"
                                                type="file" name="toPass[0][cr]" id="formFile">
                                        </div>
                                    </div> --}}

                                    {{-- <div class="col-md-6">
                                        <div class="mt-3">
                                            <label for="formFile" class="form-label">Vehicle Picture</label>
                                            <input style="border: 1px solid black;" class="form-control" type="file" name="toPass[0][vehicle_pic]" id="formFile">
                                        </div>
                                    </div> --}}

                                    <div class="col-md-12">
                                        <div class="mt-3">
                                            <span class="fs-5 fw-bold me-5">Owner Information :</span>
                                            <input class="me-1" type="checkbox" id="acctInfo1"
                                                onclick="getAccountInfo(1)">
                                            <label for="acctInfo" class="me-3" style="align-self: center;"> same as
                                                account </label>
                                            <div style="width: 100%; border-top: 1px solid black;"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">First name: </label>
                                            <input style="border: 1px solid black;" type="text" id="first_name1"
                                                name="toPass[0][first_name]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4 indiInputs mt-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Middle name: </label>
                                            <input style="border: 1px solid black;" type="text" id="middle_name1"
                                                name="toPass[0][middle_name]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4 indiInputs mt-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Last name: </label>
                                            <input style="border: 1px solid black;" type="text" id="last_name1"
                                                name="toPass[0][last_name]" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Main Contact no. : </label>
                                            <input style="border: 1px solid black;" type="tel" id="main_contact1"
                                                name="toPass[0][main_contact]" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Secondary Contact no. : </label>
                                            <input style="border: 1px solid black;" type="tel"
                                                id="secondary_contact1" name="toPass[0][secondary_contact]"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Alternative Email Address: </label>
                                            <input style="border: 1px solid black;" type="tel" id="email1"
                                                name="toPass[0][email]" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-2">
                                        <div>
                                            <label class="form-label mt-2">Select Address : </label>
                                            <select style="border: 1px solid black;" name="toPass[0][owner_address]"
                                                id="owner_address1" class="form-select"
                                                aria-label="Default select example"
                                                onchange="getAddress($(this).val(),1)">
                                                <option selected>---</option>
                                                @foreach ($crms_account[0]->acc_address as $i => $address)
                                                    <option value="{{ $address->id }}">Address {{ $i + 1 }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Vehicle Ownership Status : </label>
                                            <select style="border: 1px solid black;" id="vos1"
                                                name="toPass[0][vos]" class="form-select"
                                                aria-label="Default select example" disabled>
                                                <option value="">---</option>
                                                @foreach ($vehicle_ownership_status as $vos)
                                                    <option id="<?= "$vos->id-$vos->subcat_hoatype_id" ?>"
                                                        value="<?= "$vos->id" ?>">{{ $vos->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="row ms-0 mt-1" style="border: 1px solid gray;width: 100%;">
                                            <div class="col-md-4 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">Category : </label>
                                                    <select style="border: 1px solid black;"
                                                        name="toPass[0][category_id]" id="category1"
                                                        class="form-select " aria-label=" example" disabled>
                                                        <option value="">---</option>
                                                        @foreach ($categories as $category)
                                                            <option value="<?= $category->id ?>">{{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="col-md-4 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">Sub Category : </label>
                                                    <select style="border: 1px solid black;"
                                                        name="toPass[0][sub_category_id]" id="sub_category1"
                                                        class="form-select " aria-label="Default select example"
                                                        disabled required>
                                                        <option value="">---</option>
                                                        @foreach ($subcats as $subcat)
                                                            <option value="<?= $subcat->id ?>">{{ $subcat->name }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">HOA : </label>
                                                    <select style="border: 1px solid black;" id="hoa1"
                                                        name="toPass[0][hoa]" class="form-select"
                                                        aria-label="Default select example" disabled>
                                                        <option value="">---</option>
                                                        @foreach ($hoas as $hoa)
                                                            <option value="<?= $hoa->id ?>">{{ $hoa->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">Member Type : </label>
                                                    <select style="border: 1px solid black;" id="hoa_type1"
                                                        name="toPass[0][hoa_type]" class="form-select"
                                                        aria-label="Default select example" disabled disabled>
                                                        <option value="">---</option>
                                                        @foreach ($hoatypes as $hoatype)
                                                            <option value="<?= "$hoatype->id"
                                                                ?>">{{ $hoatype->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">Block : </label>
                                                    <input style="border: 1px solid black;" type="number"
                                                        id="block1" name="toPass[0][block]" class="form-control"
                                                        disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">Lot : </label>
                                                    <input style="border: 1px solid black;" type="number"
                                                        id="lot1" name="toPass[0][lot]" class="form-control"
                                                        disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">House Number : </label>
                                                    <input style="border: 1px solid black;" type="number"
                                                        id="house_number1" name="toPass[0][house_number]"
                                                        class="form-control" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">Street : </label>
                                                    <input style="border: 1px solid black;" type="text"
                                                        id="street1" name="toPass[0][street]" class="form-control"
                                                        disabled>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">Building / Apartment / Condo :
                                                    </label>
                                                    <input style="border: 1px solid black;" type="text"
                                                        id="building_name1" name="toPass[0][building_name]"
                                                        class="form-control" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">Subdivision / Village : </label>
                                                    <input style="border: 1px solid black;" type="text"
                                                        id="subdivision1" name="toPass[0][subdivision]"
                                                        class="form-control" disabled>
                                                </div>
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">City : </label>
                                                    <select style="border: 1px solid black;" name="toPass[0][city]"
                                                        id="city1" class="form-select"
                                                        aria-label="Default select example" disabled>
                                                        <option selected>---</option>
                                                        @foreach ($cities as $city)
                                                            <option value="{{ $city->bl_id }}">
                                                                {{ $city->description }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <div class="mb-1">
                                                    <label class="form-label mb-2">Zip Code : </label>
                                                    {{-- <select style="border: 1px solid black;" name="toPass[0][zip_code]"
                                                        id="zip_code1" class="form-select " aria-label=" example" required>
                                                        <option value="">---</option>

                                                    </select> --}}
                                                    <input style="border: 1px solid black;" type="number"
                                                        id="zip_code1" name="toPass[0][zip_code]"
                                                        class="form-control" disabled>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    {{-- <div class="col-md-4 compInputs mb-3"> --}}
                                    {{-- <div class="mb-1">
                                                    <label class="form-label mb-2">Email : </label>
                                                    <input style="border: 1px solid black;" type="email" id="email1"
                                                        name="toPass[0][email]" class="form-control">
                                                </div> --}}
                                    {{-- </div> --}}

                                    {{-- <div class="col-md-6 formFileDiv">
                                                <div class="mb-3">
                                                    <label for="formFile" class="form-label">Driver License (Front)</label>
                                                    <input style="border: 1px solid black;" class="form-control"
                                                        type="file" name="toPass[0][front_license]" id="formFile">
                                                </div>
                                            </div>
                                            <div class="col-md-6 formFileDiv">
                                                <div class="mb-3">
                                                    <label for="formFile" class="form-label">Driver License (Back)</label>
                                                    <input style="border: 1px solid black;" class="form-control"
                                                        type="file" name="toPass[0][back_license]" id="formFile">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- <div class="col-md-2 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mt-2">Block : </label>
                                            <input style="border: 1px solid black;" type="number" id="block1"
                                                name="toPass[0][block]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mt-2">Lot : </label>
                                            <input style="border: 1px solid black;" type="number" id="lot1"
                                                name="toPass[0][lot]" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mt-2">House Number : </label>
                                            <input style="border: 1px solid black;" type="number" id="house_number1"
                                                name="toPass[0][house_number]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mt-2">Street : </label>
                                            <input style="border: 1px solid black;" type="text" id="street1"
                                                name="toPass[0][street]" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Building / Apartment / Condo : </label>
                                            <input style="border: 1px solid black;" type="text" id="building_name1"
                                                name="toPass[0][building_name]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Subdivision / Village : </label>
                                            <input style="border: 1px solid black;" type="text" id="subdivision1"
                                                name="toPass[0][subdivision]" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">City : </label>
                                            <select style="border: 1px solid black;" name="toPass[0][city]" id="city1"
                                                class="form-select" aria-label="Default select example"
                                                onchange="cityChange($(this).val(),vehicleCount)">
                                                <option selected>---</option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->bl_id }}">{{ $city->description }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Zip Code : </label>
                                            <select style="border: 1px solid black;" name="toPass[0][zip_code]"
                                                id="zip_code1" class="form-select " aria-label=" example" required>
                                                <option value="">---</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Category : </label>
                                            <select style="border: 1px solid black;" name="toPass[0][category_id]"
                                                onchange="categoryChange($(this).val(),1)" id="category1"
                                                class="form-select " aria-label=" example" required>
                                                <option value="">---</option>
                                                @foreach ($categories as $category)
                                                    <option value="<?= $category->id ?>">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-4 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Sub Category : </label>
                                            <select style="border: 1px solid black;" name="toPass[0][sub_category_id]"
                                                onchange="sub_categoryChange($(this).val(),1)" id="sub_category1"
                                                class="form-select " aria-label="Default select example" disabled
                                                required>
                                                <option value="">---</option>
                                                @foreach ($subcats as $subcat)
                                                    <option value="<?= $subcat->id ?>">{{ $subcat->name }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">HOA : </label>
                                            <select style="border: 1px solid black;" id="hoa"
                                                name="toPass[0][hoa]" class="form-select"
                                                aria-label="Default select example">
                                                <option value="">---</option>
                                                @foreach ($hoas as $hoa)
                                                    <option value="<?= $hoa->id ?>">{{ $hoa->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Member Type : </label>
                                            <select style="border: 1px solid black;" id="hoa_type1"
                                                name="toPass[0][hoa_type]" onchange="hoa_change($(this).val(),1)"
                                                class="form-select" aria-label="Default select example" disabled
                                                required>
                                                <option value="">---</option>
                                                @foreach ($hoatypes as $hoatype)
                                                    <option value="<?= "$hoatype->id-$hoatype->subcat_hoa_type_id" ?>">{{ $hoatype->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Vehicle Ownership Status : </label>
                                            <select style="border: 1px solid black;" id="vos1"
                                                name="toPass[0][vos]" class="form-select"
                                                aria-label="Default select example" disabled>
                                                <option value="">---</option>
                                                @foreach ($vehicle_ownership_status as $vos)
                                                    <option id="<?= "$vos->id-$vos->subcat_hoatype_id" ?>"
                                                        value="<?= "$vos->id" ?>">{{ $vos->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}

                                    {{-- <div class="col-md-4 compInputs mb-3"> --}}
                                    {{-- <div class="mb-1">
                                            <label class="form-label mb-2">Email : </label>
                                            <input style="border: 1px solid black;" type="email" id="email1"
                                                name="toPass[0][email]" class="form-control">
                                        </div> --}}
                                    {{-- </div> --}}

                                    {{-- <div class="col-md-6 formFileDiv">
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label">Driver License (Front)</label>
                                            <input style="border: 1px solid black;" class="form-control"
                                                type="file" name="toPass[0][front_license]" id="formFile">
                                        </div>
                                    </div>
                                    <div class="col-md-6 formFileDiv">
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label">Driver License (Back)</label>
                                            <input style="border: 1px solid black;" class="form-control"
                                                type="file" name="toPass[0][back_license]" id="formFile">
                                        </div>
                                    </div> --}}

                                </div>
                            </div>
                        </div>
                        <div id="input_vehicle"></div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="submit_vehicle" class="btn btn-primary">Submit</button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    let vehicleCount = 1;
    var subcats = {!! json_encode($subcats) !!};
    var hoatypes = {!! json_encode($hoatypes) !!};
    var vehicle_ownership_status = {!! json_encode($vehicle_ownership_status) !!};

    $(document).ready(function() {
        getRecentInfo();
    });



    function categoryChange(id, counter, selectedSubCategoryId = null) {
        let toCheckSelected = $(`#sub_category${counter == vehicleCount ? vehicleCount : counter}`).val()
        let toCheckSelectedHoa = $(`#hoa_type${counter == vehicleCount ? vehicleCount : counter}`).val()
        let toCheckSelectedVos = $(`#vos${counter == vehicleCount ? vehicleCount : counter}`).val()
        if (toCheckSelected) {
            $(`#sub_category${counter == vehicleCount ? vehicleCount : counter}`).val('')
        }
        if (toCheckSelectedHoa) {
            $(`#hoa_type${counter == vehicleCount ? vehicleCount : counter}`).val('')
        }
        if (toCheckSelectedVos) {
            $(`#vos${counter == vehicleCount ? vehicleCount : counter}`).val('')
        }

        // if (counter == vehicleCount) {
        //     $('#sub_category' + vehicleCount).prop('disabled', false);
        // } else {
        //     $('#sub_category' + counter).prop('disabled', false);
        // }

        subcats.forEach(data => {
            if (+data.category_id == id) {
                $(`#sub_category${counter == vehicleCount ? vehicleCount : counter} option[value="${data.id}"]`)
                    .show();
            } else {
                $(`#sub_category${counter == vehicleCount ? vehicleCount : counter} option[value="${data.id}"]`)
                    .hide();
            }
        })

        if (selectedSubCategoryId) {
            $('#sub_category' + counter).val(selectedSubCategoryId);
        }

    };

    function sub_categoryChange(id, counter, selectedHoaTypeId = null) {
        let toCheckSelected = $(`#hoa_type${counter == vehicleCount ? vehicleCount : counter}`).val()
        let toCheckSelectedVos = $(`#vos${counter == vehicleCount ? vehicleCount : counter}`).val()

        if (toCheckSelected) {
            $(`#hoa_type${counter == vehicleCount ? vehicleCount : counter}`).val('')
        }
        if (toCheckSelectedVos) {
            $(`#vos${counter == vehicleCount ? vehicleCount : counter}`).val('')
        }

        // if (counter == vehicleCount) {
        //     $('#hoa_type' + vehicleCount).prop('disabled', false);
        // } else {
        //     $('#hoa_type' + counter).prop('disabled', false);
        // }

        hoatypes.forEach(data => {
            if (+data.sub_category_id == id) {
                $(`#hoa_type${counter == vehicleCount ? vehicleCount : counter} option[value="${data.id}"]`)
                    .show();
                if (id == 40 || id == 44) {
                    $(`#hoa_type${counter == vehicleCount ? vehicleCount : counter} option[value="${data.id}"]`)
                        .prop('selected', true)
                    // $('#vos' + vehicleCount).prop('disabled', false);
                    hoa_change(`${data.id}`, counter)
                }
            } else {
                $(`#hoa_type${counter == vehicleCount ? vehicleCount : counter} option[value="${data.id}"]`)
                    .hide();
            }
            if (+data.id == selectedHoaTypeId) {
                $(`#hoa_type${counter == vehicleCount ? vehicleCount : counter} option[value="${data.id}"]`)
                    .prop('selected', true)
                hoa_change(`${data.id}`, counter)
            }
        })

    };

    function hoa_change(id, counter, selectedVosId = null) {
        // console.log(vehicle_ownership_status,'vosss')
        // console.log(hoatypes,'hoatypes')
        if (typeof(id) == 'number') {
            vehicle_ownership_status.forEach(data => {
                if (+data.id == selectedVosId) {
                    $(`#vos${counter == vehicleCount ? vehicleCount : counter} option[id="${data.id}-${data.subcat_hoatype_id}"]`)
                        .prop('selected', true);
                    return;
                }
            })
        }
        // let splitID = id.split('-')

        let getSubcatHoaTypeID = hoatypes.filter(rec => {
            return rec.sub_category_id == id
        })[0].subcat_hoa_type_id
        // console.log(getSubcatHoaTypeID,id,'getSubcatHoaTypeID')
        let toCheckSelected = $(`#vos${counter == vehicleCount ? vehicleCount : counter}`).val()
        if (toCheckSelected) {
            $(`#vos${counter == vehicleCount ? vehicleCount : counter}`).val('')
        }

        if (counter == vehicleCount) {
            $('#vos' + vehicleCount).prop('disabled', false);
        } else {
            $('#vos' + counter).prop('disabled', false);
        }
        // console.log(id,'id of vos')
        vehicle_ownership_status.forEach(data => {
            if (+data.subcat_hoatype_id == +getSubcatHoaTypeID) {
                $(`#vos${counter == vehicleCount ? vehicleCount : counter} option[id="${data.id}-${data.subcat_hoatype_id}"]`)
                    .show();
            } else {
                $(`#vos${counter == vehicleCount ? vehicleCount : counter} option[id="${data.id}-${data.subcat_hoatype_id}"]`)
                    .hide();
            }
        })


    };

    function cityChange(id, counter, selectedZipcode = null) {
        $('#zip_code' + counter).html('');
        $('#zip_code' + counter).append(`<option value="">--- </option>`);

        $.get("{{ url('crmxi_getZipcode') }}" + `/${id}`, function(response) {

            response.forEach(function(a) {
                var e = `<option value="${a.zip_code}">${a.zip_code}</option>`;
                $('#zip_code' + counter).append(e);
                $(`#zip_code${counter} option[value="${a.zip_code}"]`).prop('selected', true)
            });

            // Set the selected subcategory if provided
            if (selectedZipcode) {
                $('#zip_code' + counter).val(selectedZipcode);
            }
        });
    }

    function vehicleForm() {
        ++vehicleCount;
        var form = `
            <div id="myInputs-${vehicleCount}" class="mb-4">
                <div class="card" style="border: 1px solid black">
                            <div class="infoHeader card-header">
                                <div class="h5 fw-bold indiInputs ">
                                    <i class="fas fa-car me-1"></i>
                                    <span>Vehicle Information (${vehicleCount})</span>
                                </div>
                            </div>
                            <div class="card-body pt-1 ps-4 pe-4">
                                <div class="row">
                                    <div class="col-md-3 mt-3 " >
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Plate no. : </label>
                                            <input style="border: 1px solid black;" type="text" name="toPass[${vehicleCount-1}][plate]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-3 " >
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Brand : </label>
                                            <select style="border: 1px solid black;" name="toPass[${vehicleCount-1}][brand]" class="form-select" aria-label="Default select example">
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
                                    </div>
                                    <div class="col-md-3 mt-3 " >
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Series : </label>
                                            <input style="border: 1px solid black;" type="text" name="toPass[${vehicleCount-1}][vehicle_series]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-3 " >
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Year / Model : </label>
                                            <select style="border: 1px solid black;" id="year_model" name="toPass[${vehicleCount-1}][year_model]" class="form-select" aria-label="Default select example">
                                                <option value="" selected>---</option>
                                                <?php
                                                $years = range(1975, strftime("%Y", time()));
                                                foreach ($years as $year) : ?>
                                                    <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                                <?php endforeach; ?>
                                            </select></label>
                                        </div>
                                    </div>

                                    <div class="col-md-3 mt-3 " >
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Color : </label>
                                            <select style="border: 1px solid black;" name="toPass[${vehicleCount-1}][color]" class="form-select" aria-label="Default select example">
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
                                    </div>
                                    <div class="col-md-3 mt-3 " >
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Type : </label>
                                            <select style="border: 1px solid black;" name="toPass[${vehicleCount-1}][type]" class="form-select" aria-label="Default select example">
                                                <option selected>---</option>
                                                <option value="Car">Car</option>
                                                <option value="Motorcycle">Motorcycle</option>
                                            </select></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-3 " >
                                        <div class="mb-1">
                                            <label class="form-label mb-2">OR ID : </label>
                                            <input style="border: 1px solid black;" type="text" name="toPass[${vehicleCount-1}][orID]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-3 " >
                                        <div class="mb-1">
                                            <label class="form-label mb-2">CR ID : </label>
                                            <input style="border: 1px solid black;" type="text" name="toPass[${vehicleCount-1}][crID]" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-3 mt-3 " >
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Old Sticker Year : </label>
                                            <input style="border: 1px solid black;" type="text" name="toPass[${vehicleCount-1}][sticker_year]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3 " >
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Old Sticker No. : </label>
                                            <input style="border: 1px solid black;" type="text" name="toPass[${vehicleCount-1}][sticker_no]" class="form-control">
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-6">
                                        <div class="mt-3">
                                            <label for="formFile" class="form-label">OR</label>
                                            <input style="border: 1px solid black;" class="form-control" type="file" name="toPass[${vehicleCount-1}][or]" id="formFile">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mt-3">
                                            <label for="formFile" class="form-label">CR</label>
                                            <input style="border: 1px solid black;" class="form-control" type="file" name="toPass[${vehicleCount-1}][cr]" id="formFile">
                                        </div>
                                    </div>  --}}

                                    {{--  <div class="col-md-6">
                                        <div class="mt-3">
                                            <label for="formFile" class="form-label">Vehicle Picture</label>
                                            <input style="border: 1px solid black;" class="form-control" type="file" name="toPass[${vehicleCount-1}][vehicle_pic]" id="formFile">
                                         </div>
                                    </div> --}}

                                    <div class="col-md-12">
                                        <div class="mt-3">
                                            <div class="d-flex justify-start">
                                                <span class="fs-5 fw-bold me-5">Owner Information :</span>
                                                <input class="me-1" type="checkbox" id="acctInfo${vehicleCount}" onclick="getAccountInfo(${vehicleCount})" >
                                                <label for="acctInfo" class="me-3" style="align-self: center;"> same as account </label>
                                                <input class="me-1" type="checkbox" id="recentInfo${vehicleCount}" onclick="getRecentInfo(${vehicleCount})" >
                                                <label for="recentInfo" style="align-self: center;"> same as above </label>
                                            </div>
                                            <div style="width: 100%; border-top: 1px solid black;"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-3" >
                                        <div class="mb-1">
                                            <label class="form-label mb-2" >First name: </label>
                                            <input style="border: 1px solid black;" type="text" id="first_name" name="toPass[${vehicleCount-1}][first_name]" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-md-4 indiInputs mt-3" >
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Middle name: </label>
                                            <input style="border: 1px solid black;" type="text" name="toPass[${vehicleCount-1}][middle_name]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4 indiInputs mt-3" >
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Last name: </label>
                                            <input style="border: 1px solid black;" type="text" name="toPass[${vehicleCount-1}][last_name]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Main Contact no. : </label>
                                            <input style="border: 1px solid black;" type="number" name="toPass[${vehicleCount-1}][main_contact]" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Secondary Contact no. : </label>
                                            <input style="border: 1px solid black;" type="number" name="toPass[${vehicleCount-1}][secondary_contact]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Alternative Email Address: </label>
                                            <input style="border: 1px solid black;" type="number" name="toPass[${vehicleCount-1}][email]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-2">
                                        <div>
                                            <label class="form-label mt-2">Select Address : </label>
                                            <select style="border: 1px solid black;" name="toPass[${vehicleCount-1}][owner_address]" id="owner_address${vehicleCount}"
                                                class="form-select" aria-label="Default select example" onchange="getAddress($(this).val(),${vehicleCount})">
                                                <option selected>---</option>
                                                @foreach ($crms_account[0]->acc_address as $i => $address)
                                                    <option value="{{ $address->id }}">Address {{ $i + 1 }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-3">
                                        <div class="mb-1">
                                            <label class="form-label mb-2">Vehicle Ownership Status : </label>
                                            <select style="border: 1px solid black;" id="vos${vehicleCount}"
                                                name="toPass[${vehicleCount-1}][vos]" class="form-select"
                                                aria-label="Default select example" disabled>
                                                <option value="">---</option>
                                                @foreach ($vehicle_ownership_status as $vos)
                                                    <option id="<?= "$vos->id-$vos->subcat_hoatype_id" ?>"
                                                        value="<?= "$vos->id" ?>">{{ $vos->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row ms-0 mt-1" style="border: 1px solid gray;width: 100%;">
                                        <div class="col-md-4 mb-3">
                                            <div class="mb-1">
                                                <label class="form-label mb-2">Category : </label>
                                                <select style="border: 1px solid black;" name="toPass[${vehicleCount-1}][category_id]" id="category${vehicleCount}"
                                                    class="form-select " aria-label=" example" disabled>
                                                    <option value="">---</option>
                                                    @foreach ($categories as $category)
                                                        <option value="<?= $category->id ?>">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-md-4 mb-3">
                                            <div class="mb-1">
                                                <label class="form-label mb-2">Sub Category : </label>
                                                <select style="border: 1px solid black;" name="toPass[${vehicleCount-1}][sub_category_id]" id="sub_category${vehicleCount}"
                                                    class="form-select " aria-label="Default select example" disabled
                                                    required>
                                                    <option value="">---</option>
                                                    @foreach ($subcats as $subcat)
                                                        <option value="<?= $subcat->id ?>">{{ $subcat->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="mb-1">
                                                <label class="form-label mb-2">HOA : </label>
                                                <select style="border: 1px solid black;" id="hoa${vehicleCount}"
                                                    name="toPass[${vehicleCount-1}][hoa]" class="form-select"
                                                    aria-label="Default select example" disabled>
                                                    <option value="">---</option>
                                                    @foreach ($hoas as $hoa)
                                                        <option value="<?= $hoa->id ?>">{{ $hoa->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <div class="mb-1">
                                                <label class="form-label mb-2">Member Type : </label>
                                                <select style="border: 1px solid black;" id="hoa_type${vehicleCount}"
                                                    name="toPass[${vehicleCount-1}][hoa_type]" class="form-select" aria-label="Default select example" disabled
                                                    disabled>
                                                    <option value="">---</option>
                                                    @foreach ($hoatypes as $hoatype)
                                                        <option value="<?= "$hoatype->id" ?>">{{ $hoatype->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <div class="mb-1">
                                                <label class="form-label mb-2">Block : </label>
                                                <input style="border: 1px solid black;" type="number" id="block${vehicleCount}"
                                                    name="toPass[${vehicleCount-1}][block]" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="mb-1">
                                                <label class="form-label mb-2">Lot : </label>
                                                <input style="border: 1px solid black;" type="number" id="lot${vehicleCount}"
                                                    name="toPass[${vehicleCount-1}][lot]" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="mb-1">
                                                <label class="form-label mb-2">House Number : </label>
                                                <input style="border: 1px solid black;" type="number" id="house_number${vehicleCount}"
                                                    name="toPass[${vehicleCount-1}][house_number]" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="mb-1">
                                                <label class="form-label mb-2">Street : </label>
                                                <input style="border: 1px solid black;" type="text" id="street${vehicleCount}"
                                                    name="toPass[${vehicleCount-1}][street]" class="form-control" disabled>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="mb-1">
                                                <label class="form-label mb-2">Building / Apartment / Condo : </label>
                                                <input style="border: 1px solid black;" type="text" id="building_name${vehicleCount}"
                                                    name="toPass[${vehicleCount-1}][building_name]" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="mb-1">
                                                <label class="form-label mb-2">Subdivision / Village : </label>
                                                <input style="border: 1px solid black;" type="text" id="subdivision${vehicleCount}"
                                                    name="toPass[${vehicleCount-1}][subdivision]" class="form-control" disabled>
                                            </div>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <div class="mb-1">
                                                <label class="form-label mb-2">City : </label>
                                                <select style="border: 1px solid black;" name="toPass[${vehicleCount-1}][city]" id="city${vehicleCount}"
                                                    class="form-select" aria-label="Default select example" disabled>
                                                    <option selected>---</option>
                                                    @foreach ($cities as $city)
                                                        <option value="{{ $city->bl_id }}">{{ $city->description }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="mb-1">
                                                <label class="form-label mb-2">Zip Code : </label>
                                                {{-- <select style="border: 1px solid black;" name="toPass[${vehicleCount-1}][zip_code]"
                                                    id="zip_code${vehicleCount}" class="form-select " aria-label=" example" required>
                                                    <option value="">---</option>

                                                </select> --}}
                                                <input style="border: 1px solid black;" type="number" id="zip_code${vehicleCount}"
                                                    name="toPass[${vehicleCount-1}][zip_code]" class="form-control" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label">Driver License (Front)</label>
                                            <input style="border: 1px solid black;" class="form-control" type="file" name="toPass[${vehicleCount-1}][front_license]" id="formFile">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label">Driver License (Back)</label>
                                            <input style="border: 1px solid black;" class="form-control" type="file" name="toPass[${vehicleCount-1}][back_license]" id="formFile">
                                        </div>
                                    </div> --}}

                                </div>
                            </div>
                        </div>
            </div>
        `
        $('#input_vehicle').append(form);
        $('select').addClass('highlightSelect');

    };
    // vehicleForm();
    function getAccountInfo(counter) {
        var getInfo = {!! json_encode($crms_account[0]) !!}
        // getInfo.map(rec=>{
        //     if(rec.includes('contact')){

        //     }
        // })
        if (counter > 1) {
            $('#recentInfo' + counter).prop('checked', false);
        }
        let isChecked = $('#acctInfo' + counter).is(':checked')
        if (isChecked) {
            var data = getInfo;
            var result = {};
            $.each(data, function(key, value) {
                // Split the key into parts like ['toPass', '1', 'plate']
                var parts = key.replace(/\]/g, '').split('[');
                var current = result;

                // Traverse the parts to build the nested object structure
                for (var i = 0; i < parts.length; i++) {
                    var part = parts[i];
                    if (!current[part]) {
                        current[part] = (i === parts.length - 1) ? value : {};
                    }
                    current = current[part];
                }
            });
            var lastData = result

            var map = {
                'firstname' : result['firstname'],
                'middlename' : result['middlename'],
                'lastname' : result['lastname'],
                'main_contact' : result['main_contact'],
                'secondary_contact' : result['secondary_contact'],
                'email' : result['email'],
                'owner_address' : null,
                'city' : result['city'],
                'zip_code' : result['zipcode'],
                'category_id' : result['category_id'],
                'sub_category_id' : result['sub_category_id'],
                'hoa' : result['hoa'],
                'hoa_type' : result['hoa_type'],
                'vos' : result['vos'],
                'block' : result['block'],
                'lot' : result['lot'],
                'house_number' : result['house_number'],
                'street' : result['street'],
                'building_name' : result['building_name'],
                'subdivision' : result['subdivision_village'],
                'current_vehicle_id' : $('#current_vehicle_id1').val(),
                'current_owner_id' : $('#current_owner_id1').val(),
            }

            // Iterate over the dynamic forms and update fields
            $('#vehicleForm').find('.form-control, .form-select').each(function() {
                var name = $(this).attr('name');
                var type = $(this).attr('type');

                if (type === 'file') return;

                if (name.includes(counter - 1)) {

                    if (!(name.includes('plate') ||
                            name.includes('brand') ||
                            name.includes('vehicle_series') ||
                            name.includes('year_model') ||
                            name.includes('color') ||
                            name.includes('type') ||
                            name.includes('orID') ||
                            name.includes('crID') ||
                            name.includes('sticker_year') ||
                            name.includes('sticker_no')) ||
                        name.includes('hoa_type') ||
                        name.includes('current_vehicle_id') ||
                        name.includes('current_owner_id')
                    ) {
                        let splitName = name.replace(/\]/g, '').split('[');
                        // $(this).val(lastData[splitName[splitName.length - 1]]);
                        $(this).val(map[splitName[splitName.length - 1]]);
                        // console.log(lastData, lastData[splitName[splitName.length - 1]], splitName[splitName.length - 1]);

                    }

                    if (name.includes('first_name')) {
                        $(this).val(lastData['firstname'])
                    };

                    if (name.includes('middle_name')) {
                        $(this).val(lastData['middlename'])
                    };

                    if (name.includes('last_name')) {
                        $(this).val(lastData['lastname'])
                    };
                    // if (name.includes('subdivision')) {
                    //     $(this).val(lastData['subdivision_village'])
                    // };

                    // if (name.includes('zip_code')) {
                    //     cityChange(lastData['city'],counter)
                    //     // $(this).val(lastData['zipcode'])
                    // };

                    // if (name.includes('category_id')) {
                    //     categoryChange(lastData['category_id'], counter);
                    //     $(this).val(lastData['category_id'])
                    // };
                    // if (name.includes('sub_category_id')) {
                    //     sub_categoryChange(lastData['sub_category_id'], counter);
                    //     $(this).val(lastData['sub_category_id'])
                    // };
                }
            });
        } else {
            $('#vehicleForm').find('.form-control, .form-select').each(function() {
                var name = $(this).attr('name');
                var type = $(this).attr('type');
                if (name.includes(counter - 1)) {
                    if (!(name.includes('plate') ||
                            name.includes('brand') ||
                            name.includes('vehicle_series') ||
                            name.includes('year_model') ||
                            name.includes('color') ||
                            name.includes('type') ||
                            name.includes('orID') ||
                            name.includes('crID') ||
                            name.includes('sticker_year') ||
                            name.includes('sticker_no'))) {
                        $(this).val('');
                    }
                }

            })
        }

    }

    function getRecentInfo(counter) {
        $('#input_vehicle').on('click', `#recentInfo${counter}`, function() {
            var isChecked = $(this).is(":checked");
            $('#acctInfo' + counter).prop('checked', false);
            if (isChecked) {
                var formDataJSON = {};

                // Select all input, select, and textarea fields inside the form
                $('#vehicleForm').find('input, select, textarea').each(function() {
                    var name = $(this).attr('name');
                    var value = $(this).val();

                    if (name) { // Ensure the element has a name attribute
                        formDataJSON[name] = value;
                    }
                });

                var formDataJSONString = JSON.stringify(formDataJSON);
                passDataToDynamicForm(formDataJSONString, counter);
            } else {
                $('#input_vehicle').find('.form-control, .form-select').each(function() {
                    var name = $(this).attr('name');
                    var type = $(this).attr('type');
                    if (name.includes(counter - 1)) {
                        $(this).val('');
                    }

                })

            }
        });
    };

    function parseFormData(jsonString) {
        var fromJson = JSON.parse(jsonString);
        getProp = Object.keys(fromJson);

        var filProp = getProp.filter(rec => {
            return rec.includes(vehicleCount - 1);
        });
        filProp.map(rec => {
            delete fromJson[rec];
        });
        var data = fromJson;
        var result = {};

        $.each(data, function(key, value) {
            // Split the key into parts like ['toPass', '1', 'plate']
            var parts = key.replace(/\]/g, '').split('[');
            var current = result;

            // Traverse the parts to build the nested object structure
            for (var i = 0; i < parts.length; i++) {
                var part = parts[i];
                if (!current[part]) {
                    current[part] = (i === parts.length - 1) ? value : {};
                }
                current = current[part];
            }
        });
        return result;
    };

    function passDataToDynamicForm(jsonString, counter) {
        var data = parseFormData(jsonString);
        // var lastData = data.toPass[vehicleCount-2]
        var lastData = data.toPass[counter - 2]

        // Iterate over the dynamic forms and update fields
        $('#input_vehicle').find('.form-control, .form-select').each(function() {
            var name = $(this).attr('name');
            var type = $(this).attr('type');

            if (type === 'file') return;

            if (name.includes(counter - 1)) {

                // if(name.includes('plate')
                // || name.includes('brand')
                // || name.includes('vehicle_series')
                // || name.includes('year_model')
                // || name.includes('color')
                // || name.includes('type')
                // || name.includes('orID')
                // || name.includes('crID')
                // || name.includes('sticker_year')
                // || name.includes('sticker_no')) return;

                if (!(name.includes('plate') ||
                        name.includes('brand') ||
                        name.includes('vehicle_series') ||
                        name.includes('year_model') ||
                        name.includes('color') ||
                        name.includes('type') ||
                        name.includes('orID') ||
                        name.includes('crID') ||
                        name.includes('sticker_year') ||
                        name.includes('sticker_no'))) {
                    let splitName = name.replace(/\]/g, '').split('[');
                    $(this).val(lastData[splitName[splitName.length - 1]]);
                }
                getAddress(lastData['owner_address'], counter)
                // if (name.includes('zip_code')) {
                //     cityChange(lastData['city'],counter)
                //     $(this).val(lastData['zip_code'])
                // };

                // if (name.includes('category_id')) {
                //     categoryChange(lastData['category_id'], counter);
                //     // $(this).val(lastData['category_id'])
                // };
                // if (name.includes('sub_category_id')) {
                //     sub_categoryChange(lastData['sub_category_id'], counter);
                //     // $(this).val(lastData['sub_category_id'])
                // };
                // if (name.includes('hoa_type')) {
                //     hoa_change(lastData['hoa_type'], counter);
                //     // $(this).val(lastData['hoa_type'])
                // };
            }


        });
    };


    $(document).on('submit', '#submit_vehicle', function() {
        $(this).prop('disabled', true);
        $(this).html(
            `
                <div class="spinner-border spinner-border-sm" role="status"></div>
                <br>
                Processing
            `);
    });

    $('select').addClass('highlightSelect');

    function checkPlateNo(plateNo) {
        if (!plateNo) {
            $('.error-message-plate_no1').hide();
            $('#submit_vehicle').attr('disabled', false);
            return;
        }

        $.ajax({
            type: "GET",
            url: `{{ url('/check_plate_no') }}/${plateNo}`,
            dataType: "json",
            success: function(response) {

                if (response.length > 0) {
                    $('#submit_vehicle').attr('disabled', true);
                    $('.error-message-plate_no1').text(`Already exist!`).show();
                } else {
                    $('#submit_vehicle').attr('disabled', false);
                    $('.error-message-plate_no1').hide();
                }
            }
        });
    }

    function getAddress(id, counter) {
        let acc_address = {!! json_encode($crms_account[0]->acc_address) !!};
        let getAccAddress = acc_address.filter(rec => rec.id == id)
        // console.log(getAccAddress,'acc_address ')
        const fields = {
            [`#block${counter}`]: !getAccAddress ? '' : getAccAddress[0]?.block,
            [`#lot${counter}`]: !getAccAddress ? '' : getAccAddress[0]?.lot,
            [`#house_number${counter}`]: !getAccAddress ? '' : getAccAddress[0]?.house_number,
            [`#street${counter}`]: !getAccAddress ? '' : getAccAddress[0]?.street,
            [`#building_name${counter}`]: !getAccAddress ? '' : getAccAddress[0]?.building_name,
            [`#subdivision${counter}`]: !getAccAddress ? '' : getAccAddress[0]?.subdivision_village,
            [`#city${counter}`]: !getAccAddress ? '' : getAccAddress[0]?.city,
            [`#zip_code${counter}`]: !getAccAddress ? '' : getAccAddress[0]?.zipcode,
            [`#category${counter}`]: !getAccAddress ? '' : getAccAddress[0]?.category_id,
            [`#sub_category${counter}`]: !getAccAddress ? '' : getAccAddress[0]?.sub_category_id,
            [`#hoa${counter}`]: !getAccAddress ? '' : getAccAddress[0]?.hoa, // For Patch 11-26-24 Bug Fix
            [`#hoa_type${counter}`]: !getAccAddress ? '' : getAccAddress[0]?.hoa_type,
        }
        $.each(fields, function(selector, value) {
            $(selector).val(value);
            $(selector).prop('disabled', true);
        });

        hoa_change(getAccAddress[0].sub_category_id, counter);


    }
</script>
