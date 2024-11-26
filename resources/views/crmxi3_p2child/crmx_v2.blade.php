@extends('layouts.main-app')

@section('title', 'CRMx')

@section('links_css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap');

    body {
        font-family: 'Nunito', sans-serif;
    }

    .no-header-border {
        border-top: 1px solid #ccc; /* Add top border to table body */
    }
</style>
@endsection

@section('content')

<div class="container-fluid mt-4">
    <div class="card myCard">
        <div class="card-body p-5">
            <div class="text-muted h1  fw-bold">
                <i class="fas fa-users"></i> CRM
            </div>
            <hr>


            @foreach ($errors->all() as $message)
            {{$message}}
            @endforeach

            <div class="d-flex justify-content-between">
                <div>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search..." id="searchField" name="searchField">
                        <button type="button" class="btn btn-primary" id="searchBtn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="fas fa-plus"></i> Add Customer
                    </button>
                </div>
            </div>

            <div class="table-responsive mt-4">
                <table id="crms_table" class="table table-bordered w-100">
                    <thead>
                        <tr>
                            <th>Customer ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Owned Vehicles</th>
                            <th>Vehicles</th>
                            <th>Stickers Registered</th>
                            <th>Status</th>
                            <th>Created by</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"> <i class="fas fa-plus"></i> Add Customer</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/insert_crm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div class="card" style="border-radius: 15px;">
                            <div class="card-header bg-primary">
                                <div class="h5 fw-bold text-white">
                                    <i class="fas fa-user-alt"></i> Customer Information
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- <div class="row">
                                    <div class="h5 fw-bold mb-4 text-muted">Customer ID</div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Customer-ID: </label>
                                            <input type="number" name="customer_id" class="form-control" required>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="row">
                                    <div class="h5 fw-bold mb-4 text-muted">Personal Information</div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">First name: </label>
                                            <input type="text" name="first_name" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Middle name: </label>
                                            <input type="text" name="middle_name" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Last name: </label>
                                            <input type="text" name="last_name" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Block Lot/Number/PO Box</label>
                                            <input type="text" name="blk_lot" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Street</label>
                                            <input type="text" name="street" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Bulding / Apartment / Condo</label>
                                            <input type="text" name="building_name" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Subdivision / Village</label>
                                            <input type="text" name="subdivision" class="form-control">
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-4">
                                        <label class="form-label">Select HOA:</label>
                                        <select name="hoa" class="form-select" aria-label="Default select example">
                                            <option value="0" selected>---</option>
                                            @foreach($hoas as $hoa)
                                            <option value="{{$hoa->name}}">{{$hoa->name}}</option>
                                            @endforeach
                                        </select>
                                    </div> -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">TIN NO:</label>
                                            <input type="text" name="tin_no" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">City</label>
                                            <select name="city" class="form-select" aria-label="Default select example">
                                                <option selected>---</option>
                                                @foreach($cities as $city)
                                                <option value="{{$city->description}}">{{$city->description}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Zip Code</label>
                                            <input type="number" name="zip_code" class="form-control">
                                        </div>
                                    </div>

                                     <!-- <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">TIN NO:</label>
                                            <input type="text" name="tin_no" class="form-control">
                                        </div>
                                    </div> -->

                                    <div class="col-md-4 mb-2">
                                        <label for="" class="form-label">Category: </label>
                                        <select name="category_id" onchange="categoryChange($(this).val())" class="form-select " aria-label=" example" required>
                                            <option value="">---</option>
                                            @foreach ($categories as $category)
                                            <option value="<?= $category->id ?>">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="Sub Category">Sub Category: </label>
                                        <select name="sub_category_id" onchange="sub_categoryChange($(this).val())" id="sub_category" class="form-select mt-2" aria-label="Default select example" required>
                                            <option value="">---</option>

                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Select HOA:</label>
                                        <select id="hoa" name="hoa" class="form-select" aria-label="Default select example">
                                            <option value="0" selected>---</option>
                                            @foreach($hoas as $hoa)
                                            <option value="{{$hoa->name}}">{{$hoa->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Address </label>
                                            <input type="text" name="address" class="form-control" required>
                                        </div>
                                    </div> -->

                                    <div class="col-md-4">
                                        <label class="form-label">Civil Status:</label>
                                        <select name="civil_status" class="form-select" aria-label="Default select example">
                                            <option selected>---</option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Widowed">Widowed</option>
                                            <option value="Separated">Separated</option>
                                            <option value="Divorced">Divorced</option>

                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Nationality:</label>
                                        <select name="nationality" class="form-select" aria-label="Default select example" required>
                                            <option selected>---</option>
                                            <option value="">-- select one --</option>
                                            <option value="afghan">Afghan</option>
                                            <option value="albanian">Albanian</option>
                                            <option value="algerian">Algerian</option>
                                            <option value="american">American</option>
                                            <option value="andorran">Andorran</option>
                                            <option value="angolan">Angolan</option>
                                            <option value="antiguans">Antiguans</option>
                                            <option value="argentinean">Argentinean</option>
                                            <option value="armenian">Armenian</option>
                                            <option value="australian">Australian</option>
                                            <option value="austrian">Austrian</option>
                                            <option value="azerbaijani">Azerbaijani</option>
                                            <option value="bahamian">Bahamian</option>
                                            <option value="bahraini">Bahraini</option>
                                            <option value="bangladeshi">Bangladeshi</option>
                                            <option value="barbadian">Barbadian</option>
                                            <option value="barbudans">Barbudans</option>
                                            <option value="batswana">Batswana</option>
                                            <option value="belarusian">Belarusian</option>
                                            <option value="belgian">Belgian</option>
                                            <option value="belizean">Belizean</option>
                                            <option value="beninese">Beninese</option>
                                            <option value="bhutanese">Bhutanese</option>
                                            <option value="bolivian">Bolivian</option>
                                            <option value="bosnian">Bosnian</option>
                                            <option value="brazilian">Brazilian</option>
                                            <option value="british">British</option>
                                            <option value="bruneian">Bruneian</option>
                                            <option value="bulgarian">Bulgarian</option>
                                            <option value="burkinabe">Burkinabe</option>
                                            <option value="burmese">Burmese</option>
                                            <option value="burundian">Burundian</option>
                                            <option value="cambodian">Cambodian</option>
                                            <option value="cameroonian">Cameroonian</option>
                                            <option value="canadian">Canadian</option>
                                            <option value="cape verdean">Cape Verdean</option>
                                            <option value="central african">Central African</option>
                                            <option value="chadian">Chadian</option>
                                            <option value="chilean">Chilean</option>
                                            <option value="chinese">Chinese</option>
                                            <option value="colombian">Colombian</option>
                                            <option value="comoran">Comoran</option>
                                            <option value="congolese">Congolese</option>
                                            <option value="costa rican">Costa Rican</option>
                                            <option value="croatian">Croatian</option>
                                            <option value="cuban">Cuban</option>
                                            <option value="cypriot">Cypriot</option>
                                            <option value="czech">Czech</option>
                                            <option value="danish">Danish</option>
                                            <option value="djibouti">Djibouti</option>
                                            <option value="dominican">Dominican</option>
                                            <option value="dutch">Dutch</option>
                                            <option value="east timorese">East Timorese</option>
                                            <option value="ecuadorean">Ecuadorean</option>
                                            <option value="egyptian">Egyptian</option>
                                            <option value="emirian">Emirian</option>
                                            <option value="equatorial guinean">Equatorial Guinean</option>
                                            <option value="eritrean">Eritrean</option>
                                            <option value="estonian">Estonian</option>
                                            <option value="ethiopian">Ethiopian</option>
                                            <option value="fijian">Fijian</option>
                                            <option value="filipino">Filipino</option>
                                            <option value="finnish">Finnish</option>
                                            <option value="french">French</option>
                                            <option value="gabonese">Gabonese</option>
                                            <option value="gambian">Gambian</option>
                                            <option value="georgian">Georgian</option>
                                            <option value="german">German</option>
                                            <option value="ghanaian">Ghanaian</option>
                                            <option value="greek">Greek</option>
                                            <option value="grenadian">Grenadian</option>
                                            <option value="guatemalan">Guatemalan</option>
                                            <option value="guinea-bissauan">Guinea-Bissauan</option>
                                            <option value="guinean">Guinean</option>
                                            <option value="guyanese">Guyanese</option>
                                            <option value="haitian">Haitian</option>
                                            <option value="herzegovinian">Herzegovinian</option>
                                            <option value="honduran">Honduran</option>
                                            <option value="hungarian">Hungarian</option>
                                            <option value="icelander">Icelander</option>
                                            <option value="indian">Indian</option>
                                            <option value="indonesian">Indonesian</option>
                                            <option value="iranian">Iranian</option>
                                            <option value="iraqi">Iraqi</option>
                                            <option value="irish">Irish</option>
                                            <option value="israeli">Israeli</option>
                                            <option value="italian">Italian</option>
                                            <option value="ivorian">Ivorian</option>
                                            <option value="jamaican">Jamaican</option>
                                            <option value="japanese">Japanese</option>
                                            <option value="jordanian">Jordanian</option>
                                            <option value="kazakhstani">Kazakhstani</option>
                                            <option value="kenyan">Kenyan</option>
                                            <option value="kittian and nevisian">Kittian and Nevisian</option>
                                            <option value="kuwaiti">Kuwaiti</option>
                                            <option value="kyrgyz">Kyrgyz</option>
                                            <option value="laotian">Laotian</option>
                                            <option value="latvian">Latvian</option>
                                            <option value="lebanese">Lebanese</option>
                                            <option value="liberian">Liberian</option>
                                            <option value="libyan">Libyan</option>
                                            <option value="liechtensteiner">Liechtensteiner</option>
                                            <option value="lithuanian">Lithuanian</option>
                                            <option value="luxembourger">Luxembourger</option>
                                            <option value="macedonian">Macedonian</option>
                                            <option value="malagasy">Malagasy</option>
                                            <option value="malawian">Malawian</option>
                                            <option value="malaysian">Malaysian</option>
                                            <option value="maldivan">Maldivan</option>
                                            <option value="malian">Malian</option>
                                            <option value="maltese">Maltese</option>
                                            <option value="marshallese">Marshallese</option>
                                            <option value="mauritanian">Mauritanian</option>
                                            <option value="mauritian">Mauritian</option>
                                            <option value="mexican">Mexican</option>
                                            <option value="micronesian">Micronesian</option>
                                            <option value="moldovan">Moldovan</option>
                                            <option value="monacan">Monacan</option>
                                            <option value="mongolian">Mongolian</option>
                                            <option value="moroccan">Moroccan</option>
                                            <option value="mosotho">Mosotho</option>
                                            <option value="motswana">Motswana</option>
                                            <option value="mozambican">Mozambican</option>
                                            <option value="namibian">Namibian</option>
                                            <option value="nauruan">Nauruan</option>
                                            <option value="nepalese">Nepalese</option>
                                            <option value="new zealander">New Zealander</option>
                                            <option value="ni-vanuatu">Ni-Vanuatu</option>
                                            <option value="nicaraguan">Nicaraguan</option>
                                            <option value="nigerien">Nigerien</option>
                                            <option value="north korean">North Korean</option>
                                            <option value="northern irish">Northern Irish</option>
                                            <option value="norwegian">Norwegian</option>
                                            <option value="omani">Omani</option>
                                            <option value="pakistani">Pakistani</option>
                                            <option value="palauan">Palauan</option>
                                            <option value="panamanian">Panamanian</option>
                                            <option value="papua new guinean">Papua New Guinean</option>
                                            <option value="paraguayan">Paraguayan</option>
                                            <option value="peruvian">Peruvian</option>
                                            <option value="polish">Polish</option>
                                            <option value="portuguese">Portuguese</option>
                                            <option value="qatari">Qatari</option>
                                            <option value="romanian">Romanian</option>
                                            <option value="russian">Russian</option>
                                            <option value="rwandan">Rwandan</option>
                                            <option value="saint lucian">Saint Lucian</option>
                                            <option value="salvadoran">Salvadoran</option>
                                            <option value="samoan">Samoan</option>
                                            <option value="san marinese">San Marinese</option>
                                            <option value="sao tomean">Sao Tomean</option>
                                            <option value="saudi">Saudi</option>
                                            <option value="scottish">Scottish</option>
                                            <option value="senegalese">Senegalese</option>
                                            <option value="serbian">Serbian</option>
                                            <option value="seychellois">Seychellois</option>
                                            <option value="sierra leonean">Sierra Leonean</option>
                                            <option value="singaporean">Singaporean</option>
                                            <option value="slovakian">Slovakian</option>
                                            <option value="slovenian">Slovenian</option>
                                            <option value="solomon islander">Solomon Islander</option>
                                            <option value="somali">Somali</option>
                                            <option value="south african">South African</option>
                                            <option value="south korean">South Korean</option>
                                            <option value="spanish">Spanish</option>
                                            <option value="sri lankan">Sri Lankan</option>
                                            <option value="sudanese">Sudanese</option>
                                            <option value="surinamer">Surinamer</option>
                                            <option value="swazi">Swazi</option>
                                            <option value="swedish">Swedish</option>
                                            <option value="swiss">Swiss</option>
                                            <option value="syrian">Syrian</option>
                                            <option value="taiwanese">Taiwanese</option>
                                            <option value="tajik">Tajik</option>
                                            <option value="tanzanian">Tanzanian</option>
                                            <option value="thai">Thai</option>
                                            <option value="togolese">Togolese</option>
                                            <option value="tongan">Tongan</option>
                                            <option value="trinidadian or tobagonian">Trinidadian or Tobagonian</option>
                                            <option value="tunisian">Tunisian</option>
                                            <option value="turkish">Turkish</option>
                                            <option value="tuvaluan">Tuvaluan</option>
                                            <option value="ugandan">Ugandan</option>
                                            <option value="ukrainian">Ukrainian</option>
                                            <option value="uruguayan">Uruguayan</option>
                                            <option value="uzbekistani">Uzbekistani</option>
                                            <option value="venezuelan">Venezuelan</option>
                                            <option value="vietnamese">Vietnamese</option>
                                            <option value="welsh">Welsh</option>
                                            <option value="yemenite">Yemenite</option>
                                            <option value="zambian">Zambian</option>
                                            <option value="zimbabwean">Zimbabwean</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Owned Vehicles </label>
                                            <input type="number" name="owned_vehicles" class="form-control">
                                        </div>
                                    </div>
                                    <div class="h5 fw-bold mt-4 text-muted">Contact Information</div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">Main Contact no.</label>
                                        <input type="number" name="main_contact" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">Secondary Contact no.</label>
                                        <input type="number" name="secondary_contact" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="" class="form-label">Tertiary Contact no.</label>
                                        <input type="number" name="tertiary_contact" class="form-control">
                                    </div>

                                    <div class="h5 fw-bold mt-4 text-muted">Drivers License</div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="mb-3">
                                                <label for="formFile" class="form-label">Front ID</label>
                                                <input class="form-control" type="file" name="front_license" id="formFile">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="mb-3">
                                                <label for="formFile" class="form-label">Back ID</label>
                                                <input class="form-control" type="file" name="back_license" id="formFile">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="card mt-4">
                            <div class="card-header bg-primary">
                                <div class="h5 fw-bold text-white">
                                    <i class="fas fa-flag"></i> Reason of Red Tag
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="reason">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="form-floating">
                                                <textarea class="form-control" name="reason_of_tag" placeholder="Leave a comment here" id="floatingTextarea" style="height:200px"></textarea>
                                                <label for="floatingTextarea">Reason of Red Tag</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <div class="card mt-5" style="border-radius: 15px;">
                            <div class="card-header bg-primary">
                                <div class="h5 fw-bold text-white">
                                    <i class="fas fa-car"></i> Vehicle Information
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-end">
                                    <button onclick="addVehicle()" type="button" class="btn btn-success btn-md">
                                        <i class="fas fa-plus-circle"></i> Add Vehicle
                                    </button>
                                </div>
                                <div class="card mt-4" style="border-radius: 15px;">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between">
                                            <div class="h6 mb-3 text-muted"> <i class="fas fa-car"></i> Vehicle 1</div>
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
                                                <select id="year_model" name="year_model[]" class="form-select" aria-label="Default select example">
                                                    <option value="" selected>---</option>
                                                    <?php
                                                    $years = range(1975, strftime("%Y", time()));
                                                    foreach ($years as $year) : ?>
                                                        <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                                    <?php endforeach; ?>
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
                                                <label class="form-label">Old Sticker no.</label>
                                                <input type="text" name="sticker_no[]" placeholder=" * Optional" class="form-control">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Old Sticker Year</label>
                                                <select id="sticker_year" name="sticker_year[]" class="form-select" aria-label="Default select example">
                                                    <option value="" selected>---</option>
                                                    <?php
                                                    $current_year = date('Y');
                                                    for ($i = 2010; $i <= $current_year + 1; $i++) { ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php } ?>
                                                </select></label>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">OR ID:</label>
                                                <input type="text" name="orID[]" placeholder="" class="form-control">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">CR ID:</label>
                                                <input type="text" name="crID[]" placeholder="" class="form-control">
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <div class="mb-3">
                                                        <label for="formFile" class="form-label">OR</label>
                                                        <input class="form-control" type="file" name="or[]" id="formFile">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <div class="mb-3">
                                                        <label for="formFile" class="form-label">CR</label>
                                                        <input class="form-control" type="file" name="cr[]" id="formFile">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <div class="mb-3">
                                                        <label for="formFile" class="form-label">Vehicle Picture</label>
                                                        <input class="form-control" type="file" name="vehicle_pic[]" id="formFile">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="input_vehicle"></div>
                            </div>
                        </div>
                        <!--
                        <div class="row">

                            <div class="col-md-12 mt-4 mb-3">
                                <div class="form-check">
                                    <input onclick="check()" class="form-check-input" type="checkbox" id="flexCheckChecked">
                                    <label class="form-check-label" for="flexCheckChecked">
                                        Check if the customer is candidate for red tagging
                                    </label>
                                </div>
                            </div>


                        </div> -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="submit_crm" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('links_js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    let table = null;

    $(document).ready(function() {
        $('#table_id').DataTable();
    });
    $(document).ready(function() {
        $('#table_id2').DataTable();

        $('#searchBtn').click(function () {
            let searchField = $('#searchField');

            // Assuming you have a text input field for the search query with id "searchField"
            let searchQuery = searchField.val();

            // Assuming your DataTable AJAX URL accepts a query parameter named "search"
            // Using Blade syntax to generate the URL dynamically
            let ajaxUrl = "{{ route('getCrmsV2') }}" + "?q=" + searchQuery;

            // Reload the DataTable with the new AJAX URL
            table.ajax.url(ajaxUrl).load();
        });
    });

    function loadCrms() {
        table = $('#crms_table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            pageLength: 15,
            destroy: true,
            lengthMenu: [
                [15, 30, 50, 100],
                ['15', '30', '50', '100']
            ],
            ajax: {
                url: "{{ route('getCrmsV2') }}",
            },
            columns: [{
                    data: 'customer_id'
                },
                {
                    data: 'cname'
                },
                {
                    name: 'email',
                    render: function (data, type, row) {
                        // remove white space
                        if(row.email == '' || row.email == null) {
                            return '';
                        }

                        let email = row.email.split('@');

                        return `
                            <div class="text-wrap">${email[0] ?? ''}</div>
                            <div class="text-wrap">@</div>
                            <div class="text-wrap">${email[1] ?? ''}</div>
                        `;
                    }
                },
                {
                    data: 'address'
                },
                {
                    data: 'owned_vehicles'
                },
                {
                    data: 'vehicles',
                    name: 'vehicles.plate_no',

                },
                {
                    data: 'stickers',
                    visible: false

                },
                {
                    data: 'status',
                },
                {
                    data: 'creator',
                    name: 'creator.name'
                },
                {
                    render : function(data, type, row) {
                        return `<div class="d-flex justify-content-center">
                            @if(
                                auth()->user()->email == 'itqa@atomitsoln.com' ||
                                auth()->user()->email == 'srsadmin@atomitsoln.com'
                            )
                            <a href="/crm_v2/view-spc/${row.crm_id}/${row.customer_id}" class="me-3">
                                <i class="fas fa-eye" style="color:#B2BEB5; font-size:20px"></i>
                            </a>
                            @endif

                            <a href="/crm_v3/view-spc/${row.crm_id}/${row.customer_id}" class="me-3">
                                <i class="fa-solid fa-users" style="color:#B2BEB5; font-size:20px"></i>
                            </a>

                            <a href="/crm3/view-spc/${row.crm_id}/${row.customer_id}" class="me-3">
                                <i class="fa-solid fa-users" style="color:#B2BEB5; font-size:20px"></i>
                            </a>

                            <a href="/crm/edit-details-crm/${row.crm_id}">
                                <i class="far fa-edit" style="color:#B2BEB5; font-size:20px"></i>
                            </a>
                        </div>`;
                    },
                    orderable: false,
                    searchable: false
                }
            ],

            order: [
                [3, 'desc']
            ],
            language: {
                emptyTable: "Please enter your search parameters..."
            },
            drawCallback: function () {
                // Check if table is empty and show/hide header accordingly
                if (table.rows().count() === 0) {
                    $('#crms_table thead').hide();
                    $('#crms_table tbody').find('tr:first td:first').addClass('border-0');
                } else {
                    $('#crms_table thead').show();
                    $('#crms_table tbody').find('tr:first td:first').removeClass('border-0');
                }
            }
        });
    }

    loadCrms();

    // function check() {
    //     var x = document.getElementById("flexCheckChecked");
    //     var y = document.getElementById("reason");

    //     if (x.checked == true) {
    //         y.style.display = "block";
    //     } else {
    //         y.style.display = "none";
    //     }
    // }

    function check1() {
        var x = document.getElementById("flexCheckChecked1");
        var y = document.getElementById("reason1");

        if (x.checked == true) {
            y.style.display = "block";
        } else {
            y.style.display = "none";
        }
    }



    function get_crm1(id) {
        $.get('/edit_crm/' + id, function(crm) {
            $('#crm_id1').val(crm[0].crm_id)
            $('#firstname1').val(crm[0].firstname);
            $('#mname1').val(crm[0].middlename);
            $('#lname1').val(crm[0].lastname);
            $('#address1').val(crm[0].address);
            $('#blk1').val(crm[0].blk_lot);
            $('#owned_v1').val(crm[0].owned_vehicles);


            if (crm[0].red_tag == 1) {

                $(document).ready(function() {
                    $("#flexCheckChecked1").click();
                    $('#reason_of_tag1').val(crm[0].reason_of_tag);
                });
            }
            $("#flexCheckChecked1").click(function() {
                $('#reason_of_tag1').val('');
            });


        })
    }

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
                                                    <option value="" selected>---</option>
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
                                                            <option value="" selected>---</option>
                                                            <?php
                                                            $current_year = date('Y');
                                                            for ($i = 2010; $i <= $current_year + 1; $i++) { ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php } ?>
                                                        </select></label>
                                                    </div>

                                                    <div class="col-md-3">
                                                <label class="form-label">OR ID:</label>
                                                <input type="text" name="orID[]" placeholder="" class="form-control">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">CR ID:</label>
                                                <input type="text" name="crID[]" placeholder="" class="form-control">
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <div class="mb-3">
                                                        <label for="formFile" class="form-label">OR</label>
                                                        <input class="form-control" type="file" name="or[]" id="formFile">
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

    // function categoryChange(id) {
    //     $('#sub_category').html('');
    //     $('#sub_category').append(`<option value="">--- </option>`);
    //     $.get('/getSubCategories/' + id,
    //         function(response) {
    //             response.forEach(function(a) {
    //                 var e = `<option value="${a.id}">${a.name}</option>`;
    //                 $('#sub_category').append(e);

    //             });


    //         });

    // }

    function categoryChange(id) {
        $('#sub_category').html('');
        $('#sub_category').append(`<option value="">--- </option>`);
        $('#hoa').html('');
        $('#hoa').append(`<option value="0">--- </option>`);
        $.get('/getSubCategories2/' + id,
            function(response) {
                // response.forEach(function(a) {
                //     var e = `<option value="${a.id}">${a.name}</option>`;
                //     $('#sub_category').append(e);

                // });

                response[0].forEach(function(a) {
                    var e = `<option value="${a.id}">${a.name}</option>`;
                    $('#sub_category').append(e);

                });

                response[1].forEach(function(a) {
                    var e = `<option value="${a.name}">${a.name}</option>`;
                    $('#hoa').append(e);
                });
            });

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
    $(document).on('submit', '#submit_crm', function() {
        $(this).prop('disabled', true);
        $(this).html(`<div class="spinner-border spinner-border-sm" role="status">
                    </div>
                    <br>
                    Processing`);
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
