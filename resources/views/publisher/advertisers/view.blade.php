@extends('layouts.publisher.layout')

@section('styles')
    <style>
        .profile-header {
            /* background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%); */
            background: var(--primary-color);
            border-radius: 0 0 20px 20px;
            box-shadow: 0 10px 30px rgba(103, 119, 239, 0.3);
            padding: 2rem 0;
            margin-bottom: 2rem;
            color: var(--text-light);
        }

        .profile-avatar {
            width: 250px;
            height: 120px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .profile-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .profile-card {
            border-radius: 15px;
            background-color: #fff;
            border: none;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .profile-card-header {
            background-color: var(--primary-color);
            color: var(--text-light);
            padding: 1rem 1.5rem;
            font-weight: 600;
            border-radius: 15px 15px 0 0 !important;
        }

        .stat-item {
            text-align: center;
            padding: 1.5rem;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(103, 119, 239, 0.1);
        }

        .stat-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .skill-badge {
            background-color: #f1f5fe;
            color: var(--primary-dark);
            padding: 0.5rem 1rem;
            margin: 0.3rem;
            border-radius: 50px;
            font-weight: 500;
            display: inline-block;
        }

        .progress {
            height: 10px;
            border-radius: 5px;
        }

        .progress-bar {
            background-color: var(--primary-color);
        }

        .border-right .card {
            margin: 0px;
            box-shadow: none;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(103, 119, 239, 0.1);
            color: var(--primary-color);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 0.3rem;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background-color: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }

        .btn-edit {
            background-color: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-edit:hover {
            color: var(--primary-color);
            transform: translateY(-2px) scale(1.002);
        }

        .tab-content {
            padding: 1rem 0;
        }

        .nav-pills .nav-link.active {
            background-color: var(--primary-color);
            border-radius: 50px;
        }

        .nav-pills .nav-link.active:hover {
            background-color: var(--primary-color) !important;
            border-radius: 50px;
        }

        .nav-pills .nav-link {
            color: var(--text-dark);
            font-weight: 500;
            border-radius: 50px !important;
            box-shadow: 0 5px 5px rgba(0, 0, 0, 0.09);
        }

        .nav-pills .nav-link:hover {
            background-color: #fff !important;
            border-radius: 50px;
        }

        .timeline {
            position: relative;
            padding-left: 50px;
        }

        .timeline:before {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }

        .timeline-badge {
            position: absolute;
            left: -50px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }

        .timeline-content {
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .timeline-item:last-child {
            margin-bottom: 0;
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


        .form-control {
            height: 42px;
            border: 2px solid #e0e3ed;
            padding: 0.75rem;
            color: var(--primary-color);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--primary-color);
        }
    </style>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg collapse-btn">
        <li class="breadcrumb-item mt-1">
            <a href="{{ route('publisher.dashboard') }}"><i data-feather="home"></i></a>
        </li>
        <li class="breadcrumb-item mt-1">
            <a href="{{ route('publisher.my-advertisers') }}" class="text-sm">Advertisers</a>
        </li>
        <li class="breadcrumb-item mt-1">
            <a href="#" class="text-sm">My Advertisers</a>
        </li>
        <li class="breadcrumb-item mt-1 active">
            <a href="#" class="text-sm">{{ $advertiser->name }}</a>
        </li>
    </ol>
@endsection

@section('content')
    <div class="profile-header text-center">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                @php
                    $initials = \App\Helper\Methods::getInitials($advertiser->name);
                    $colorCode = \App\Helper\Methods::getColorFromName($advertiser->name);
                    $imageUrl = "https://placehold.co/280x125/{$colorCode}/FFFFFF?text={$initials}";
                @endphp
                @if(!empty($advertiser->fetch_logo_url))

                    <img src="{{ $advertiser->fetch_logo_url }}" alt="{{ $advertiser->name }}" class="profile-avatar mb-3" />

                @elseif(!empty($advertiser->logo))

                    <img src="{{ \App\Helper\Methods::staticAsset("storage/{$advertiser->logo}") }}"
                        alt="{{ $advertiser->name }}" class="profile-avatar mb-3" />

                @else
                    <img src="{{ $imageUrl }}"
                        alt="{{ $advertiser->name }}" class="profile-avatar mb-3" />
                @endif
                <h2 class="mb-2">{{ $advertiser->name }}</h2>
                <div class="mb-2">
                    {{-- <img src="https://flagcdn.com/h80/ua.png"
                        style="border: 1.5px solid #fff; border-radius: 10%; width: 35px; height: 25px; object-fit: cover;"
                        alt="Ukraine" data-toggle="tooltip" data-original-title="Ukraine"> --}}
                    @if (!is_array($advertiser->primary_regions))
                        @if ($advertiser->primary_regions == null)
                            <span class=" text-md text-danger">Invalid Region Data</span>
                        @else
                            <img src="https://flagcdn.com/h80/{{ strtolower($advertiser->primary_regions) }}.png"
                                style="border: 1.5px solid #fff; border-radius: 10%; width: 35px; height: 25px; object-fit: cover;"
                                data-toggle="tooltip" data-original-title="{{ $advertiser->primary_regions }}"
                                class="img-fluid cursor-pointer" alt="{{ $advertiser->primary_regions }}">
                        @endif
                    @else
                        @if (empty($advertiser->primary_regions))
                            <span class=" text-md text-danger">Invalid Region Data</span>
                        @else
                            @php
                                $regions = $advertiser->primary_regions;
                                $maxToShow = 10;
                                $totalRegions = count($regions);
                            @endphp

                            @foreach (array_slice($regions, 0, $maxToShow) as $pr)
                                <img src="https://flagcdn.com/h80/{{ strtolower($pr) }}.png"
                                    style="border: 1.5px solid #fff; border-radius: 10%; width: 35px; height: 25px; object-fit: cover;"
                                    data-toggle="tooltip" data-original-title="{{ $pr }}" class="img-fluid cursor-pointer"
                                    alt="{{ $pr }}">
                            @endforeach

                            @if ($totalRegions > $maxToShow)
                                @php
                                    $remaining = array_slice($regions, $maxToShow);
                                    $tooltipText = implode(', ', $remaining);
                                @endphp
                                <span class="badge badge-secondary cursor-pointer" data-toggle="tooltip"
                                    data-original-title="{{ $tooltipText }}">
                                    +{{ count($remaining) }} more
                                </span>
                            @endif

                        @endif
                    @endif
                </div>
                <div class="mb-4">
                    @php
                    $isPending = $advertiser->status == \App\Models\AdvertiserPublisher::STATUS_PENDING;
                    $isHold = $advertiser->status == \App\Models\AdvertiserPublisher::STATUS_HOLD;
                    $isRejected = $advertiser->status == \App\Models\AdvertiserPublisher::STATUS_REJECTED;
                    $isJoined = $advertiser->status == \App\Models\AdvertiserPublisher::STATUS_ACTIVE;
                    $isNoStatus = empty($advertiser->status);

                    $class = $isPending ? "warning" : ($isHold ? "info" : ($isRejected ? "danger"
                    : ($isJoined ? "success" : "primary")));
                    @endphp

                    <span class="badge bg-{{ $class }} text-white text-xs cursor-pointer">
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
                    <span class="badge badge-light">Advertiser</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-4">
            <!-- About Me Card -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <i class="fas fa-user mr-2"></i>About Me
                </div>
                <div class="card-body">
                    <p class="card-text {{ $advertiser->short_description ? '' : 'text-danger' }}">
                        {{ $advertiser->short_description ?? 'Description Not Available'}}</p>
                    <hr>
                    <div class="mb-3">
                        <h6><i class="fas fa-envelope mr-2 text-primary"></i>Address</h6>
                        <p class="card-text {{ $advertiser->address ? '' : 'text-danger' }}">
                            {{ $advertiser->address ?? 'Address Not Available' }}</p>
                    </div>
                    <div class="mb-3">
                        <h6><i class="fas fa-phone mr-2 text-primary"></i>Phone</h6>
                        <p class="card-text {{ $advertiser->phone_number ? '' : 'text-danger' }}">
                            {{ $advertiser->phone_number ?? 'Phone Number Not Available' }}</p>
                    </div>
                    <div>
                        <h6><i class="fas fa-calendar-alt mr-2 text-primary"></i>Joined</h6>
                        <p class="text-muted">{{ \Carbon\Carbon::parse($advertiser->created_at)->format('F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- If Want to show some more details, enable this and work out!! -->
            <!-- <div class="profile-card">
                    <div class="profile-card-header">
                        <i class="fas fa-code mr-2"></i>Skills
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6>UI/UX Design</h6>
                            <div class="progress mt-2">
                                <div class="progress-bar" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h6>HTML/CSS</h6>
                            <div class="progress mt-2">
                                <div class="progress-bar" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h6>JavaScript</h6>
                            <div class="progress mt-2">
                                <div class="progress-bar" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h6>React</h6>
                            <div class="progress mt-2">
                                <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-2">Other Skills</h6>
                            <div>
                                <span class="skill-badge"><i class="fas fa-check mr-1"></i>Figma</span>
                                <span class="skill-badge"><i class="fas fa-check mr-1"></i>Adobe XD</span>
                                <span class="skill-badge"><i class="fas fa-check mr-1"></i>Bootstrap</span>
                                <span class="skill-badge"><i class="fas fa-check mr-1"></i>SASS</span>
                            </div>
                        </div>
                    </div>
                </div> -->
        </div>

        <!-- Right Column -->
        <div class="col-lg-8">
            <!-- Stats Row -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-project-diagram"></i>
                            </div>
                            <h3 class="mb-1">{{ $advertiser->commission }}</h3>
                            <p class="text-muted mb-0">Commission Rate</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <h3 class="mb-1">@if($advertiser->average_payment_time) {{ $advertiser->average_payment_time }}
                            @else - @endif</h3>
                            <p class="text-muted mb-0">Avg. Payout (Days)</p>
                        </div>
                    </div>
                </div>

                <!-- <div class="col-md-4">
                                    <div class="stat-item">
                                        <div class="stat-icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <h3 class="mb-1">128</h3>
                                        <p class="text-muted mb-0">Clients</p>
                                    </div>
                                </div> -->
            </div>

            <!-- Tabs Navigation -->
            <ul class="nav nav-pills mb-1" id="profileTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="transaction-tab" data-toggle="pill" href="#transaction" role="tab"><i
                            class="fas fa-stream mr-2"></i>Transactions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="links-tab" data-toggle="pill" href="#links" role="tab"><i
                            class="fas fa-project-diagram mr-2"></i>Links</a>
                </li>
                <!-- <li class="nav-item">
                              <a class="nav-link" id="experience-tab" data-toggle="pill" href="#experience" role="tab"><i
                                  class="fas fa-briefcase mr-2"></i>Experience</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" id="education-tab" data-toggle="pill" href="#education" role="tab"><i
                                  class="fas fa-graduation-cap mr-2"></i>Education</a>
                            </li> -->
            </ul>

            <!-- Tabs Content -->
            <div class="tab-content" id="profileTabsContent">
                <!-- Activity Tab -->
                <div class="tab-pane fade show active" id="transaction" role="tabpanel">
                    <div class="card p-0">
                        <div class="card-body p-0">
                            <table class="table table-sm mb-0" style="font-size: 0.875rem;">
                                <thead class="text-white" style="background-color: #00a9da;">
                                    <tr>
                                        <th scope="col" class="pl-3 py-2">ID</th>
                                        <th scope="col" class="py-2">Date</th>
                                        <th scope="col" class="py-2">Amount</th>
                                        <th scope="col" class="pr-3 py-2">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($transactions->count() > 0)
                                        @foreach ($transactions as $data)
                                        <tr>
                                            <td class="pl-3">{{ $data->transaction_id }}</td>
                                            <td class="">{{\Carbon\Carbon::parse($data->transaction_date)->format('d F Y')}}</td>
                                            <td class="">${{ number_format($data->sale_amount , 2) }}</td>
                                            <td class="p-0 ">
                                                @if ($data->commission_status == 'approved')
                                                <div class="d-flex align-items-center">
                                                    <span class="rounded-circle bg-success mr-2"
                                                        style="width:10px; height:10px;"></span>
                                                    <h6 class="fw-semibold text-dark mt-2">Approved</h6>
                                                </div>
                                                @elseif($data->commission_status == 'rejected')
                                                    <div class="d-flex align-items-center">
                                                        <span class="rounded-circle bg-danger mr-2"
                                                            style="width:10px; height:10px;"></span>
                                                        <h6 class="fw-semibold text-dark mt-2">Rejected</h6>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-center">
                                                        <span class="rounded-circle bg-warning mr-2"
                                                            style="width:10px; height:10px;"></span>
                                                        <h6 class="fw-semibold text-dark mt-2">Pending</h6>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center"><span class="text-danger">No Transaction Record Available</span></td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Projects Tab -->
                <div class="tab-pane fade" id="links" role="tabpanel">
                    <div class="profile-card">
                        <div class="row">
                            <!-- Tracking Link Box -->
                            <div class="col-md-6 border-right border-primary">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0 font-weight-bold">
                                                <i class="fas fa-link text-primary mr-2"></i> Tracking Link
                                            </h6>
                                        </div>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm" id="trackingLink"
                                                value="{{ $advertiser->tracking_url_long ?? $advertiser->tracking_url }}"
                                                readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-sm btn-outline-primary copy-btn"
                                                    data-target="#trackingLink" type="button">
                                                    <i class="far fa-copy"></i> Copy
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Short Link Box -->
                            <div class="col-md-6">
                                <div class="card shadow-none">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0 font-weight-bold">
                                                <i class="fas fa-link text-primary mr-2"></i> Short Link
                                            </h6>
                                        </div>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm" id="shortLink"
                                                value="{{ $advertiser->tracking_url_short }}" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-sm btn-outline-primary copy-btn"
                                                    data-target="#shortLink" type="button">
                                                    <i class="far fa-copy"></i> Copy
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @include('publisher.widgets.deeplink', compact('advertiser'))
                </div>
            </div>

            <!-- Experience Tab -->
            <!-- <div class="tab-pane fade" id="experience" role="tabpanel">
                              <div class="profile-card">
                                <div class="card-body">
                                  <div class="timeline">
                                    <div class="timeline-item mb-4">
                                      <div class="timeline-badge bg-primary text-white"><i class="fas fa-briefcase"></i></div>
                                      <div class="timeline-content">
                                        <h6 class="mb-1">Senior UI/UX Designer</h6>
                                        <p class="text-muted mb-1">TechSolutions Inc.</p>
                                        <small class="text-muted">2019 - Present</small>
                                        <p class="mt-2 mb-0">Lead designer for all web and mobile applications.</p>
                                      </div>
                                    </div>
                                    <div class="timeline-item mb-4">
                                      <div class="timeline-badge bg-primary text-white"><i class="fas fa-briefcase"></i></div>
                                      <div class="timeline-content">
                                        <h6 class="mb-1">UI Designer</h6>
                                        <p class="text-muted mb-1">Digital Creations</p>
                                        <small class="text-muted">2017 - 2019</small>
                                        <p class="mt-2 mb-0">Designed interfaces for client websites and applications.</p>
                                      </div>
                                    </div>
                                    <div class="timeline-item">
                                      <div class="timeline-badge bg-primary text-white"><i class="fas fa-briefcase"></i></div>
                                      <div class="timeline-content">
                                        <h6 class="mb-1">Junior Designer</h6>
                                        <p class="text-muted mb-1">WebWorks Agency</p>
                                        <small class="text-muted">2015 - 2017</small>
                                        <p class="mt-2 mb-0">Assisted senior designers and created marketing materials.</p>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div> -->

            <!-- Education Tab -->
            <!-- <div class="tab-pane fade" id="education" role="tabpanel">
                              <div class="profile-card">
                                <div class="card-body">
                                  <div class="timeline">
                                    <div class="timeline-item mb-4">
                                      <div class="timeline-badge bg-primary text-white"><i class="fas fa-graduation-cap"></i></div>
                                      <div class="timeline-content">
                                        <h6 class="mb-1">Master's in Interaction Design</h6>
                                        <p class="text-muted mb-1">Stanford University</p>
                                        <small class="text-muted">2013 - 2015</small>
                                        <p class="mt-2 mb-0">Specialized in human-computer interaction and user experience.</p>
                                      </div>
                                    </div>
                                    <div class="timeline-item">
                                      <div class="timeline-badge bg-primary text-white"><i class="fas fa-graduation-cap"></i></div>
                                      <div class="timeline-content">
                                        <h6 class="mb-1">Bachelor's in Graphic Design</h6>
                                        <p class="text-muted mb-1">California College of Arts</p>
                                        <small class="text-muted">2009 - 2013</small>
                                        <p class="mt-2 mb-0">Focused on visual communication and digital media.</p>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div> -->
        </div>
    </div>
    </div>
    {{-- <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-end mb-4 ">
                    @php
                    $isPending = $advertiser->status == \App\Models\AdvertiserPublisher::STATUS_PENDING;
                    $isHold = $advertiser->status == \App\Models\AdvertiserPublisher::STATUS_HOLD;
                    $isRejected = $advertiser->status == \App\Models\AdvertiserPublisher::STATUS_REJECTED;
                    $isJoined = $advertiser->status == \App\Models\AdvertiserPublisher::STATUS_ACTIVE;
                    $isNoStatus = empty($advertiser->status);

                    $class = $isPending ? "warning" : ($isHold ? "info" : ($isRejected ? "danger"
                    : ($isJoined ? "success" : "primary")));
                    @endphp

                    <span class="badge bg-{{ $class }} text-white text-xs cursor-pointer">
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
                <div class="row align-items-center">
                    <!-- Company Details -->
                    <div class="col-6">
                        <h4 class="mb-1">{{ $advertiser->name }}</h4>
                        <p class="mb-1 text-muted text-sm">Commission Rate: <strong>
                                @if(!empty($advertiser->commission))
                                {{ $advertiser->commission }} {{ $advertiser->commission_type }}
                                @else
                                Revshare 80%
                                @endif
                            </strong>
                        </p>
                        <p class="mb-1 text-muted text-sm">Avg Payout: <strong>@if($advertiser->average_payment_time)
                                {{ $advertiser->average_payment_time }} days @else - @endif</strong></p>
                        <p class="mb-1 text-muted text-sm">Region:
                            @if (!is_array($advertiser->primary_regions))
                            @if ($advertiser->primary_regions == null)
                            <span class=" text-md text-danger">Invalid Region Data</span>
                            @else
                            <img src="https://flagsapi.com/{{ $advertiser->primary_regions }}/flat/24.png"
                                title="{{ $advertiser->primary_regions }}" class="img-fluid cursor-pointer" alt="">
                            @endif
                            @else
                            @if (empty($advertiser->primary_regions))
                            <span class=" text-md text-danger">Invalid Region Data</span>
                            @else
                            @foreach ($advertiser->primary_regions as $pr)
                            <img src="https://flagsapi.com/{{ $pr }}/flat/24.png" title="{{ $pr }}"
                                class="img-fluid cursor-pointer" alt="">
                            @endforeach
                            @endif
                            @endif
                        </p>
                        <a href="{{ $advertiser->url }}" target="_blank"
                            class="text-primary text-xs d-inline-flex align-items-center">
                            <i class="ri-link-m me-1"></i> Visit Website
                        </a>
                    </div>

                    <!-- Company Logo -->
                    <div class="col-6 text-end">
                        @if(!empty($advertiser->fetch_logo_url))

                        <img src="{{ $advertiser->fetch_logo_url }}" alt="{{ $advertiser->name }}" class="img-fluid"
                            height="140" width="140" />

                        @elseif(!empty($advertiser->logo))

                        <img src="{{ \App\Helper\Methods::staticAsset(" storage/{$advertiser->logo}") }}" alt="{{
                        $advertiser->name }}" class="img-fluid"
                        height="140" width="140">

                        @else
                        <img src="{{ \App\Helper\Methods::staticAsset('assets/media/logos/placeholder.jpeg') }}"
                            alt="{{ $advertiser->name }}" class="mw-50px mw-lg-75px" class="img-fluid" height="140"
                            width="140">
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12 mb-4">
        <ul class="nav" id="mainTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="btn btn-outline-primary mb-0 btn-sm active" id="description-tab" data-bs-toggle="tab"
                    data-bs-target="#description" type="button" role="tab" aria-controls="description"
                    aria-selected="false">Description</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="btn btn-outline-primary mx-1 btn-sm" id="transactions-tab" data-bs-toggle="tab"
                    data-bs-target="#transactions" type="button" role="tab" aria-controls="transactions"
                    aria-selected="true">Transactions</button>
            </li>
            @if(isset($advertiser->status) && $advertiser->status == \App\Models\AdvertiserPublisher::STATUS_ACTIVE)
            <li class="nav-item" role="presentation">
                <button class="btn btn-outline-primary mb-0 btn-sm" id="links-tab" data-bs-toggle="tab"
                    data-bs-target="#links" type="button" role="tab" aria-controls="links"
                    aria-selected="true">Links</button>
            </li>
            @endif
        </ul>

        <div class="tab-content mt-3" id="mainTabContent">

            <!-- Description Tab -->
            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6>Description</h6>
                                </div>
                                <div class="card-body">
                                    <p class="text-sm text-center">
                                        @if (empty($advertiser->short_description || $advertiser->short_description ==
                                        null))
                                        {{ $advertiser->short_description }}
                                        @else
                                        ProfitRefer is an affiliate PPC platform that lets publishers earn commissions for
                                        every valid click. It connects advertisers and publishers with real-time tracking,
                                        performance analytics, and high-converting offers.
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6>Terms & Condition</h6>
                                </div>
                                <div class="card-body">
                                    <p class="text-sm text-center">
                                        Pay-Per-Click (PPC) and TM+ Biding is not allowed for this advertiser and violation
                                        of terms will result in immediate termination of contract with the advertiser.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Transactions Tab -->
            <div class="tab-pane fade" id="transactions" role="tabpanel" aria-labelledby="transactions-tab">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-responsive table-hover">
                                <thead>
                                    <tr>
                                        <th><small class="text-muted d-block text-xs fw-bold">Transaction ID</small></th>
                                        <th><small class="text-muted d-block text-xs fw-bold">Date</small></th>
                                        <th><small class="text-muted d-block text-xs fw-bold">Sales Amount</small></th>
                                        <th><small class="text-muted d-block text-xs fw-bold">Comm. Amount</small></th>
                                        <th><small class="text-muted d-block text-xs fw-bold">Status</small></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $data)
                                    <tr class="align-middle border-bottom">
                                        <td>
                                            <div>
                                                <h6 class="fw-semibold text-dark mt-2">{{ $data->transaction_id }}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="fw-semibold text-dark mt-2">
                                                    {{\Carbon\Carbon::parse($data->transaction_date)->format('Y/m/d')}}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="fw-semibold text-dark mt-2">${{ $data->sale_amount }}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="fw-semibold text-dark mt-2">${{ $data->commission_amount }}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                @if ($data->commission_status == 'approved')
                                                <div class="d-flex align-items-center">
                                                    <span class="rounded-circle bg-success mr-2"
                                                        style="width:10px; height:10px;"></span>
                                                    <h6 class="fw-semibold text-dark mt-2">Approved</h6>
                                                </div>
                                                @elseif($data->commission_status == 'rejected')
                                                <div class="d-flex align-items-center">
                                                    <span class="rounded-circle bg-danger mr-2"
                                                        style="width:10px; height:10px;"></span>
                                                    <h6 class="fw-semibold text-dark mt-2">Rejected</h6>
                                                </div>
                                                @else
                                                <div class="d-flex align-items-center">
                                                    <span class="rounded-circle bg-warning mr-2"
                                                        style="width:10px; height:10px;"></span>
                                                    <h6 class="fw-semibold text-dark mt-2">Pending</h6>
                                                </div>
                                                @endif
                                            </div>
                                        </td>

                                    </tr>

                                    @endforeach
                                </tbody>
                            </table>
                            <div id="custom-length-container" class="mb-3"></div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Links Tab -->
            <div class="tab-pane fade" id="links" role="tabpanel" aria-labelledby="links-tab">
                <div class="row">
                    <div class="col-12 col-lg-6 mt-4">
                        <!-- Tabs -->
                        @include("publisher.widgets.deeplink", compact('advertiser'))

                        {{-- <ul class="nav" id="mainTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="btn btn-outline-primary mb-0 btn-sm active" id="trackinglink-tab"
                                    data-bs-toggle="tab" data-bs-target="#trackinglink" type="button" role="tab"
                                    aria-controls="trackinglink" aria-selected="true">
                                    Tracking Links
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="btn btn-outline-primary mb-0 mx-1 btn-sm" id="deeplink-tab"
                                    data-bs-toggle="tab" data-bs-target="#deeplink" type="button" role="tab"
                                    aria-controls="deeplink" aria-selected="false">
                                    Create Deep Link
                                </button>
                            </li>
                        </ul> --}}

                        <!-- Tab Content -->
                        {{-- <div class="tab-content mt-3" id="mainTabContent">
                            <!-- Information Tab -->
                            <div class="tab-pane fade show active" id="trackinglink" role="tabpanel"
                                aria-labelledby="trackinglink-tab">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <div style="width: 25%;">
                                            <h6 class="text-sm">Tracking Links</h6>
                                            <p class="text-sm text-secondary mb-0">Promote a Brand with a simple link.</p>
                                        </div>
                                        <div>
                                            <ul class="nav" id="mainTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="btn btn-outline-primary mb-0 btn-sm active"
                                                        id="trackingLink-tab" data-bs-toggle="tab"
                                                        data-bs-target="#trackingLink" type="button" role="tab"
                                                        aria-controls="trackingLink" aria-selected="true">
                                                        Tracking Link
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="btn btn-outline-primary mb-0 mx-1 btn-sm"
                                                        id="shorttrackingLink-tab" data-bs-toggle="tab"
                                                        data-bs-target="#shorttrackingLink" type="button" role="tab"
                                                        aria-controls="shorttrackingLink" aria-selected="false">
                                                        Short Tracking Link
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body px-4 py-0">
                                        <div class="tab-content mt-3" id="linkTabContent">
                                            <div class="tab-pane fade active show" id="trackingLink" role="tabpanel"
                                                aria-labelledby="trackingLink-tab">
                                                <!-- Tracking Link -->
                                                <div class="card mb-4">
                                                    <div class="card-header">
                                                        <h6 class="text-sm">Tracking Link</h6>
                                                    </div>
                                                    <div class="card-body border-top">
                                                        <div class="d-flex flex-column">
                                                            <a class="text-sm text-primary"
                                                                href="https://app.theaffilo.com/track?linkmid=386129147&linkaffid=79526583"
                                                                target="_blank" style="word-break: break-all;">
                                                                https://app.theaffilo.com/track?linkmid=386129147&linkaffid=79526583
                                                            </a>
                                                            <input type="text" id="copyText"
                                                                value="https://app.theaffilo.com/track?linkmid=386129147&linkaffid=79526583"
                                                                hidden>
                                                            <button id="copyBtn" class="btn btn-primary mt-3 w-auto">Copy
                                                                Link</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="shorttrackingLink" role="tabpanel"
                                                aria-labelledby="shorttrackingLink-tab">

                                                <!-- Short Tracking Link -->
                                                <div class="card mb-4">
                                                    <div class="card-header">
                                                        <h6 class="text-sm">Short Tracking Link</h6>
                                                    </div>
                                                    <div class="card-body border-top">
                                                        <div class="d-flex flex-column">
                                                            <a class="text-sm text-primary"
                                                                href="https://app.theaffilo.com/short/pdqKVMGnF1"
                                                                target="_blank" style="word-break: break-all;">
                                                                https://app.theaffilo.com/short/pdqKVMGnF1
                                                            </a>
                                                            <input type="text" id="copyText2"
                                                                value="https://app.theaffilo.com/short/pdqKVMGnF1" hidden>
                                                            <button id="copyBtn2" class="btn btn-primary mt-3 w-auto">Copy
                                                                Link</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Company Tab -->
                            <div class="tab-pane fade" id="deeplink" role="tabpanel" aria-labelledby="deeplink-tab">
                                <div class="card">

                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="text-sm">Create A Link</h6>
                                            <p class="text-sm text-secondary">Promote a Brand with a simple link.</p>
                                        </div>
                                        <div class="card-body px-4 py-0">
                                            <form>
                                                <div class="row mb-4">
                                                    <div>
                                                        <input type="text" class="form-control" placeholder="Brand Name"
                                                            required>
                                                    </div>
                                                    <p class="text-success text-sm mt-2"><i class="fa fa-check"></i> Deep
                                                        Link
                                                    </p>
                                                    <div class="mb-3">
                                                        <input type="text" class="form-control"
                                                            placeholder="Enter Landing Page (Optional)">
                                                    </div>
                                                    <div>
                                                        <input type="text" class="form-control"
                                                            placeholder="Enter Sub ID (Optional)">
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn btn-primary w-100">Create</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 mt-4">
                        <!--begin::Graph-->
                        <div class="card">
                            <!--begin::Card body-->
                            <div class="card-body">
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-wrap">

                                    @if(isset($advertiser->status))

                                    <div class="d-flex flex-column border-bottom">
                                        <div class="card-header">
                                            <h5>Tracking Link</h5>
                                        </div>

                                        <div class="card-body ">

                                            @if(isset($advertiser->is_tracking_generate) && isset($advertiser->tracking_url)
                                            && $advertiser->is_tracking_generate == 1)
                                            <div>
                                                <a href="{{ $advertiser->tracking_url_long ?? $advertiser->tracking_url }}"
                                                    target="_blank" id="trackingURL">{{ $advertiser->tracking_url_long ??
                                                    $advertiser->tracking_url }}</a>
                                            </div>
                                            <input type="text" id="copyText"
                                                value="{{ $advertiser->tracking_url_long ?? $advertiser->tracking_url }}"
                                                hidden />
                                            <button id="copyBtn" class="btn btn-primary mt-3 w-auto">Copy
                                                Link</button>
                                            @elseif(isset($advertiser->is_tracking_generate) &&
                                            $advertiser->is_tracking_generate == 2)
                                            <a href="javascript:void(0)"><i>Generating tracking
                                                    links.....</i></a>
                                            <br /><br />
                                            <a href="javascript:void(0)" class="btn btn-xs btn-outline-dashed"
                                                style="border: 1px solid var(--bs-primary);background: var(--bs-primary);color: white;">Copy
                                                Tracking Link</a>
                                            @else
                                            -
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <div class="card-header">
                                            <h5>Short Tracking Link</h5>
                                        </div>
                                        <div class="card-body ">
                                            @if(isset($advertiser->is_tracking_generate) &&
                                            isset($advertiser->tracking_url_short) && $advertiser->is_tracking_generate ==
                                            1)
                                            <div>
                                                <a href="{{ $advertiser->tracking_url_short }}" id="trackingShortURL"
                                                    target="_blank">{{ $advertiser->tracking_url_short }}</a>
                                            </div>
                                            <input type="text" id="copyText2" value="{{ $advertiser->tracking_url_short }}"
                                                hidden>
                                            <button id="copyBtn2" class="btn btn-primary mt-3 w-auto">Copy
                                                Link</button>
                                            @elseif(isset($advertiser->is_tracking_generate) &&
                                            $advertiser->is_tracking_generate == 2)
                                            <a href="javascript:void(0)"><i>Generating short tracking
                                                    links.....</i></a>
                                            <br /><br />
                                            <a href="javascript:void(0)" class="btn btn-xs btn-outline-dashed"
                                                style="border: 1px solid var(--bs-primary);background: var(--bs-primary);color: white;">Copy
                                                Short Tracking Link</a>
                                            @else
                                            -
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <!--end::Promotional Methods-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Card body-->

                    </div>
                </div>
            </div>
        </div>

    </div> --}}
@endsection
@section('scripts')
    <script>
        document.querySelectorAll('.copy-btn').forEach(button => {
            button.addEventListener('click', function () {
                const target = this.getAttribute('data-target');
                const input = document.querySelector(target);
                input.select();
                document.execCommand('copy');

                // Visual feedback
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check"></i> Copied!';
                setTimeout(() => {
                    this.innerHTML = originalText;
                }, 2000);
            });
        });
    </script>
@endsection
