@extends('layouts.publisher.layout')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet" />

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
<script>
    function regenerateTokenRequest() {
        $.ajax({
            url: '{{ route("publisher.api-info.regenerate-token") }}',
            type: 'POST',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                $("#api_token").val(response.token);
            },
            error: function (response) {
                showErrors(response);
            }
        });
    }
</script>
@endsection

@section("content")
    <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('publisher.dashboard') }}"><i
                                            class="ri-home-5-line text-primary"></i></a></li>
                                <li class="breadcrumb-item"><a href="">Tools</a></li>
                                <li class="breadcrumb-item"><a href="">API Integration</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
    <div class="card">
        <div class="row my-4 justify-content-center">
            <div class="col-8">
                <div class="card-header pb-2">
                    <h3 class="mb-3 text-capitalize">API Token <small>(This will be used for API
                            documentation.)</small></h3>
                    <input type="text" class="form-control @error('api_token') border-danger @enderror" id="api_token"
                        name="api_token" placeholder="" value="{{ auth()->user()->api_token }}"
                        style="padding-left: 18px;font-weight: 900;">
                </div>
                <div class="col-xxl-12 col-lg-12 col-sm-12 m-top-40" style="margin: 20px 5%;">
                    <a href="javascript:void(0)" onclick="clickToCopy()" id="copyToken" class="btn btn-sm btn-success">Copy</a>
                    <a href="javascript:void(0)" onclick="regenerateTokenRequest()" class="btn btn-sm btn-danger">Regenerate Token</a>
                    <a href="{{ env("DOC_APP_URL") . "/api/documentation" }}" target="_blank" class="btn btn-sm btn-warning">View
                        Documentation</a>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    </div>
    <script>
        function clickToCopy() {
            const apiTokenElement = document.getElementById("api_token");
            copyToClipboard(apiTokenElement);

            normalMsg({ message: "API Token Successfully Copied.", success: true });
        }

        function copyToClipboard(element) {
            if (!element) {
                console.error("Element not found.");
                return;
            }

            let text = "";

            // Determine the content type (input/textarea vs div/span)
            if (element.value !== undefined) {
                text = element.value;
            } else {
                text = element.textContent || element.innerText;
            }

            // Create a temporary textarea to copy the content
            const tempInput = document.createElement("textarea");
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
        }

        function normalMsg(response) {
            if (response.success) {
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        }

    </script>
@endsection
