@if($errors->any())

    <div class="alert alert-danger overflow-hidden p-0" role="alert">
        <div class="p-3 bg-danger text-fixed-white d-flex justify-content-between">
            <h6 class="aletr-heading mb-0 text-fixed-white"><strong class="text-fixed-black">Whoops!</strong> There were some problems with your input.</h6>
            <button type="button" class="btn-close p-0 text-fixed-white" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
        </div>
        <hr class="my-0">
        <div class="p-3">
            @foreach ($errors->all() as $error)
                <p class="mb-0">{{ $error }}</p>
            @endforeach
        </div>
    </div>

@elseif(Session::has('success'))

    <div class="alert alert-success overflow-hidden p-0" role="alert">
        <div class="p-3 bg-success text-fixed-white d-flex justify-content-between">
            <h6 class="aletr-heading mb-0 text-fixed-white"><strong class="text-fixed-black">Process Completed!</strong></h6>
            <button type="button" class="btn-close p-0 text-fixed-white" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
        </div>
        <hr class="my-0">
        <div class="p-3">
            <p class="mb-0">{{ Session::get('success') }}</p>
        </div>
    </div>

    @php
        \Illuminate\Support\Facades\Session::forget('success');
    @endphp

@elseif(Session::has('status'))+

<div class="alert alert-solid-success alert-dismissible fs-15 fade show mb-4">
    {!! Session::get('status') !!}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
</div>

@php
    \Illuminate\Support\Facades\Session::forget('success');
@endphp

@elseif(Session::has('high_priority_error'))

    <div class="alert alert-danger overflow-hidden p-0" role="alert">
        <div class="p-3 bg-danger text-fixed-white d-flex justify-content-between">
            <h6 class="aletr-heading mb-0 text-fixed-white"><strong class="text-fixed-black">Error!</strong></h6>
            <button type="button" class="btn-close p-0 text-fixed-white" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
        </div>
        <hr class="my-0">
        <div class="p-3">
            <p class="mb-0">{!! \Illuminate\Support\Facades\Session::get('high_priority_error') !!}</p>
        </div>
    </div>

    @php
        \Illuminate\Support\Facades\Session::forget('high_priority_error');
    @endphp

@elseif(Session::has('warning'))

    <div class="alert alert-warning overflow-hidden p-0" role="alert">
        <div class="p-3 bg-warning text-fixed-white d-flex justify-content-between">
            <h6 class="aletr-heading mb-0 text-fixed-white"><strong class="text-fixed-black">Warning!</strong></h6>
            <button type="button" class="btn-close p-0 text-fixed-white" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
        </div>
        <hr class="my-0">
        <div class="p-3">
            <p class="mb-0">{!! \Illuminate\Support\Facades\Session::get('warning') !!}</p>
        </div>
    </div>

    @php
        \Illuminate\Support\Facades\Session::forget('warning');
    @endphp

@elseif(Session::has('error'))

    <div class="alert alert-danger overflow-hidden p-0" role="alert">
        <div class="p-3 bg-danger text-fixed-white d-flex justify-content-between">
            <h6 class="aletr-heading mb-0 text-fixed-white"><strong class="text-fixed-black">Error!</strong></h6>
            <button type="button" class="btn-close p-0 text-fixed-white" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
        </div>
        <hr class="my-0">
        <div class="p-3">
            <p class="mb-0">{!! \Illuminate\Support\Facades\Session::get('error') !!}</p>
        </div>
    </div>

    @php
        \Illuminate\Support\Facades\Session::forget('error');
    @endphp

@endif
