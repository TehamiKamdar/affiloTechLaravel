@extends('layouts.publisher.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('publisherAssets/assets/css/profile.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg collapse-btn">
    <li class="breadcrumb-item mt-1">
        <a href="#"><i data-feather="home"></i></a>
    </li>
    <li class="breadcrumb-item mt-1">
        <a href="#" class="text-sm">Profile</a>
    </li>
    <li class="breadcrumb-item mt-1 active">
        <a href="#" class="text-sm">Information</a>
    </li>
</ol>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#countrySelect').on('change', function () {
                let countryId = $(this).val();
                $('#stateSelect').html('<option value="">Loading...</option>');
                $('#citySelect').html('<option value="">Select a City...</option>');

                if (countryId) {
                    $.ajax({
                        url: '/publisher/profile/get-states/' + countryId,
                        type: 'GET',
                        success: function (data) {
                            $('#stateSelect').empty().append('<option value="">Select a State...</option>');
                            $.each(data, function (key, state) {
                                $('#stateSelect').append(`<option value="${state.id}">${state.name}</option>`);
                            });
                        }
                    });
                } else {
                    $('#stateSelect').html('<option value="">Select a State...</option>');
                }
            });

            $('#stateSelect').on('change', function () {
                let stateId = $(this).val();
                $('#citySelect').html('<option value="">Loading...</option>');

                if (stateId) {
                    $.ajax({
                        url: '/publisher/profile/get-cities/' + stateId,
                        type: 'GET',
                        success: function (data) {
                            $('#citySelect').empty().append('<option value="">Select a City...</option>');
                            $.each(data, function (key, city) {
                                $('#citySelect').append(`<option value="${city.id}">${city.name}</option>`);
                            });
                        }
                    });
                } else {
                    $('#citySelect').html('<option value="">Select a City...</option>');
                }
            });
        });
    </script>

@endsection

@section('content')
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('publisher.dashboard') }}"><i
                                            class="ri-home-5-line text-primary"></i></a></li>
                                <li class="breadcrumb-item"><a href="">Profile</a></li>
                                <li class="breadcrumb-item"><a href="">Payment & Billing Information</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            @include("partial.alert")

            <!--begin::Navbar-->
            @include('publisher.settings.navbar')
            <!--end::Navbar-->

            <!--begin::Basic info-->

            <!-- Billing Information Card -->
            <div class="card mb-4 border-bottom">
                <div class="card-header">
                    <h5 class="mb-0">Billing Information</h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('publisher.profile.storepayments') }}">
                        @csrf

                        <div class="mb-3 row">
                            <label for="billingName" class="col-md-4 col-form-label">Billing Name</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="billingName" name="fname" placeholder="Name"
                                    value="{{ $billing->name ?? '' }}">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="billingPhone" class="col-md-4 col-form-label">Billing Phone</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="billingPhone" name="billing_phone"
                                    placeholder="Phone" value="{{ $billing->phone ?? '' }}">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="billingAddress" class="col-md-4 col-form-label">Billing Address</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="billingAddress" name="billing_address"
                                    placeholder="Address" value="{{ $billing->address ?? '' }}">
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label class="col-md-4 col-form-label">Country / State / City</label>
                            <div class="col-md-8">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <select name="country" id="countrySelect" class="form-control">
                                            <option value="">Select Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country['id'] }}" {{ isset($company->country) && $company->country == $country['id'] ? 'selected' : '' }}>
                                                    {{ ucwords($country['name']) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <select name="state" id="stateSelect" class="form-control">
                                            <option value="">Select State</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <select name="city" id="citySelect" class="form-control">
                                            <option value="">Select City</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="companyRegistration" class="col-md-4 col-form-label">Company Registration
                                Number</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="companyRegistration" name="company_registration"
                                    placeholder="Registration Number" value="{{ $billing->company_registration_no ?? '' }}">
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="taxNumber" class="col-md-4 col-form-label">TAX/VAT Number</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="taxNumber" name="tax_number"
                                    placeholder="TAX/VAT Number" value="{{ $billing->tax_vat_no ?? '' }}">
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <!--begin::Sign-in Method-->




            <!-- Payment Settings Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Payment Settings</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('publisher.profile.storepayment') }}" method="POST" id="paymentForm"
                        enctype="multipart/form-data">
                        @csrf

                        <!-- Payment Frequency & Threshold -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="payment_frequency" class="form-label">Payment Frequency <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" id="payment_frequency" name="payment_frequency" required>
                                    <option disabled selected>Please Select</option>
                                    <option value="every_month" {{ isset($payment->payment_frequency) && $payment->payment_frequency == "every_month" ? "selected" : "" }}>Every Month</option>
                                    <option value="after_45_days" {{ isset($payment->payment_frequency) && $payment->payment_frequency == "after_45_days" ? "selected" : "" }}>After 45 Days
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="payment_threshold" class="form-label">Payment Threshold <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" id="payment_threshold" name="payment_threshold" required>
                                    <option disabled selected>Please Select</option>
                                    @foreach([100, 500, 1000, 2500, 5000, 10000] as $threshold)
                                        <option value="{{ $threshold }}" {{ isset($payment->payment_threshold) && $payment->payment_threshold == $threshold ? 'selected' : '' }}>${{ $threshold }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <small class="text-muted">Note: You can schedule when you would like to receive your commission
                                payments and at what threshold your approved commissions must reach before payout.</small>
                        </div>

                        <!-- Payment Method Section -->
                        <div class="mb-4">
                            <h6 class="fw-semibold">Payment Method</h6>
                            <small class="text-muted">Select your active payment method where you want to withdraw
                                funds.</small>
                        </div>

                        <!-- Conditionally Included Payment Method -->
                        @if(isset($payment->payment_method) && $payment->payment_method)
                            @if($payment->payment_method == "bank")
                                @include("publisher.settings.default_method.bank", compact('payment'))
                            @elseif($payment->payment_method == "paypal")
                                @include("publisher.settings.default_method.paypal", compact('payment'))
                            @elseif($payment->payment_method == "payoneer")
                                @include("publisher.settings.default_method.payoneer", compact('payment'))
                            @endif
                        @else
                            <div class="alert alert-danger text-white fw-bold">No default payment method selected yet.</div>
                        @endif

                        <!-- Other Payment Options (Dynamic) -->
                        <div id="settingOptions">
                            @include("publisher.settings.form.options", compact('payment', 'countries'))
                        </div>
                    </form>
                </div>
            </div>


            <script>

  const form = document.getElementById('paymentForm');

   console.log(form)
    form.addEventListener('submit', function (e) {
        let valid = true;

        // Clear all required fields first
        form.querySelectorAll('[data-method-form]').forEach(section => {
            section.querySelectorAll('input, select, textarea').forEach(field => {
                field.removeAttribute('required');
                field.classList.remove('is-invalid');
            });
        });

        // Validate only visible section
        const selected = form.querySelector('.payment-radio:checked')?.value;
        const activeForm = document.querySelector(`#collapse-${selected}`);

        if (activeForm) {
            activeForm.querySelectorAll('input, select, textarea').forEach(field => {
                if (!field.disabled && field.offsetParent !== null) {
                    field.setAttribute('required', true);
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        valid = false;
                    }
                }
            });
        }

        if (!valid) {
            e.preventDefault();
        }

});
</script>
@endsection
