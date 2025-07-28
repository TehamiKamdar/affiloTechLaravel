<!--begin::Loader-->
<div id="table-loader" class="table-loader" style="display: none;">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<!--end::Loader-->

<!--begin::Table-->
<table class="table table-hover" id="kt_table_users">
    <thead>
        <tr class="text-uppercase">
            <th scope="col">Advertiser</th>
            <th scope="col">Tracking URL</th>
            <th scope="col">Deep URL</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        @if(count($links))
            @foreach($links as $key => $link)
                @php
                    $initials = \App\Helper\Methods::getInitials($link->name);
                    $colorCode = \App\Helper\Methods::getColorFromName($link->name);
                    $imageUrl = "https://placehold.co/32/{$colorCode}/FFFFFF?text={$initials}";
                    // dd($link->name);
                @endphp
                <tr>
                    <td title="Visit Website">
                        <a href="{{ $link->landing_url }}" class="nav-link px-0 d-flex align-items-center" style="gap: 8px;">
                            <img src="{{ $imageUrl }}" alt="{{ $initials }}" class="rounded-circle" width="32" height="32">
                            <div>
                                <h6 class="fw-semibold mb-0">{{ \Illuminate\Support\Str::limit(blank($link->name) ? '-' : $link->name, 20, '....') }}</h6>
                                <span class="text-muted">{{$link->sid}}</span>
                            </div>
                        </a>
                    </td>

                    <td>
                        <a href="{{ $link->tracking_url}}" class="nav-link px-0" target="_blank">
                            {{ $link->tracking_url }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ $link->tracking_url_long}}" class="nav-link px-0 deep-link" target="_blank">
                                {{ \Illuminate\Support\Str::limit($link->tracking_url_long ?? "-", 30, $end='...') }}
                        </a>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary copy-link-btn">
                            <i class="fas fa-copy"></i>
                        </button>
                    </td>
                </tr>

            @endforeach
        @else
            <tr>
                <td colspan="6" class="text-center">
                    <small>No Deep link Exist</small>
                </td>
            </tr>
        @endif
    </tbody>
</table>
