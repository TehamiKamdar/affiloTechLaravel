<div class="table-responsive">
    <table class="table invoice-detail-table">
        <thead>
            <tr>
                <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7" scope="col">Date</th>
                <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7" scope="col">Condition</th>
                <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7" scope="col">Rate</th>
                <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7" scope="col">Type</th>
                <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7" scope="col">Additional Info</th>
            </tr>
        </thead>
        <tbody>
        @foreach($commissions as $commission)
            <tr>
                <td>
                    {{ $commission->date ?? "-" }}
                </td>
                <td>
                    {{ $commission->condition ?? "-" }}
                </td>
                <td>
                    {{ $commission->rate ?? "-" }}
                </td>
                <td>
                    {{ $commission->type ?? "-" }}
                </td>
                <td>
                    {{ $commission->info ?? "-" }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
