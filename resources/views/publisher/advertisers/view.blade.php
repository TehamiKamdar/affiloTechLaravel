@extends('layouts.publisher.layout')

@section('content')
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('publisher.dashboard') }}"><i
                                    class="ri-home-5-line text-primary"></i></a></li>
                        <li class="breadcrumb-item"><a href="">Advertisers</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('publisher.my-advertisers') }}">My Advertisers</a></li>
                        <li class="breadcrumb-item"><a href="">{{ $advertiser->name }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <div class="col-12 mb-4">
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
                         
                         <img src="{{ \App\Helper\Methods::staticAsset("storage/{$advertiser->logo}") }}" alt="{{ $advertiser->name }}" class="img-fluid"
                            height="140" width="140">
                          
                        @else
                        <img src="{{ \App\Helper\Methods::staticAsset('assets/media/logos/placeholder.jpeg') }}" alt="{{ $advertiser->name }}" class="mw-50px mw-lg-75px"class="img-fluid"
                            height="140" width="140">
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
                                        @if (empty($advertiser->short_description || $advertiser->short_description == null))
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
                                                    <h6 class="fw-semibold text-dark mt-2"> {{\Carbon\Carbon::parse($data->transaction_date)->format('Y/m/d')}}</h6>
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
                        </div> --}}
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

                                                @if(isset($advertiser->is_tracking_generate) && isset($advertiser->tracking_url) && $advertiser->is_tracking_generate == 1)
                                                    <div>
                                                        <a href="{{ $advertiser->tracking_url_long ?? $advertiser->tracking_url }}"
                                                        target="_blank"
                                                        id="trackingURL">{{ $advertiser->tracking_url_long ?? $advertiser->tracking_url }}</a>
                                                    </div>
                                                    <input type="text" id="copyText"
                                                        value="{{ $advertiser->tracking_url_long ?? $advertiser->tracking_url }}"
                                                        hidden />
                                                    <button id="copyBtn" class="btn btn-primary mt-3 w-auto">Copy
                                                        Link</button>
                                                @elseif(isset($advertiser->is_tracking_generate) && $advertiser->is_tracking_generate == 2)
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
                                                @if(isset($advertiser->is_tracking_generate) && isset($advertiser->tracking_url_short) && $advertiser->is_tracking_generate == 1)
                                                    <div>
                                                        <a href="{{ $advertiser->tracking_url_short }}" id="trackingShortURL"
                                                        target="_blank">{{ $advertiser->tracking_url_short }}</a>
                                                    </div>
                                                    <input type="text" id="copyText2" value="{{ $advertiser->tracking_url_short }}"
                                                        hidden>
                                                    <button id="copyBtn2" class="btn btn-primary mt-3 w-auto">Copy
                                                        Link</button>
                                                @elseif(isset($advertiser->is_tracking_generate) && $advertiser->is_tracking_generate == 2)
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

    </div>
@endsection
@section('scripts')
    <script>
        const copyBtn = document.getElementById('copyBtn');
        const copyText = document.getElementById('copyText');

        copyBtn.addEventListener('click', () => {
            // Copy to clipboard
            navigator.clipboard.writeText(copyText.value).then(() => {
                // Change button style and text
                copyBtn.classList.remove('btn-primary');
                copyBtn.classList.add('btn-success');
                copyBtn.textContent = 'Copied!';

                // Optional: revert after a delay
                setTimeout(() => {
                    copyBtn.classList.remove('btn-success');
                    copyBtn.classList.add('btn-primary');
                    copyBtn.textContent = 'Copy Link';
                }, 2000);
            });
        });

        const copyBtn2 = document.getElementById('copyBtn2');
        const copyText2 = document.getElementById('copyText2');

        copyBtn2.addEventListener('click', () => {
            // Copy to clipboard
            navigator.clipboard.writeText(copyText2.value).then(() => {
                // Change button style and text
                copyBtn2.classList.remove('btn-primary');
                copyBtn2.classList.add('btn-success');
                copyBtn2.textContent = 'Copied!';

                // Optional: revert after a delay
                setTimeout(() => {
                    copyBtn.classList.remove('btn-success');
                    copyBtn.classList.add('btn-primary');
                    copyBtn.textContent = 'Copy Link';
                }, 2000);
            });
        });
    </script>
@endsection
