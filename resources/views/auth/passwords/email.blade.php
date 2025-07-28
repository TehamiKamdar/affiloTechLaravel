@extends('layouts.pr_auth')

@section('content')
<div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center p-4">
    <div class="card rounded-4 p-4 p-lg-5" style="max-width: 600px; width: 100%;">
        <div class="card-body p-4">
            <form id="kt_sign_in_form" action="{{ route('password.email') }}" method="post" class="needs-validation" novalidate>
                @csrf

                <!-- Logo -->
                <div class="text-center mb-4">
                    @if(env("APP_ENV") != "local")
                        <a href="{{ url('/') }}" class="d-inline-block mb-4">
                            <img src="{{ asset('publisherAssets/assets/affiloTechLogo.png') }}" alt="Logo" style="height: 50px;">
                        </a>
                    @endif
                </div>

                <!-- Heading -->
                <div class="text-center mb-4">
                    <h1 class="h2 mb-2">{{ __('Reset Password') }}</h1>
                    <p class="text-muted">{{ __('Get access to your publisher account') }}</p>
                </div>

                <!-- Divider -->
                <hr class="my-4">

                <!-- Status/Alerts -->
                @if (session('status'))
                    <div class="alert alert-success mb-4" role="alert">
                        {{ session('status') }}
                    </div>
                @elseif ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Email Input -->
                <div class="mb-4">
                    <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="Email" required>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="d-grid mb-4">
                    <button type="submit" id="kt_sign_in_submit" class="btn btn-primary btn-lg">
                        <span class="indicator-label">{{ __('Send Password Reset Link') }}</span>
                        <span class="indicator-progress d-none">
                            {{ __('Please wait...') }}
                            <span class="spinner-border spinner-border-sm ms-2"></span>
                        </span>
                    </button>
                </div>

                <!-- Links -->
                <div class="text-center text-muted mb-2">
                    {{ __('Not a Member yet?') }}
                    <a href="{{ route('register') }}" class="text-decoration-none">{{ __('Sign up') }}</a>
                </div>
                <div class="text-center text-muted my-2">OR</div>
                <div class="text-center text-muted">
                    {{ __('Already have an Account?') }}
                    <a href="{{ route('login') }}" class="text-decoration-none">{{ __('Sign in') }}</a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-6">
        <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
            <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6"
                style="background-image:url('{{ asset('adminDashboard/assets/img/curved-images/curved6.jpg') }}')">
            </div>
        </div>
    </div>
</div>
@endsection
