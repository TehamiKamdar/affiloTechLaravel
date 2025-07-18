@extends("layouts.admin.layout")

@section("styles")

@endsection

@section('content')

    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5>Edit Publisher ( {{ $publisher->name }} )</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route("admin.publishers.status", ['status' => $publisher->status]) }}">Publishers</a></li>
                        <li class="breadcrumb-item"><a href="">Edit Publisher</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

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

                        <button type="submit" class="btn btn-sm btn-gradient-primary">Submit</button>

                    </form>

                </div>
            </div>
        </div>
        <!-- Base style - Hover table end -->
    </div>

@endsection

@section("scripts")

    <!-- jquery-validation Js -->
    <script src="{{ \App\Helper\Methods::staticAsset('panel/assets/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script src="{{ \App\Helper\Methods::staticAsset('admin/assets/js/validation/edit-publisher.js') }}"></script>

@endsection
