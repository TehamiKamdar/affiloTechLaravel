<!--begin::Loader-->
<div id="table-loader" class="table-loader" style="display: none;">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<!--end::Loader-->


<!--begin::Table-->
<div class="table-responsive">
    <table class="table nowrap w-100" id="myTable">
    <thead>
        <tr class="text-uppercase">
            <th scope="col">Advertiser</th>
            <th scope="col">Tracking Short URL</th>
            <th scope="col">Tracking URL</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        @if(count($links))
            @foreach($links as $link)
            @php
                    $initials = \App\Helper\Methods::getInitials($link->name);
                    $colorCode = \App\Helper\Methods::getColorFromName($link->name);
                    $imageUrl = "https://placehold.co/32/{$colorCode}/FFFFFF?text={$initials}";
                @endphp
                <tr class="border-bottom">
                    <td title="Visit Website">
                        <a href="{{ $link->url }}" class="nav-link px-0 d-flex align-items-center" style="gap: 8px;">
                            <img src="{{ $imageUrl }}" alt="{{ $initials }}" class="rounded-circle" width="32" height="32">
                            <div>
                                <h6 class="fw-semibold mb-0">{{ \Illuminate\Support\Str::limit(blank($link->name) ? '-' : $link->name, 20, '....') }}</h6>
                            <span class="text-muted">{{ $link->sid }}</span>
                            </div>
                        </a>
                    </td>
                    <td>
                        <a href="{{ $link->tracking_url_short }}" class="nav-link px-0" target="_blank">
                            {{ $link->tracking_url_short }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ $link->tracking_url_long}}" class="nav-link px-0 tracking-link" target="_blank">
                            {{ \Illuminate\Support\Str::limit($link->tracking_url_long ?? "-", 30, '...') }}
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
                    <small>No text link Exist</small>
                </td>
            </tr>
        @endif
    </tbody>
</table>
</div>
