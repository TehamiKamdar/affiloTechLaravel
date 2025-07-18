@extends('layouts.publisher.layout')

@section('content')
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('publisher.dashboard') }}"><i
                                            class="ri-home-5-line text-primary"></i></a></li>
                                <li class="breadcrumb-item"><a href="">Tools</a></li>
                                <li class="breadcrumb-item"><a href="">Link Generator</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            @include("partial.alert")

            @include("publisher.widgets.deeplink")
<script>
    function copyLink(link){
        let text = link;
        let message = 'Link Copied Successfully!'
         var tempInput = document.createElement("textarea");
    tempInput.value = text;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);

    // Display success message (optional)
    if (message) {
         normalMsg({"message": message, "success": true});
    }
    }
</script>
@endsection
