<!DOCTYPE html>

<html lang="en">
<!--begin::Head-->

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Affilo Tech | @yield('title')</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('publisherAssets/assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('publisherAssets/assets/bundles/bootstrap-social/bootstrap-social.css') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('publisherAssets/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('publisherAssets/assets/css/components.css') }}">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{ asset('publisherAssets/assets/css/custom.css') }}">
    <link rel='shortcut icon' type='image/x-icon' href='{{ asset('publisherAssets/assets/affiloTech.png') }}' />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.21.0/sweetalert2.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/formvalidation/0.6.2-dev/css/formValidation.min.css"/>
</head>
@yield('styles')

<body class="">
    <div class="loader"></div>
    <div id="app">
        @yield('content')
    </div>
</body>

</html>

<!--begin::Javascript-->
<script src="{{ asset('publisherAssets/assets/js/app.min.js') }}"></script>
<script src="{{ asset('publisherAssets/assets/js/scripts.js') }}"></script>
<script src="{{ asset('publisherAssets/assets/js/custom.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
    integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/formvalidation/0.6.2-dev/js/formValidation.min.js"
    integrity="sha512-DlXWqMPKer3hZZMFub5hMTfj9aMQTNDrf0P21WESBefJSwvJguz97HB007VuOEecCApSMf5SY7A7LkQwfGyVfg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!--end::Global Javascript Bundle-->
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@yield('script')
<!--end::Javascript-->
