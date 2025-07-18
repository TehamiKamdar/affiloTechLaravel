@extends('layouts.publisher.layout')

@section('styles')
    <style>
        /* Optional: adjust Swiper container height */
        .swiper {
            padding: 20px;
        }

        .metric-label {
            color: #7f8c8d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .metric-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .metric-change {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .metric-change.up {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .metric-change.down {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }


        .swiper-slide {
            width: auto;
            /* let card width decide slide size */
        }

        .form-group {
            margin-bottom: 1.75rem;
        }

        label {
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
        }

        /* Modern Select Dropdown */
        .custom-select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            height: 50px;
            padding: 0.75rem 1.25rem;
            border: 2px solid #e0e3ed;
            border-radius: 8px;
            background-color: white;
            color: var(--primary-color);
            font-weight: 500;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2300a9da' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1.25rem center;
            background-size: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .custom-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(103, 119, 227, 0.2);
            outline: none;
        }

        select option {
            padding: 12px 16px;
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

        /* Input styles */
        .input-group-text {
            background-color: white;
            border: 2px solid #e0e3ed;
            border-right: none;
            color: var(--primary-color);
            padding: 0 1.25rem;
            border-radius: 8px 0 0 8px !important;
        }


        .form-control {
            height: 50px;
            border: 2px solid #e0e3ed;
            border-left: none;
            padding: 0.75rem 1.25rem;
            color: var(--primary-color);
            font-weight: 500;
            border-radius: 0 8px 8px 0 !important;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--primary-color);
        }
    </style>
@endsection

@section('navbar')
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="w-100 d-flex justify-content-between">
            <ul class="navbar-nav mr-3">
                <div class="menu-box"><a href="#" data-toggle="sidebar" class="bg-white rounded-circle nav-link nav-link-lg
                                            collapse-btn"> <i data-feather="align-justify"></i></a></div>
            </ul>
            <ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg
                                            collapse-btn">
                <li class="breadcrumb-item active">
                    <a href="{{ route('publisher.dashboard')}}"><i data-feather="home" class="text-primary"></i></a>
                </li>
            </ol>
            <div class="logout-box">
                <a href="" class="bg-white rounded-circle nav-link nav-link-lg collapse-btn" title="Logout"><i
                        data-feather="power" class="text-danger"></i></a>
            </div>
        </div>
    </nav>
@endsection

@section('content')
<input type="hidden" name="" id="publisher_id" value="{{ $publisher_id }}">
<!-- [ breadcrumb ] end -->
<div class="card mb-4">
    <div class="card-body p-0">

        @if(auth()->user()->is_completed == 0)
            <div class="card-px text-center my-5">
                <h3 class="fw-bold mb-3">Welcome to {{ config('app.name') }} Network!</h3>
                <p class="text-muted fw-semibold mb-4">Complete your profile to experience the worldwide affiliate journey
                    with {{ config('app.name') }} now!
                    <br />Click on "Complete Profile" to complete your registration.
                </p>
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">Complete
                    Profile</a>
            </div>
        @elseif(auth()->user()->status == "active")
        @else
            <div class="contents">
                <div class="row">
                    <div class="col-lg-12">
                        @if(auth()->user()->status == \App\Models\User::STATUS_PENDING)
                            <div class="alert-icon-big alert alert-danger " role="alert">
                                <div class="alert-content">
                                    <h3 class="alert-heading">Announcement</h3>
                                    <p>Thank You for pre-registering with {{ config('app.name') }}. We will start affiliation
                                        approvals in {{ now()->addMonths(4)->format("F Y") }}. <br>
                                        Our team will contact you after the launch.</p>
                                </div>
                            </div>
                        @elseif(auth()->user()->status == \App\Models\User::STATUS_HOLD)
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
<div class="modal fade" id="kt_modal_create_app" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Complete Profile to Get Started</h4>
                <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body py-4 px-4 px-lg-6">
                <div class="d-flex flex-column flex-lg-row gap-5" id="kt_modal_create_app_stepper">
                    <!-- Stepper Column -->
                    <div class="stepper-column">
                        <div class="stepper-nav">
                            <!-- Step 1 -->
                            <div class="stepper-item active" data-kt-stepper-element="nav">
                                <div class="stepper-wrapper">
                                    <div class="stepper-icon">
                                        <i class="ri-check-line stepper-check"></i>
                                        <span class="stepper-number">1</span>
                                    </div>
                                    <div class="stepper-label">
                                        <h3 class="stepper-title">Website</h3>
                                        <div class="stepper-desc">Add your promotional space</div>
                                    </div>
                                </div>
                                <div class="stepper-line"></div>
                            </div>

                            <!-- Step 2 -->
                            <div class="stepper-item" data-kt-stepper-element="nav">
                                <div class="stepper-wrapper">
                                    <div class="stepper-icon">
                                        <i class="ri-check-line stepper-check"></i>
                                        <span class="stepper-number">2</span>
                                    </div>
                                    <div class="stepper-label">
                                        <h3 class="stepper-title">Website Details</h3>
                                        <div class="stepper-desc">Tell us more about your website</div>
                                    </div>
                                </div>
                                <div class="stepper-line"></div>
                            </div>

                            <!-- Step 3 -->
                            <div class="stepper-item" data-kt-stepper-element="nav">
                                <div class="stepper-wrapper">
                                    <div class="stepper-icon">
                                        <i class="ri-check-line stepper-check"></i>
                                        <span class="stepper-number">3</span>
                                    </div>
                                    <div class="stepper-label">
                                        <h3 class="stepper-title">Company</h3>
                                        <div class="stepper-desc">Submit your company details</div>
                                    </div>
                                </div>
                                <div class="stepper-line"></div>
                            </div>

                            <!-- Step 4 -->
                            <div class="stepper-item" data-kt-stepper-element="nav">
                                <div class="stepper-wrapper">
                                    <div class="stepper-icon">
                                        <i class="ri-check-line stepper-check"></i>
                                        <span class="stepper-number">4</span>
                                    </div>
                                    <div class="stepper-label">
                                        <h3 class="stepper-title">Completed</h3>
                                        <div class="stepper-desc">Confirm and Submit</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Column -->
                    <div class="flex-grow-1">
                        <form id="kt_modal_create_app_form" method="POST"
                            action="{{ route('publisher.complete.profile') }}">
                            @csrf

                            <!-- Step 1 Content -->
                            <div class="step-content active" data-kt-stepper-element="content">
                                <div class="mb-4">
                                    <label class="form-label d-flex align-items-center fw-bold">
                                        <span class="required">Website URL</span>
                                        <i class="ri-information-line ms-2" data-bs-toggle="tooltip"
                                            title="Add your complete domain URL. EX: https://www.domain.com"></i>
                                    </label>
                                    <input type="text" class="form-control form-control-lg" name="website_url"
                                        placeholder="https://www.example.com">
                                </div>

                                <div class="mb-4">
                                    <label class="form-label d-flex align-items-center fw-bold">
                                        <span class="required">Website Type</span>
                                        <i class="ri-information-line ms-2" data-bs-toggle="tooltip"
                                            title="Select your website main promotional type"></i>
                                    </label>

                                    <div class="list-group">
                                        <!-- Option 1 -->
                                        <label class="list-group-item list-group-item-action">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <div class="bg-light-primary p-2 rounded">
                                                        <i class="ri-coupon-2-line text-primary fs-4"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">Coupons / Deals</h6>
                                                    <small class="text-muted">Promotions through coupons & deals on
                                                        website</small>
                                                </div>
                                                <input class="form-check-input ms-3" type="radio" name="website_type"
                                                    value="1">
                                            </div>
                                        </label>

                                        <!-- Option 2 -->
                                        <label class="list-group-item list-group-item-action">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <div class="bg-light-danger p-2 rounded">
                                                        <i class="ri-article-line text-danger fs-4"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">Content / Blogs / Reviews</h6>
                                                    <small class="text-muted">Promotions through creating content, blogs
                                                        or reviews</small>
                                                </div>
                                                <input class="form-check-input ms-3" type="radio" name="website_type"
                                                    value="2">
                                            </div>
                                        </label>

                                        <!-- Option 3 -->
                                        <label class="list-group-item list-group-item-action">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <div class="bg-light-success p-2 rounded">
                                                        <i class="ri-share-line text-success fs-4"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">Sub-Network & Others</h6>
                                                    <small class="text-muted">Sub-Network, Comparison, Email, App,
                                                        Extension etc.</small>
                                                </div>
                                                <input class="form-check-input ms-3" type="radio" name="website_type"
                                                    value="3">
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2 Content -->
                            <div class="step-content" data-kt-stepper-element="content">
                                <div class="mb-4">
                                    <label class="form-label d-flex align-items-center fw-bold">
                                        <span class="required">Website's Introduction</span>
                                        <i class="ri-information-line ms-2" data-bs-toggle="tooltip"
                                            title="Enter brief introduction of your website."></i>
                                    </label>
                                    <textarea class="form-control" name="website_intro" rows="3"></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Website Category</label>
                                    <select name="website_category" class="form-select">
                                        <option value="">Select category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Website Country/Region</label>
                                    <select name="website_country" class="form-select">
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Step 3 Content -->
                            <div class="step-content" data-kt-stepper-element="content">
                                <div class="mb-4">
                                    <label class="form-label d-flex align-items-center fw-bold">
                                        <span class="required">Company Name</span>
                                        <i class="ri-information-line ms-2" data-bs-toggle="tooltip"
                                            title="Enter your company's official name"></i>
                                    </label>
                                    <input type="text" class="form-control" name="company_name">
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">First Name</label>
                                        <input type="text" class="form-control" name="first_name">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Last Name</label>
                                        <input type="text" class="form-control" name="last_name">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Phone Number</label>
                                    <input type="text" class="form-control" name="phone_number">
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Address <small class="text-muted">(Street + City +
                                            State + Zip)</small></label>
                                    <input type="text" class="form-control" name="address">
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Country</label>
                                    <select name="country" class="form-select">
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Step 4 Content -->
                            <div class="step-content text-center" data-kt-stepper-element="content">
                                <h3 class="fw-bold mb-3">Profile Completed!</h3>
                                <div class="text-muted fs-4 mb-5">Start your earning with {{ config('app.name') }}!
                                </div>
                                <div class="py-4">
                                    <i class="ri-checkbox-circle-fill text-success" style="font-size: 5rem;"></i>
                                </div>
                                <h4 class="fw-bold d-none" id="countDownLine">Redirecting you to the dashboard in <span
                                        id="countdown">3</span>...</h4>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="d-flex justify-content-between pt-4 mt-4 border-top">
                                <button type="button" class="btn btn-outline-secondary prev-step"
                                    data-kt-stepper-action="previous">
                                    <i class="ri-arrow-left-line me-2"></i>Previous
                                </button>
                                <div>
                                    <button type="submit" class="btn btn-primary submit-btn d-none"
                                        data-kt-stepper-action="submit">
                                        Submit <i class="ri-check-line ms-2"></i>
                                    </button>
                                    <button type="button" class="btn btn-primary next-step"
                                        data-kt-stepper-action="next">
                                        Next <i class="ri-arrow-right-line ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row ">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="card">
            <div class="card-statistic-4">
                <div class="align-items-center justify-content-between">
                    <div class="row ">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                            <div class="card-content">
                                <h5 class="metric-label">Approved Amount</h5>
                                <h2 class="metric-value">${{number_format($approvedTotal, 2)}}</h2>
                                @if ($approvedChange > 0)
                                    <span class="metric-change up">
                                        <i class="fas fa-arrow-up mr-1"></i> {{ $rejectedChange }}%
                                    </span>
                                @else
                                    <span class="metric-change down">
                                        <i class="fas fa-arrow-down mr-1"></i> {{ $rejectedChange }}%
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                            <div class="banner-img">
                                <img src="{{asset('publisherAssets/assets/img/banner/2.png')}}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="card">
            <div class="card-statistic-4">
                <div class="align-items-center justify-content-between">
                    <div class="row ">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                            <div class="card-content">
                                <h5 class="metric-label">pending amount</h5>
                                <h2 class="metric-value">${{number_format($pendingTotal, 2)}}</h2>
                                @if ($pendingChange > 0)
                                    <span class="metric-change up">
                                        <i class="fas fa-arrow-up mr-1"></i> {{ $rejectedChange }}%
                                    </span>
                                @else
                                    <span class="metric-change down">
                                        <i class="fas fa-arrow-down mr-1"></i> {{ $rejectedChange }}%
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                            <div class="banner-img">
                                <img src="{{asset('publisherAssets/assets/img/banner/3.png')}}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="card">
            <div class="card-statistic-4">
                <div class="align-items-center justify-content-between">
                    <div class="row ">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                            <div class="card-content">
                                <h5 class="metric-label">rejected amount</h5>
                                <h2 class="metric-value">${{number_format($rejectedTotal, 2)}}</h2>
                                @if ($rejectedChange > 0)
                                    <span class="metric-change up">
                                        <i class="fas fa-arrow-up mr-1"></i> {{ $rejectedChange }}%
                                    </span>
                                @else
                                    <span class="metric-change down">
                                        <i class="fas fa-arrow-down mr-1"></i> {{ $rejectedChange }}%
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                            <div class="banner-img">
                                <img src="{{asset('publisherAssets/assets/img/banner/1.png')}}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="card">
            <div class="card-statistic-4">
                <div class="align-items-center justify-content-between">
                    <div class="row ">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                            <div class="card-content">
                                <h5 class="metric-label">Total Sales</h5>
                                <h2 class="metric-value">${{number_format($Total, 2)}}</h2>
                                @if ($totalSalesChange > 0)
                                    <span class="metric-change up">
                                        <i class="fas fa-arrow-up mr-1"></i> {{ $rejectedChange }}%
                                    </span>
                                @else
                                    <span class="metric-change down">
                                        <i class="fas fa-arrow-down mr-1"></i> {{ $rejectedChange }}%
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                            <div class="banner-img">
                                <img src="{{asset('publisherAssets/assets/img/banner/4.png')}}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Graph Chart Start -->


<div class="row">
    <div class="col-12">
        <div class="card ">
            <div class="card-header">
                <h4>Sales Chart (Current Month)</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 ">
                        <div id="chart1" style="height: 300px;"></div>
                        <div class="row mb-0">
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                <div class="list-inline text-center">
                                    <div class="list-inline-item p-r-30">
                                        <h5 class="m-b-0" id="sales">$0.00</h5>
                                        <p class="text-muted font-14 m-b-0">Total</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                <div class="list-inline text-center">
                                    <div class="list-inline-item p-r-30">
                                        <h5 class="m-b-0" id="approved">$0.00</h5>
                                        <p class="text-muted font-14 m-b-0">Approved</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                <div class="list-inline text-center">
                                    <div class="list-inline-item p-r-30">
                                        <h5 class="mb-0 m-b-0" id="pending">$0.00</h5>
                                        <p class="text-muted font-14 m-b-0">Pending</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                <div class="list-inline text-center">
                                    <div class="list-inline-item p-r-30">
                                        <h5 class="mb-0 m-b-0" id="rejected">$0.00</h5>
                                        <p class="text-muted font-14 m-b-0">Rejected</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


{{-- New Advertisers --}}

<div class="card">
    <div class="card-header">
        <h4>New Advertisers</h4>
    </div>
    <div class="card-body">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                @foreach ($advertisers as $data)
                <div class="swiper-slide">
                    <div class="advertiser-card card h-100 border-0 shadow-sm" style="width: 300px;">
                        <div class="card-header d-flex justify-content-between align-items-center"
                            style="background-color: #00a9da; color: white;">
                            <h5 class="mb-0">{{ \Illuminate\Support\Str::limit($data->name ?: '-', 15) }}</h5>
                            @php
                                $isPending = $data->status == \App\Models\AdvertiserPublisher::STATUS_PENDING;
                                $isHold = $data->status == \App\Models\AdvertiserPublisher::STATUS_HOLD;
                                $isRejected = $data->status == \App\Models\AdvertiserPublisher::STATUS_REJECTED;
                                $isJoined = $data->status == \App\Models\AdvertiserPublisher::STATUS_ACTIVE;
                                $isNoStatus = empty($data->status);

                                $class = $isPending ? "badge-warning" : ($isHold ? "badge-info" : ($isRejected ? "badge-danger"
                                    : ($isJoined ? "badge-success" : "badge-warning")));
                            @endphp
                            <span class="badge badge-sm {{$class}} text-white text-xs cursor-pointer" @if($isNoStatus)
                                data-toggle="modal" data-target="#kt_modal_apply_data"
                                onclick="singleSelectAdvertiser(`{{ $data->id }}`)" @endif title="Status">
                                @if($isPending)
                                    Pending
                                @elseif($isHold)
                                    Hold
                                @elseif($isRejected)
                                    Rejected
                                @elseif($isJoined)
                                    Joined
                                @else
                                    Not Joined
                                @endif

                            </span>
                        </div>

                        <div class="card-body">
                            <div class="advertiser-attributes">
                                <div class="attribute-item">
                                    <i class="fas fa-percentage mr-2" style="color: #00a9da;"></i>
                                    <span class="attribute-label">Commission:</span>
                                    <span class="attribute-value">
                                        @if($data->commission_type == 'Percentage' || $data->commission_type == 'percentage' || $data->commission_type == '%')
                                        {{ empty($data->commission) || $data->commission == '0'
                                            ? 'Revshare 80%'
                                            : (strpos($data->commission, '%') !== false ? $data->commission : $data->commission . '%')
                                        }}
                                        @else
                                        {{ empty($data->commission) || $data->commission == '0'
                                            ? 'Revshare 80%'
                                            : (strpos($data->commission, '%') !== false ? $data->commission : $data->commission)
                                        }}
                                        @endif
                                    </span>
                                </div>
                                <div class="attribute-item">
                                    <i class="fas fa-map-marker-alt mr-2" style="color: #00a9da;"></i>
                                    <span class="attribute-label">Region:</span>
                                    @php
                                        $regionsRaw = $data->primary_regions;

                                        // Ensure $regionsRaw is a JSON string before decoding
                                        if (is_string($regionsRaw)) {
                                            $regions = json_decode($regionsRaw, true);
                                            if (json_last_error() !== JSON_ERROR_NONE || !is_array($regions)) {
                                                $regions = [$regionsRaw];
                                            }
                                        } elseif (is_array($regionsRaw)) {
                                            $regions = $regionsRaw; // already an array, no need to decode
                                        } else {
                                            $regions = [$regionsRaw]; // fallback for anything else
                                        }

                                        // Clean region codes
                                        $regions = array_filter(array_map(function ($code) {
                                            return strtoupper(trim(str_replace('"', '', html_entity_decode($code))));
                                        }, $regions));

                                        $displayRegions = array_slice($regions, 0, 3);
                                        $extraRegions = array_slice($regions, 3);
                                    @endphp


                                    @if (!empty($displayRegions))
                                        @foreach ($displayRegions as $code)
                                            <div class="d-inline-block">
                                                <img src="https://flagsapi.com/{{ $code }}/flat/24.png"
                                                    alt="{{ $code }} flag"
                                                    title="{{ $code }}"
                                                    class="mr-1 mb-1 cursor-pointer" />
                                            </div>
                                        @endforeach

                                        @if (count($extraRegions) > 0)
                                            @php
                                                $popoverContent = '';
                                                foreach ($extraRegions as $code) {
                                                    $popoverContent .=
                                                    "
                                                        <img src='https://flagsapi.com/{$code}/flat/16.png' title='{$code}' class='mr-1 mb-1'> {$code}<br>
                                                    ";
                                                }
                                            @endphp
                                            <span class="text-muted small ml-1"
                                                data-toggle="popover"
                                                data-trigger="hover focus"
                                                data-placement="top"
                                                data-html="true"
                                                title="More Regions"
                                                data-content="{!! $popoverContent !!}">
                                                +{{ count($extraRegions) }} more
                                            </span>
                                        @endif
                                    @else
                                        <small class="text-danger">No region data</small>
                                    @endif
                                </div>
                                <div class="attribute-item">
                                    <i class="fas fa-calendar-alt mr-2" style="color: #00a9da;"></i>
                                    <span class="attribute-label">Since:</span>
                                    <span class="attribute-value">{{ \Carbon\Carbon::parse($data->created_at)->format('M Y') }}</span>
                                </div>
                                {{-- <div class="attribute-item">
                                    <i class="fas fa-tags mr-2" style="color: #00a9da;"></i>
                                    <span class="attribute-label">Category:</span>
                                    <span class="attribute-value">Technology</span>
                                </div> --}}
                                <div class="attribute-item">
                                    <i class="fas fa-star mr-2" style="color: #00a9da;"></i>
                                    <span class="attribute-label">Website:</span>
                                    <span class="attribute-value"><a href="{{ $data->url }}" target="_blank">Click Here</a></span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row justify-content-around">
                                <a href="{{ route("publisher.view-advertiser", ['advertiser' => $data->id]) }}" class="btn btn-sm" style="background-color: #00a9da; color: white;">View Details</a>
                                <form action="{{ route('publisher.apply-advertiser') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="advertiser_id" value="{{ $data->id }}">
                                    <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Latest Transactions</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @if ($transactions->count() == 0)
                        <h5 class="text-center">Transactions Record Not Found</h5>
                    @else
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Advertisers Name</th>
                                    <th>Date</th>
                                    <th>Commission Amount</th>
                                    <th>Sale Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $data)
                                <tr>
                                    <td>{{ $data->transaction_id }}</td>
                                    <td><a href="{{ route('publisher.view-advertiser', $data->internal_advertiser_id) }}" class="nav-link" target="_blank">{{ $data->advertiser_name }}</a></td>
                                    <td>{{\Carbon\Carbon::parse($data->transaction_date)->format('Y/m/d')}}</td>
                                    <td>${{number_format($data->commission_amount, 2)}}</td>
                                    <td>${{number_format($data->sale_amount, 2)}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            </table>

                        {{-- <table class="table table-responsive table-hover">
                            <tbody>
                                @foreach ($transactions as $data)
                                    <tr class="align-middle border-bottom">
                                        <td>
                                            <div>
                                                <small class="text-muted d-block text-xs fw-bold">Transaction ID</small>
                                                <h6 class="fw-semibold text-dark mt-2">{{$data->transaction_id}}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <small class="text-muted d-block text-xs fw-bold">Date</small>
                                                <h6 class="fw-semibold text-dark mt-2">
                                                    {{\Carbon\Carbon::parse($data->transaction_date)->format('Y/m/d')}}
                                                </h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <small class="text-muted d-block text-xs fw-bold">Sales Amount</small>
                                                <h6 class="fw-semibold text-dark mt-2">${{$data->sale_amount}}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <small class="text-muted d-block text-xs fw-bold">Commission Amount</small>
                                                <h6 class="fw-semibold text-dark mt-2">${{$data->commission_amount}}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <small class="text-muted d-block text-xs fw-bold">Status</small>
                                                @if ($data->commission_status == 'approved')
                                                    <div class="d-flex align-items-center">
                                                        <span class="rounded-circle bg-success me-2"
                                                            style="width:10px; height:10px;"></span>
                                                        <h6 class="fw-semibold text-dark mt-2">Approved</h6>
                                                    </div>
                                                @elseif($data->commission_status == 'rejected')
                                                    <div class="d-flex align-items-center">
                                                        <span class="rounded-circle bg-danger me-2"
                                                            style="width:10px; height:10px;"></span>
                                                        <h6 class="fw-semibold text-dark mt-2">Rejected</h6>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-center">
                                                        <span class="rounded-circle bg-warning me-2"
                                                            style="width:10px; height:10px;"></span>
                                                        <h6 class="fw-semibold text-dark mt-2">Pending</h6>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td style="width: 30px;">
                                            <div class="dropdown">
                                                <button class="bg-white border-0" type="button" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#">View</a></li>
                                                    <li><a class="dropdown-item" href="#">Edit</a></li>
                                                    <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table> --}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">
        <div class="row">
            <div class="col-5">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0 text-center">Advertisers Record</h6>
                    </div>
                    <div class="card-body d-flex justify-content-center">
                        <div id="chart1" style="max-width: 380px; width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-7">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0 text-center">Top 5 Advertisers Sales</h6>
                    </div>
                    <div class="card-body d-flex justify-content-center">
                        <canvas id="topAdvertiserBar" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>
                    Link Generator
                  </h4>
                </div>
                <div class="card-body">
                  <form>
                    <div class="form-group">
                        <label for="dropdownSelect" class="text-primary"><i class="fas fa-list-ul mr-2"></i>Select Any
                            Advertiser</label>
                        <select class="custom-select" id="dropdownSelect" required>
                            <option selected disabled>Choose an option...</option>
                            @foreach(\App\Helper\PublisherData::getAdvertiserList() as $advertiserList)
                                <option value="{{ $advertiserList['sid'] }}" @if(isset($advertiser->sid) && $advertiser->sid === $advertiserList['sid']) selected @endif data-dd="{{ $advertiserList['deeplink_enabled'] }}">{{ $advertiserList['name'] }}</option>
                            @endforeach
                        </select>
                        <div id="deepLinkContent" style="margin-top: 5px;">
                            <div class="pt-1" style="color: green;">
                                <img src="{{ asset('publisherAssets/assets/icons8-check.gif') }}" alt="enabled">
                                <span class="icon-text ml-1">Deep Link</span>
                            </div>
                            <div class="pt-1" style="color: red;">
                                <img src="{{ asset('publisherAssets/assets/icons8-cross.gif') }}" alt="disabled">
                                <span class="icon-text ml-1">Deep Link</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                      <label for="input1" class="text-primary"><i class="fas fa-tag mr-2"></i>Enter Landing Page
                        URL</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                        </div>
                        <input type="text" class="form-control text-black" id="input1" placeholder="Enter something...">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="input2" class="text-primary"><i class="fas fa-barcode mr-2"></i>Enter Sub ID <span
                          class="text-danger">(Optional)</span></label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                        </div>
                        <input type="text" class="form-control text-black" id="input2"
                          placeholder="Enter something else...">
                      </div>
                    </div>

                    <div class="form-group text-center mt-5">
                      <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-check mr-2"></i> Create
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
@endsection
@section('scripts')
    <!-- JS Libraies -->
    <script src="{{asset('publisherAssets/assets/bundles/amcharts4/core.js')}}"></script>
    <script src="{{asset('publisherAssets/assets/bundles/amcharts4/maps.js')}}"></script>
    <script src="{{asset('publisherAssets/assets/bundles/amcharts4/charts.js')}}"></script>
    <script src="{{asset('publisherAssets/assets/bundles/amcharts4/animated.js')}}"></script>
    <script src="{{asset('publisherAssets/assets/bundles/amcharts4/worldLow.js')}}"></script>
    <!-- Page Specific JS File -->
    <script src="{{asset('publisherAssets/assets/js/page/index.js')}}"></script>
@endsection
