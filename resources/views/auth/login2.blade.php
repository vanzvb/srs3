<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>

    <link rel="icon" href="{{ asset('images/bffhai.png') }}" type="image/x-icon">

    <style>
        body {
            background: @if(request()->is('hoa/login')) url('{{ asset('images/bg-8.jpg') }}') @else url('{{ asset('images/bg-9.jpg') }}') @endif no-repeat right center/cover fixed;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .custom-card {
            background-color: rgba(0, 0, 0, 0.5) !important;
            backdrop-filter: blur(15px);
        }

        .login-title {
            font-size: 2rem;
            font-weight: bold;
            text-shadow: 3px 3px 3px #ff0000;
        }

        .custom-label {
            font-size: 1.25rem;
            font-weight: bold;
        }

        .custom-login-btn {
            background-color: #fb3434 !important;
            border: 2px solid #fb3434 !important;
            color: #ffffff !important;
            font-weight: bold !important;
            border-radius: 0.5rem !important;
            transition: background-color 0.3s, border-color 0.3s, color 0.3s !important;
        }

        .custom-login-btn:hover {
            background-color: #252525 !important;
            /* Darker background color on hover */
            border-color: #252525 !important;
            /* Darker border color on hover */
            color: #ffffff !important;
            /* White text on hover for better contrast */
        }

        .custom-forgot-pw-btn {
            background-color: #9c9b9b !important;
            border-color: #9c9b9b !important;
            font-weight: bold !important;
        }

        .custom-forgot-pw-btn:hover {
            background-color: #252525 !important;
            border-color: #252525 !important;
        }

        .alert-danger {
            background-color: #ff4444 !important;
            color: #ffffff !important;
            font-size: 1rem !important;
            font-weight: bold !important;
            padding: 1rem !important;
            margin-bottom: 1rem !important;
            border: 0 !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
            transition: box-shadow 0.3s ease !important;
        }

        .alert-danger:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15) !important;
        }

        /* Copyright text style */
        .copyright {
            font-size: 14px;
            color: #ffffff;
            text-align: center;
            margin-top: 1.5rem;
            transition: opacity 0.3s ease;
        }

        /* Style for form controls */
        .form-control {
            background-color: #f8f9fa !important;
            /* Light gray background */
            border: 1px solid #ced4da !important;
            /* Border color */
            border-radius: 0.5rem !important;
            /* Rounded corners */
            color: #495057 !important;
            /* Text color */
            font-size: 1rem !important;
            /* Font size */
            padding: 0.5rem 1rem !important;
            /* Padding */
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out !important;
            /* Smooth transition for border and box-shadow */
        }

        /* Hover style for form controls */
        .form-control:hover {
            border-color: #ff4444 !important;
            /* Vibrant red border color on hover */
        }

        /* Focus style for form controls */
        .form-control:focus {
            border-color: #ff4444 !important;
            /* Vibrant red border color on focus */
            box-shadow: 0 0 0 0.2rem rgba(255, 68, 68, 0.25) !important;
            /* Vibrant red box shadow on focus */
        }

        /* Style for form labels */
        .form-label {
            color: #fff !important;
            /* Text color */
            font-size: 1rem !important;
            /* Font size */
            font-weight: bold !important;
            /* Font weight */
            margin-bottom: 0.5rem !important;
            /* Bottom margin for spacing */
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
</head>

<body>
    <div class="container-fluid mx-5">
        <div class="row d-flex @if(request()->is('hoa/login')) justify-content-end @else justify-content-start @endif align-items-center">
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card custom-card">
                    <div class="card-body text-light">
                        <div class="text-center mb-3">
                            <img src="{{ asset('images/bffhai.png') }}" alt="SRS" height="100" width="100"
                                class="img-fluid">

                            <h1 class="login-title">SRS LOGIN</h1>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="m-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        @if (session('error'))
                            <div class="alert alert-danger text-center">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success text-center">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div>
                            <form method="POST" action="/admin-login">
                                @csrf
                                <div class="mb-2">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="text" class="form-control" id="email" name="email"
                                        placeholder="Email Address">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password:</label>
                                    <input type="password" class="form-control" id="password" placeholder="********"
                                        name="password">
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" name="remember" class="form-check-input" id="rememberMe">
                                    <label class="form-check-label" for="rememberMe">Remember me</label>
                                </div>
                                <button type="submit" class="btn btn-secondary custom-login-btn mt-1 w-100">
                                    Login
                                </button>
                                <div class="text-center">
                                    <a href="/srs/a/password/reset"
                                        class="btn btn-secondary custom-forgot-pw-btn w-100 text-white mt-3">Forgot
                                        Password?</a>

                                    {{-- <a class="btn btn-link text-white" href="{{ route('unlock-account') }}">
                                        {{ __('Unlock Account') }}
                                    </a> --}}

                                    <p class="text-white mt-3" style="font-size: 14px;">
                                        <span> Best viewed using Google Chrome <img
                                                src="{{ asset('images/google.png') }}" class="ms-2"
                                                height="20"></span>
                                    </p>
                                </div>
                                <div class="text-center">
                                    <p class="copyright">
                                        Copyright @ {{ date('Y') }} Znergee <br> SRS VER 2.3
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>

</html>
