@extends('layouts.main-app')

@section('content')
<div class="row">
    <p>DASHBOARD as of {{ date('M d, Y h:i A') }}</p>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">CUSTOMERS</h5>
                <hr>
                {{-- <h4>{{ $customersCount }}</h4> --}}
                <h4>{{ $dashboard->customer_count ?? 0 }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">INVOICE CREATED</h5>
                <hr>
                {{-- <h4>{{ $invoicesCount }}</h4> --}}
                <h4>{{ $dashboard->invoices_count ?? 0 }}</h4>
            </div>
        </div>
    </div>
</div>
<div class="row mt-5">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">OPEN SRS</h5>
                <hr>
                {{-- <h4>{{ $openSrsCount }}</h4> --}}
                <h4>{{ $dashboard->open_srs_count ?? 0 }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">CLOSED SRS</h5>
                <hr>
                {{-- <h4>{{ $closedSrsCount }}</h4> --}}
                <h4>{{ $dashboard->closed_srs_count ?? 0 }}</h4>
            </div>
        </div>
    </div>
</div>
@endsection
