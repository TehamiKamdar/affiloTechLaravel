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
        <tr>
            <th><small class="text-muted d-block text-xs fw-bold">Advertiser</small></th>
            <th><small class="text-muted d-block text-xs fw-bold">Website Url</small></th>
            <th><small class="text-muted d-block text-xs fw-bold">Tracking Link</small></th>
            <th><small class="text-muted d-block text-xs fw-bold">Short Link</small></th>
            <th><small class="text-muted d-block text-xs fw-bold">Sub Id</small></th>

        </tr>
    </thead>
    <tbody>
        @if(count($links))
            @foreach($links as $link)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">

                            <div>
                                <div class="fw-bold" title="{{ $link->name }}">{{ \Illuminate\Support\Str::limit(blank($link->name) ? '-' : $link->name, 20, '....') }}</div>
                                <small class="text-muted">({{ $link->sid }})</small>
                            </div>
                        </div>
                    </td>
                    <td class="link-cell">
                        <a href="{{ $link->url }}" target="_blank"
                            class="nav-link text-sm">{{ \Illuminate\Support\Str::limit($link->url, 40, '....') }}</a>
                    </td>
                    <td class="link-cell">
                        <a href="{{ $link->tracking_url_long }}" target="_blank" class="nav-link text-sm">
                            {{ \Illuminate\Support\Str::limit($link->tracking_url_long ?? "-", 30, '...') }} <i class="fas fa-copy copy-btn ms-2 cursor-pointer" title="Copy link" data-link="{{ $link->tracking_url_long }}"></i>
                        </a>
                    </td>

                    <td class="link-cell">
                        <a href="{{ $link->tracking_url_short }}" target="_blank" class="nav-link text-sm">
                            {{ $link->tracking_url_short }} <i class="fas fa-copy copy-btn ms-2 cursor-pointer" title="Copy link"  data-link="{{ $link->tracking_url_short }}"></i>
                        </a>
                    </td>
                    <td class="link-cell">
                        <p class="text-muted text-sm">{{ $link->sub_id ?? "-" }}</p>
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
