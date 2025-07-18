<div class="table-responsive">
    <table class="table invoice-detail-table">
        <thead>
            <tr class="thead-default">
                <th style="width: 30%">Key</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            @php
                $fields = [
                    'Description' => $advertiser->description ?? "-",
                    'Short Description' => $advertiser->short_description ?? "-",
                    'Program Policies' => $advertiser->program_policies ?? "-",
                ];
            @endphp
            @foreach($fields as $label => $value)
                <tr>
                    <th>{{ $label }}</th>
                    <td>{!! $value !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
