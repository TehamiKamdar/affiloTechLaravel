@extends("layouts.admin.layout")

@section('styles')
@endsection

@section('scripts')
    <script src="{{ asset('adminDashboard/assets/js/edit-publisher.js') }}"></script>
@endsection

@section("content")

    <div class="d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-fluid">

                <form id="kt_publisher_form" data-kt-search-element="form" class="w-100 position-relative mb-5 updateData" autocomplete="off" action="{{ route('admin.publishers.update', ['publisher' => $publisher->id]) }}"  method="POST">
                    @csrf
                    @method('PUT')

                    <!--begin::Card-->
                    <div class="card">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title flex-column">
                                <h3 class="fw-bold mb-1">Edit Publisher ( {{ $publisher->name }} )</h3>
                            </div>
                            <!--begin::Card title-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">

                            @include("partial.admin.alert")
                            <!--begin::Input group-->
                            <div class="row mb-6 mt-6">
                                <div class="col-lg-12">
                                    <!--begin::Label-->
                                    <label class="col-form-label fw-semibold fs-6 required">
                                        Name
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="fv-row">
                                        <input type="text" name="name" class="form-control form-control-lg form-control-solid" placeholder="Enter Name" value="{{ $publisher->name }}" />
                                    </div>
                                    <!--end::Col-->
                                </div>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6 mt-6">
                                <div class="col-lg-12">
                                    <!--begin::Label-->
                                    <label class="col-form-label fw-semibold fs-6 required">
                                        Email Address
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="fv-row">
                                        <input type="email" name="email" class="form-control form-control-lg form-control-solid" placeholder="Enter Email Address" value="{{ $publisher->email }}" />
                                    </div>
                                    <!--end::Col-->
                                </div>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6 mt-6">
                                <div class="col-lg-12">
                                    <!--begin::Label-->
                                    <label class="col-form-label fw-semibold fs-6">
                                        Password
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="fv-row">
                                        <input type="password" name="password" class="form-control form-control-lg form-control-solid" placeholder="Enter Password" />
                                    </div>
                                    <!--end::Col-->
                                </div>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6 mt-6">
                                <div class="col-lg-12">
                                    <!--begin::Label-->
                                    <label class="col-form-label fw-semibold fs-6">
                                        Confirm Password
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="fv-row">
                                        <input type="password" name="password_confirmation" class="form-control form-control-lg form-control-solid" placeholder="Enter Confirm Password" />
                                    </div>
                                    <!--end::Col-->
                                </div>
                            </div>
                            <!--end::Input group-->

                        </div>
                        <!--end::Card body-->
                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                            <button type="submit" class="btn btn-primary" id="kt_publishers_submit">Save Changes</button>
                        </div>
                    </div>
                    <!--end::Card-->

                </form>

            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>

@endsection
