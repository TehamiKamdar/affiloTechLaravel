<head>
    <title>Dasho - Most Complete Bootstrap Admin Template</title>
    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description"
          content="Dasho Bootstrap admin template made using bootstrap 5 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
    <meta name="keywords"
          content="admin templates, bootstrap admin templates, bootstrap 5, dashboard, dashboard templets, sass admin templets, html admin templates, responsive, bootstrap admin templates free download,premium bootstrap admin templates, Elite Able, Dasho bootstrap admin template">
    <meta name="author" content="Phoenixcoded" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon icon -->
    <link rel="shortcut icon" href="{{ \App\Helper\Methods::staticAsset('assets/media/logos/favicon.png') }}" />
    <!-- fontawesome icon -->
    <link rel="stylesheet" href="{{ \App\Helper\Methods::staticAsset('panel/assets/fonts/fontawesome/css/fontawesome-all.min.css') }}">
    <!-- animation css -->
    <link rel="stylesheet" href="{{ \App\Helper\Methods::staticAsset('panel/assets/plugins/animation/css/animate.min.css') }}">

    <!-- notification css -->
    <link rel="stylesheet" href="{{ \App\Helper\Methods::staticAsset('panel/assets/plugins/notification/css/notification.min.css') }}">

    <!-- vendor css -->
    <link rel="stylesheet" href="{{ \App\Helper\Methods::staticAsset('panel/assets/css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    
    @yield('styles')

</head>
