<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>
<section class="h-100 gradient-form">
    <div class="container py-4 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-xl-10">
                <div class="card rounded-3 text-black shadow">
                    <div class="row g-0">
                        <div class="card-header">Login</div>
                        <div class="card-body p-md-4 mx-md-3">
                            {{-- <div class="text-center">
                                <img class="img-fluid" src="{{ asset('srs/images/logo1.jpg') }}" style="width: 200px;" alt="logo">
                                <h4 class="mt-3 mb-4 pb-1">BFFHAI Team</h4>
                            </div> --}}
                            <div class="col-md-5 mx-auto p-2 p-md-3">
                                <form action="{{ route('login') }}" method="POST">
                                    <div class="form-floating">
                                        <input type="email" id="email" class="form-control" name="email" placeholder="Email address" autocomplete="off" />
                                        <label class="form-label" for="email" style="color: #767676;">Email address</label>
                                    </div>
                                    <div class="form-floating mt-3">
                                        <input type="password" id="password" class="form-control" name="password" placeholder="Password" />
                                        <label class="form-label" for="password" style="color: #767676;">Password</label>
                                    </div>
                                    <div class="row p-3 mt-3 text-center">
                                        @csrf
                                        <button type="submit" class="btn btn-primary" style="background-color: #326bc4; color: white;">SIGN IN</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<script src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>