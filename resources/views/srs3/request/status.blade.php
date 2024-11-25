@extends('layouts.guest')

@section('title', 'Sticker Application Request Status')

@section('content')
<div class="container px-md-4">
    <div class=" px-md-4 mb-3">
        <div class="card mt-3 shadow mb-5 bg-body rounded">
            <div class="card-header text-center bg-primary" style="color: white;">
                <img src="{{ asset('images/bflogo.png') }}" height="100" width="100" alt="">
                <h5 style="font-weight: bold;">BFFHAI</h5>
                <h5 style="font-weight: bold;">Sticker Request Status</h5>
            </div>
            <div class="container justify-content-center align-items-center">
                <div class="p-md-4 mt-1 mb-3">
                    <div id="stat_errror_msg" class="row text-center justify-content-center">
                    </div>
                    <div class="row text-center justify-content-center">
                        <div id="stat_msg" style="display: none;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Request ID</th>
                                        <th>Request Date</th>
                                        <th>Status</th>
                                        <th id="last_col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                            <span id="look_for_details"></span>
                        </div>
                    </div>
                    <form action="#" id="req_status_form" method="POST">
                        @csrf
                        <div class="row text-center justify-content-center">
                            <div class="col-md-4">
                                <label class="form-label" for="">Enter Request ID</label>
                                <div class="row g-0">
                                    <div class="col-4"><p style="font-size: 24px; font-weight: bold;">SRS #</p></div>
                                    <div class="col-8">
                                        <input class="form-control" type="text" name="req_id" id="req_id" value="{{ request()->q }}" required>
                                    </div>
                                </div>
                                
                                <br>
                                <button class="btn btn-primary" type="submit" id="check_btn">Check Status</button>
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
<script src="{{ asset('js/srs3/11srst29_decrypted.js') }}"></script>
<script>
    // Disable F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U
    document.addEventListener('keydown', function(e) {
    if (e.key === 'F12' || 
        (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J')) || 
        (e.ctrlKey && e.key === 'U')) {
        e.preventDefault();
    }
});
</script>
@endsection
