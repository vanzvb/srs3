<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>
        @yield('title')
    </title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body oncontextmenu="return false;">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container py-md-2 px-md-5">
        <a href="#">
            <img src="{{ asset('images/bflogo.png') }}" height="70" width="70" alt="Logo">
        </a>
        <a class="navbar-brand ms-3" href="#">Sticker Application</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->path() == 'sticker/new' ? 'active' : null }}" aria-current="page" href="/sticker/new">New</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->path() == 'sticker/renewal' ? 'active' : null }}" aria-current="page" href="/sticker/renewal">Renewal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->path() == 'sticker/request/status' ? 'active' : null }}" href="{{ route('request.status') }}">Status</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->path() == 'sticker/rates' ? 'active' : null }}" href="/sticker/rates">Rates</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
@yield('content')

<script src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
@yield('links_js')
</body>
</html>