@if ($advertisers->count() == 0)
    <div class="d-flex justify-content-center-align-items-center">
        <h6>No Advertisers Found</h6>
    </div>
@else
    <div class="row">

        @foreach ($advertisers as $advertiser)
    @php
    $name = $advertiser->name;
    if (empty($name) || $name === '---' || stripos($name, '(CLOSED)') !== false || stripos($name, '???') !== false) {
    $nametrue = false;
}else{
 $nametrue = true;
}
    @endphp
     @if($nametrue)
    <div class="col-md-6 col-lg-4 mb-4">
            <div class="advertiser-card card h-100 border-0 shadow-sm">
              <div class="card-header d-flex justify-content-between align-items-center"
                style="background-color: #00a9da; color: white;">
                <h5 title="{{ $advertiser->name ?: 'No Name Record' }}" class="cursor-pointer">
                    {{ \Illuminate\Support\Str::limit($advertiser->name ?: '-', 15) }}
                </h5>
                @php

                    $isPending = $advertiser->status == \App\Models\AdvertiserPublisher::STATUS_PENDING;
                    $isHold = $advertiser->status == \App\Models\AdvertiserPublisher::STATUS_HOLD;
                    $isRejected = $advertiser->status == \App\Models\AdvertiserPublisher::STATUS_REJECTED;
                    $isJoined = $advertiser->status == \App\Models\AdvertiserPublisher::STATUS_ACTIVE;
                    $isNoStatus = empty($advertiser->status);

                    $class = $isPending ? "badge-warning" : ($isHold ? "badge-info" : ($isRejected ? "badge-danger"
                        : ($isJoined ? "badge-success" : "badge-primary")));
                @endphp
                <span class="badge badge-sm {{$class}} text-white text-xs cursor-pointer" @if($isNoStatus) data-bs-toggle="modal" data-bs-target="#kt_modal_apply_data_{{ $advertiser->id }}" onclick="singleSelectAdvertiser(`{{ $advertiser->id }}`)" @endif
                    title="Status">
                    @if($isPending)
                        Pending
                    @elseif($isHold)
                        Hold
                    @elseif($isRejected)
                        Rejected
                    @elseif($isJoined)
                        Joined
                    @else
                        Apply
                    @endif

                </span>
              </div>
              <div class="card-body">

                <div class="advertiser-attributes">
                  <div class="attribute-item">
                    <i class="fas fa-percentage mr-2" style="color: #00a9da;"></i>
                    <span class="attribute-label">Commission:</span>
                    <span class="attribute-value">
                        @if($advertiser->commission_type == 'Percentage' || $advertiser->commission_type == 'percentage' || $advertiser->commission_type == '%')
                        {{ empty($advertiser->commission) || $advertiser->commission == '0'
                            ? 'Revshare 80%'
                            : (strpos($advertiser->commission, '%') !== false ? $advertiser->commission : $advertiser->commission . '%')
                        }}
                        @else
                        {{ empty($advertiser->commission) || $advertiser->commission == '0'
                            ? 'Revshare 80%'
                            : (strpos($advertiser->commission, '%') !== false ? $advertiser->commission : $advertiser->commission)
                        }}
                        @endif
                    </span>
                  </div>

                  <div class="attribute-item">
                    <i class="fas fa-map-marker-alt mr-2" style="color: #00a9da;"></i>
                    <span class="attribute-label">Region:</span>
                    @php
                        $regionsRaw = $advertiser->primary_regions;
                        $regions = json_decode($regionsRaw, true);

                        if (json_last_error() !== JSON_ERROR_NONE || !is_array($regions)) {
                            $regions = [$regionsRaw];
                        }

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
                    <span class="attribute-value">{{ \Carbon\Carbon::parse($advertiser->created_at)->format('M Y') }}</span>
                  </div>

                  {{-- <div class="attribute-item">
                    <i class="fas fa-tags mr-2" style="color: #00a9da;"></i>
                    <span class="attribute-label">Category:</span>
                    <span class="attribute-value">Technology</span>
                  </div> --}}

                  <div class="attribute-item">
                    <i class="fas fa-star mr-2" style="color: #00a9da;"></i>
                    <span class="attribute-label">Website:</span>
                    <span class="attribute-value"><a href="{{ $advertiser->url }}" target="_blank">Click Here</a></span>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <div class="row justify-content-around">
                <a href="{{ route("publisher.view-advertiser", ['advertiser' => $advertiser->id]) }}" class="btn btn-sm" style="background-color: #00a9da; color: white;">View Details</a>
                {{-- <button class="btn btn-sm btn-success" style="color: white;">Active</button> --}}
              </div>
              </div>
            </div>
          </div>


            {{-- <div class="col-lg-3 col-md-4 col-sm-6 col-12  mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <h5 title="{{ $advertiser->name ?: 'No Name Record' }}" class="cursor-pointer">
                                {{ \Illuminate\Support\Str::limit($advertiser->name ?: '-', 20) }}
                            </h5>
                            <p class="text-xs">({{ $advertiser->sid }})</p>
                            <a href="{{ $advertiser->url }}" target="_blank" class="fw-semibold nav-link text-xs mt-2">
                                <i class="ri-link-m" title="Visit Website"></i> Visit Website
                            </a>
                        </div>
                        <div class="row justify-content-center mb-3">
                            <div class="col-6">
                                <i class="ri-lg ri-wallet-3-line text-green cursor-pointer" title="Avg Payout Time"></i>
                                <h6 class="text-xs">{{ empty($advertiser->average_payment_time) || $advertiser->average_payment_time == '0' ? 'N/A' : $advertiser->average_payment_time.' Days' }}</h6>
                            </div>
                            <div class="col-6">
                                <i class="ri-lg ri-coins-line text-golden cursor-pointer" title="Commission Rate"></i>
                                <h6 class="text-xs">
                                    @if($advertiser->commission_type == 'Percentage' || $advertiser->commission_type == 'percentage' || $advertiser->commission_type == '%')
                                    {{ empty($advertiser->commission) || $advertiser->commission == '0'
                                        ? 'Revshare 80%'
                                        : (strpos($advertiser->commission, '%') !== false ? $advertiser->commission : $advertiser->commission . '%')
                                    }}
                                    @else
                                    {{ empty($advertiser->commission) || $advertiser->commission == '0'
                                        ? 'Revshare 80%'
                                        : (strpos($advertiser->commission, '%') !== false ? $advertiser->commission : $advertiser->commission)
                                    }}
                                    @endif
                                </h6>
                            </div>
                        </div>
                        <div class="row justify-content-center mb-3">
                            <div class="col-6">
                                @php

                                    $isPending = $advertiser->status == \App\Models\AdvertiserPublisher::STATUS_PENDING;
                                    $isHold = $advertiser->status == \App\Models\AdvertiserPublisher::STATUS_HOLD;
                                    $isRejected = $advertiser->status == \App\Models\AdvertiserPublisher::STATUS_REJECTED;
                                    $isJoined = $advertiser->status == \App\Models\AdvertiserPublisher::STATUS_ACTIVE;
                                    $isNoStatus = empty($advertiser->status);

                                    $class = $isPending ? "badge-warning" : ($isHold ? "badge-info" : ($isRejected ? "badge-danger"
                                        : ($isJoined ? "badge-success" : "badge-primary")));
                                @endphp
                                <span class="badge badge-sm {{$class}} text-white text-xs cursor-pointer" @if($isNoStatus) data-bs-toggle="modal" data-bs-target="#kt_modal_apply_data_{{ $advertiser->id }}" onclick="singleSelectAdvertiser(`{{ $advertiser->id }}`)" @endif
                                    title="Status">
                                    @if($isPending)
                                        Pending
                                    @elseif($isHold)
                                        Hold
                                    @elseif($isRejected)
                                        Rejected
                                    @elseif($isJoined)
                                        Joined
                                    @else
                                        Apply
                                    @endif

                                </span>

                                <div class="modal fade" id="kt_modal_apply_data_{{ $advertiser->id }}" tabindex="-1" aria-hidden="true">
                            <!--begin::Modal dialog-->
                            <div class="modal-dialog modal-dialog-centered mw-1000px">
                                <!--begin::Modal content-->
                                <div class="modal-content">
                                    <!--begin::Modal header-->
                                    <div class="modal-header">
                                        <div class=" col-12 d-flex justify-content-between align-items-center">
                                            <!--begin::Modal title-->
                                            <div>
                                                <h2 class="text-lg">
                                                Selected Advertiser
                                            </h2>
                                            </div>
                                            <!--end::Modal title-->
                                            <!--begin::Close-->
                                            <div class="btn btn-close btn-sm bg-danger" data-kt-apply-advertiser-modal-action="close"  onclick="close_modal(`{{ $advertiser->id }}`)">

                                            </div>
                                            <!--end::Close-->
                                        </div>
                                    </div>
                                    <!--end::Modal header-->
                                    <!--begin::Modal body-->
                                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                        <!--begin::Form-->
                                        <form class="form w-100" novalidate="novalidate" action="{{ route('publisher.apply-advertiser') }}" method="post" >
                                            @csrf

                                            <div class="dt-responsive table-responsive">
                                                <table class="table table-striped table-bordered nowrap">
                                                    <tbody class="search-api" id="tableContent">

                                                    </tbody>
                                                </table>
                                            </div>

                                            <p class="font-weight-bold text-black">Optional: Tell us about your promotional methods and general marketing plan for this merchant to help speed up approval. (Websites you'll use, PPC terms, etc.)</p>

                                            <textarea class="form-control" rows="4" cols="4" name="message"></textarea>
                                            <input type="hidden" name='advertiser_id' value="{{ $advertiser->id }}">
                                            <!--begin::Actions-->
                                            <div class="text-center mt-5">
                                                <button type="reset" class="btn btn-light me-3" data-kt-apply-advertiser-modal-action="cancel" onclick="close_modal(`{{ $advertiser->id }}`)">Cancel</button>
                                                <button type="submit" class="btn btn-primary" id="kt_advertiser_apply_submit" data-kt-apply-advertiser-modal-action="submit">
                                                    <span class="indicator-label">Apply</span>
                                                </button>
                                            </div>
                                            <!--end::Actions-->
                                        </form>
                                        <!--end::Form-->
                                    </div>
                                    <!--end::Modal body-->
                                </div>
                                <!--end::Modal content-->
                            </div>
                            <!--end::Modal dialog-->
                        </div>
                        <!--end::Modal - New Card-->
                            </div>
                            <div class="col-6 d-flex flex-wrap justify-content-center align-items-center">
                            @php
                                $regionsRaw = $advertiser->primary_regions;
                                $regions = json_decode($regionsRaw, true);

                                if (json_last_error() !== JSON_ERROR_NONE || !is_array($regions)) {
                                    $regions = [$regionsRaw];
                                }

                                $regions = array_filter(array_map(function ($code) {
                                    return strtoupper(trim(str_replace('"', '', html_entity_decode($code))));
                                }, $regions));

                                $displayRegions = array_slice($regions, 0, 3);
                                $extraRegions = array_slice($regions, 3);
                            @endphp

                            @if (!empty($displayRegions))
                                @foreach ($displayRegions as $code)
                                    <div>
                                        <img src="https://flagsapi.com/{{ $code }}/flat/24.png"
                                        alt="{{ $code }} flag"
                                        title="{{ $code }}"
                                        class="me-1 mb-1 cursor-pointer" />
                                    </div>
                                @endforeach

                                @if (count($extraRegions) > 0)
                                    @php
                                        $popoverContent = '';
                                        foreach ($extraRegions as $code) {
                                            $popoverContent .=
                                            "
                                                <img src='https://flagsapi.com/{$code}/flat/16.png' title='{$code}' class='me-1 mb-1'> {$code}<br>
                                            ";
                                        }
                                    @endphp
                                    <span class="text-muted text-xs ms-1"
                                    data-bs-toggle="popover"
                                    data-bs-trigger="hover focus"
                                    data-bs-placement="top"
                                    data-bs-html="true"
                                    title="More Regions"
                                    data-bs-content="{!! $popoverContent !!}">
                                    +{{ count($extraRegions) }} more
                                </span>
                                @endif
                            @else
                                <small class="text-danger">No region data</small>
                            @endif



                            </div>
                        </div>
                        <div class="col-12">
                            <a href="{{ route("publisher.view-advertiser", ['advertiser' => $advertiser->id]) }}"
                                class="btn btn-hover btn-sm btn-primary border border-primary border-radius-xl text-primary"
                                style="background-color: rgb(219, 248, 255);">
                                <i class="ri-xs ri-eye-line text-primary me-2" title="View Details"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div> --}}
             @endif
        @endforeach
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
            popoverTriggerList.forEach(function (popoverTriggerEl) {
                new bootstrap.Popover(popoverTriggerEl);
            });
        });
    </script>

@endif
