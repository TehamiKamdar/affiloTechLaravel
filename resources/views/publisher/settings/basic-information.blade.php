@extends('layouts.publisher.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('publisherAssets/assets/css/profile.css') }}">
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

    <!--begin::Navbar-->
    @include('publisher.settings.navbar')
    <!--end::Navbar-->
    <div class="card mb-5">
        <div class="card-header ">
            <h5>Basic Information</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('publisher.profile.basic-information.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                  <!-- Name & Username -->
                  <div class="row mb-4">
                    <div class="col-md-6">
                      <label class="form-label required">Full Name</label>
                      <input type="text" name="name" value="{{ $user->name }}" class="form-control" placeholder="Your full name" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label required">Username</label>
                      <input type="text" name="user_name" value="{{ $publisher->user_name ?? '' }}" class="form-control" placeholder="User name" />
                    </div>
                  </div>

                  <!-- Bio -->
                  <div class="mb-4">
                    <label class="form-label required">Bio</label>
                    <small class="text-muted d-block mb-1">Tell advertisers about yourself and what you're looking
                      for.</small>
                    <textarea name="bio" rows="4" class="form-control" placeholder="Write your bio here...">{{ $publisher->intro ?? '' }}</textarea>
                  </div>

                  <!-- Region & Language -->
                  <div class="row mb-4">
                    <div class="col-md-6">
                      <label class="form-label required">Target Region</label>
                      <select name="country" class="form-control" required>
                            <option value="">Select a Country...</option>
                            @foreach($countries as $country)
                                <option value="{{ $country['name'] }}" @if(in_array($country['name'], json_decode($publisher->customer_reach ?? '[]', true))) selected @endif>
                                    {{ $country['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label required">Language</label>
                      <select name="language" class="form-control" required>
                            <option value="">Select a Language...</option>
                            @foreach($languages as $language)
                                <option value="{{ $language }}" @if(in_array($language, json_decode($publisher->language ?? '[]', true))) selected @endif>
                                    {{ $language }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                  </div>

                  <!-- Gender & DOB -->
                  <div class="row mb-4">
                    <div class="col-md-6">
                      <label class="form-label required">Gender</label>
                      <select name="gender" class="form-control">
                        <option value="">Select Gender</option>
                        <option value="male" @selected(old('gender', $publisher->gender ?? '') === 'male')>Male</option>
                        <option value="female" @selected(old('gender', $publisher->gender ?? '') === 'female')>Female
                        </option>
                        <option value="nonbinary" @selected(old('gender', $publisher->gender ?? '') ===
                          'nonbinary')>Nonbinary</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label required">Date of Birth</label>
                      <input type="date" name="dob" value="{{ $publisher->dob ?? '' }}" class="form-control" />
                    </div>
                  </div>

                  <!-- Address -->
                  <div class="mb-4">
                    <label class="form-label required">Address</label>
                    <input type="text" value="{{ $publisher->location_address_1 ?? '' }}" name="location_address_1"
                      class="form-control" placeholder="Enter your address" />
                  </div>

                  <!-- Location Fields -->
                  <div class="row mb-4">
                    <div class="col-md-4">
                      <label class="form-label required">Country</label>
                      <select name="location_country" id="location_country" class="form-control" required>
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                        <option value="{{ $country['id'] }}"
    @selected((string) old('location_country', (string) $publisher->location_country) === (string) $country['id'])>
    {{ ucwords($country['name']) }}
</option>

                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label required">State</label>
                      <select name="location_state" id="location_state" class="form-control" required>
                        <option value="">Select State</option>
                        @foreach($states as $state)
                        <option value="{{ $state['id'] }}" @selected($publisher->location_state == $state['id'])>
                          {{ ucwords($state['name']) }}
                        </option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label required">City</label>
                      <select name="location_city" id="location_city" class="form-control" required>
                        <option value="">Select City</option>
                        @foreach($cities as $city)
                        <option value="{{ $city['id'] }}" @selected($publisher->location_city == $city['id'])>
                          {{ ucwords($city['name']) }}
                        </option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <!-- Media Kit -->
                  <div class="mb-4">
                    <label class="form-label">Media Kit</label>
                    <input type="file" name="mediakit_image" class="form-control border-0 mb-3" />
                    <div class="table-responsive">
                      <table class="table table-bordered text-center">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Size</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          @forelse($mediakits as $kit)
                          <tr>
                            <td class="text-start"><a href="{{ \App\Helper\Methods::staticAsset($kit->image) }}"
                                target="_blank">{{ $kit->name }}</a></td>
                            <td>{{ $kit->size }} Kb</td>
                            <td>
                              <a href="{{ route('publisher.profile.basic-information.media-kits.delete', $kit->id) }}"
                                class="text-danger"><i class="fa-solid fa-trash"></i></a>
                            </td>
                          </tr>
                          @empty
                          <tr>
                            <td colspan="3">
                              <img src="{{ \App\Helper\Methods::staticAsset('assets/media/folders/1.svg') }}"
                                class="mb-2" />
                              <p class="mb-0">No Media Kits Found</p>
                            </td>
                          </tr>
                          @endforelse
                        </tbody>
                      </table>
                    </div>
                  </div>
                  @php
    $profileImage = isset($publisher) && $publisher->image
        ? 'storage/' . $publisher->image
        : 'assets/media/avatars/blank.png';
@endphp
                  <!-- Avatar -->
                  <div class="mb-4">
    <label class="form-label required">Profile Image</label>

    <div class="image-input image-input-outline"
        data-kt-image-input="true"
        style="background-image: url('{{ \App\Helper\Methods::staticAsset($profileImage) }}')">

        <div class="image-input-wrapper w-125px h-125px"
            style="background-image: url('{{ \App\Helper\Methods::staticAsset($profileImage) }}')">
        </div>

        <label class="btn btn-icon btn-circle btn-active-color-primary bg-body shadow"
            data-kt-image-input-action="change" title="Change avatar">
            <i class="ki-duotone ki-pencil fs-7"></i>
            <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
            <input type="hidden" name="avatar_remove" />
        </label>

        <span class="btn btn-icon btn-circle btn-active-color-primary bg-body shadow"
            data-kt-image-input-action="remove" title="Remove avatar">
            <i class="ki-duotone ki-cross fs-2"></i>
        </span>
    </div>

    <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
</div>

                  <!-- Submit -->
                  <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4"
                      style="background-color: #00a9da; border-color: #00a9da;">
                      Update Profile
                    </button>
                  </div>
                </form>
        </div>
    </div>


@endsection
