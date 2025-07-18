@extends('layouts.admin.layout')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // Cache the AJAX URL with the status title
            const ajaxUrl = '{{ route('admin.publishers.ajax') }}' + '?status={{ $title }}';

            // Initialize DataTable with the cached URL
            $('#publisherListing').DataTable({
                processing: true,
                serverSide: true,
                ajax: ajaxUrl,
                pageLength: 250,
                lengthMenu: [10, 25, 50, 100, 250, 500, 1000],
                columns: [
                    { data: 'created_at', name: 'created_at', width: '25%' },
                    { data: 'name', name: 'name', width: '20%' },
                    { data: 'email', name: 'email', width: '25%' },
                    { data: 'status', name: 'status', width: '10%', className: 'text-center' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, width: '20%', className: 'text-center' }
                ],
                initComplete: function() {
                    $(this).addClass('table table-row-bordered table-row-dashed gy-4 align-middle fw-bold');
                }
            });
        });
        function goToLogin(id)
        {
            Swal.fire({
                title: 'Access Publisher Account',
                text: "If you access publisher account. Then Admin account will be logout. Do you want to access?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Login'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Please Wait!',
                        text: "Your publisher account will be access is few minutes.",
                        showConfirmButton: false,
                    });
                    window.location.href = `{{ url('/') }}/admin/publishers/access-login/${id}`;
                }
            });
        }
    </script>
@endsection

@section('content')
    <div class="d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title flex-column">
                            <h3 class="fw-bold mb-1">{{ $title }} Publishers</h3>
                        </div>
                        <div class="card-toolbar my-1">
                            <!-- You can add select filters or other controls here if needed -->
                        </div>
                    </div>
                    <div class="card-body pt-0">

                        @include('partial.admin.alert')

                        <div class="table-responsive">
                            <table id="publisherListing" class="table">
                                <thead class="fs-7 text-uppercase">
                                <tr>
                                    <th>Created At</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th class="text-end">Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
