@if(count($advertisers))
    @foreach($advertisers->chunk(4) as $advertiserChunk)
        <tr>
            @foreach($advertiserChunk as $advertiser)
                <td style="width: 25%;">
                    <div class="form-group d-inline">
                        <div
                            class="checkbox checkbox-primary checkbox-fill d-inline">
                            <input type="checkbox" name="advertisers[{{ $advertiser['id'] }}]" class="advertiser-checkbox"
                                   id="advertisers-{{ $advertiser['id'] }}" {{ $advertiser['locked_status'] ? "checked" : ''}} value="true">
                            <label for="advertisers-{{ $advertiser['id'] }}" class="cr">{{ \Illuminate\Support\Str::limit($advertiser['name'], 50, '...') }}</label>
                        </div>
                    </div>
                </td>
            @endforeach
        </tr>
    @endforeach

    @if(count($advertisers) && $advertisers instanceof \Illuminate\Pagination\LengthAwarePaginator )
        <tr>
            <td colspan="4" class="text-right">

                <div class="row">
                    <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                        <div class="dataTables_length" id="kt_project_users_table_length">
                            <label>
                                <select name="per_page" id="per-page-select" class="form-select form-select-sm form-select-solid">
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </label>
                        </div>
                        <div class="dataTables_info" id="kt_project_users_table_info" role="status" aria-live="polite">
                            Showing {{ $from }} to {{ $to }} of {{ $advertisers->total() }} entries
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end" id="pagination-container">
                        {{ $advertisers->withQueryString()->links('partial.publisher_pagination') }}
                    </div>

                </div>

            </td>
        </tr>
    @endif
@else
    @include("admin.publisher.lock-unlock-network.empty")
@endif
