@extends('layouts.at_auth')

@section('title')
Verify Login
@endsection

@section('content')
    <div class="row min-vh-100 align-items-center justify-content-center">

        <!-- Left Side: Form Card -->
        <div class="col-12 col-md-9 col-lg-6">
            <div class="card bg-light rounded-lg p-4">
                <div class="card-body">

                    <form action="{{ route('verification.verify.code') }}" method="POST" class="needs-validation" novalidate id="kt_sing_in_two_factor_form">
                        @csrf

                        <!-- Icon -->
                        <div class="text-center mb-4">
                            <i class="fas fa-envelope-open-text fa-5x text-primary"></i>
                        </div>

                        <!-- Heading -->
                        <div class="text-center mb-4">
                            <h1 class="h2 mb-2">Email Verification</h1>
                            <p class="text-muted mb-1">Enter the verification code we sent to</p>
                            <h3 class="h5">{{ \App\Helper\Methods::obfuscateEmail(request()->user()->email) }}</h3>

                            @if ($errors->any())
                                <div class="alert alert-danger mt-3">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <!-- Verification Code Inputs -->
                        <div class="form-group mb-4">
                            <label class="form-label">Type 6 digit code received in email</label>
                            <div class="d-flex justify-content-between">
                                <input type="text" id="code_1" name="code_1" maxlength="1" class="form-control text-center mx-1 py-3" oninput="moveToNext(this, 'code_2')" autofocus>
                                <input type="text" id="code_2" name="code_2" maxlength="1" class="form-control text-center mx-1 py-3" oninput="moveToNext(this, 'code_3')">
                                <input type="text" id="code_3" name="code_3" maxlength="1" class="form-control text-center mx-1 py-3" oninput="moveToNext(this, 'code_4')">
                                <input type="text" id="code_4" name="code_4" maxlength="1" class="form-control text-center mx-1 py-3" oninput="moveToNext(this, 'code_5')">
                                <input type="text" id="code_5" name="code_5" maxlength="1" class="form-control text-center mx-1 py-3" oninput="moveToNext(this, 'code_6')">
                                <input type="text" id="code_6" name="code_6" maxlength="1" class="form-control text-center mx-1 py-3">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group text-center mb-4">
                            <button type="submit" id="kt_sing_in_two_factor_submit" class="btn btn-primary btn-lg btn-block">
                                <span class="indicator-label">Confirm</span>
                                <span class="indicator-progress d-none">
                                    Please wait...
                                    <span class="spinner-border spinner-border-sm ml-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>

                    <!-- Resend Link -->
                    <div class="text-center">
                        <p class="text-muted mb-0">
                            Didn't get the code?
                            <a href="javascript:void(0)" onclick="resendEmail()" class="text-primary">Resend</a>
                            or
                            <a href="#" class="text-primary">Contact Support</a>
                        </p>
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection

@section("script")
    <script>
        function moveToNext(current, nextFieldID) {
            if (current.value.length >= current.maxLength) {
                document.getElementById(nextFieldID).focus();
            }
        }
        function resendEmail()
        {
            $.ajax({
                url: "{{ route("verification.resend.email") }}",
                type: 'POST',
                headers: {'X-CSRF-Token': "{{ csrf_token() }}"},
                success: function(response) {
                    // console.log(response); // Handle response data
                },
                error: function(xhr, status, error) {
                    console.error(error); // Handle errors
                }
            });
        }
    </script>
@endsection
