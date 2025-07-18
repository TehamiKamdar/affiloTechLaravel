@extends('layouts.publisher.layout')

@section('styles')
@endsection

@section('scripts')
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
                                <li class="breadcrumb-item"><a href="">Login Information</a></li>
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
            <div class="card mb-5 mb-xl-10">
                <!--begin::Card header-->
                <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                    data-bs-target="#kt_account_profile_details" aria-expanded="true"
                    aria-controls="kt_account_profile_details">
                    <!--begin::Card title-->
                    <div class="card-title m-0">
                        <h3 class="fw-bold m-0">Login Information</h3>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--begin::Card header-->
                <!--begin::Content-->
                <div id="kt_account_settings_profile_details" class="collapse show">
                    <!--begin::Content-->
                    <div class="card-body">
                        <!--begin:::Tabs-->
                        <ul class="nav">
                            <!--begin:::Tab item-->
                            <li class="nav-item me-1">
                                <a class="btn btn-outline-primary mb-0 btn-sm @if(request()->route()->getName() == "publisher.profile.login-information.change-email") active @endif"
                                    href="{{ route("publisher.profile.login-information.change-email") }}">User Email </a>
                            </li>
                            <!--end:::Tab item-->
                            <!--begin:::Tab item-->
                            <li class="nav-item">
                                <a class="btn btn-outline-primary mb-0 btn-sm @if(request()->route()->getName() == "publisher.profile.login-information.change-password") active @endif"
                                    href="{{ route("publisher.profile.login-information.change-password") }}">Login
                                    Password</a>
                            </li>
                            <!--end:::Tab item-->
                        </ul>
                        <!--end:::Tabs-->
                        <!--begin:::Tab content-->
                        <div class="tab-content mt-5" id="myTabContent">
                            @if(request()->route()->getName() == "publisher.profile.login-information.change-email")
                                <!--begin:::Tab pane-->
                                <div class="tab-pane fade show active" id="kt_ecommerce_customer_general" role="tabpanel">
                                    <!--begin::Card-->
                                    <div class="card shadow-sm border-1">
                                        <div class="card-body p-5">
                                            <div class="row align-items-center mb-4">
                                                <!-- Email Label -->
                                                <label class="col-md-4 col-form-label fw-semibold fs-6 text-muted">
                                                    User Email
                                                </label>

                                                <!-- Email Input -->
                                                <div class="col-md-8">
                                                    <input type="email" name="company"
                                                        class="form-control form-control-lg form-control-solid" disabled
                                                        value="{{ auth()->user()->email }}" />
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action Button -->
                                        <div class="card-footer bg-white border-0 d-flex justify-content-end p-4">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#change-email-modal">
                                                <i class="fas fa-edit me-2"></i> Change Email
                                            </button>
                                        </div>
                                    </div>

                                </div>
                                <!--end:::Tab pane-->
                            @else
                                <!-- Begin: Change Password Tab Pane -->
                                <div class="tab-pane fade show active" id="kt_ecommerce_customer_advanced" role="tabpanel">
                                    <form action="{{ route('publisher.profile.changes.password-update') }}" method="POST"
                                        id="changePasswordForm">
                                        @csrf

                                        <div class="container py-5">
                                            <div class="row g-4">

                                                <!-- Current Password -->
                                                <div class="col-md-6">
                                                    <label for="current_password" class="form-label">Current Password <span
                                                            class="text-danger">*</span></label>
                                                    <input type="password" name="current_password" id="current_password"
                                                        class="form-control" required>
                                                </div>

                                                <!-- New Password -->
                                                <div class="col-md-6">
                                                    <label for="password" class="form-label">New Password <span
                                                            class="text-danger">*</span></label>
                                                    <input type="password" name="password" id="password" class="form-control"
                                                        required>
                                                </div>

                                                <!-- Confirm New Password -->
                                                <div class="col-md-6">
                                                    <label for="password_confirmation" class="form-label">Confirm New Password
                                                        <span class="text-danger">*</span></label>
                                                    <input type="password" name="password_confirmation"
                                                        id="password_confirmation" class="form-control" required>
                                                </div>

                                            </div>

                                            <!-- Submit Button -->
                                            <div class="d-flex justify-content-end mt-4">
                                                <button type="submit" class="btn btn-primary px-4">
                                                    Update Password
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- End: Change Password Tab Pane -->

                            @endif
                        </div>
                        <!--end:::Tab content-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Basic info-->

            @if(request()->route()->getName() == "publisher.profile.login-information.change-email")

                <!-- Begin: Change Email Modal -->
                <div class="modal fade" id="change-email-modal" tabindex="-1" aria-labelledby="changeEmailLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content rounded-4 shadow-sm">
                            <!-- Modal Header -->
                            <div class="modal-header border-bottom-0 pb-0">
                                <h5 class="modal-title fs-4 fw-bold" id="changeEmailLabel">Change Email</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <!-- Modal Body -->
                            <div class="modal-body pt-3">
                                <form action="{{ route('publisher.profile.changes.email-update') }}" method="POST"
                                    id="changeEmailForm">
                                    @csrf
                                    @method('PATCH')

                                    <div class="row g-4">
                                        <!-- New Email -->
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">New Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                placeholder="Enter new email" required>
                                        </div>

                                        <!-- Confirm Email -->
                                        <div class="col-md-6">
                                            <label for="email_confirmation" class="form-label">Confirm Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email_confirmation"
                                                name="email_confirmation" placeholder="Confirm new email" required>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
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
                <!-- End: Change Email Modal -->


            @endif
@endsection
