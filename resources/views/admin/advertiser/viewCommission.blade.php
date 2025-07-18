<div class="table-responsive">
    <table class="table invoice-detail-table">
        <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Condition</th>
                <th scope="col">Rate</th>
                <th scope="col">Type</th>
                <th scope="col">Additional Info</th>
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
