@extends('layouts.publisher.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('publisherAssets/assets/bundles/izitoast/css/iziToast.min.css') }}">
    <style>
        .card-header {
            background-color: var(--primary-color) !important;
            color: var(--secondary-color) !important;
            border-radius: 10px 10px 0 0 !important;
            font-weight: 600 !important;
        }

        .token-display {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 16px;
            word-break: break-all;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('publisherAssets/assets/bundles/izitoast/js/iziToast.min.js') }}"></script>
    <script>
        function regenerateTokenRequest() {
            $.ajax({
                url: '{{ route("publisher.api-info.regenerate-token") }}',
                type: 'POST',
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                success: function (response) {
                    $("#api_token").val(response.token);

                    iziToast.success({
                        title: 'Success',
                        message: 'API Token Regenerated Successfully.',
                        position: 'topRight',
                        timeout: 3000
                    });
                },
                error: function (response) {
                    showErrors(response); // Assuming this handles displaying errors
                }
            });
        }

    </script>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg collapse-btn">
        <li class="breadcrumb-item mt-1">
            <a href="{{ route('publisher.dashboard') }}"><i data-feather="home"></i></a>
        </li>
        <li class="breadcrumb-item mt-1">
            <a href="#" class="text-sm">Tools</a>
        </li>
        <li class="breadcrumb-item mt-1 active">
            <a href="#" class="text-sm">API Integration</a>
        </li>
    </ol>
@endsection

@section("content")

    <div class="row justify-content-center">
        <div class="col-md-8 col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-key mr-2"></i>API Token Management
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="apiToken" class="text-primary">Your API Token</label>
                        <div class="input-group mb-3">
                            <input type="text"
                                class="form-control token-display @error('api_token') border-danger @enderror"
                                name="api_token" id="api_token" value="{{ auth()->user()->api_token }}" readonly>
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" onclick="clickToCopy()" type="button"
                                    id="copyTokenBtn">
                                    <i class="far fa-copy"></i> Copy
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            Keep this token secure. Do not share it publicly.
                        </small>
                    </div>

                    <div class="d-flex flex-wrap justify-content-between mt-4">
                        <button class="btn btn-primary mb-2" onclick="regenerateTokenRequest()" id="regenerateTokenBtn">
                            <i class="fas fa-sync-alt"></i> Regenerate Token
                        </button>

                        <button class="btn btn-outline-primary mb-2" id="viewDocsBtn">
                            <i class="fas fa-book"></i> View Documentation
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row my-4 justify-content-center">
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
                <a href="javascript:void(0)" onclick="regenerateTokenRequest()" class="btn btn-sm btn-danger">Regenerate
                    Token</a>
                <a href="{{ env(" DOC_APP_URL") . "/api/documentation" }}" target="_blank"
                    class="btn btn-sm btn-warning">View
                    Documentation</a>
            </div>
            <div class="clearfix"></div>
        </div>
    </div> --}}

    </div>
    <script>
        document.getElementById('viewDocsBtn').addEventListener('click', function () {
            window.location.href = "{{ env('DOC_APP_URL') . '/api/documentation' }}";
        });

        function clickToCopy() {
            const apiTokenElement = document.getElementById("api_token");
            const success = copyToClipboard(apiTokenElement);

            if (success) {
                iziToast.success({
                    title: 'Copied',
                    message: 'API Token Successfully Copied.',
                    position: 'topRight',
                    timeout: 3000
                });
            } else {
                iziToast.error({
                    title: 'Error',
                    message: 'Failed to copy API Token.',
                    position: 'topRight'
                });
            }
        }

        function copyToClipboard(element) {
            if (!element) {
                console.error("Element not found.");
                return false;
            }

            let text = "";

            // Handle both input/textarea and regular text elements
            if (element.value !== undefined) {
                text = element.value;
            } else {
                text = element.textContent || element.innerText;
            }

            // Create and append a hidden textarea
            const tempInput = document.createElement("textarea");
            tempInput.style.position = "absolute";
            tempInput.style.left = "-9999px";
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();

            try {
                const successful = document.execCommand("copy");
                document.body.removeChild(tempInput);
                return successful;
            } catch (err) {
                console.error("Copy failed:", err);
                document.body.removeChild(tempInput);
                return false;
            }
        }


    </script>
@endsection
