@if($errors->any())

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="alert-content">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>

@elseif(Session::has('success'))

    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <div class="alert-content">
            <p>{{ Session::get('success') }}</p>
        </div>
    </div>

    @php
        Session::forget('success');
    @endphp

@elseif(Session::has('status'))

    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <div class="alert-content">
            <p>{{ Session::get('status') }}</p>
        </div>
    </div>

    @php
        Session::forget('success');
    @endphp

@elseif(Session::has('high_priority_error'))

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="alert-content">
            <p>{{ Session::get('high_priority_error') }}</p>
        </div>
    </div>

    @php
        Session::forget('high_priority_error');
    @endphp

@elseif(Session::has('error'))

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="alert-content">
            <p>{!! Session::get('error') !!}</p>
        </div>
    </div>

    @php
        Session::forget('error');
    @endphp

@endif
