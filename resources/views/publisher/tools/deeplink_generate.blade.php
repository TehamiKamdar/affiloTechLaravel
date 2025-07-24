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
    .card-header h4 {
        color: white !important;
    }
  .form-group {
            margin-bottom: 1.75rem;
        }

        label {
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
        }

        /* Modern Select Dropdown */
        .custom-select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            height: 50px;
            padding: 0.75rem 1.25rem;
            border: 2px solid #e0e3ed;
            border-radius: 8px;
            background-color: white;
            color: var(--primary-color);
            font-weight: 500;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2300a9da' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1.25rem center;
            background-size: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .custom-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(103, 119, 227, 0.2);
            outline: none;
        }

        select option {
            padding: 12px 16px;
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

        /* Input styles */
        .input-group-text {
            background-color: white;
            border: 2px solid #e0e3ed;
            border-right: none;
            color: var(--primary-color);
            padding: 0 1.25rem;
            border-radius: 8px 0 0 8px !important;
        }


        .form-control {
            height: 50px;
            border: 2px solid #e0e3ed;
            border-left: none;
            padding: 0.75rem 1.25rem;
            color: var(--primary-color);
            font-weight: 500;
            border-radius: 0 8px 8px 0 !important;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--primary-color);
        }
</style>
@endsection

@section('scripts')
<script src="{{ asset('publisherAssets/assets/bundles/izitoast/js/iziToast.min.js') }}"></script>
@endsection

@section('breadcrumb')
<ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg collapse-btn">
    <li class="breadcrumb-item mt-1">
        <a href="#"><i data-feather="home"></i></a>
    </li>
    <li class="breadcrumb-item mt-1">
        <a href="#" class="text-sm">Tools</a>
    </li>
    <li class="breadcrumb-item mt-1 active">
        <a href="#" class="text-sm">Link Builder</a>
    </li>
</ol>
@endsection

@section('content')

@include("partial.alert")

<div class="col-8 mx-auto">
    @include("publisher.widgets.deeplink")
</div>
{{-- <script>
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
</script> --}}
@endsection
