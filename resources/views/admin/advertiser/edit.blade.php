@extends("layouts.admin.layout")

@section('breadcrumb')
<ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg collapse-btn">
    <li class="breadcrumb-item mt-1">
        <a href="{{ route('publisher.dashboard') }}"><i data-feather="home"></i></a>
    </li>
    <li class="breadcrumb-item mt-1">
        <a href="#" class="text-sm">Edit Advertiser</a>
    </li>
    <li class="breadcrumb-item mt-1 active">
        <a href="#" class="text-sm">Advertisers</a>
    </li>
</ol>
@endsection

@section("styles")

    <!-- data tables css -->
    <link href="{{ asset('assets/admin/plugins/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/plugins/css/jquery.tagsinput.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/plugins/css/select.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/plugins/css/summernote-bs4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/plugins/css/summernote.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/plugins/css/select2.min.css') }}" rel="stylesheet">
    <style>
        table td:last-child {
            padding: 0.65rem 0.75rem !important;
        }

        table td:last-child .btn {
            margin-right: 0 !important;
        }
        select option {
            background-color: white;
            color: var(--primary-color);
            font-weight: 500;
            border-bottom: 1px solid #f0f2fc;
        }

        option:hover {
            background-color: var(--primary-very-light) !important;
        }

        select option:checked,
        select option:active {
            background-color: var(--primary-very-light) !important;
            color: var(--primary-color);
        }


        .form-control {
            border: 2px solid #e0e3ed;
            color: var(--primary-color);
            font-weight: 500;
            border-radius: 8px !important;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--primary-color);
        }
    </style>

@endsection

@section('editor')

@endsection

@section('scripts')
<script src="{{ asset('assets/admin/plugins/js/summernote.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/js/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/js/jquery.tagsinput.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        @if(\App\Helper\Advertiser\Base::getFormFieldReadOnly($advertiser->source, "program_policies"))
            $('#short_description, #description').summernote({
                height: 300
            });
        @elseif(\App\Helper\Advertiser\Base::getFormFieldReadOnly($advertiser->source, "short_description"))
            $('#program_policies, #description').summernote({
                height: 300
            });
        @elseif(\App\Helper\Advertiser\Base::getFormFieldReadOnly($advertiser->source, "description"))
            $('#program_policies, #short_description').summernote({
                height: 300
            });
        @else
            $('#program_policies, #description, #short_description').summernote({
                height: 300
            });
        @endif
        });
</script>

@endsection

@section('content')
<div class="contents">

    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">

                            @include("partial.admin.alert")

                            <form
                                action="{{ route("admin.advertisers.api.update", ["advertiser" => $advertiser->id]) }}"
                                method="POST" enctype="multipart/form-data" id="userForm">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                            <label for="name" class="font-weight-bold text-black">Advertiser
                                                Name</label>
                                            <input type="text" id="name" name="name" class="form-control"
                                                value="{{ old('name', $advertiser->name ?? '') }}">
                                            @if($errors->has('name'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('name') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('url') ? 'has-error' : '' }}">
                                            <label for="url" class="font-weight-bold text-black">Advertiser URL</label>
                                            <input type="url" id="url" name="url" class="form-control"
                                                value="{{ old('url', $advertiser->url ?? '') }}">
                                            @if($errors->has('url'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('url') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('primary_region') ? 'has-error' : '' }}">
                                            <label for="primary_region" class="font-weight-bold text-black">Primary
                                                Region</label>
                                            <select class="js-example-basic-single js-states form-control"
                                                id="primary_region" name="primary_region">
                                                <option value="" disabled selected>Please Select</option>
                                                @foreach($countries as $country)
                                                <option {{ in_array($country['iso2'], $advertiser->primary_regions ??
                                                    []) ? "selected" : null }} value="{{ $country['iso2'] }}">{{
                                                    $country['name'] }}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('primary_region'))
                                            <em class="invalid-feedback">
                                                {{ $errors->first('primary_region') }}
                                            </em>
                                            @endif

                                        </div>
                                    </div> --}}
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('currency_code') ? 'has-error' : '' }}">
                                            <label for="currency_code" class="font-weight-bold text-black">Currency
                                                Code</label>
                                            <select class="js-example-basic-single js-states form-control"
                                                id="currency_code" name="currency_code">
                                                <option value="" disabled selected>Please Select</option>
                                                @foreach($countries as $country)
                                                    @if($country['currency'])
                                                        <option {{ $country['currency'] == $advertiser->currency_code ?? '' ? "selected" : null }} value="{{ $country['currency'] }}">
                                                            {{ $country['currency'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @if($errors->has('currency_code'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('currency_code') }}
                                                </em>
                                            @endif

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div
                                            class="form-group {{ $errors->has('average_payment_time') ? 'has-error' : '' }}">
                                            <label for="average_payment_time"
                                                class="font-weight-bold text-black">Average Payment Time</label>
                                            <input type="text" id="average_payment_time" name="average_payment_time"
                                                class="form-control"
                                                value="{{ old('average_payment_time', $advertiser->average_payment_time ?? '') }}">
                                            @if($errors->has('average_payment_time'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('average_payment_time') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div
                                            class="form-group {{ $errors->has('validation_days') ? 'has-error' : '' }}">
                                            <label for="validation_days" class="font-weight-bold text-black">Validation
                                                Days</label>
                                            <input type="text" id="validation_days" name="validation_days"
                                                class="form-control"
                                                value="{{ old('validation_days', $advertiser->validation_days ?? '') }}">
                                            @if($errors->has('validation_days'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('validation_days') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div
                                            class="form-group {{ $errors->has('supported_regions') ? 'has-error' : '' }}">
                                            <label for="supported_regions" class="font-weight-bold text-black">Supported
                                                Regions</label>
                                            <div class="atbd-select ">
                                                <select name="supported_regions[]" id="supported_regions"
                                                    class="form-control " multiple="multiple">
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country['iso2'] }}" {{ isset($advertiser->supported_regions) && in_array($country['iso2'], $advertiser->supported_regions) ? "selected" : null }}>{{ $country['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if($errors->has('supported_regions'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('supported_regions') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('categories') ? 'has-error' : '' }}">
                                            <label for="categories" class="font-weight-bold text-black">Categories (Max.
                                                4)</label>
                                            <div class="atbd-select ">
                                                <select name="categories[]" id="categories" class="form-control "
                                                    multiple="multiple">
                                                    @foreach($categories as $category)
                                                        <option {{ isset($advertiser->categories) && in_array($category['id'], $advertiser->categories) ? "selected" : null }} value="{{ $category['id'] }}">
                                                            {{ $category['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if($errors->has('categories'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('categories') }}
                                                </em>
                                            @endif

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('epc') ? 'has-error' : '' }}">
                                            <label for="epc" class="font-weight-bold text-black">EPC</label>
                                            <input type="text" id="epc" name="epc" class="form-control"
                                                value="{{ old('epc', $advertiser->epc ?? '') }}">
                                            @if($errors->has('epc'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('epc') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div
                                            class="form-group {{ $errors->has('deeplink_enabled') ? 'has-error' : '' }}">
                                            <label for="deeplink_enabled" class="font-weight-bold text-black">Deeplink
                                                Enabled</label>
                                            <select class="js-example-basic-single js-states form-control"
                                                id="deeplink_enabled" name="deeplink_enabled">
                                                <option value="" disabled selected>Please Select</option>
                                                <option {{ isset($advertiser->deeplink_enabled) && $advertiser->deeplink_enabled == 1 ? "selected" : null }} value="1">
                                                    True</option>
                                                <option {{ isset($advertiser->deeplink_enabled) && $advertiser->deeplink_enabled == 0 ? "selected" : null }} value="0">
                                                    False</option>
                                            </select>
                                            @if($errors->has('deeplink_enabled'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('deeplink_enabled') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('tags') ? 'has-error' : '' }}">
                                            <label for="tags" class="font-weight-bold text-black">Tags</label>
                                            <div class="atbd-select ">
                                                <input type="text" name="tags" class="form-control"
                                                    data-role="tagsinput"
                                                    value="@if($advertiser->tags){{$advertiser->tags }}@endif">
                                            </div>
                                            @if($errors->has('tags'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('tags') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('offer_type') ? 'has-error' : '' }}">
                                            <label for="offer_type" class="font-weight-bold text-black">Offer
                                                Type</label>
                                            <input type="text" id="offer_type" name="offer_type" class="form-control"
                                                value="{{ old('offer_type', $advertiser->offer_type ?? '') }}">
                                            @if($errors->has('offer_type'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('offer_type') }}
                                                </em>
                                            @endif

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group {{ $errors->has('commission') ? 'has-error' : '' }}">
                                            <label for="commission"
                                                class="font-weight-bold text-black">Commission</label>
                                            <input type="text" id="commission" name="commission" class="form-control"
                                                value="{{ old('commission', $advertiser->commission ?? '') }}">
                                            @if($errors->has('commission'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('commission') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div
                                            class="form-group {{ $errors->has('commission_type') ? 'has-error' : '' }}">
                                            <label for="offer_type" class="font-weight-bold text-black">Commission
                                                Type</label>
                                            <input type="text" id="commission_type" name="commission_type"
                                                class="form-control"
                                                value="{{ old('commission_type', $advertiser->commission_type ?? '') }}">
                                            @if($errors->has('commission_type'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('commission_type') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div
                                            class="form-group {{ $errors->has('promotional_methods') ? 'has-error' : '' }}">
                                            <label for="promotional_methods"
                                                class="font-weight-bold text-black">Promotional Method</label>
                                            <div class="atbd-select ">
                                                <select name="promotional_methods[]" id="promotional_methods"
                                                    class="form-control " multiple="multiple">
                                                    @foreach($methods as $method)
                                                        <option {{ isset($advertiser->promotional_methods) && in_array($method['id'], $advertiser->promotional_methods) ? "selected" : null }} value="{{ $method['id'] }}">
                                                            {{ $method['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if($errors->has('promotional_methods'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('promotional_methods') }}
                                                </em>
                                            @endif

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div
                                            class="form-group {{ $errors->has('program_restrictions') ? 'has-error' : '' }}">
                                            <label for="program_restrictions"
                                                class="font-weight-bold text-black">Program Restrictions</label>
                                            <div class="atbd-select ">
                                                <select name="program_restrictions[]" id="program_restrictions"
                                                    class="form-control " multiple="multiple">
                                                    @foreach($methods as $method)
                                                        <option value="{{ $method['id'] }}" {{ isset($advertiser->program_restrictions) && in_array($method['id'], $advertiser->program_restrictions) ? "selected" : null }}>
                                                            {{ $method['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if($errors->has('program_restrictions'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('program_restrictions') }}
                                                </em>
                                            @endif

                                        </div>
                                    </div>
                                </div>


                                <div class="row mt-4">
                                    <div class="col-lg-12">
                                        <div
                                            class="form-group {{ $errors->has('click_through_url') ? 'has-error' : '' }}">
                                            <label for="click_through_url" class="font-weight-bold text-black">Click
                                                Through URL</label>
                                            <input type="url" id="click_through_url" name="click_through_url"
                                                class="form-control"
                                                value="{{ old('click_through_url', $advertiser->click_through_url ?? '') }}">
                                            @if($errors->has('click_through_url'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('click_through_url') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-lg-12">
                                        <div class="form-group {{ $errors->has('custom_domain') ? 'has-error' : '' }}">
                                            <label for="custom_domain" class="font-weight-bold text-black">Custom
                                                Domain</label>
                                            <input type="text" id="custom_domain" name="custom_domain"
                                                class="form-control"
                                                value="{{ old('custom_domain', $advertiser->custom_domain ?? '') }}">
                                            @if($errors->has('custom_domain'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('custom_domain') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group {{ $errors->has('logo') ? 'has-error' : '' }}">
                                            <label for="logo" class="font-weight-bold text-black">Logo</label>
                                            <input class="form-control" type="file" name="logo" id="logo">
                                            @if($errors->has('logo'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('logo') }}
                                                </em>
                                            @endif
                                            <label for="logo_preview" class="font-weight-bold text-black">Logo
                                                Preview</label><br />
                                            <img class="img-thumbnail"
                                                src="{{ isset($advertiser->logo) && str_contains($advertiser->logo, "http") ? Storage::url($advertiser->logo) : \App\Helper\Static\Methods::staticAsset(Storage::url($advertiser->logo)) }}"
                                                alt="">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div
                                            class="form-group {{ $errors->has('program_policies') ? 'has-error' : '' }}">
                                            <label for="program_policies" class="font-weight-bold text-black">Program
                                                Policies</label>
                                            <textarea name="program_policies" id="program_policies" cols="30" rows="10"
                                                class="form-control">{{ $advertiser->program_policies ?? null }}</textarea>
                                            @if($errors->has('program_policies'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('program_policies') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-lg-12">
                                        <div
                                            class="form-group {{ $errors->has('short_description') ? 'has-error' : '' }}">
                                            <label for="short_description" class="font-weight-bold text-black">Short
                                                Description</label>
                                            <textarea name="short_description" id="short_description" cols="30"
                                                rows="10"
                                                class="form-control">{{$advertiser->short_description ?? null }}</textarea>
                                            @if($errors->has('short_description'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('short_description') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-lg-12">
                                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                            <label for="description"
                                                class="font-weight-bold text-black">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="10"
                                                class="form-control">{{ $advertiser->description ?? null }}</textarea>
                                            @if($errors->has('description'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('description') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div>
                                </div>




                                <div style="margin-top:20px;">
                                    <input class="btn btn-primary" type="submit" value="Save">
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection


@section('scripts')
<script>
    $(document).ready(function () {

        $("#categories").select2({
            placeholder: "Please Select",
            dropdownCssClass: "tag",
            allowClear: true,
            maximumSelectionLength: 4
        });

        $("#promotional_methods").select2({
            placeholder: "Please Select",
            dropdownCssClass: "tag",
            allowClear: true,
            maximumSelectionLength: 4
        });

        $("#program_restrictions").select2({
            placeholder: "Please Select",
            dropdownCssClass: "tag",
            allowClear: true,
            maximumSelectionLength: 4
        });

        $("#supported_regions").select2({
            placeholder: "Please Select",
            dropdownCssClass: "tag",
            allowClear: true,
            maximumSelectionLength: 4
        });


        $('#program_policies, #description, #short_description').summernote({
            height: 300
        });
    });
</script>
<script>

    $(document).ready(function () {
        $("#userForm").validate({
            rules: {
                password: {
                    minlength: 5,
                },
                confirm_password: {
                    minlength: 5,
                    equalTo: "#password"
                }
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) { // un-hightlight error inputs
                $(element)
                    .closest('.form-group').removeClass('has-error');
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element.closest('.input-modal-group'));
            }
        });
    });
</script>

@endsection
