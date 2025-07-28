<!--begin::Loader-->
<div id="table-loader" class="table-loader" style="display: none;">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<!--end::Loader-->

<!--begin::Table-->
<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-uppercase">
            <th>Advertise</th>
            <th>Date</th>
            <th>Tracking Link Click</th>
            <th>Deeplink Click</th>
            <th>Coupon Click</th>
            <th>Total Click</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @if(count($clicks))

            @foreach($clicks as $click)
                @php
                    $initials = \App\Helper\Methods::getInitials($click->advertiser_name);
                    $colorCode = \App\Helper\Methods::getColorFromName($click->advertiser_name);
                    $imageUrl = "https://placehold.co/32/{$colorCode}/FFFFFF?text={$initials}";
                @endphp
                <tr class="border-bottom">
                    <td>
                        <a href="{{ route("publisher.view-advertiser", ['advertiser' => $click->advertiser_id]) }}"class="nav-link px-0 d-flex align-items-center" style="gap: 8px;">
                            <img src="{{ $imageUrl }}" alt="{{ $initials }}" class="rounded-circle" width="32" height="32">
                            <div>
                                <h6 class="fw-semibold mb-0">{{ \Illuminate\Support\Str::limit( $click->advertiser_name , 50, '....') }}</h6>
                                <span class="text-muted">{{ $click->sid }}</span>
                            </div>
                        </a>
                            {{-- <h6 class="fw-semibold text-dark mt-2">
                                {{ $click->advertiser_name }}
                            </h6>
                            <a class="fw-semibold nav-link text-xs mt-2" href="{{ route("publisher.view-advertiser", ['advertiser' => $click->advertiser_id]) }}"><i class="ri-link-m"></i>Visit Website</a> --}}

                    </td>
                    <td>
                        <div>
                            <h6 class="fw-semibold text-dark mt-2">{{ $click->date }}</span>
                        </div>
                    </td>
                    <td>

                        @if($click->link_type == 'tracking')
                            <h6 class="fw-semibold text-dark mt-2">
                                {{ $click->total_clicks }}
                            </h6>
                        @else
                        <h6 class="fw-semibold text-dark mt-2">0</h6> @endif
                    </td>
                    <td class="pe-0">
                    @if($click->link_type == 'deeplink')
                            <h6 class="fw-semibold text-dark mt-2">
                                {{ $click->total_clicks }}
                            </h6>
                        @else
                        <h6 class="fw-semibold text-dark mt-2">0</h6> @endif
                    </td>
                    <td class="pe-0">
                    @if($click->link_type == 'coupon')
                            <h6 class="fw-semibold text-dark mt-2">
                                {{ $click->total_clicks }}
                            </h6>
                        @else
                        <h6 class="fw-semibold text-dark mt-2">0</h6> @endif
                    </td>
                    <td class="pe-0">
                        <h6 class="fw-semibold text-dark mt-2">{{ $click->total_clicks }}</h6>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6" class="text-center">
                    <small>No Clicks Exist</small>
                </td>
            </tr>
        @endif
    </tbody>
</table>
