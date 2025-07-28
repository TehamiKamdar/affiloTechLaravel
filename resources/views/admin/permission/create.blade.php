@extends("layouts.admin.layout")

@section("styles")

    <!-- data tables css -->
    <link rel="stylesheet"
        href="{{ asset('adminDashboard/assets/plugins/data-tables/css/datatables.min.css') }}">

    <style>
        table td:last-child {
            padding: 0.65rem 0.75rem !important;
        }

        table td:last-child .btn {
            margin-right: 0 !important;
        }
    </style>

@endsection

@section('content')
<div class="contents">

    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="page-header">
                    <div class="page-block">
                        <div class="row align-items-center">
                            <div class="col-md-12">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="ri-home-5-line text-primary"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route("admin.permissions.index") }}">Permissions</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Create Permission</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">

                            @include("partial.admin.alert")

                            <form action="{{ route("admin.permissions.store") }}" method="POST"
                                enctype="multipart/form-data" id="userForm">
                                @csrf
                                <input type="text" placeholder="Enter Permission" class="form-control" name="name">
                                <div style="margin-top:20px;">
                                    <input class="btn btn-sm btn-primary" type="submit" value="Save">
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@pushonce('scripts')
<script src="{{ asset("adminDashboard/assets/js/plugins/jquery.validate.min.js") }}"></script>
<script>

    $(document).ready(function () {
        $("#userForm").validate({
            rules: {
                password: {
                    minlength: 5,
                },
                confirm_password: {
                    minlength: 5,
                    equalTo: "#password"
                }
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) { // un-hightlight error inputs
                $(element)
                    .closest('.form-group').removeClass('has-error');
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element.closest('.input-modal-group'));
            }
        });
    });
</script>
@endpushonce
@endsection
