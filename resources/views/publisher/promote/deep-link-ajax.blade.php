<!--begin::Loader-->
<div id="table-loader" class="table-loader" style="display: none;">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<!--end::Loader-->

<!--begin::Table-->
<table class="table" id="kt_table_users">
    <thead>
        <tr>
            <th><small class="text-muted d-block text-xs fw-bold">Advertiser</small></th>
            <th><small class="text-muted d-block text-xs fw-bold">Website Url</small></th>
            <th><small class="text-muted d-block text-xs fw-bold">Deep Link</small></th>
            <th><small class="text-muted d-block text-xs fw-bold">Deep Short Link</small></th>
            <th><small class="text-muted d-block text-xs fw-bold">Sub ID</small></th>
        </tr>
    </thead>
    <tbody>
        @if(count($links))
            @foreach($links as $key => $link)
                <tr>
                    <td>

                        <!--begin::User details-->
                        <div class="d-flex align-items-center">

                            <div>
                                <div class="fw-bold" title="{{ $link->name }}">{{ \Illuminate\Support\Str::limit(blank($link->name) ? '-' : $link->name, 20, '....') }}</div>
                                <small class="text-muted">({{ $link->sid }})</small>
                            </div>

                        </div>
                        <!--end::User details-->
                    </td>
                    <td class="link-cell">
                        <a href="{{$link->landing_url}}" target="_blank" class="nav-link text-sm">{{ \Illuminate\Support\Str::limit($link->landing_url, 40, '....') }}</a>
                    </td>

                    <td class="link-cell">
                        <a href="{{ $link->tracking_url}}" class="nav-link text-sm">{{ $link->tracking_url}}<i class="fas fa-copy copy-btn ms-2 cursor-pointer" title="Copy link"  data-link="{{ $link->tracking_url }}"></i></a>
                    </td>

                    <td class="link-cell">
                        <a href="{{$link->tracking_url_long}}" class="nav-link text-sm">{{ \Illuminate\Support\Str::limit($link->tracking_url_long ?? "-", 30, $end='...') }}<i class="fas fa-copy copy-btn ms-2 cursor-pointer" title="Copy link" data-link="{{ $link->tracking_url_long }}"></i></a>
                    </td>
                   <td class="link-cell">
                        <span>{{ $link->sub_id ?? "-" }}</span>
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

    <script>
    function close_modal(ad_id){
        console.log(ad_id)
        $('#kt_modal_apply_data_'+ad_id).hide();
         $('.modal-backdrop').remove(); // For Bootstrap or similar systems

    // Remove body scroll locking (if applicable)
    $('body').removeClass('modal-open');
    $('body').css('overflow', '');
    }
</script>
</table>
