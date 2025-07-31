@extends('layouts.at_auth')

@section('title')
Register
@endsection

@section('content')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-8 offset-lg-2">
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="w-100 d-flex align-items-center justify-content-around  flex-wrap">

                                <!-- Website Logo -->
                                <div class="my-4 mb-md-0">
                                    <a href="{{ url('/') }}">
                                        <img src="{{ asset('publisherAssets/assets/affiloTechLogo.png') }}"
                                            alt="Website Logo" style="height: 40px;">
                                    </a>
                                </div>

                                <!-- Heading and Description -->
                                <div class="mt-4 text-center">
                                    <h3 class="fw-bolder text-primary mb-1">{{ __('Sign Up') }}</h3>
                                    <p class="mb-0">{{ __('Get registered for your publisher account') }}</p>
                                </div>

                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="name">First Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name') }}" required autocomplete="name"
                                            placeholder="Full Name">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="username">User Name</label>
                                        <input type="text" class="form-control @error('user_name') is-invalid @enderror"
                                            name="user_name" value="{{ old('user_name') }}" required
                                            autocomplete="user_name" placeholder="User Name">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required autocomplete="email"
                                        placeholder="Email">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="password" class="d-block">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            name="password" required autocomplete="new-password" placeholder="Password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <div id="pwindicator" class="pwindicator">
                                            <div class="bar"></div>
                                            <div class="label"></div>
                                        </div>
                                        <div class="text-muted text-sm">
                                            {{ __('Use 8 or more characters with a mix of letters, numbers & symbols.') }}
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <label for="password2" class="d-block">Password Confirmation</label>
                                        <input id="password2" type="password" class="form-control" required
                                            autocomplete="new-password" name="password_confirmation"
                                            placeholder="Repeat Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="terms" value="1" {{ old('terms') ? 'checked' : '' }}
                                        required>
                                    <label class="" for="flexCheckDefault">
                                        I agree the <a href="https://www.theaffilo.com/terms"
                                            class="text-dark font-weight-bolder">Terms and Conditions</a>
                                    </label>
                                    @error('terms')
                                        <p class="mt-2 text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="g-recaptcha " data-sitekey="6LfmrHsrAAAAAIZGVAUylfd7PrPaBbs527qZqvny"></div>
                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        {{__('Sign up')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="mb-4 text-muted text-center">
                            Already Registered? <a href="{{ route("login") }}">Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
