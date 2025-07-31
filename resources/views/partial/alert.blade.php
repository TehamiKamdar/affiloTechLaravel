@if($errors->any())
    <div class="alert alert-primary alert-dismissible fade show d-flex justify-content-between align-items-start" role="alert">
        <div>
            <h6 class="alert-heading"><strong>Whoops!</strong> There were some problems with your input.</h6>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close btn-danger ml-3 mt-1" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@elseif(Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex justify-content-between align-items-start" role="alert">
        <div>
            <h4 class="alert-heading">Process Completed!</h4>
            <div>{{ Session::get('success') }}</div>
        </div>
        <button type="button" class="btn-close btn-danger ml-3 mt-1" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @php Session::forget('success'); @endphp

@elseif(Session::has('status'))
    <div class="alert alert-success border border-success alert-dismissible fade show d-flex justify-content-between align-items-start" role="alert" style="border-style: dashed;">
        <div>{!! Session::get('status') !!}</div>
        <button type="button" class="btn-close btn-danger ml-3 mt-1" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @php Session::forget('success'); @endphp

@elseif(Session::has('high_priority_error'))
    <div class="alert alert-danger border border-danger d-flex justify-content-between align-items-start" style="border-style: dashed;" role="alert">
        <div>
            <h4 class="text-danger">Error!</h4>
            <div class="text-dark">{!! Session::get('high_priority_error') !!}</div>
        </div>
    </div>
    @php Session::forget('high_priority_error'); @endphp

@elseif(Session::has('warning'))
    <div class="alert alert-warning d-flex justify-content-between align-items-start" role="alert" style="background-color: #fff0c2; border-style: dashed; border-width: 2px;">
        <div>
            <h6>Warning!</h6>
            <p class="text-sm mb-0">{!! Session::get('warning') !!}</p>
        </div>
    </div>
    @php Session::forget('warning'); @endphp

@elseif(Session::has('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex justify-content-between align-items-start" role="alert">
        <div>
            <h4 class="alert-heading">Error!</h4>
            <div>{!! Session::get('error') !!}</div>
        </div>
        <button type="button" class="btn-close btn-danger ml-3 mt-1" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @php Session::forget('error'); @endphp
@endif
