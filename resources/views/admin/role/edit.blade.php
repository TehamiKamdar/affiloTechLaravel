@extends("layouts.admin.layout")

@section('breadcrumb')
    <ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg collapse-btn">
        <li class="breadcrumb-item mt-1">
            <a href="{{ route('publisher.dashboard') }}"><i data-feather="home"></i></a>
        </li>
        <li class="breadcrumb-item mt-1">
            <a href="#" class="text-sm">Manage</a>
        </li>
        <li class="breadcrumb-item mt-1">
            <a href="#" class="text-sm">Roles</a>
        </li>
        <li class="breadcrumb-item mt-1 active">
            <a href="#" class="text-sm">Edit Role</a>
        </li>
    </ol>
@endsection

@section("styles")

    <!-- data tables css -->
    <link rel="stylesheet" href="{{ asset('adminDashboard/assets/plugins/data-tables/css/datatables.min.css') }}">

    <style>
        table td:last-child {
            padding: 0.65rem 0.75rem !important;
        }

        table td:last-child .btn {
            margin-right: 0 !important;
        }

        select option {
            background-color: white;
            color: var(--primary-color);
            font-weight: 500;
            border-bottom: 1px solid #f0f2fc;
        }

        option:hover {
            background-color: var(--primary-very-light) !important;
        }

        select option:checked,
        select option:active {
            background-color: var(--primary-very-light) !important;
            color: var(--primary-color);
        }


        .form-control {
            border: 2px solid #e0e3ed;
            color: var(--primary-color);
            font-weight: 500;
            border-radius: 8px !important;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--primary-color);
        }
    </style>

@endsection

@section('content')
    <div class="contents">
        <div class="container-fluid">
            <div class="social-dash-wrap">
                <div class="row mb-5">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">

                                @include("partial.admin.alert")

                                <form action="{{ route("admin.roles.updates") }}" method="POST"
                                    enctype="multipart/form-data" id="userForm">
                                    @csrf
                                    <input type="hidden" placeholder="Enter Permission" class="form-control" name="id"
                                        value="{{$role->id}}">
                                    <label for="name"
                                        style="font-size: 16px;font-weight: 900;margin-top: 8px;">Title</label>
                                    <input type="text" placeholder="Enter Role Name" class="form-control" name="name"
                                        value="{{$role->name}}">
                                    <label for="name" style="font-size: 16px;font-weight: 900;margin-top: 8px;">Select
                                        Permission</label>

                                    <select id="permissions" name="permission[]" class="form-control" multiple>
                                        @foreach ($permission as $p)
                                            <option value="{{$p->id}}" {{ in_array($p->id, $rolePermissions) ? 'selected' : '' }}>{{$p->name}}</option>
                                        @endforeach
                                    </select>
                                    <div style="margin-top:20px;">
                                        <input class="btn btn-primary" type="submit" value="Save">
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
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

@endsection
