@if($advertisers->count())
    @foreach($advertisers->chunk(4) as $advertiserChunk)
        <tr>
            @foreach($advertiserChunk as $advertiser)
                <td style="width: 25%;">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input checkbox" name="ids[]" {{ $advertiser->is_show == 1 ? "checked" : ($advertiser->is_show == 0 ? "unchecked" : "")  }} value="{{ $advertiser->id }}" title="{{ $advertiser->name }}"> {{ \Illuminate\Support\Str::limit($advertiser->name, 30, '....') }}
                            @if($advertiser->status !== 2)
                                <input type="hidden" class="form-check-input" name="not_showed_ids[]" value="{{ $advertiser->id }}">
                            @endif
                        </label>
                    </div>
                </td>
            @endforeach
        </tr>
    @endforeach

    <tr class="text-center">
        <td colspan="4">
            <div class="d-flex justify-content-center mt-1 mb-20">
                {{ $advertisers->withQueryString()->links('partial.publisher_pagination') }}
            </div>
        </td>
    </tr>
@else
    <tr>
        <td colspan="4" class="text-center">
            <small>No Advertiser Exist</small>
        </td>
    </tr>
@endif
