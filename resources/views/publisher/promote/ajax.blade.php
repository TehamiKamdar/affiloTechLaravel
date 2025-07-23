<div class="row">


    @if(count($coupons))
        @foreach($coupons as $coupon)
            @php
                $advertiser = \App\Models\Advertiser::where('advertiser_id', $coupon->advertiser_id)->first();
            @endphp

            <div class="col-lg-4 col-md-6 col-sm-12 col-12 coupon-card">
                <div class="card">
                    <div class="coupon-tearaway">
                        <div class="coupon-main">
                            <div class="coupon-details">
                                <div class="coupon-header">
                                    <h3 title="{{ $coupon->title }}">{{ \Illuminate\Support\Str::limit($coupon->title, 20, '....') }}</h3>
                                    {{-- <div class="coupon-tag">Limited Time</div> --}}
                                </div>
                                <p class="coupon-description" title="{{ $coupon->description }}">{{ $coupon->description ? \Illuminate\Support\Str::limit($coupon->description, 40, '....') : 'No Description' }}</p>
                                <div class="coupon-dates">
                                    <div class="date-container">
                                        <div class="{{ \Carbon\Carbon::parse($coupon->start_date)->lt(now()) ? 'date-label-expired' : 'date-label-valid' }}">
                                            {{ \Carbon\Carbon::parse($coupon->start_date)->lt(now()) ? 'EXPIRED' : 'VALID' }}
                                        </div>

                                        <div class="date-range">
                                            <span>{{ \Carbon\Carbon::parse($coupon->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($coupon->end_date)->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="coupon-code-container">
                                    <div class="code-label">USE CODE:</div>
                                    <div class="coupon-code">{{ $coupon->code}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="tear-away">
                            <div class="perforation"></div>
                            <button class="coupon-btn" {{ \Carbon\Carbon::parse($coupon->start_date)->gt(now()) ? '' : 'disabled' }}>
                                <span>Redeem Now</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-6 col-lg-4 mb-4">
                <div class="card coupon-card">
                    <div class="bg-primary p-3">
                        <div class="ribbon">HOT</div>
                        <h5 class="coupon-code mb-1 d-none">{{ $coupon->code}} </h5>
                        <p class="coupon-desc mb-0 text-uppercase">{{ $coupon->type}}</p>
                    </div>
                    <div class="card-body">
                        <h3 class="coupon-value text-primary">{{ $coupon->code}}</h3>
                        <p class="coupon-details" title="{{ $coupon->title }}">
                            {{ \Illuminate\Support\Str::limit($coupon->title, 40, '....') }}
                        </p>
                        <div class="d-flex">
                            <p class="text-success text-sm me-3">
                                <i class="fas fa-clock me-1"></i> Starts: {{ \Carbon\Carbon::parse($coupon->start_date)->format('d M Y') }}
                            </p>
                            <p class="text-danger text-sm">
                                <i class="fas fa-clock me-1"></i> Expires: {{ \Carbon\Carbon::parse($coupon->end_date)->format('d M Y') }}
                            </p>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <button class="btn btn-primary btn-sm copy-btn" data-code="{{ $coupon->code}}">
                                <i class="fas fa-copy me-1"></i> Copy Code
                            </button>

                        </div>
                        <div class="d-flex justify-content-end align-items-center">
                            <p class="text-xs">Created By:</p>
                            <h6 class="mb-1"><a href="{{ route(" publisher.view-advertiser", ['advertiser'=> $advertiser->id])
                                    }}">{{ \Illuminate\Support\Str::limit($coupon->advertiser_name, 50, '....') }}</a></h6>
                        </div>
                    </div>
                </div>
            </div> --}}
        @endforeach
    @else
        <div class="d-flex justify-content-center">
            No Coupons Available
        </div>
    @endif
</div>
