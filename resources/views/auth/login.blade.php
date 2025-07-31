@extends('layouts.at_auth')

@section('title')
Login
@endsection

@section('content')
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    @include('partial.alert')
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="w-100 d-flex align-items-center justify-content-around  flex-wrap">

                                <!-- Website Logo -->
                                <div class="my-4 mb-md-0">
                                    <a href="{{ url('/') }}">
                                        <img src="{{ asset('publisherAssets/assets/affiloTechLogo.png') }}" alt="Website Logo" style="height: 40px;">
                                    </a>
                                </div>

                                <!-- Heading and Description -->
                                <div class="mt-4 text-center">
                                    <h3 class="fw-bolder text-primary mb-1">{{ __('Sign In') }}</h3>
                                    <p class="mb-0">{{ __('Get access to your publisher account') }}</p>
                                </div>

                            </div>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input placeholder="Email" id="email" type="email"
                                        class="form-control @error('email') border-danger @enderror" name="email"
                                        autocomplete="off" value="{{ old('email') }}" required autofocus>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="d-block">
                                        <label for="password" class="control-label">Password</label>
                                        <div class="float-right">
                                            @if (Route::has('password.request'))
                                                <a class="text-primary fs-6 fw-bolder" href="{{ route('password.request') }}">
                                                    {{ __('Forgot Password ?') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <input placeholder="Password" id="password" type="password"
                                        class="form-control @error('password') border-danger @enderror" name="password"
                                        autocomplete="off" required>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                        Login
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="mt-5 text-muted text-center">
                        Don't have an account? <a href="{{ route('register') }}">Create One</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
