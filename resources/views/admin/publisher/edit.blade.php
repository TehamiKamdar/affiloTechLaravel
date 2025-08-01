@extends("layouts.admin.layout")

@section('breadcrumb')
<ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg collapse-btn">
    <li class="breadcrumb-item mt-1">
        <a href="{{ route('publisher.dashboard') }}"><i data-feather="home"></i></a>
    </li>
    <li class="breadcrumb-item mt-1">
        <a href="#" class="text-sm">Publishers</a>
    </li>
    <li class="breadcrumb-item mt-1 active">
        <a href="#" class="text-sm">Edit Publishers</a>
    </li>
</ol>
@endsection

@section("styles")
<style>
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

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- Base style - Hover table start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Edit - Publisher ( {{ $publisher->name }} )</h5>
                </div>
                <div class="card-body">

                    <form id="edit-publisher" autocomplete="off" action="{{ route('admin.publishers.update', ['publisher' => $publisher->id]) }}"  method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control"
                                           name="name"
                                           placeholder="Enter Name" value="{{ $publisher->name }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Email Address</label>
                                    <input type="text" class="form-control" name="email"
                                           placeholder="Enter Email Address" value="{{ $publisher->email }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control"
                                           name="password" placeholder="Enter Password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Confirm password</label>
                                    <input type="password" class="form-control"
                                           name="password_confirmation"
                                           placeholder="Confirm Confirm Password">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>

                    </form>

                </div>
            </div>
        </div>
        <!-- Base style - Hover table end -->
    </div>

@endsection

@section("scripts")

    <!-- jquery-validation Js -->
    <script src="{{ asset('adminDashboard/assets/js/plugins/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('adminDashboard/assets/js/edit-publisher.js') }}"></script>

@endsection
