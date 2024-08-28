<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BFFHAI-SRS LOGIN</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        .myCard {
            border-radius: 30px;
            background-color: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            max-width: 310px;
            min-height: 340px;
            box-shadow: 10px;
        }

        .display {

            text-shadow: 4px 4px #FF0000;
            font-weight: 900;
        }

        #login {
            min-height: 100vh;
            background-image: url('images/car.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .btn_glow {
            background-color: rgba(250, 250, 250, 0.2);
            border: 1px;
            backdrop-filter: blur(1px);
        }
    </style>
</head>

<body>
    <section id="login">
        <div class="container-fluid p-5">
            <div class="row ">
                <div class="col-md-4 ">
                    <center>
                        <div class="card myCard p-2 ">

                            <div class="card-body p-4">
                                <div class="display display-3 font-weight-bold text-white border-1 mb-4">
                                    SRS
                                </div>
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="row mb-3 ">

                                        @error('email')
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <span class="text-danger fw-bold text-center" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            </div>
                                        </div>
                                        @enderror

                                        @error('password')
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <span class="text-danger fw-bold text-center" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            </div>
                                        </div>
                                        @enderror

                                        <input id="email" placeholder="Email Address" type="email" class="form-control-lg border-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus />

                                    </div>

                                    <div class="row mb-3">


                                        <input id="password" placeholder="Password" type="password" class="form-control-lg border-0 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" />

                                    </div>

                                    <div class="d-flex">
                                        <div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>


                                            </div>
                                        </div>
                                        <div>
                                            <label class="form-check-label text-white" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>

                                    <div class="row mb-0 d-flex justify-content-center mt-4">

                                        <button type="submit" class="btn btn_glow text-white shadow-xl">
                                            {{ __('Login') }}
                                        </button>

                                        @if (Route::has('password.request'))
                                        <a class="btn btn-link text-white" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                        @endif

                                    </div>
                                </form>

                                <div class="d-flex justify-content-center mt-3">
                                    <p class="text-white" style="font-size: 12px;">
                                        <span> Best viewed using Google Chrome<img src="{{ asset('images/google.png') }}" class="ms-2" height="20"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </center>
                </div>
                <div class="col-md-8">

                </div>

            </div>
        </div>
    </section>

    <script src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>