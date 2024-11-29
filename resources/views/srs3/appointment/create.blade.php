@extends('layouts.guest')

@section('title', 'Sticker Application Appointment')

@section('content')
<div class="container px-md-5">
    <div class=" px-md-5 mb-3">
        <div class="mt-3">
            <div id="appointment_load" style="display: none;">
                <div class="alert alert-success" role="alert">
                    <div class="col-12 text-center">
                        <strong>Submitting Appointment this is v3</strong>
                    </div>
                    <div class="col-12 text-center mt-3">
                        <img src="{{ asset('css/loading.gif') }}" height="25" width="25">
                    </div>
                </div>
            </div>
            <div id="appointment_msg">
            </div>
        </div>
        <div class="card mt-3 shadow mb-5 bg-body rounded">
            <div class="container justify-content-center align-items-center">
                <div id="apptFormDiv" class="p-md-4 mt-1 mb-3">
                    <div class="col-12 text-center mt-3 mb-5">
                        <h3 style="font-weight: bold;">SRS #{{ $srsNumber }}</h3>
                    </div>
                    <form id="setApptForm" action="/appointment" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Date</label>
                                <input type="date" class="form-control" name="date" id="date" value="{{ date('Y-m-d', strtotime('tomorrow')) }}">
                            </div>
                            <div class="col-md-6">
                                <label for="">Time</label>
                                <select name="" class="form-select" id="time">
                                    <option value="8">08 AM</option>
                                    <option value="9">09 AM</option>
                                    <option value="10">10 AM</option>
                                    <option value="11">11 AM</option>
                                    <option value="13">01 PM</option>
                                    <option value="14">02 PM</option>
                                    <option value="15">03 PM</option>
                                    <option value="16">04 PM</option>
                                    <!-- <option value="17">05 PM</option> -->
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="">Available Time Slots</label>
                                <div class="text-center" id="timeslots">

                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12 text-center">
                                <input type="hidden" name="key" value="{{ request()->key }}">
                                @csrf
                                <input type="hidden" id="srn" name="srn" value="{{ $srn }}">
                                <button id="submit_appt_btn" class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('links_js')
<script src="{{ asset('js/11sa29.js') }}"></script>

@endsection