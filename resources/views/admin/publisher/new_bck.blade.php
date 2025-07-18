@extends("layouts.admin.layout")

@section('styles')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">

@endsection

@section('content')


    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <nav>
            <ol class="breadcrumb mb-0 align-items-center">
                <li class="breadcrumb-item"><a href="javascript:void(0);" class="text-dark h4">Publisher</a></li>
                <li class="breadcrumb-item active text-muted fs-13 fw-normal" aria-current="page">{{ $title }} Publishers</li>
            </ol>
        </nav>
    </div>
    <!-- Page Header Close -->

    @include('partial.admin.alert')

    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        {{ $title }} Publishers
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="publisherListing" class="table table-bordered text-nowrap w-100">
                            <thead>
                            <tr>
                                <th>Created At</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th class="text-end">Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--End::row-1 -->

@endsection

@section('scripts')

    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

    <!-- Datatables Cdn -->
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.6/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <!-- Internal Datatables JS -->
    <script src="{{ \App\Helper\Methods::staticAsset('panel/assets/js/datatables.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // Cache the AJAX URL with the status title
            const ajaxUrl = '{{ route('admin.publishers.ajax') }}' + '?status={{ $title }}';

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
                pagingType: 'simple_numbers',
                drawCallback: function(settings) {
                    let pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                    let totalPages = Math.ceil(settings.fnRecordsDisplay() / settings._iDisplayLength);
                    let currentPage = Math.floor(settings._iDisplayStart / settings._iDisplayLength) + 1;

                    let paginationHtml = `
                    <nav aria-label="Page navigation" class="pagination-style-4">
                        <ul class="pagination mb-0 flex-wrap">
                            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                                <a class="page-link" href="#" data-dt-page="prev"><i class="ri-arrow-left-double-line"></i></a>
                            </li>`;

                    for (let i = 1; i <= totalPages; i++) {
                        paginationHtml += `
                                                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                                                        <a class="page-link" href="#" data-dt-page="${i}">${i}</a>
                                                    </li>
                                                  `;
                    }

                    paginationHtml += `
                            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                                <a class="page-link" href="#" data-dt-page="next"><i class="ri-arrow-right-double-line"></a>
                            </li>
                        </ul>
                    </nav>`;

                    pagination.html(paginationHtml);
                }
            });

            // Handle custom pagination clicks
            $(document).on('click', '.pagination .page-link', function(e) {
                e.preventDefault();
                let table = $('#publisherListing').DataTable();
                let action = $(this).data('dt-page');
                let currentPage = table.page.info().page;

                if (action === 'prev') {
                    table.page('previous').draw('page');
                } else if (action === 'next') {
                    table.page('next').draw('page');
                } else if (typeof action === 'number') {
                    table.page(action - 1).draw('page');
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
