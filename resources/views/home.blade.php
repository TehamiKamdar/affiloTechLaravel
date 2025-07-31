@extends('layouts.publisher.layout')

@section('styles')
    <style>
        /* Optional: adjust Swiper container height */
                 /* Stepper Styles */
    .stepper-column {
        width: 250px;
        flex-shrink: 0;
    }

    .stepper-nav {
        position: relative;
        padding-left: 1.5rem;
    }

    .stepper-item {
        position: relative;
        padding-bottom: 1.5rem;
    }

    .stepper-item.active .stepper-number {
        background-color: var(--bs-primary);
        color: white;
        border-color: var(--bs-primary);
    }

    .stepper-item.completed .stepper-number {
        display: none;
    }

    .stepper-item.completed .stepper-check {
        display: block;
    }

    .stepper-wrapper {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .stepper-icon {
        position: relative;
        width: 2.5rem;
        height: 2.5rem;
        flex-shrink: 0;
    }

    .stepper-number {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border: 2px solid var(--bs-gray-300);
        border-radius: 50%;
        font-weight: bold;
        background-color: white;
    }

    .stepper-check {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        width: 2.5rem;
        height: 2.5rem;
        color: var(--bs-primary);
        font-size: 1.25rem;
    }

    .stepper-line {
        position: absolute;
        left: 1.25rem;
        top: 2.5rem;
        width: 2px;
        background-color: var(--bs-gray-300);
    }

    .stepper-item.active .stepper-line,
    .stepper-item.completed .stepper-line {
        background-color: var(--bs-primary);
    }

    .stepper-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .stepper-desc {
        font-size: 0.875rem;
        color: var(--bs-gray-600);
    }

    /* Step Content */
    .step-content {
        display: none;
    }

    .step-content.active {
        display: block;
    }
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
            padding: 0.75rem 1.25rem;
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

@section('breadcrumb')
    <ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg
                                                collapse-btn">
        <li class="breadcrumb-item active">
            <a href="{{ route('publisher.dashboard')}}"><i data-feather="home" class="text-primary"></i></a>
        </li>
    </ol>
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
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#kt_modal_create_app">Complete
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
                    <button type="button" class="btn btn-sm btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                        <span class="text-white text-lg">&times;</span>
                    </button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body py-4 px-4 px-lg-6">
                    <div class="d-flex flex-column flex-lg-row gap-5" id="kt_modal_create_app_stepper">
                        <div class="col-4">
                            <!-- Stepper Column -->
                        <div class="stepper-column">
                            <div class="stepper-nav">
                                <!-- Step 1 -->
                                <div class="stepper-item active" data-kt-stepper-element="nav">
                                    <div class="stepper-wrapper">
                                        <div class="stepper-icon">
                                            <i class="fas fa-check stepper-check"></i>
                                            <span class="stepper-number bg-primary text-white">1</span>
                                        </div>
                                        <div class="stepper-label">
                                            <h3 class="stepper-title h5">Website</h3>
                                            <div class="stepper-desc small">Add your promotional space</div>
                                        </div>
                                    </div>
                                    <div class="stepper-line"></div>
                                </div>

                                <!-- Step 2 -->
                                <div class="stepper-item" data-kt-stepper-element="nav">
                                    <div class="stepper-wrapper">
                                        <div class="stepper-icon">
                                            <i class="fas fa-check stepper-check"></i>
                                            <span class="stepper-number">2</span>
                                        </div>
                                        <div class="stepper-label">
                                            <h3 class="stepper-title h5">Website Details</h3>
                                            <div class="stepper-desc small">Tell us more about your website</div>
                                        </div>
                                    </div>
                                    <div class="stepper-line"></div>
                                </div>

                                <!-- Step 3 -->
                                <div class="stepper-item" data-kt-stepper-element="nav">
                                    <div class="stepper-wrapper">
                                        <div class="stepper-icon">
                                            <i class="fas fa-check stepper-check"></i>
                                            <span class="stepper-number">3</span>
                                        </div>
                                        <div class="stepper-label">
                                            <h3 class="stepper-title h5">Company</h3>
                                            <div class="stepper-desc small">Submit your company details</div>
                                        </div>
                                    </div>
                                    <div class="stepper-line"></div>
                                </div>

                                <!-- Step 4 -->
                                <div class="stepper-item" data-kt-stepper-element="nav">
                                    <div class="stepper-wrapper">
                                        <div class="stepper-icon">
                                            <i class="fas fa-check stepper-check"></i>
                                            <span class="stepper-number">4</span>
                                        </div>
                                        <div class="stepper-label">
                                            <h3 class="stepper-title h5">Completed</h3>
                                            <div class="stepper-desc small">Confirm and Submit</div>
                                        </div>
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
                                        <label class="form-label d-flex align-items-center font-weight-bold">
                                            <span class="required">Website URL</span>
                                            <i class="fas fa-info-circle ml-2" data-toggle="tooltip"
                                                title="Add your complete domain URL. EX: https://www.domain.com"></i>
                                        </label>
                                        <input type="text" class="form-control form-control-lg" name="website_url"
                                            placeholder="https://www.example.com">
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label d-flex align-items-center font-weight-bold">
                                            <span class="required">Website Type</span>
                                            <i class="fas fa-info-circle ml-2" data-toggle="tooltip"
                                                title="Select your website main promotional type"></i>
                                        </label>

                                        <div class="list-group">
                                            <!-- Option 1 -->
                                            <label class="list-group-item list-group-item-action">
                                                <div class="d-flex align-items-center">
                                                    <div class="mr-3">
                                                        <div class="bg-light-primary p-2 rounded">
                                                            <i class="fas fa-tag text-primary fa-lg"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">Coupons / Deals</h6>
                                                        <small class="text-muted">Promotions through coupons & deals on
                                                            website</small>
                                                    </div>
                                                    <input class="form-check-input ml-3" type="radio" name="website_type"
                                                        value="1">
                                                </div>
                                            </label>

                                            <!-- Option 2 -->
                                            <label class="list-group-item list-group-item-action">
                                                <div class="d-flex align-items-center">
                                                    <div class="mr-3">
                                                        <div class="bg-light-danger p-2 rounded">
                                                            <i class="fas fa-newspaper text-danger fa-lg"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">Content / Blogs / Reviews</h6>
                                                        <small class="text-muted">Promotions through creating content, blogs
                                                            or reviews</small>
                                                    </div>
                                                    <input class="form-check-input ml-3" type="radio" name="website_type"
                                                        value="2">
                                                </div>
                                            </label>

                                            <!-- Option 3 -->
                                            <label class="list-group-item list-group-item-action">
                                                <div class="d-flex align-items-center">
                                                    <div class="mr-3">
                                                        <div class="bg-light-success p-2 rounded">
                                                            <i class="fas fa-share-alt text-success fa-lg"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">Sub-Network & Others</h6>
                                                        <small class="text-muted">Sub-Network, Comparison, Email, App,
                                                            Extension etc.</small>
                                                    </div>
                                                    <input class="form-check-input ml-3" type="radio" name="website_type"
                                                        value="3">
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2 Content -->
                                <div class="step-content" data-kt-stepper-element="content">
                                    <div class="mb-4">
                                        <label class="form-label d-flex align-items-center font-weight-bold">
                                            <span class="required">Website's Introduction</span>
                                            <i class="fas fa-info-circle ml-2" data-toggle="tooltip"
                                                title="Enter brief introduction of your website."></i>
                                        </label>
                                        <textarea class="form-control" name="website_intro" rows="3"></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label font-weight-bold">Website Category</label>
                                        <select name="website_category" class="form-control">
                                            <option value="">Select category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label font-weight-bold">Website Country/Region</label>
                                        <select name="website_country" class="form-control">
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
                                        <label class="form-label d-flex align-items-center font-weight-bold">
                                            <span class="required">Company Name</span>
                                            <i class="fas fa-info-circle ml-2" data-toggle="tooltip"
                                                title="Enter your company's official name"></i>
                                        </label>
                                        <input type="text" class="form-control" name="company_name">
                                    </div>

                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label font-weight-bold">First Name</label>
                                            <input type="text" class="form-control" name="first_name">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label font-weight-bold">Last Name</label>
                                            <input type="text" class="form-control" name="last_name">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label font-weight-bold">Phone Number</label>
                                        <input type="text" class="form-control" name="phone_number">
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label font-weight-bold">Address <small class="text-muted">(Street
                                                + City +
                                                State + Zip)</small></label>
                                        <input type="text" class="form-control" name="address">
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label font-weight-bold">Country</label>
                                        <select name="country" class="form-control">
                                            <option value="">Select Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Step 4 Content -->
                                <div class="step-content text-center" data-kt-stepper-element="content">
                                    <h3 class="font-weight-bold h5 mb-3">Profile Completed!</h3>
                                    <div class="text-muted mb-5">Start your earning with {{ config('app.name') }}!</div>
                                    <div class="py-4">
                                        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                                    </div>
                                    <h4 class="font-weight-bold h5 d-none" id="countDownLine">Redirecting you to the
                                        dashboard in <span id="countdown">3</span>...</h4>
                                </div>

                                <!-- Navigation Buttons -->
                                <div class="d-flex justify-content-between pt-4 mt-4 border-top">
                                    <button type="button" class="btn btn-outline-secondary prev-step"
                                        data-kt-stepper-action="previous">
                                        <i class="fas fa-arrow-left mr-2"></i>Previous
                                    </button>
                                    <div>
                                        <button type="submit" class="btn btn-primary submit-btn d-none"
                                            data-kt-stepper-action="submit">
                                            Submit <i class="fas fa-check ml-2"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary next-step"
                                            data-kt-stepper-action="next">
                                            Next <i class="fas fa-arrow-right ml-2"></i>
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
                            <div id="salesChart" style="height: 400px;"></div>
                            <div class="row mb-0">
                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                    <div class="list-inline text-center">
                                        <div class="list-inline-item p-r-30" onclick="changeChartData('sales', '#00a9da')">
                                            <h5 class="m-b-0 cursor-pointer " id="sales">$0.00</h5>
                                            <p class="text-muted font-14 m-b-0">Total</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                    <div class="list-inline text-center">
                                        <div class="list-inline-item p-r-30"
                                            onclick="changeChartData('approved', '#54ca68')">
                                            <h5 class="m-b-0 cursor-pointer " id="approved">$0.00</h5>
                                            <p class="text-muted font-14 m-b-0">Approved</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                    <div class="list-inline text-center">
                                        <div class="list-inline-item p-r-30"
                                            onclick="changeChartData('pending', '#ffa426')">
                                            <h5 class="mb-0 cursor-pointer m-b-0" id="pending">$0.00</h5>
                                            <p class="text-muted font-14 m-b-0">Pending</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                    <div class="list-inline text-center">
                                        <div class="list-inline-item p-r-30"
                                            onclick="changeChartData('rejected', '#fc544b')">
                                            <h5 class="mb-0 cursor-pointer m-b-0" id="rejected">$0.00</h5>
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
                                                        <img src="https://flagsapi.com/{{ $code }}/flat/24.png" alt="{{ $code }} flag"
                                                            title="{{ $code }}" class="mr-1 mb-1 cursor-pointer" />
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
                                                    <span class="text-muted small ml-1" data-toggle="popover" data-trigger="hover focus"
                                                        data-placement="top" data-html="true" title="More Regions"
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
                                            <span
                                                class="attribute-value">{{ \Carbon\Carbon::parse($data->created_at)->format('M Y') }}</span>
                                        </div>
                                        {{-- <div class="attribute-item">
                                            <i class="fas fa-tags mr-2" style="color: #00a9da;"></i>
                                            <span class="attribute-label">Category:</span>
                                            <span class="attribute-value">Technology</span>
                                        </div> --}}
                                        <div class="attribute-item">
                                            <i class="fas fa-star mr-2" style="color: #00a9da;"></i>
                                            <span class="attribute-label">Website:</span>
                                            <span class="attribute-value"><a href="{{ $data->url }}" target="_blank">Click
                                                    Here</a></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-around">
                                        <a href="{{ route("publisher.view-advertiser", ['advertiser' => $data->id]) }}"
                                            class="btn btn-sm" style="background-color: #00a9da; color: white;">View Details</a>
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
                                            <td><a href="{{ route('publisher.view-advertiser', $data->internal_advertiser_id) }}"
                                                    class="nav-link" target="_blank">{{ $data->advertiser_name }}</a></td>
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
                            <h4 class="mb-0 text-center">Advertisers Record</h4>
                        </div>
                        <div class="card-body d-flex justify-content-center">
                            <div id="advertisersChart" style="width: 100%; height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-7">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4 class="mb-0 text-center">Top 5 Advertisers Sales</h4>
                        </div>
                        <div class="card-body d-flex justify-content-center">
                            <div id="topAdvertiserBar" style="height:400px; width: 100%;"></div>
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
                    <h4>Clicks Performance (Country Wise)</h4>
                </div>
                <div class="card-body">
                    <div id="countryClicksMap" style="height: 500px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
    @include('publisher.widgets.deeplink')
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
    <script>
        function top5AdvertChart() {
            am4core.ready(function () {
                // Use animated theme
                am4core.useTheme(am4themes_animated);

                // Data from Blade
                const advertiserLabels = @json(collect($topSales)->pluck('advertiser_name'));
                const advertiserSales = @json(collect($topSales)->pluck('total_sales_amount'));
                const currency = "{{ $topSales->first()->sale_amount_currency ?? '' }}";

                // Combine data
                const chartData = advertiserLabels.map((label, index) => ({
                    name: label,
                    value: parseFloat(advertiserSales[index])
                }));

                // Create chart instance
                let chart = am4core.create("topAdvertiserBar", am4charts.XYChart);
                chart.data = chartData;

                // X Axis (Category) - Horizontal
                let categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "name";
                categoryAxis.renderer.grid.template.disabled = true;
                categoryAxis.renderer.labels.template.fill = am4core.color("#6c757d");
                categoryAxis.renderer.minGridDistance = 20;
                categoryAxis.renderer.labels.template.rotation = -25; // optional: rotate labels

                // Y Axis (Value) - Vertical
                let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.renderer.grid.template.disabled = true;
                valueAxis.renderer.labels.template.fill = am4core.color("#6c757d");
                valueAxis.min = 0;

                // Series
                let series = chart.series.push(new am4charts.ColumnSeries());
                series.dataFields.categoryX = "name";
                series.dataFields.valueY = "value";
                series.name = "Sales";
                series.columns.template.fill = am4core.color("#00a9da");
                series.columns.template.strokeWidth = 0;
                series.columns.template.column.cornerRadiusTopLeft = 4;
                series.columns.template.column.cornerRadiusTopRight = 4;
                series.tooltipText = "{categoryX}: " + currency + " {valueY.formatNumber('#,###.00')}";

                // Tooltip + Cursor
                chart.cursor = new am4charts.XYCursor();
                chart.cursor.lineX.disabled = true;
                chart.cursor.lineY.disabled = true;

                // Disable legend if not needed
                chart.legend = new am4charts.Legend();
                chart.legend.enabled = false;
            });

        }
    </script>
    <script>
        am4core.ready(function () {

            am4core.useTheme(am4themes_animated);

            // Create chart instance
            let chart = am4core.create("countryClicksMap", am4maps.MapChart);
            chart.geodata = am4geodata_worldLow;
            chart.projection = new am4maps.projections.Miller();

            // Disable zooming and panning
            chart.maxZoomLevel = 1;
            chart.seriesContainer.draggable = false;
            chart.seriesContainer.resizable = false;
            chart.chartContainer.wheelable = false;
            chart.zoomControl = new am4maps.ZoomControl();
            chart.zoomControl.slider.height = 0; // hide zoom slider

            // Laravel data using ISO 2-letter country codes
            const countryClicks = @json($clicks->pluck('total_clicks', 'country'));

            // console.log(countryClicks);
            const countryNameToISO = { "Afghanistan": "AF", "Albania": "AL", "Algeria": "DZ", "American Samoa": "AS", "Andorra": "AD", "Angola": "AO", "Anguilla": "AI", "Antarctica": "AQ", "Antigua and Barbuda": "AG", "Argentina": "AR", "Armenia": "AM", "Aruba": "AW", "Australia": "AU", "Austria": "AT", "Azerbaijan": "AZ", "Bahamas": "BS", "Bahrain": "BH", "Bangladesh": "BD", "Barbados": "BB", "Belarus": "BY", "Belgium": "BE", "Belize": "BZ", "Benin": "BJ", "Bermuda": "BM", "Bhutan": "BT", "Bolivia": "BO", "Bosnia and Herzegovina": "BA", "Botswana": "BW", "Brazil": "BR", "British Virgin Islands": "VG", "Brunei": "BN", "Bulgaria": "BG", "Burkina Faso": "BF", "Burundi": "BI", "Cambodia": "KH", "Cameroon": "CM", "Canada": "CA", "Cape Verde": "CV", "Cayman Islands": "KY", "Central African Republic": "CF", "Chad": "TD", "Chile": "CL", "China": "CN", "Colombia": "CO", "Comoros": "KM", "Congo": "CG", "Cook Islands": "CK", "Costa Rica": "CR", "Croatia": "HR", "Cuba": "CU", "Cyprus": "CY", "Czech Republic": "CZ", "Denmark": "DK", "Djibouti": "DJ", "Dominica": "DM", "Dominican Republic": "DO", "Ecuador": "EC", "Egypt": "EG", "El Salvador": "SV", "Equatorial Guinea": "GQ", "Eritrea": "ER", "Estonia": "EE", "Ethiopia": "ET", "Falkland Islands": "FK", "Faroe Islands": "FO", "Fiji": "FJ", "Finland": "FI", "France": "FR", "French Guiana": "GF", "French Polynesia": "PF", "Gabon": "GA", "Gambia": "GM", "Georgia": "GE", "Germany": "DE", "Ghana": "GH", "Gibraltar": "GI", "Greece": "GR", "Greenland": "GL", "Grenada": "GD", "Guadeloupe": "GP", "Guam": "GU", "Guatemala": "GT", "Guernsey": "GG", "Guinea": "GN", "Guinea-Bissau": "GW", "Guyana": "GY", "Haiti": "HT", "Honduras": "HN", "Hong Kong": "HK", "Hungary": "HU", "Iceland": "IS", "India": "IN", "Indonesia": "ID", "Iran": "IR", "Iraq": "IQ", "Ireland": "IE", "Isle of Man": "IM", "Israel": "IL", "Italy": "IT", "Ivory Coast": "CI", "Jamaica": "JM", "Japan": "JP", "Jersey": "JE", "Jordan": "JO", "Kazakhstan": "KZ", "Kenya": "KE", "Kiribati": "KI", "Kuwait": "KW", "Kyrgyzstan": "KG", "Laos": "LA", "Latvia": "LV", "Lebanon": "LB", "Lesotho": "LS", "Liberia": "LR", "Libya": "LY", "Liechtenstein": "LI", "Lithuania": "LT", "Luxembourg": "LU", "Macau": "MO", "Macedonia": "MK", "Madagascar": "MG", "Malawi": "MW", "Malaysia": "MY", "Maldives": "MV", "Mali": "ML", "Malta": "MT", "Marshall Islands": "MH", "Martinique": "MQ", "Mauritania": "MR", "Mauritius": "MU", "Mayotte": "YT", "Mexico": "MX", "Micronesia": "FM", "Moldova": "MD", "Monaco": "MC", "Mongolia": "MN", "Montenegro": "ME", "Montserrat": "MS", "Morocco": "MA", "Mozambique": "MZ", "Myanmar": "MM", "Namibia": "NA", "Nauru": "NR", "Nepal": "NP", "Netherlands": "NL", "New Caledonia": "NC", "New Zealand": "NZ", "Nicaragua": "NI", "Niger": "NE", "Nigeria": "NG", "Niue": "NU", "North Korea": "KP", "Northern Mariana Islands": "MP", "Norway": "NO", "Oman": "OM", "Pakistan": "PK", "Palau": "PW", "Palestine": "PS", "Panama": "PA", "Papua New Guinea": "PG", "Paraguay": "PY", "Peru": "PE", "Philippines": "PH", "Poland": "PL", "Portugal": "PT", "Puerto Rico": "PR", "Qatar": "QA", "Reunion": "RE", "Romania": "RO", "Russia": "RU", "Rwanda": "RW", "Saint Kitts and Nevis": "KN", "Saint Lucia": "LC", "Saint Vincent and the Grenadines": "VC", "Samoa": "WS", "San Marino": "SM", "Sao Tome and Principe": "ST", "Saudi Arabia": "SA", "Senegal": "SN", "Serbia": "RS", "Seychelles": "SC", "Sierra Leone": "SL", "Singapore": "SG", "Slovakia": "SK", "Slovenia": "SI", "Solomon Islands": "SB", "Somalia": "SO", "South Africa": "ZA", "South Korea": "KR", "South Sudan": "SS", "Spain": "ES", "Sri Lanka": "LK", "Sudan": "SD", "Suriname": "SR", "Swaziland": "SZ", "Sweden": "SE", "Switzerland": "CH", "Syria": "SY", "Taiwan": "TW", "Tajikistan": "TJ", "Tanzania": "TZ", "Thailand": "TH", "Timor-Leste": "TL", "Togo": "TG", "Tokelau": "TK", "Tonga": "TO", "Trinidad and Tobago": "TT", "Tunisia": "TN", "Turkey": "TR", "Turkmenistan": "TM", "Turks and Caicos Islands": "TC", "Tuvalu": "TV", "Uganda": "UG", "Ukraine": "UA", "United Arab Emirates": "AE", "United Kingdom": "GB", "United States": "US", "Uruguay": "UY", "Uzbekistan": "UZ", "Vanuatu": "VU", "Vatican": "VA", "Venezuela": "VE", "Vietnam": "VN", "Wallis and Futuna": "WF", "Western Sahara": "EH", "Yemen": "YE", "Zambia": "ZM", "Zimbabwe": "ZW" };


            const mapData = Object.entries(countryClicks).map(([country, value]) => {
                const iso = countryNameToISO[country];
                return iso ? { id: iso, value } : null;
            }).filter(Boolean);

            // Create series
            let polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());
            polygonSeries.useGeodata = true;
            polygonSeries.exclude = ["AQ"];
            polygonSeries.data = mapData;

            polygonSeries.dataFields.id = "id";
            polygonSeries.dataFields.value = "value";

            polygonSeries.mapPolygons.template.tooltipText = "{name}: {value}";
            polygonSeries.mapPolygons.template.stroke = am4core.color("#fff");
            polygonSeries.mapPolygons.template.strokeWidth = 0.5;

            // ✅ Default grey for all
            polygonSeries.mapPolygons.template.fill = am4core.color("#E5E5E5");

            // ✅ Colored only those with data
            let hoverColor = am4core.color("#00a9da");
            polygonSeries.mapPolygons.template.adapter.add("fill", function (fill, target) {
                const data = target.dataItem?.dataContext;
                return data && data.value > 0 ? am4core.color("#00a9da") : am4core.color("#E5E5E5");
            });



        });
    </script>
@if(auth()->user()->is_completed == 0)
    <script>
    document.addEventListener('DOMContentLoaded', function() {
    // Initialize the stepper
    const stepper = {
        currentStep: 1,
        totalSteps: 4,
        form: document.getElementById('kt_modal_create_app_form'),
        stepContents: document.querySelectorAll('[data-kt-stepper-element="content"]'),
        stepItems: document.querySelectorAll('[data-kt-stepper-element="nav"]'),
        prevBtn: document.querySelector('.prev-step'),
        nextBtn: document.querySelector('.next-step'),
        submitBtn: document.querySelector('.submit-btn'),

        init: function() {
            this.setupEventListeners();
            this.showStep(this.currentStep);
        },

        setupEventListeners: function() {
            this.prevBtn.addEventListener('click', () => this.prevStep());
            this.nextBtn.addEventListener('click', () => this.nextStep());
            this.submitBtn.addEventListener('click', (e) => this.submitForm(e));
        },

        showStep: function(stepNumber) {
            // Hide all steps
            this.stepContents.forEach(step => step.classList.remove('active'));
            this.stepItems.forEach(item => {
                item.classList.remove('active', 'completed');
                // Reset all step numbers
                const number = item.querySelector('.stepper-number');
                number.classList.remove('bg-primary', 'text-white');
            });

            // Show current step
            this.stepContents[stepNumber - 1].classList.add('active');
            this.stepItems[stepNumber - 1].classList.add('active');

            // Add blue background to current step number
            const currentNumber = this.stepItems[stepNumber - 1].querySelector('.stepper-number');
            currentNumber.classList.add('bg-primary', 'text-white');

            // Mark previous steps as completed and add blue background to their numbers
            for (let i = 0; i < stepNumber - 1; i++) {
                this.stepItems[i].classList.add('completed');
                const number = this.stepItems[i].querySelector('.stepper-number');
                number.classList.add('bg-primary', 'text-white');
            }

            // Update buttons visibility
            if (stepNumber === 1) {
                this.prevBtn.style.visibility = 'hidden';
            } else {
                this.prevBtn.style.visibility = 'visible';
            }

            if (stepNumber === this.totalSteps) {
                this.nextBtn.classList.add('d-none');
                this.submitBtn.classList.remove('d-none');
            } else {
                this.nextBtn.classList.remove('d-none');
                this.submitBtn.classList.add('d-none');
            }

            this.currentStep = stepNumber;
        },

        prevStep: function() {
            if (this.currentStep > 1) {
                this.showStep(this.currentStep - 1);
            }
        },

        nextStep: function() {
            if (this.validateCurrentStep()) {
                if (this.currentStep < this.totalSteps) {
                    this.showStep(this.currentStep + 1);
                }
            }
        },

        validateCurrentStep: function() {
            let isValid = true;

            // Clear previous errors
            this.clearErrors();

            // Step 1 validation
            if (this.currentStep === 1) {
                const websiteUrl = this.form.querySelector('[name="website_url"]');
                const websiteType = this.form.querySelector('[name="website_type"]:checked');

                if (!websiteUrl.value.trim()) {
                    this.showError(websiteUrl, 'Website URL is required');
                    isValid = false;
                } else if (!this.isValidUrl(websiteUrl.value.trim())) {
                    this.showError(websiteUrl, 'Please enter a valid URL (e.g., https://www.example.com)');
                    isValid = false;
                }

                if (!websiteType) {
                    const errorElement = document.createElement('div');
                    errorElement.className = 'invalid-feedback d-block';
                    errorElement.textContent = 'Please select a website type';
                    this.form.querySelector('.list-group').after(errorElement);
                    isValid = false;
                }
            }

            // Step 2 validation
            else if (this.currentStep === 2) {
                const websiteIntro = this.form.querySelector('[name="website_intro"]');
                const websiteCategory = this.form.querySelector('[name="website_category"]');
                const websiteCountry = this.form.querySelector('[name="website_country"]');

                if (!websiteIntro.value.trim()) {
                    this.showError(websiteIntro, 'Website introduction is required');
                    isValid = false;
                }

                if (!websiteCategory.value) {
                    this.showError(websiteCategory, 'Please select a category');
                    isValid = false;
                }

                if (!websiteCountry.value) {
                    this.showError(websiteCountry, 'Please select a country');
                    isValid = false;
                }
            }

            // Step 3 validation
            else if (this.currentStep === 3) {
                const companyName = this.form.querySelector('[name="company_name"]');
                const firstName = this.form.querySelector('[name="first_name"]');
                const lastName = this.form.querySelector('[name="last_name"]');
                const phoneNumber = this.form.querySelector('[name="phone_number"]');
                const address = this.form.querySelector('[name="address"]');
                const country = this.form.querySelector('[name="country"]');

                if (!companyName.value.trim()) {
                    this.showError(companyName, 'Company name is required');
                    isValid = false;
                }

                if (!firstName.value.trim()) {
                    this.showError(firstName, 'First name is required');
                    isValid = false;
                }

                if (!lastName.value.trim()) {
                    this.showError(lastName, 'Last name is required');
                    isValid = false;
                }

                if (!phoneNumber.value.trim()) {
                    this.showError(phoneNumber, 'Phone number is required');
                    isValid = false;
                }

                if (!address.value.trim()) {
                    this.showError(address, 'Address is required');
                    isValid = false;
                }

                if (!country.value) {
                    this.showError(country, 'Please select a country');
                    isValid = false;
                }
            }

            return isValid;
        },

        showError: function(input, message) {
            input.classList.add('is-invalid');
            const errorElement = document.createElement('div');
            errorElement.className = 'invalid-feedback';
            errorElement.textContent = message;
            input.parentNode.appendChild(errorElement);

            // Scroll to the first error
            if (!this.firstError) {
                this.firstError = input;
                input.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        },

        clearErrors: function() {
            // Clear all error states
            this.firstError = null;
            const invalidInputs = this.form.querySelectorAll('.is-invalid');
            invalidInputs.forEach(input => input.classList.remove('is-invalid'));

            const errorMessages = this.form.querySelectorAll('.invalid-feedback');
            errorMessages.forEach(msg => msg.remove());
        },

        isValidUrl: function(url) {
            try {
                new URL(url);
                return true;
            } catch (e) {
                return false;
            }
        },

        submitForm: function(e) {
            e.preventDefault();
            var countDownLine = document.getElementById('countDownLine')
            countDownLine.classList.remove('d-none');
            // Validate all steps before submitting
            let allValid = true;

            for (let step = 1; step <= this.totalSteps - 1; step++) {
                this.showStep(step);
                if (!this.validateCurrentStep()) {
                    allValid = false;
                }
            }

            // If all valid, submit the form
            if (allValid) {
                this.showStep(this.totalSteps);

                // Start countdown before submission
                let countdown = 3;
                const countdownElement = document.getElementById('countdown');
                countdownElement.textContent = countdown;

                const timer = setInterval(() => {
                    countdown--;
                    countdownElement.textContent = countdown;

                    if (countdown <= 0) {
                        clearInterval(timer);
                        this.form.submit();
                    }
                }, 1000);
            } else {
                // Show the first step with errors
                this.showStep(1);
                this.validateCurrentStep();
            }
        }
    };

    // Initialize the stepper
    stepper.init();

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endif

@endsection
