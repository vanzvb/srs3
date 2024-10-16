@extends('layouts.guest')

@section('title', 'Sticker Application Request - Renewal')
<style>
    .vid-modal {
      display: none; /* Hidden by default */
      position: fixed; /* Stay in place */
      z-index: 1; /* Sit on top */
      padding-top: 100px; /* Location of the box */
      left: 0;
      top: 0;
      width: 100%; /* Full width */
      height: 100%; /* Full height */
      overflow: auto !important; /* Enable scroll if needed */
      background-color: rgb(0,0,0); /* Fallback color */
      background-color: rgba(0,0,0,0.9) !important; /* Black w/ opacity */
    }
    
    /* Modal Content (image) */
    .video-modal-content {
      margin: auto;
      display: block;
      width: 80%;
      max-width: 700px;
    }
    
    
    /* Add Animation */
    .video-modal-content, #caption {  
      -webkit-animation-name: zoom;
      -webkit-animation-duration: 0.6s;
      animation-name: zoom;
      animation-duration: 0.6s;
    }
    
    @-webkit-keyframes zoom {
      from {-webkit-transform:scale(0)} 
      to {-webkit-transform:scale(1)}
    }
    
    @keyframes zoom {
      from {transform:scale(0)} 
      to {transform:scale(1)}
    }
    
    /* The Close Button */
    .closeVideoModal {
      position: absolute;
      top: 15px;
      right: 35px;
      color: #f1f1f1 !important;
      font-size: 40px !important;
      font-weight: bold !important;
      transition: 0.3s;
    }
    
    .closeVideoModal:hover,
    .closeVideoModal:focus {
      color: #bbb;
      text-decoration: none;
      cursor: pointer;
    }
    
    /* 100% Image Width on Smaller Screens */
    @media only screen and (max-width: 700px){
      .video-modal-content {
        width: 100%;
      }
    }
</style>
@section('content')
<div class="container px-md-5">
    <div class=" px-md-5 mb-3">
        <div class="card mt-3 shadow mb-5 bg-body rounded">
            <div class="card-header text-center bg-primary" style="color: white;">
                <img src="{{ asset('images/bflogo.png') }}" height="100" width="100" alt="">
                <h5>BFFHAI</h5>
                <h5>Sticker Application - Renewal 3.0</h5>
            </div>
            <div id="request_renewal_msg" class="row justify-content-center">
            </div>
            {{-- <div class="container justify-content-center align-items-center">
                <div class="p-md-4 mt-1 mb-3">
                    <form id="sticker_renewal_form">
                        <div class="row justify-content-center align-items-center mt-3 g-0">
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control form-control-sm" id="email" name="email" placeholder="Registered Email Address" value="{{ old('email') }}" required>
                                    <label for="email" class="form-label" style="color: grey;">Registered Email Address</label>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div> --}}

            <div class="p-md-4 mt-1 mb-3">
                <form id="sticker_renewal_form">
                    <div class="d-flex justify-content-center align-items-center gap-3">
                        
                        <!-- Dropdown for Email or Account ID -->
                        <div>
                            <label for="identifier_type" class="form-label">Select Identifier</label>
                            <select class="form-select form-select-sm" id="identifier_type" name="identifier_type" required>
                                <option value="email" selected>Email</option>
                                <option value="account_id">Account ID</option>
                            </select>
                        </div>

                        <!-- Input Field -->
                        <div>
                            <label for="identifier" class="form-label">Enter Here</label>
                            <input type="text" class="form-control form-control-sm" id="identifier" name="identifier" placeholder value="{{ old('identifier') }}" required>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <label class="form-label d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-sm">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
                <hr>
                                    <h4><b>BFFHAI SRS RENEWAL</b></h4>
                    <h4>FOR HOA MEMBERS ONLY</h4>
                    <p><b>User Guide</b></p>
                    <br>
                    <br>
            </div>
            
            
            

            

            <br>

            <div class="row mb-5 p-3 p-md-0">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    {{-- <h4><b>BFFHAI SRS RENEWAL</b></h4>
                    <h4>FOR HOA MEMBERS ONLY</h4>
                    <p><b>User Guide</b></p>
                    <br>
                    <br> --}}

                    <div class="row">
                        <div class="col-md-6">
                            {{-- 1. Submit Registered Email Address --}}
                        </div>
                        <div class="col-md-3">
                            <!-- <a data-value="/videos/SRS RENEWAL SUBMISSION.mp4" href="#" style="color: black;" id="modal_vid_1" class="modal_vid">Play Tutorial</a> -->

                            {{-- <a href="https://www.youtube.com/watch?v=Q_RliKBGJ8k" target=_blank>Play Video Tutorial</a> --}}
                        </div>
                        <div class="col-md-3">
                            {{-- 48 Sec --}}
                        </div>
                    </div>

                    <div class="row mt-2 mt-md-0">
                        <div class="col-md-6">
                            {{-- 2. Read Email and Submit Vehicle Details --}}
                        </div>
                        <div class="col-md-3">
                            <!-- <a data-value="videos/SRS RENEWAL EMAIL VEHICLE.mp4" href="#" style="color: black;" id="modal_vid_2" class="modal_vid">Play Tutorial</a> -->
                            <!-- <a href="https://youtu.be/bk-d5SU9V7s" target="_blank">Play Tutorial</a> -->
                            {{-- <a href="https://www.youtube.com/watch?v=_wkylO89wd0" target="_blank">Play Video Tutorial</a> --}}
                        </div>
                        <div class="col-md-3">
                            {{-- 1 Min --}}
                        </div>
                    </div>

                    <div class="row mt-2 mt-md-0">
                        <div class="col-md-6">
                            {{-- 3. Appointment and Sticker Release --}}
                        </div>
                        <div class="col-md-3">
                            <!-- <a data-value="videos/SRS RENEWAL APPOINTMENT.mp4" href="#" style="color: black;" id="modal_vid_3" class="modal_vid">Play Tutorial</a> -->
                            {{-- <a href="https://www.youtube.com/watch?v=TkjK095DZsc" target="_blank">Play Video Tutorial</a> --}}
                        </div>
                        <div class="col-md-3">
                            {{-- 51 Sec --}}
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<div id="videoModal" class="modal vid-modal text-center">
    <span class="closeVideoModal">&times;</span>
    {{-- <img class="modal-content" id="video01"> --}}
    <video id="video01" style="height: 90%; width: 90%; display: none" controls>
        {{-- <source src="/videos/SRS RENEWAL SUBMISSION.mp4" class="video-modal-content" type="video/mp4"> --}}
    </video>

    <video id="video02" style="height: 90%; width: 90%; display: none;" controls>
        {{-- <source src="/videos/SRS RENEWAL EMAIL VEHICLE.mp4" class="video-modal-content" type="video/mp4"> --}}
    </video>

    <video id="video03" style="height: 90%; width: 90%; display: none;" controls>
        {{-- <source src="/videos/SRS RENEWAL APPOINTMENT.mp4" class="video-modal-content" type="video/mp4"> --}}
    </video>

</div>
@endsection

@section('links_js')
{{-- <script src="{{ asset('js/11srr2423.js') }}"></script> --}}
<script src="{{ asset('js/srs3/11srr2423_v3.js') }}"></script>
@endsection
