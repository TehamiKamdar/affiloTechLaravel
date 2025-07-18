<!--begin::Loader-->
<div id="table-loader" class="table-loader" style="display: none;">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<!--end::Loader-->
<div class="row">
    @if(count($data))
        @foreach($data as $d)
            @if(
                        !empty($d['total_transactions']) ||
                        !empty($d['total_received_sale_amount']) ||
                        !empty($d['total_commissions_amount']) ||
                        !empty($d['total_clicks'])
                    )


                    <!-- Card 1 -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card">
                            <div class="bg-primary p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="{{ route("publisher.view-advertiser", ['advertiser' => $d['advertiser_id']]) }}"
                                            class=" text-sm mb-1 text-uppercase fw-500" title="{{ $d['advertiser_name'] }}">{{ \Illuminate\Support\Str::limit($d['advertiser_name'], 30, '....') }}</a>
                                        <p class="text-xs mb-0">({{ $d['advertiser_sid'] }})</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row border-bottom">
                                    <div class="col-6">
                                        <div class="stat-box border-end">
                                            <div class="text-xs">Total Transactions</div>
                                            <div class="stat-value">{{ $d['total_transactions'] }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-box">
                                            <div class="text-xs">Total Sales</div>
                                            <div class="stat-value positive">${{number_format($d['total_received_sale_amount'] , 2)}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="stat-box border-end mt-2">
                                            <div class="text-xs">Total Commission</div>
                                            <div class="stat-value positive">${{ number_format($d['total_commissions_amount']  ?? 0 ,2)}}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-box mt-2">
                                            <div class="text-xs">Total Clicks</div>
                                            <div class="stat-value">{{ $d['total_clicks'] ?? 0 }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <tr>
                        <td class="d-flex align-items-center" style="
                    padding-left: 10px;
                ">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                <a href="{{ route(" publisher.view-advertiser", ['advertiser'=> $d['advertiser_id']]) }}">
                                    <div class="symbol-label">
                                        @if(!empty($d['fetch_logo_url']))
                                        @if($d['fetch_logo_url'] !=
                                        'https://sg-quk-data.oss-ap-southeast-1.aliyuncs.com/quk/logo/default.png')
                                        <img src="{{ $d['fetch_logo_url']}}" alt="{{ $d['advertiser_name'] }}" class="w-100" />
                                        @else
                                        <img src="{{ \App\Helper\Methods::staticAsset('assets/media/logos/placeholder.jpeg') }}"
                                            alt="{{ $d['advertiser_name'] }}" class="w-100">
                                        @endif
                                        @else
                                        @if($d['logo'])
                                        <img src="{{ \App\Helper\Methods::staticAsset(" storage/{$d['logo']}") }}"
                                            alt="{{ $d['advertiser_name'] }}" class="w-100" />
                                        @else
                                        <img src="{{ \App\Helper\Methods::staticAsset('assets/media/logos/placeholder.jpeg') }}"
                                            alt="{{ $d['advertiser_name'] }}" class="w-100">
                                        @endif
                                        @endif


                                    </div>
                                </a>
                            </div>
                            <!--end::Avatar-->
                            <!--begin::User details-->
                            <div class="d-flex flex-column">
                                <a href="{{ route(" publisher.view-advertiser", ['advertiser'=> $d['advertiser_id']]) }}"
                                    class="text-gray-800 text-hover-primary mb-1">
                                    {{ \Illuminate\Support\Str::limit($d['advertiser_name'], 50, '....') }}
                                </a>

                            </div>
                            <!--end::User details-->
                        </td>
                        <td style="text-align:center;">
                            <span class="fw-bold">{{ $d['total_transactions'] }}</span>
                        </td>
                        <td style="text-align:center;">
                            <span class="text-gray-800 text-hover-primary">${{$d['total_received_sale_amount'] }}</span><br />

                        </td>
                        <td class="pe-0" style="text-align:center;">
                            <span>${{ $d['total_commissions_amount'] ?? 0 }}</span>
                        </td>
                        <td class="pe-0" style="text-alin:center;">
                            <span>{{ $d['total_clicks'] ?? 0 }}</span>
                        </td>
                    </tr> --}}
            @endif
        @endforeach
    @else
        <div class="d-flex-justify--content">
            <h5>No Data Exists</h5>
        </div>
    @endif
</div>
