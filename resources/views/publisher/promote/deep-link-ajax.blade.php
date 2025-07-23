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
        <tr>
            <th scope="col">Advertiser</th>
            <th scope="col">Tracking URL</th>
            <th scope="col">Deep URL</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        @if(count($links))
            @foreach($links as $key => $link)
                <tr>
                    <td title="Visit Website"><a href="{{$link->landing_url}}" class="nav-link px-0">{{ \Illuminate\Support\Str::limit(blank($link->name) ? '-' : $link->name, 20, '....') }}</a></td>
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
