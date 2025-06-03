<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    <style>
        .welcome-page {
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .welcome-box {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .btn-login {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        .btn-register {
            background-color: #36b9cc;
            border-color: #36b9cc;
        }
        .logo-container {
            margin-bottom: 2rem;
        }
        .logo-text {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
        }
    </style>
</head>
<body class="hold-transition welcome-page">
    <div class="container">
        <div class="row justify-content-center align-items-center" style="height: 100vh;">
            <div class="col-md-6">
                <div class="welcome-box p-5">
                    <div class="text-center logo-container">
                        <div class="logo-text">{{ config('app.name', 'OlaniwunMs') }}</div>
                        <p class="lead">Employee & Project Management System</p>
                    </div>

                    <div class="text-center mb-4">
                        <p>Welcome to our application. Please login or create an account to continue.</p>
                    </div>

                    <div class="d-flex justify-content-center">
                        @if (Route::has('login'))
                            <div class="d-flex flex-column flex-md-row">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg px-4 me-md-2">
                                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-login btn-lg text-white px-4 me-md-2 mb-3 mb-md-0 mr-md-3">
                                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                                    </a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="btn btn-register btn-lg text-white px-4">
                                            <i class="fas fa-user-plus mr-2"></i> Create Account
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>

                    <div class="text-center mt-5">
                        <p class="text-muted">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
