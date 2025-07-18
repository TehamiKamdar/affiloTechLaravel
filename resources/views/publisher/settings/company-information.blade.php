@extends('layouts.publisher.layout')

@section('styles')
@endsection

@section('scripts')
    <script>
        $('#location_country').change(function () {

            $("#settingForm").addClass("disableDiv");
            $("#showLoader").show();
            $("#location_state").html("<option value='' selected disabled>Please Select</option>");
            $("#location_city").html("<option value='' selected disabled>Please Select</option>");

            $.ajax({
                url: '{{ route("get-states") }}',
                type: 'POST',
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                data: { "country_id": $(this).val(), "_token": "{{ csrf_token() }}" },
                success: function (response) {
                    response.map(state => {
                        $("#location_state").append(`<option value="${state.id}">${state.name}</option>`);
                    })
                    $("#settingForm").removeClass("disableDiv");
                    $("#showLoader").hide();
                },
                error: function (response) {
                    showErrors(response);
                    $("#settingForm").removeClass("disableDiv");
                    $("#showLoader").hide();
                }
            });
        });
        $('#location_state').change(function () {

            $("#settingForm").addClass("disableDiv");
            $("#showLoader").show();

            $.ajax({
                url: '{{ route("get-cities") }}',
                type: 'POST',
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                data: { "state_id": $(this).val(), "country_id": $('#location_country').val(), "_token": "{{ csrf_token() }}" },
                success: function (response) {
                    $("#location_city").html("<option value='' selected disabled>Please Select</option>");
                    response.map(city => {
                        $("#location_city").append(`<option value="${city.id}">${city.name}</option>`);
                    })
                    $("#settingForm").removeClass("disableDiv");
                    $("#showLoader").hide();
                },
                error: function (response) {
                    showErrors(response);
                    $("#settingForm").removeClass("disableDiv");
                    $("#showLoader").hide();
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
                                <li class="breadcrumb-item"><a href="">Company Information</a></li>
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
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Company Information</h5>
                    </div>
                    <form action="{{ route('publisher.profile.company-information.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="company_name" class="form-label">Company Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="company_name" id="company_name" class="form-control"
                                    placeholder="Company Name" value="{{ $company->company_name ?? '' }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="legal_entity_type" class="form-label">Legal Entity Type <span
                                        class="text-danger">*</span></label>
                                <select name="legal_entity_type" id="legal_entity_type" class="form-select" required>
                                    <option value="">Select a Legal Entity Type...</option>
                                    @foreach(\App\Models\Publisher::getLegalEntity() as $entity)
                                        <option value="{{ $entity['value'] }}" {{ isset($company->legal_entity_type) && $company->legal_entity_type == $entity['value'] ? 'selected' : '' }}>
                                            {{ $entity['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="location_country" class="form-label">Country <span
                                            class="text-danger">*</span></label>
                                    <select name="country" id="location_country" class="form-select" required>
                                        <option value="">Select a Country...</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country['id'] }}" {{ isset($company->country) && $company->country == $country['id'] ? 'selected' : '' }}>
                                                {{ ucwords($country['name']) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="location_state" class="form-label">State <span
                                            class="text-danger">*</span></label>
                                    <select name="state" id="location_state" class="form-select" required>
                                        <option value="">Select a State...</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state['id'] }}" {{ isset($company->state) && $company->state == $state['id'] ? 'selected' : '' }}>
                                                {{ ucwords($state['name']) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="location_city" class="form-label">City <span
                                            class="text-danger">*</span></label>
                                    <select name="city" id="location_city" class="form-select" required>
                                        <option value="">Select a City...</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city['id'] }}" {{ isset($company->city) && $company->city == $city['id'] ? 'selected' : '' }}>
                                                {{ ucwords($city['name']) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label for="address" class="form-label">Address Line 1 <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="address" id="address" class="form-control" required
                                    value="{{ $company->address ?? '' }}">
                            </div>

                            <div class="mt-3">
                                <label for="phone_number" class="form-label">Company Contact <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="phone_number" id="phone_number" class="form-control" required
                                    placeholder="Contact Number" value="{{ $company->phone_number ?? '' }}">
                            </div>

                            <div class="mt-3">
                                <label for="address_2" class="form-label">Address Line 2</label>
                                <input type="text" name="address_2" id="address_2" class="form-control" required
                                    placeholder="Brief introduction" value="{{ $company->address_2 ?? '' }}">
                            </div>
                        </div>

                        <div class="card-footer text-end bg-white">
                            <button type="submit" class="btn btn-primary px-4">Update</button>
                        </div>
                    </form>
                </div>
@endsection
