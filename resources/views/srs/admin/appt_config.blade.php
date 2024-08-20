@extends('layouts.main-app')

@section('title', 'Appointment Configurator - BFFHAI')

@section('content')
<div class="container p-2">
    @if ($errors->any())
        <div class="alert close-alert alert-danger alert-dismissible fade show text-center mt-3" role="alert">
            @foreach ($errors->all() as $message)
                <strong>{{ $message }}</strong>
                <br>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (Session::has('updateSuccess') || Session::has('hoaAddSuccess'))
        <div class="alert close-alert alert-success alert-dismissible fade show text-center mt-3" role="alert">
            <div class="col-12 text-center">
                <strong>{{ Session::get('updateSuccess') }}</strong>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card mt-2">
        <div class="card-body p-3">
            <div class="row">
                <div class="col-6">
                    <div class="text-muted h4 fw-bold">
                        Appointment Configurator
                    </div>
                </div>
            </div>
            <hr>
            <form action="" action="/srs/appt/config" method="POST">
                @method('PATCH')
                @csrf
                <div class="p-3">
                    Days Allowed
                    <div class="row p-3">
                        <div class="table-responsive">
                            <table class="table table-bordered border-3">
                                <thead>
                                    <tr style="background: #3c8dbc">  
                                        <td style="background: #fff;color: #00c0ef;font-size: 10px;vertical-align: middle;text-align: center;"><b>SUNDAY</b></td>
                                        <td style="background: #fff;color: #00c0ef;font-size: 10px;vertical-align: middle;text-align: center;"><b>MONDAY</b></td>
                                        <td style="background: #fff;color: #00c0ef;font-size: 10px;vertical-align: middle;text-align: center;"><b>TUESDAY</b></td>
                                        <td style="background: #fff;color: #00c0ef;font-size: 10px;vertical-align: middle;text-align: center;"><b>WEDNESDAY</b></td>
                                        <td style="background: #fff;color: #00c0ef;font-size: 10px;vertical-align: middle;text-align: center;"><b>THURSDAY</b></td>
                                        <td style="background: #fff;color: #00c0ef;font-size: 10px;vertical-align: middle;text-align: center;"><b>FRIDAY</b></td>
                                        <td style="background: #fff;color: #00c0ef;font-size: 10px;vertical-align: middle;text-align: center;"><b>SATURDAY</b></td>
                                    </tr>
                                </thead>
                                <tbody>  
                                    <tr>
                                        <td>
                                            <center>
                                                <input type="checkbox" id="chck_sunday" name="chck_sunday" {{ in_array(7, $apptConfigs['days_allowed']->value) ? 'checked' : '' }}>
                                            </center>
                                        </td>
                                        <td>
                                            <center>
                                                <input type="checkbox" id="chck_monday" name="chck_monday" {{ in_array(1, $apptConfigs['days_allowed']->value) ? 'checked' : '' }}>
                                            </center>
                                        </td>
                                        <td>
                                            <center>
                                                <input type="checkbox" id="chck_tuesday" name="chck_tuesday" {{ in_array(2, $apptConfigs['days_allowed']->value) ? 'checked' : '' }}>
                                            </center>
                                        </td>
                                        <td>
                                            <center>
                                                <input type="checkbox" id="chck_wednesday" name="chck_wednesday" {{ in_array(3, $apptConfigs['days_allowed']->value) ? 'checked' : '' }}>
                                            </center>
                                        </td>
                                        <td>
                                            <center>
                                                <input type="checkbox" id="chck_thursday" name="chck_thursday" {{ in_array(4, $apptConfigs['days_allowed']->value) ? 'checked' : '' }}>
                                            </center>
                                        </td>
                                        <td>
                                            <center>
                                                <input type="checkbox" id="chck_friday" name="chck_friday" {{ in_array(5, $apptConfigs['days_allowed']->value) ? 'checked' : '' }}>
                                            </center>
                                        </td>
                                        <td>
                                            <center>
                                                <input type="checkbox" id="chck_saturday" name="chck_saturday" {{ in_array(6, $apptConfigs['days_allowed']->value) ? 'checked' : '' }}>
                                            </center>
                                        </td> 
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="p-3 mt-2">
                    Daily Time Allowed
                        <div class="row p-3 g-5">
                            <div class="col-md-5">
                                <div class="row g-0">
                                    <div class="col-md-3">
                                        <label for="">From</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="time" name="daily_time_from" id="" class="form-control" min="08:15" value="{{ $apptConfigs['daily_time_allowed']->value[0] }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="row g-0">
                                    <div class="col-md-3">
                                        <label for="">To</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="time" name="daily_time_to" id="" class="form-control" max="17:55" value="{{ $apptConfigs['daily_time_allowed']->value[1] }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <br>
                <hr>
                <div class="p-3 mt-2">
                    Cashier Lane
                        <div class="row container p-3 g-0">
                            <div class="col-md-3">
                                <label>No. of Cashiers Daily</label>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="cashier_count" class="form-control form-control-sm" value="{{ $apptConfigs['cashier_count']->value }}" required>
                            </div>
                        </div>
                </div>
                <hr>
                <div class="row mt-5">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-primary btn-sm px-3" type="submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('links_js')
<script>
$(document).ready(function () {
    
});
</script>
@endsection