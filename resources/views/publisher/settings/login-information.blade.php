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

@section('content')
    @include("partial.alert")

    @include('publisher.settings.navbar')
    <div class="card mb-5 mb-xl-10">
        <div class="card-header border-0 cursor-pointer" role="button" data-toggle="collapse"
            data-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
            <div class="card-title m-0">
                <h4 class="fw-bold m-0">Login Information</h4>
            </div>
        </div>
        <div id="kt_account_settings_profile_details" class="collapse show">
            <div class="card-body">
                <ul class="nav">
                    <li class="nav-item me-1">
                        <a class="btn btn-outline-primary mb-0 btn-sm @if(request()->route()->getName() == "publisher.profile.login-information.change-email") active @endif"
                            href="{{ route("publisher.profile.login-information.change-email") }}">User Email </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary mb-0 btn-sm @if(request()->route()->getName() == "publisher.profile.login-information.change-password") active @endif"
                            href="{{ route("publisher.profile.login-information.change-password") }}">Login
                            Password</a>
                    </li>
                </ul>
                <div class="tab-content mt-5" id="myTabContent">
                    @if(request()->route()->getName() == "publisher.profile.login-information.change-email")
                        <div class="tab-pane fade show active" id="kt_ecommerce_customer_general" role="tabpanel">
                            <div class="card shadow-none">
                                <div class="card-body">
                                    <div class="row align-items-center mb-4">
                                        <label class="col-md-4 col-form-label fw-semibold fs-6 text-muted">
                                            User Email
                                        </label>

                                        <div class="col-md-8">
                                            <input type="email" name="company" class="form-control form-control-solid"
                                                value="{{ auth()->user()->email }}" />
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer bg-white border-0 d-flex justify-content-end p-4">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#change-email-modal">
                                        <i class="fas fa-edit me-2"></i> Change Email
                                    </button>
                                </div>
                            </div>

                        </div>
                    @else
                        <div class="tab-pane fade show active" id="kt_ecommerce_customer_advanced" role="tabpanel">
                            <form action="{{ route('publisher.profile.changes.password-update') }}" method="POST"
                                id="changePasswordForm">
                                @csrf

                                <div class="container">
                                    <div class="row justify-content-center">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="mb-3">
                                                <label for="current_password" class="form-label">Current Password <span
                                                        class="text-danger">*</span></label>
                                                <input type="password" name="current_password" id="current_password"
                                                    class="form-control" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="password" class="form-label">New Password <span
                                                        class="text-danger">*</span></label>
                                                <input type="password" name="password" id="password" class="form-control"
                                                    required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="password_confirmation" class="form-label">Confirm New Password <span
                                                        class="text-danger">*</span></label>
                                                <input type="password" name="password_confirmation" id="password_confirmation"
                                                    class="form-control" required>
                                            </div>

                                            <div class="d-flex justify-content-end mt-4">
                                                <button type="submit" class="btn btn-primary px-4">
                                                    Update Password
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>


                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(request()->route()->getName() == "publisher.profile.login-information.change-email")

        <div class="modal fade" id="change-email-modal" tabindex="-1" aria-labelledby="changeEmailLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 shadow-sm">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fs-4 fw-bold" id="changeEmailLabel">Change Email</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body pt-3">
                        <form action="{{ route('publisher.profile.changes.email-update') }}" method="POST" id="changeEmailForm">
                            @csrf
                            @method('PATCH')

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">New Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Enter new email" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="email_confirmation" class="form-label">Confirm Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email_confirmation" name="email_confirmation"
                                        placeholder="Confirm new email" required>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-5">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
