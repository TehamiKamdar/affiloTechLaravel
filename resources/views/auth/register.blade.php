@extends('layouts.pr_auth')

@section('content')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<section class="min-vh-100 mb-8">
      <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg"
        style="background-image: url('{{ asset('adminDashboard/assets/img/curved-images/curved6.jpg') }}');">
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-5 text-center mx-auto">
              <h1 class="text-white mb-2 mt-5">Welcome!</h1>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row mt-lg-n10 mt-md-n11 mt-n10">
          <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
            <form role="form text-left" novalidate="novalidate" id="registration-form" method="POST" action="{{ route('register') }}">
              @csrf

              <div class="d-flex flex-center">
                <a href="{{ url('/') }}" class="mb-4 z-index-0" style="text-align: center;">
                    <img alt="Logo" src="{{ asset('assets/media/logos/logo-2.webp') }}"  class="h-100 w-70"/>
                </a>
              </div>


              <!-- Display Validation Errors -->
              @if ($errors->any())
                  <div class="alert alert-danger">
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              @endif



              <div class="card z-index-0">
                <div class="card-header text-center pt-4">
                  <h5>{{ __('Register for new publisher account') }}</h5>
                </div>

                <div class="card-body">
                  <div class="mb-3">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Full Name" >
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <div class="mb-3">
                    <input type="text" class="form-control @error('user_name') is-invalid @enderror" name="user_name" value="{{ old('user_name') }}" required autocomplete="user_name" placeholder="User Name" >
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <div class="mb-3">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email" >
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <div class="mb-3">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                    @enderror
                  </div>
                  <div class="text-muted text-sm">{{ __('Use 8 or more characters with a mix of letters, numbers & symbols.') }}</div>
                  <div class="mb-3">
                    <input type="password" class="form-control bg-transparent" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat Password">
                  </div>
                  <div class="form-check form-check-info text-left">
                    <input class="" type="checkbox" name="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
                    <label class="" for="flexCheckDefault">
                      I agree the <a href="https://www.theaffilo.com/terms" class="text-dark font-weight-bolder">Terms and Conditions</a>
                    </label>
                    @error('terms')
                        <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
 <div class="g-recaptcha" data-sitekey="6LfmrHsrAAAAAIZGVAUylfd7PrPaBbs527qZqvny"></div>
<br>
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">{{__('Sign up')}}</button>
                  </div>
                  <p class="text-sm mt-3 mb-0">{{__('Already have an account?')}} <a href="{{ route("login") }}"
                      class="text-primary text-gradient font-weight-bolder">{{__('Sign in')}}</a></p>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
{{-- 
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    document.getElementById('registration-form').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent form submission for now

        // Get the reCAPTCHA response token
        var recaptchaResponse = grecaptcha.getResponse();

        if (recaptchaResponse.length === 0) {
            alert("Please complete the CAPTCHA");
            return false; // Stop form submission if CAPTCHA is not completed
        }

        // Create a hidden field to send the token to the server
        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'g-recaptcha-response';
        hiddenInput.value = recaptchaResponse;

        // Append the hidden input to the form
        document.getElementById('registration-form').appendChild(hiddenInput);

        // Submit the form
        this.submit();
    });
</script>
@endsection
