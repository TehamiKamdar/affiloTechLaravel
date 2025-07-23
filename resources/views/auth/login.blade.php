@extends('layouts.pr_auth')

@section('content')
    <section>
        <div class="page-header min-vh-75">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                        <form role="form" action="{{ route('login') }}" method="post">
                            @csrf

                            <div class="card card-plain mt-8">
                                <div class="d-flex flex-center mb-6">
                                    <a href="{{ url('/') }}">
                                        <img alt="Logo"
                                            src="{{ asset('publisherAssets/assets/affiloTechLogo.png') }}" class="h-100 w-70"/>
                                    </a>
                                </div>
                                <div class="card-header pb-0 text-left bg-transparent">
                                    <h3 class="font-weight-bolder text-primary text-gradient">{{ __('Sign In') }}</h3>
                                    <p class="mb-0">{{ __('Get access to your publisher account') }}</p>
                                </div>
                                @include("partial.alert")
                                <div class="card-body">
                                    <label>Email</label>
                                    <div class="mb-3">
                                        <input type="email" class="form-control @error('email') border-danger @enderror"
                                            name="email" autocomplete="off" value="{{ old('email') }}" placeholder="Email">
                                        @error('email')
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <label>Password</label>
                                    <div class="mb-3">
                                        <input type="password" class="form-control @error('password') border-danger @enderror"
                                            name="password" autocomplete="off" placeholder="Password">
                                        @error('password')
                                            <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        @if (Route::has('password.request'))
                                            <a class="text-primary fs-6 fw-bolder" href="{{ route('password.request') }}">
                                                {{ __('Forgot Password ?') }}
                                            </a>
                                        @endif
                                    </div>
                                    <div class="text-center">
                                        <button type="submit"
                                            class="btn bg-gradient-primary w-100 mt-4 mb-0">{{ __('Sign In') }}</button>
                                    </div>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-4 text-sm mx-auto">
                                        {{ __("Don't have an account yet?") }}
                                        <a href="{{ route('register') }}"
                                            class="text-primary text-gradient font-weight-bold">{{ __('Sign up') }}</a>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                            <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6"
                                style="background-image:url('{{ asset('adminDashboard/assets/img/curved-images/curved6.jpg') }}')">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
