@extends('layouts.guest')

@section('title', 'Sticker Application Request')

@section('content')
<div class="container px-md-4">
    <div class=" px-md-4 mb-3">
        <div class="card mt-3 shadow mb-5 bg-body rounded">
            <div class="card-header text-center bg-primary" style="color: white;">
                <img src="{{ asset('images/bflogo.png') }}" height="100" width="100" alt="">
                <h5 style="font-weight: bold;">BFFHAI</h5>
                <h5 style="font-weight: bold;">STICKER RATE AND OTHER SPECIAL ASSESSMENTS 2024</h5>
            </div>
            <div class="container justify-content-center align-items-center">
                <div class="p-md-4 mt-1 mb-3">
                    <div class="table-responsive" id="residentsRate">
                        <h6><b>RESIDENT</b></h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="25%" style="text-align: center;">Category</th>
                                    <th style="text-align: center;">No. of Cars / Motorcycles</th>
                                    <th style="text-align: center;">Rate</th>
                                    <th style="text-align: center;">Requirements</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <b>Residents (Homeowners)</b>
                                        <br>
                                        [A person who owns a home]
                                    </td>
                                    <td>
                                        <ul>
                                            <li>1<sup>st</sup> to 5<sup>th</sup></li>
                                            <li>6<sup>th</sup> to 10<sup>th</sup></li>
                                            <li>11<sup>th</sup> or more</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>₱250</li>
                                            <li>₱550</li>
                                            <li>₱1,050</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Car must be in residents name</li>
                                            <li>Endorsement by local HOA</li>
                                            <li>Proof of Residency required</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Tenants</b>
                                        [One who has the occupation or temporary possession of land or tenement]
                                    </td>
                                    <td>
                                        <ul>
                                            <li>1<sup>st</sup> to 2<sup>nd</sup></li>
                                            <li>3<sup>rd</sup> to 5<sup>th</sup></li>
                                            <li>
                                                6<sup>th</sup> or more as Regular Non-resident
                                            </li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>₱250</li>
                                            <li>₱550</li>
                                            <li>₱2,500</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Car must be in Applicants name</li>
                                            <li>Notarized Lease of contract for at least one year</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Property owner or Business owner</b>
                                        [An owner or proprietor of home or land]
                                    </td>
                                    <td>
                                        <ul>
                                            <li>1<sup>st</sup> to 2<sup>nd</sup></li>
                                            <li>3<sup>rd</sup> or more as Regular Non-resident</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>₱250</li>
                                            <li>₱2,450</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Not residing in BF</li>
                                            <li>Property owner: present TCT/TAX declaration</li>
                                            <li>Business Owner: Present Business Clearance</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Company-owned vehicle assigned to a BFFHAI member resident</b>
                                        [Refers to a vehicle registered under the company name but assigned to a resident]
                                    </td>
                                    <td>
                                        <ul>
                                            <li>1<sup>st</sup> to 2<sup>nd</sup></li>
                                            <li>3<sup>rd</sup> or more as Regular Non-resident</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>₱550</li>
                                            <li>₱2,250</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Deed of Assignment in company letter head</li>
                                            <li>Company ID</li>
                                            <li>Proof of Residency</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Owner of vehicles named under the company outside BF</b>
                                        {{-- [Refers to a vehicle registered under the company name but assigned to a resident] --}}
                                    </td>
                                    <td>
                                        <ul>
                                            <li>1<sup>st</sup> to 5 cars</li>
                                            <li>6<sup>th</sup> to 10 <sup>th</sup></li>
                                            <li>11<sup>th</sup> or more (NR REGULAR RATE)</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>₱550</li>
                                            <li>₱2,000</li>
                                            <li>₱2,450</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Vehicles must be registered in the name of the company</li>
                                            <li>Proof of ownership of company</li>
                                            <li>Proof of Residency</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Vehicles of Family-owned company inside BF</b>
                                        [Refers to vehicle registered under the name of a family corporation or entity whose family or member thereof is living in BF homes subd.]
                                    </td>
                                    <td>
                                        <ul>
                                            <li>1<sup>st</sup> to 5<sup>th</sup></li>
                                            <li>6<sup>th</sup> to 10<sup>th</sup></li>
                                            <li>11<sup>th</sup> or more</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>₱250</li>
                                            <li>₱550</li>
                                            <li>₱1,050</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Vehicles must be registered in the name of company</li>
                                            <li>Present Business Clearance</li>
                                            <li>Proof of ownership of company</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>HOA Non-Member</b>
                                        [Not registered with the federation.]
                                    </td>
                                    <td>
                                        <ul>
                                            <li>1<sup>st</sup> to 5<sup>th</sup></li>
                                            <li>6<sup>th</sup> to 10<sup>th</sup></li>
                                            <li>11<sup>th</sup> or more</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>₱400</li>
                                            <li>₱750</li>
                                            <li>₱1,300</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Car must be in resident's name</li>
                                            <li>Proof of Residency required</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>NO HOA</b>
                                        [A resident or group of residents who do not have or belong to an association]
                                    </td>
                                    <td>
                                        <ul>
                                            <li>1<sup>st</sup> to 5<sup>th</sup></li>
                                            <li>6<sup>th</sup> to 10<sup>th</sup></li>
                                            <li>11<sup>th</sup> or more</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>₱300</li>
                                            <li>₱600</li>
                                            <li>₱1,100</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Car must be in resident's name</li>
                                            <li>Proof of Residency required</li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-md-3" id="nonResidentsRate">
                        <h6><b>NON-RESIDENT</b></h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Category</th>
                                    <th style="text-align: center;">Car Rate 2024</th>
                                    <th style="text-align: center;">Motorcycle Rate 2024</th>
                                    <th style="text-align: center;">Requirements</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align: center;">1. Regular</td>
                                    <td style="text-align: center;">2,500</td>
                                    <td style="text-align: center;">850</td>
                                    <td>
                                        <ul>
                                            <li>2 Sticker per household</li>
                                            <li>OR/CR of vehicle</li>
                                            <li>Government ID</li>
                                            <li>NBI or POLICE CLEARANCE (for motorcycle only)</li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-md-2 p-2 p-md-5">
                        <p>
                            <b>The above rates include special assessments for, among others, vehicle stickers, road maintenance, security fees and environmental protection fees.</b>
                        </p>
                        <ul>
                            <li><b>SURCHARGE WILL BE IMPOSED FOR RESIDENT STARTING ON March 15, 2024</b></li>
                            <ul>
                                <li><b>SURCHARGE OF 100</b></li>
                            </ul>
                            <li><b>SURCHARGE WILL BE IMPOSED FOR NON-RESIDENT STARTING ON April 1, 2024</b></li>
                            <ul>
                                <li><b>SURCHARGE OF 300</b></li>
                            </ul>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection