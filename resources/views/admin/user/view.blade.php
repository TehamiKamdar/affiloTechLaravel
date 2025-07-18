@extends("layouts.admin.layout")

@section('styles')
<style>
    .user-profile-dashboard {
        padding: 20px;
        background: #f8f9fa;
    }

    .user-profile-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        background: white;
    }

    .card-header {
        background: linear-gradient(135deg, #f37916 0%, #f9a825 100%);
    color: white;
        border-bottom: none;
        padding: 20px 25px;
    }

    .user-badge {
        display: flex;
        flex-direction: column;
    }

    .user-type-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        align-self: flex-start;
        margin-bottom: 5px;
    }

    .user-name {
        margin: 0;
        font-weight: 700;
        font-size: 24px;
    }

    .card-actions .btn-group {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        overflow: hidden;
    }

    .card-actions .btn {
        border: none;
        color: white;
        font-size: 14px;
        padding: 5px 15px;
    }

    .card-actions .btn.active {
        background: rgba(255, 255, 255, 0.3);
    }

    .user-profile-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        padding: 20px;
    }

    .profile-summary-card {
        grid-column: 1;
        display: flex;
        align-items: center;
        padding: 20px;
        background: #f9f9f9;
        border-radius: 10px;
    }

    .avatar-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f37916 0%, #f9a825 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        color: white;
        font-size: 32px;
        font-weight: bold;
    }

    .user-meta {
        flex: 1;
    }

    .user-meta h3 {
        margin: 0 0 5px 0;
        font-size: 20px;
    }

    .user-status {
        display: flex;
        align-items: center;
        margin-top: 10px;
    }

    .status-badge {
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        color: #f37916;
    }

    .status-active {
        background: #e6f7ee;
        color: #00a854;
    }

    .status-pending {
        background: #fff7e6;
        color: #fa8c16;
    }

    .status-inactive {
        background: #fff1f0;
        color: #f5222d;
    }

    .status-toggle {
        margin-left: auto;
    }

    .btn-status {
        padding: 3px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .btn-status i {
        margin-right: 5px;
        font-size: 10px;
    }

    .btn-status-active {
        background: #00a854;
        color: white;
    }

    .btn-status-pending {
        background: #fa8c16;
        color: white;
    }

    .profile-details-card,
    .profile-actions-card {
        background: #f9f9f9;
        border-radius: 10px;
        padding: 20px;
    }

    .profile-details-card {
        grid-column: 2;
        grid-row: 1;
    }

    .profile-actions-card {
        grid-column: 1 / span 2;
    }

    .details-title,
    .actions-title {
        margin-top: 0;
        margin-bottom: 15px;
        font-size: 18px;
        color: #333;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: #666;
        font-weight: 500;
    }

    .detail-value {
        color: #333;
        font-weight: 600;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-action {
        flex: 1;
        padding: 10px;
        border-radius: 8px;
        border: none;
        background: white;
        color: #333;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.2s;
    }

    .btn-action i {
        margin-right: 8px;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-message {
        color: #f37916;
    border: 1px solid #ffc89b;
    }

    .btn-edit {
        color: #e65100;
    border: 1px solid #e65100;
    }

    .btn-security {
        color: #00a854;
        border: 1px solid #00a854;
    }

    @media (max-width: 992px) {
        .user-profile-grid {
            grid-template-columns: 1fr;
        }

        .profile-details-card {
            grid-column: 1;
            grid-row: 2;
        }

        .profile-actions-card {
            grid-column: 1;
        }
    }
</style>

@endsection

@section('content')

    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="ri-home-5-line text-primary"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">User Details</a></li>
                        <li class="breadcrumb-item"><a href="{{ route("admin.users.index") }}">Users</a></li>
                        <li class="breadcrumb-item"><a href="{{ route("admin.users.index") }}">{{ $user->name }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid user-profile-dashboard">
        <!-- Alert Notification -->
        @include("partial.admin.alert")

        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Card with glass morphism effect -->
                    <div class="card user-profile-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="user-badge">
                                <span class="user-type-badge">{{ \Illuminate\Support\Str::title($user->type) }}</span>
                                <h2 class="user-name">{{ $user->name }}</h2>
                            </div>
                            <div class="card-actions">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary active">Overview</button>
                                    <!-- Additional tabs can be added here -->
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="user-profile-grid">
                                <!-- Profile Summary -->
                                <div class="profile-summary-card">
                                    <div class="avatar-placeholder">
                                        <span class="avatar-initials">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                    <div class="user-meta">
                                        <h3>{{ $user->name }}</h3>
                                        <p class="text-muted">{{ $user->email ?? "No email provided" }}</p>
                                        <div class="user-status">
                                            <span
                                                class="status-badge status-{{ $user->status }}">{{ $user->status ?? "unknown" }}</span>
                                            <div class="status-toggle">
                                                @if($user->status == 'active')
                                                    <a href="{{route('admin.users.status.pending', ['user' => $user->id])}}"
                                                        class="btn-status btn-status-pending">
                                                        <i class="fas fa-pause"></i> Set Pending
                                                    </a>
                                                @else
                                                    <a href="{{route('admin.users.status.active', ['user' => $user->id])}}"
                                                        class="btn-status btn-status-active">
                                                        <i class="fas fa-check"></i> Activate
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detailed Information -->
                                <div class="profile-details-card">
                                    <h4 class="details-title">User Details</h4>
                                    <div class="detail-item">
                                        <span class="detail-label">User ID</span>
                                        <span class="detail-value">{{ $user->id }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Account Type</span>
                                        <span class="detail-value">{{ \Illuminate\Support\Str::title($user->type) }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Registration Date</span>
                                        <span
                                            class="detail-value">{{ date('M d, Y \a\t h:i A', strtotime($user->created_at)) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section("scripts")
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ \App\Helper\Methods::staticAsset('panel/assets/plugins/data-tables/js/datatables.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {

            $('#transactionListing').dataTable({
                scrollY: true,          // Enable vertical scrolling
                scrollX: true,          // Enable horizontal scrolling
                scrollCollapse: true,   // Allow scrolling collapse when content is smaller
                paging: true,           // Enable pagination
                autoWidth: false,       // Prevent automatic column width adjustment
                responsive: false,      // Disable responsive behavior if not needed
                ordering: true,         // Enable column ordering
                pageLength: 250,
                lengthMenu: [10, 25, 50, 100, 250, 500, 1000],
                ajax: {
                    url: "{{ route('admin.users.ajax') }}",
                    data: function (d) {
                        { { --d.source = $('#source').val(); --} }
                        { { --d.country = $('#country').val(); --} }
                        { { --d.search_filter = $('#search_filter').val(); --} }
                        { { --d.payment_id = "{{ request()->input('payment_id') ?? '' }}"; --} }
                        { { --d.r_name = "{{ request()->input('r_name') ?? '' }}"; --} }
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'status', name: 'status' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });

        }, false);
    </script>

@endsection
