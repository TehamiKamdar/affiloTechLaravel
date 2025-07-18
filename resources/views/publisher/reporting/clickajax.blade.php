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
        <tr>
            <th><small class="text-muted d-block text-xs fw-bold">Advertiser</small></th>
            <th><small class="text-muted d-block text-xs fw-bold">Date</small></th>
            <th><small class="text-muted d-block text-xs fw-bold">Tracking Link Clicks</small></th>
            <th><small class="text-muted d-block text-xs fw-bold">Deeplink Clicks</small></th>
            <th><small class="text-muted d-block text-xs fw-bold">Coupon Clicks</small></th>
            <th><small class="text-muted d-block text-xs fw-bold">Total Clicks</small></th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @if(count($clicks))

            @foreach($clicks as $click)
                <tr class="align-middle border-bottom">
                    <td>
                        <div class="d-flex flex-column">
                            <h6 class="fw-semibold text-dark mt-2">
                                {{ $click->advertiser_name }}
                            </h6>
                            <a class="fw-semibold nav-link text-xs mt-2" href="{{ route("publisher.view-advertiser", ['advertiser' => $click->advertiser_id]) }}"><i class="ri-link-m"></i>Visit Website</a>

                        </div>
                        <!--end::User details-->
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
