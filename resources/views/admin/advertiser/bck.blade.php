@extends("layouts.admin.layout")

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#advertiserListing').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.advertisers.api.ajax') }}?status={{ $title }}',
                pageLength: 250,
                lengthMenu: [10, 25, 50, 100, 250, 500, 1000],
                columns: [
                    { data: 'id', name: 'id', width: '20%' },
                    { data: 'name', name: 'name', width: '20%' },
                    { data: 'url', name: 'url', width: '20%' },
                    { data: 'source', name: 'source', width: '10%', className: 'text-center' },
                    { data: 'is_tracking_url', name: 'is_tracking_url', width: '10%', className: 'text-center' },
                    { data: 'provider_status', name: 'provider_status', width: '10%', className: 'text-center' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, width: '20%', className: 'text-center' }
                ],
                initComplete: function() {
                    $(this).addClass('table table-row-bordered table-row-dashed gy-4 align-middle fw-bold');
                }
            });
        });
    </script>
@endsection

@section("content")
    <div class="d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title flex-column">
                            <h3 class="fw-bold mb-1">{{ $title }}</h3>
                        </div>
                        <div class="card-toolbar my-1">
                            <!-- Add any additional toolbar elements here -->
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table id="advertiserListing" class="table table-row-bordered table-row-dashed gy-4 align-middle fw-bold">
                                <thead class="fs-7 text-uppercase">
                                <tr>
                                    <th>Advert. ID</th>
                                    <th>Name</th>
                                    <th>URL</th>
                                    <th class="text-center">Source</th>
                                    <th class="text-center">Is Tracking URL</th>
                                    <th class="text-center">Provider Status</th>
                                    <th>Action</th>
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
