<!--begin::Loader-->
<div id="table-loader" class="table-loader" style="display: none;">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr class="text-uppercase">
                {{-- <th>S.ID</th> --}}
                <th>Advertiser</th>
                <th>Transactions</th>
                <th>Sales</th>
                <th>Commission</th>
                <th>Clicks</th>
                <th>View</th>
            </tr>
        </thead>
        <tbody>
            @if(count($data))
                @foreach($data as $d)
                    @if( !empty($d['total_transactions']) || !empty($d['total_received_sale_amount']) || !empty($d['total_commissions_amount']) || !empty($d['total_clicks']) )
                        <tr class="border-bottom">
                            {{-- <td>
                                <h6 class="fw-semibold">{{ $d['advertiser_sid'] }}</h6>
                            </td> --}}
                            <td title="{{ $d['advertiser_name'] }}" class="p-2">
                                <h5 class="fw-semibold text-dark">{{ \Illuminate\Support\Str::limit($d['advertiser_name'], 30, '....') }}</h5>
                                <span>S.ID: {{ $d['advertiser_sid'] }}</span>
                            </td>
                            <td><h6 class="fw-semibold text-dark">{{ $d['total_transactions'] }}</h6></td>
                            <td><h6 class="fw-semibold text-dark">${{number_format($d['total_received_sale_amount'] , 2)}}</h6></td>
                            <td><h6 class="fw-semibold text-dark">${{ number_format($d['total_commissions_amount']  ?? 0 ,2)}}</h6></td>
                            <td><h6 class="fw-semibold text-dark">{{ $d['total_clicks'] ?? 0 }}</h6></td>
                            <td><a href="{{ route('publisher.view-advertiser', $d['advertiser_id']) }}" class="badge badge-primary nav-link text-md"><i class="fas fa-eye"></i></a></td>
                        </tr>
                    <!-- Card 1 -->
                        {{-- <div class="col-md-6 col-lg-4 mb-4">
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
                        </div> --}}
                    @endif
                @endforeach
            @else
                <tr class="border-bottom">
                    <td colspan="6" class="text-center">No Data Exists</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
