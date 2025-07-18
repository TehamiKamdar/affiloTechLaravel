<!DOCTYPE html>

<html lang="en">
<!--begin::Head-->

<head>
    <title>Login</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{asset('adminDashboard/assets/img/favicon.png')}}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link rel="stylesheet" href="{{asset('adminDashboard/assets/remixicons-fonts/remixicon.css')}}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link id="pagestyle" href="{{asset('adminDashboard/assets/css/soft-ui-dashboard.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.21.0/sweetalert2.css" integrity="sha512-YaSmLVC5r74Cbow8ck88y37TwiYOggPG3J75sPIaj9hwQZ0UOaXrtj11tv96aeHhSgHiS++yAuER4FiqoVh3/w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/formvalidation/0.6.2-dev/css/formValidation.min.css" integrity="sha512-B9GRVQaYJ7aMZO3WC2UvS9xds1D+gWQoNiXiZYRlqIVszL073pHXi0pxWxVycBk0fnacKIE3UHuWfSeETDCe7w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="">

    <main class="main-content  mt-0">
        @yield('content')

    </main>
</body>
</html>

<!--begin::Javascript-->
<script src="{{ asset('adminDashboard/assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('adminDashboard/assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('adminDashboard/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('adminDashboard/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('adminDashboard/assets/js/soft-ui-dashboard.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/formvalidation/0.6.2-dev/js/formValidation.min.js" integrity="sha512-DlXWqMPKer3hZZMFub5hMTfj9aMQTNDrf0P21WESBefJSwvJguz97HB007VuOEecCApSMf5SY7A7LkQwfGyVfg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
