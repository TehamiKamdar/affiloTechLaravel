@extends('layouts.pr_auth')

@section('content')
<div class="container-fluid d-flex justify-content-center align-items-center min-vh-100 p-4">
    <div class="card bg-light rounded-4 p-4 p-lg-5" style="max-width: 600px; width: 100%;">
        <div class="card-body p-4">
            <form action="{{ route('verification.verify.code') }}" method="POST" class="needs-validation" novalidate id="kt_sing_in_two_factor_form">
                @csrf

                <!-- Icon -->
                <div class="text-center mb-4">
                    <i class="fas fa-envelope-open-text fa-5x text-primary"></i>
                </div>

                <!-- Heading -->
                <div class="text-center mb-4">
                    <h1 class="h2 mb-3">Email Verification</h1>
                    <p class="text-muted mb-2">Enter the verification code we sent to</p>
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
                <div class="mb-4">
                    <label class="form-label">Type 6 digit code received in email</label>
                    <div class="d-flex justify-content-between gap-2">
                        <input type="text" id="code_1" name="code_1" maxlength="1" class="form-control form-control-lg text-center py-3" oninput="moveToNext(this, 'code_2')" autofocus>
                        <input type="text" id="code_2" name="code_2" maxlength="1" class="form-control form-control-lg text-center py-3" oninput="moveToNext(this, 'code_3')">
                        <input type="text" id="code_3" name="code_3" maxlength="1" class="form-control form-control-lg text-center py-3" oninput="moveToNext(this, 'code_4')">
                        <input type="text" id="code_4" name="code_4" maxlength="1" class="form-control form-control-lg text-center py-3" oninput="moveToNext(this, 'code_5')">
                        <input type="text" id="code_5" name="code_5" maxlength="1" class="form-control form-control-lg text-center py-3" oninput="moveToNext(this, 'code_6')">
                        <input type="text" id="code_6" name="code_6" maxlength="1" class="form-control form-control-lg text-center py-3">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid mb-4">
                    <button type="submit" id="kt_sing_in_two_factor_submit" class="btn btn-primary btn-lg">
                        <span class="indicator-label">Confirm</span>
                        <span class="indicator-progress d-none">
                            Please wait...
                            <span class="spinner-border spinner-border-sm ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>

            <!-- Resend Link -->
            <div class="text-center">
                <p class="text-muted">
                    Didn't get the code?
                    <a href="javascript:void(0)" onclick="resendEmail()" class="text-primary">Resend</a>
                    or
                    <a href="#" class="text-primary">Contact Support</a>
                </p>
            </div>

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
