@extends('layouts.publisher.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('publisherAssets/assets/css/profile.css') }}">

    <style>
        .modal-footer {
            justify-content: center;
        }

        .disableDiv {
            pointer-events: none;
            opacity: 0.5;
        }
    </style>
@endsection
@section('breadcrumb')
<ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg collapse-btn">
    <li class="breadcrumb-item mt-1">
        <a href="{{ route('publisher.dashboard') }}"><i data-feather="home"></i></a>
    </li>
    <li class="breadcrumb-item mt-1">
        <a href="#" class="text-sm">Profile</a>
    </li>
    <li class="breadcrumb-item mt-1 active">
        <a href="#" class="text-sm">Websites</a>
    </li>
</ol>
@endsection
@section('scripts')
    <script
        src="{{ \App\Helper\Methods::staticAsset('panel/assets/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script src="{{ \App\Helper\Methods::staticAsset("src/js/custom/publisher/website.js") }}"></script>
@endsection

@section('content')
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('publisher.dashboard') }}"><i class="ri-home-5-line text-primary"></i></a></li>
                                <li class="breadcrumb-item"><a href="">Profile</a></li>
                                <li class="breadcrumb-item"><a href="">Website Information</a></li>
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
                    <div class="card-header d-flex justify-content-between align-items-center bg-white">
                        <h5 class="mb-0">Websites</h5>
                        <button type="button" class="btn btn-sm btn-primary" onclick="openWebsiteModal()"
                            data-toggle="modal" data-target="#website-modal" title="Click to add a website">
                            <i class="bi bi-plus-circle me-1"></i> Add Website
                        </button>
                    </div>

                    <div class="table-responsive p-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Category</th>
                                    <th>Last Updated</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="websiteContent">

                                @foreach($websites as $website)
                                <tr class="border-bottom">
                                    <td>
                                        <a href="{{ url($website->url) }}" target="_blank" class="text-primary">{{ $website->name }}</a>
                                    </td>
                                    <td title="{{ $website->partner_types }}">
                                        {{ $website->trim_partner_types }}
                                    </td>
                                    <td title="{{ $website->categories }}">
                                        {{ $website->trim_categories }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($website->updated_at)->format('d M Y') }}
                                    </td>
                                    <td>
                                        @if($website->status == \App\Models\Website::ACTIVE)
                                            <span class="badge badge-success">Active</span>
                                        @elseif($website->status == \App\Models\Website::PENDING)
                                            <span class="badge badge-warning text-dark">Pending</span>
                                        @elseif($website->status == \App\Models\Website::REJECTED)
                                            <span class="badge badge-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" class="badge badge-info pr-2"
                                                data-toggle="modal" data-target="#website-modal-edit_{{$website->id}}">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                        @if($website->status == \App\Models\Website::PENDING)
                                            <div class="badge badge-success cursor-pointer" id="verify-btn-{{ $website->id }}"
                                                onclick="openVerifyModal('{{ $website->id }}', '{{ $website->url }}')"
                                                data-toggle="modal" data-target="#verify-modal" class="me-2">
                                                Verify
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                    {{-- <tr class="border-bottom" id="website-row-{{ $website->id }}">
                                        <td>
                                            <h6>
                                                <a href="{{ url($website->url) }}" target="_blank" class="nav-link">
                                                {{ $website->name }}
                                            </a>
                                            </h6>
                                        </td>
                                        <td title="{{ $website->partner_types }}">
                                            <h6>
                                                {{ $website->trim_partner_types }}
                                            </h6>
                                        </td>
                                        <td title="{{ $website->categories }}">
                                            <h6>
                                                {{ $website->trim_categories }}
                                            </h6>
                                        </td>
                                        <td>
                                            <h6>
                                                {{ $website->updated_at }}
                                            </h6>
                                        </td>
                                        <td class="text-center" id="status-{{ $website->id }}">
                                            @if($website->status == \App\Models\Website::ACTIVE)
                                                <span class="badge bg-success">Active</span>
                                            @elseif($website->status == \App\Models\Website::PENDING)
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($website->status == \App\Models\Website::REJECTED)
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <!--@if($website->status == \App\Models\Website::PENDING)-->
                                            <!--    <a href="javascript:void(0)" class="badge bg-success" id="verify-btn-{{ $website->id }}"-->
                                            <!--        onclick="openVerifyModal('{{ $website->id }}', '{{ $website->url }}')"-->
                                            <!--        data-toggle="modal" data-target="#verify-modal" class="me-2">-->
                                            <!--        Verify-->
                                            <!--    </a>-->
                                            <!--@endif-->
                                            <a href="javascript:void(0)" class="badge bg-info pr-2"
                                                data-toggle="modal" data-target="#website-modal-edit_{{$website->id}}">
                                                <i class="fas fa-pen text-white"></i>
                                            </a>
                                        </td>
                                    </tr> --}}

                                    <div class="modal fade" id="website-modal-edit_{{$website->id}}" tabindex="-1" aria-labelledby="websiteHeading" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <form action="javascript:void(0)" id="edit-website">
                            <div class="modal-header">
                                <h5 class="modal-title" id="websiteHeading">Edit Website</h5>
                                <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <input type="hidden" id="website_id" name="website_id">

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="website_name" class="form-label">Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="website_name" id="website_name" value="{{$website->name}}"
                                            placeholder="Enter Name">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="website_url" class="form-label">URL <span
                                                class="text-danger">*</span></label>
                                        <input type="url" class="form-control" name="website_url" id="website_url" value="{{$website->url}}"
                                            placeholder="Enter URL">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="monthly_traffic" class="form-label">Monthly Traffic <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="monthly_traffic" id="monthly_traffic" value="{{$website->monthly_traffic}}"
                                            placeholder="Enter Monthly Traffic">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="monthly_page_views" class="form-label">Monthly Page Views <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="monthly_page_views"
                                            id="monthly_page_views" placeholder="Enter Monthly Page Views" value="{{$website->monthly_page_views}}">
                                    </div>
                                        <div class="col-md-4">
                        <label for="website_country" class="form-label">Country <span class="text-danger">*</span></label>
                        <select name="website_country" id="website_country" class="form-select">
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ $website->country == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="website_type" class="form-label">Website Type <span class="text-danger">*</span></label>
                    <select name="website_type[]" id="website_type" class="form-select select2-field" multiple>
                        @foreach($methods as $method)
                            <option value="{{ $method['id'] }}" {{ in_array($method['id'], $website->partner_type ?? []) ? 'selected' : '' }}>
                                {{ $method['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

              @php
    $selectedCategories = is_array($website->categories) ? $website->categories : explode(',', $website->categories ?? '');
@endphp

<div class="mb-3">
    <label for="categories" class="form-label">Category (Max. 4) <span class="text-danger">*</span></label>
    <select name="categories[]" id="categories" class="form-select select2-field" multiple>
        @foreach($categories as $category)
            <option value="{{ $category['id'] }}" {{ in_array($category['id'], $selectedCategories) ? 'selected' : '' }}>
                {{ $category['name'] }}
            </option>
        @endforeach
    </select>
</div>

                                <div class="mb-3">
                                    <label for="website_intro" class="form-label">Website's Introduction <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" name="website_intro" id="website_intro" rows="4"
                                        placeholder="Enter website introduction" ></textarea>
                                </div>
                            </div>

                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    Save Changes
                                    <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


            <!--begin::Modals-->
            <!-- Modal: Edit Website -->
            <div class="modal fade" id="website-modal" tabindex="-1" aria-labelledby="websiteHeading" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <form action="javascript:void(0)" id="edit-website">
                            <div class="modal-header">
                                <h5 class="modal-title" id="websiteHeading">Edit Website</h5>
                                <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <input type="hidden" id="website_id" name="website_id">

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="website_name" class="form-label">Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="website_name" id="website_name"
                                            placeholder="Enter Name">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="website_url" class="form-label">URL <span
                                                class="text-danger">*</span></label>
                                        <input type="url" class="form-control" name="website_url" id="website_url"
                                            placeholder="Enter URL">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="monthly_traffic" class="form-label">Monthly Traffic <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="monthly_traffic" id="monthly_traffic"
                                            placeholder="Enter Monthly Traffic">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="monthly_page_views" class="form-label">Monthly Page Views <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="monthly_page_views"
                                            id="monthly_page_views" placeholder="Enter Monthly Page Views">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="website_country" class="form-label">Country <span
                                                class="text-danger">*</span></label>
                                        <select name="website_country" id="website_country" class="form-select">
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="website_type" class="form-label">Website Type <span
                                            class="text-danger">*</span></label>
                                    <select name="website_type[]" id="website_type" class="form-select select2-field"
                                        multiple>
                                        @foreach($methods as $method)
                                            <option value="{{ $method['id'] }}">{{ $method['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="categories" class="form-label">Category (Max. 4) <span
                                            class="text-danger">*</span></label>
                                    <select name="categories[]" id="categories" class="form-select select2-field" multiple>
                                        @foreach($categories as $category)
                                            <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="website_intro" class="form-label">Website's Introduction <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" name="website_intro" id="website_intro" rows="4"
                                        placeholder="Enter website introduction"></textarea>
                                </div>
                            </div>

                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    Save Changes
                                    <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>



            <!-- Modal: Verify Website -->
            <div class="modal fade" id="verify-modal" tabindex="-1" aria-labelledby="verifyHeading" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="verifyHeading">Verify Ownership</h5>
                            <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body" id="verifyForm">
                            <div class="mb-4">
                                <label for="htmlTag" class="form-label fw-semibold">HTML Tag</label>
                                <textarea class="form-control" rows="3" id="htmlTag" readonly></textarea>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2 float-end"
                                    id="copyHTMLTag">Copy</button>
                            </div>

                            <div class="mb-3">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Step 1: Add the meta tag to the <code>&lt;head&gt;</code>
                                        section</li>
                                    <li class="list-group-item">Step 2: Click Verify</li>
                                </ul>
                            </div>
                        </div>

                        <div class="modal-footer justify-content-center">
                            <button type="button" id="websiteVerify" class="btn btn-primary">Verify</button>
                        </div>
                    </div>
                </div>
            </div>
@endsection
