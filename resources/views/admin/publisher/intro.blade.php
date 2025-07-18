<div class="table-responsive">
    <table class="table invoice-detail-table">
        <thead>
            <tr class="thead-default">
                <th style="width: 30%">Key</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>Full Name</th>
                <td>{{ $publisher->name }}</td>
            </tr>
            <tr>
                <th>publisher id</th>
                <td>{{ $publisher->publisher_id }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>
                    {{ $publisher->email }}
                    @if($publisher->status == \App\Models\User::STATUS_PENDING)
                        <span class="badge bg-warning">Pending</span>
                    @elseif($publisher->status == \App\Models\User::STATUS_HOLD)
                        <span class="badge bg-info">Hold</span>
                    @elseif($publisher->status == \App\Models\User::STATUS_REJECT)
                        <span class="badge bg-danger">Rejected</span>
                    @else
                        <span class="badge bg-success">Verified</span>
                    @endif
                    @if($publisher->email_verified_at)
                        @if($publisher->status != \App\Models\User::STATUS_REJECT)
                            <a href="{{ route('admin.publishers.statusUpdate', ['publisher' => $publisher->id, 'status' => \App\Models\User::STATUS_REJECT]) }}" class="btn btn-sm btn-glow btn-glow-danger btn-danger float-end">Reject</a>
                        @endif
                        @if($publisher->status != \App\Models\User::STATUS_HOLD)
                            <a href="{{ route('admin.publishers.statusUpdate', ['publisher' => $publisher->id, 'status' => \App\Models\User::STATUS_HOLD]) }}" class="btn btn-sm btn-glow btn-glow-info btn-info float-end mr-2">Hold</a>
                        @endif
                        @if($publisher->status != \App\Models\User::STATUS_ACTIVE)
                            <a href="{{ route('admin.publishers.statusUpdate', ['publisher' => $publisher->id, 'status' => \App\Models\User::STATUS_ACTIVE]) }}" class="btn btn-sm btn-glow btn-glow-success btn-success float-end mr-2">Active</a>
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <th>Email Verified At</th>
                <td>
                    {{ $publisher->email_verified_at ?? "" }}
                    @if($publisher->email_verified_at)
                        <span class="badge bg-success">Email Verified</span>
                    @else
                        <span class="badge bg-danger">Email Not Verified</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Remember Token</th>
                <td>{{ $publisher->remember_token ? "YES" : "NO" }}</td>
            </tr>
            <tr>
                <th>API Key</th>
                <td>{{ $publisher->api_token }}</td>
            </tr>
            <tr>
                <th>Company Name</th>
                <td>{{ $company->company_name ?? "-" }}</td>
            </tr>
            <tr>
                <th>Contact Phone</th>
                <td>{{ $company->phone_number ?? "-" }}</td>
            </tr>
            <tr>
                <th>Contact Name</th>
                <td>{{ $company->contact_name ?? "-" }}</td>
            </tr>
            <tr>
                <th>Country</th>
                <td>{{ $company->getCountry->name ?? "-" }}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td>{{ $company->address ?? "-" }}</td>
            </tr>
            <tr>
                <th>Created At</th>
                <td>{{ $publisher->created_at ?? "-" }}</td>
            </tr>
            <tr>
                <th>Last Updated</th>
                <td>{{ $publisher->updated_at ?? "-" }}</td>
            </tr>
        </tbody>
    </table>
</div>


<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
    <!--begin::Card body-->
    <div class="card-body p-9">

        <!--begin::Notice-->

        @if($publisher->status != \App\Models\User::STATUS_ACTIVE)

            @php
                $class = "bg-light-warning";
                $message = "The publisher is waiting for the admin's approval to activate the account.";
                if($publisher->status == \App\Models\User::STATUS_REJECT)
                {
                    $class = "bg-light-danger";
                    $message = "The admin's has put the account on reject.";
                }
                elseif($publisher->status == \App\Models\User::STATUS_HOLD)
                {
                    $class = "bg-light-light";
                    $message = "The admin's has put the account on hold.";
                }
            @endphp

                <!--begin::Notice-->
            <div class="notice d-flex {{ $class }} rounded border-warning border border-dashed p-6">
                <!--begin::Icon-->
                <i class="ki-duotone ki-information fs-2tx text-warning me-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
                <!--end::Icon-->
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack flex-grow-1">
                    <!--begin::Content-->
                    <div class="fw-semibold">
                        <h4 class="text-gray-900 fw-bold">Publisher status still in {{ $publisher->status }}!</h4>
                        <div class="fs-6 text-gray-700">{{ $message }}</div>
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Notice-->
        @endif

    </div>
    <!--end::Card body-->
</div>
