@if($errors->any())

    <div class="alert alert-primary alert-dismissible fade show" role="alert">
        <h6 class="alert-heading"><strong>Whoops!</strong> There were some problems with your input.</h6>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li class="text-sm">{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-sm btn-close bg-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@elseif(Session::has('success'))

    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <h4 class="alert-heading">Process Completed!</h4>
        <div>{{ Session::get('success') }}</div>
        <button type="button" class="btn-sm btn-close bg-white text-black" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    @php
        \Illuminate\Support\Facades\Session::forget('success');
    @endphp

@elseif(Session::has('status'))

    <div class="alert alert-success bg-light-success border border-success border-dashed alert-dismissible fade show" role="alert">
        {!! Session::get('status') !!}
        <button type="button" class="btn-sm btn-close bg-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    @php
        \Illuminate\Support\Facades\Session::forget('success');
    @endphp

@elseif(Session::has('high_priority_error'))

    <div class="notice d-flex bg-light-danger rounded border-danger border border-dashed min-w-lg-600px flex-shrink-0 p-6 mb-3">
        <!--begin::Wrapper-->
        <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
            <!--begin::Content-->
            <div class="mb-3 mb-md-0 fw-semibold">
                <h4 class="text-gray-900 fw-bold">Error!</h4>
                <div class="fs-6 text-gray-700 pe-7">{!! \Illuminate\Support\Facades\Session::get('high_priority_error') !!}</div>
            </div>
            <!--end::Content-->
        </div>
        <!--end::Wrapper-->
    </div>

    @php
        \Illuminate\Support\Facades\Session::forget('high_priority_error');
    @endphp

@elseif(Session::has('warning'))

        <div class="notice alert border-warning" style="background-color: #fff0c2;border-style: dashed; padding: 15px; border-width: 2px;">
            <h6 class="">Warning!</h6>
            <p class="text-sm">{!! \Illuminate\Support\Facades\Session::get('warning') !!}</p>
        </div>
    @php
        \Illuminate\Support\Facades\Session::forget('warning');
    @endphp

@elseif(Session::has('error'))


    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h4 class="alert-heading">Error!</h4>
        <div>{!! \Illuminate\Support\Facades\Session::get('error') !!}</div>
        <button type="button" class="btn-sm btn-close bg-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    @php
        \Illuminate\Support\Facades\Session::forget('error');
    @endphp

@endif
