<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl fixed-start" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route("admin.dashboard") }}" style="text-align: center;">
            <img src="{{asset('publisherAssets/assets/affiloTechLogo.png')}}" class="navbar-brand-img h-100" style="width:60%"
                alt="main_logo">
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route("admin.dashboard") }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>shop </title>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                    <g transform="translate(1716.000000, 291.000000)">
                                        <g transform="translate(0.000000, 148.000000)">
                                            <path class="color-background opacity-6"
                                                d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z">
                                            </path>
                                            <path class="color-background"
                                                d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z">
                                            </path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            <!-- Publisher Management -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Publishers Management</h6>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed {{ Route::is('admin.publishers.*') ? 'active' : '' }}"
                    data-bs-toggle="collapse" href="#publishersMenu" role="button" aria-expanded="false"
                    aria-controls="publishersMenu">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 42 42" xmlns="http://www.w3.org/2000/svg">
                            <g fill="#FFFFFF" fill-rule="nonzero">
                                <path class="color-background opacity-6"
                                    d="M12.25,17.5 L8.75,17.5 L8.75,1.75 C8.75,0.78225 9.53225,0 10.5,0 L31.5,0 C32.46775,0 33.25,0.78225 33.25,1.75 L33.25,12.25 L29.75,12.25 L29.75,3.5 L12.25,3.5 L12.25,17.5 Z">
                                </path>
                                <path class="color-background"
                                    d="M40.25,14 L24.5,14 C23.53225,14 22.75,14.78225 22.75,15.75 L22.75,38.5 L19.25,38.5 L19.25,22.75 C19.25,21.78225 18.46775,21 17.5,21 L1.75,21 C0.78225,21 0,21.78225 0,22.75 L0,40.25 C0,41.21775 0.78225,42 1.75,42 L40.25,42 C41.21775,42 42,41.21775 42,40.25 L42,15.75 C42,14.78225 41.21775,14 40.25,14 Z M12.25,36.75 L7,36.75 L7,33.25 L12.25,33.25 L12.25,36.75 Z M12.25,29.75 L7,29.75 L7,26.25 L12.25,26.25 L12.25,29.75 Z M35,36.75 L29.75,36.75 L29.75,33.25 L35,33.25 L35,36.75 Z M35,29.75 L29.75,29.75 L29.75,26.25 L35,26.25 L35,29.75 Z M35,22.75 L29.75,22.75 L29.75,19.25 L35,19.25 L35,22.75 Z">
                                </path>
                            </g>
                        </svg>


                    </div>
                    <span class="nav-link-text ms-1">Publishers</span>
                </a>
                <div class="collapse {{ Route::is('admin.publishers.*') ? 'show' : '' }}" id="publishersMenu">
                    <ul class="nav ms-4 ps-3">
                        <li class="nav-item {{ Route::is('admin.publishers.status') && request()->route('status') == \App\Models\User::STATUS_PENDING ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.publishers.status', ['status' => \App\Models\User::STATUS_PENDING]) }}">
                                <span class="sidenav-normal"> Pending </span>
                            </a>
                        </li>
                        <!-- Repeat for other statuses -->
                        <li class="nav-item {{ Route::is('admin.publishers.status') && request()->route('status') == \App\Models\User::STATUS_HOLD ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route("admin.publishers.status", ['status' => \App\Models\User::STATUS_HOLD]) }}">
                                <span class="sidenav-normal"> Hold </span>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::is('admin.publishers.status') && request()->route('status') == \App\Models\User::STATUS_ACTIVE ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route("admin.publishers.status", ['status' => \App\Models\User::STATUS_ACTIVE]) }}">
                                <span class="sidenav-normal"> Active </span>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::is('admin.publishers.status') && request()->route('status') == \App\Models\User::STATUS_REJECT ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route("admin.publishers.status", ['status' => \App\Models\User::STATUS_REJECT]) }}">
                                <span class="sidenav-normal"> Rejected </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Advertisers Management -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Advertisers Management</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed {{ Route::is('admin.advertisers.api') ? 'active' : '' }}" data-bs-toggle="collapse" href="#advertiserMenu" role="button"
                    aria-expanded="false" aria-controls="advertiserMenu">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 42 42" xmlns="http://www.w3.org/2000/svg">
                            <g fill="#FFFFFF" fill-rule="nonzero">
                                <path class="color-background opacity-6"
                                    d="M12.25,17.5 L8.75,17.5 L8.75,1.75 C8.75,0.78225 9.53225,0 10.5,0 L31.5,0 C32.46775,0 33.25,0.78225 33.25,1.75 L33.25,12.25 L29.75,12.25 L29.75,3.5 L12.25,3.5 L12.25,17.5 Z">
                                </path>
                                <path class="color-background"
                                    d="M40.25,14 L24.5,14 C23.53225,14 22.75,14.78225 22.75,15.75 L22.75,38.5 L19.25,38.5 L19.25,22.75 C19.25,21.78225 18.46775,21 17.5,21 L1.75,21 C0.78225,21 0,21.78225 0,22.75 L0,40.25 C0,41.21775 0.78225,42 1.75,42 L40.25,42 C41.21775,42 42,41.21775 42,40.25 L42,15.75 C42,14.78225 41.21775,14 40.25,14 Z M12.25,36.75 L7,36.75 L7,33.25 L12.25,33.25 L12.25,36.75 Z M12.25,29.75 L7,29.75 L7,26.25 L12.25,26.25 L12.25,29.75 Z M35,36.75 L29.75,36.75 L29.75,33.25 L35,33.25 L35,36.75 Z M35,29.75 L29.75,29.75 L29.75,26.25 L35,26.25 L35,29.75 Z M35,22.75 L29.75,22.75 L29.75,19.25 L35,19.25 L35,22.75 Z">
                                </path>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Advertisers</span>
                </a>
                <div class="collapse {{ Route::is('admin.advertisers.api') ? 'show' : '' }}" id="advertiserMenu">
                    <ul class="nav ms-4 ps-3">
                        <li class="nav-item {{ Route::is('admin.advertisers.api') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route("admin.advertisers.api") }}">

                                <span class="sidenav-normal"> API Advertisers </span>
                            </a>
                        </li>
                          <li class="nav-item {{ Route::is('admin.advertisers.api.show_on_publisher.index') ? 'active' : '' }}">
                            <a href="{{ route("admin.advertisers.api.show_on_publisher.index") }}" class="nav-link"><span class="sidenav-normal">API Advertisers Show</span></a>
                        </li>
                        <li class="nav-item {{ Route::is('admin.advertisers.api.show_on_publisher.duplicate_record') ? 'active' : '' }}">
                            <a href="{{ route("admin.advertisers.api.show_on_publisher.duplicate_record") }}" class="nav-link"><span class="sidenav-normal">Duplicate Advertisers</span></a>
                        </li>

                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed {{ Route::is('admin.advertisers.approval.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#approvalMenu" role="button"
                    aria-expanded="false" aria-controls="approvalMenu">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 42 42" xmlns="http://www.w3.org/2000/svg">
                            <g fill="#FFFFFF" fill-rule="nonzero">
                                <path class="color-background opacity-6"
                                    d="M12.25,17.5 L8.75,17.5 L8.75,1.75 C8.75,0.78225 9.53225,0 10.5,0 L31.5,0 C32.46775,0 33.25,0.78225 33.25,1.75 L33.25,12.25 L29.75,12.25 L29.75,3.5 L12.25,3.5 L12.25,17.5 Z">
                                </path>
                                <path class="color-background"
                                    d="M40.25,14 L24.5,14 C23.53225,14 22.75,14.78225 22.75,15.75 L22.75,38.5 L19.25,38.5 L19.25,22.75 C19.25,21.78225 18.46775,21 17.5,21 L1.75,21 C0.78225,21 0,21.78225 0,22.75 L0,40.25 C0,41.21775 0.78225,42 1.75,42 L40.25,42 C41.21775,42 42,41.21775 42,40.25 L42,15.75 C42,14.78225 41.21775,14 40.25,14 Z M12.25,36.75 L7,36.75 L7,33.25 L12.25,33.25 L12.25,36.75 Z M12.25,29.75 L7,29.75 L7,26.25 L12.25,26.25 L12.25,29.75 Z M35,36.75 L29.75,36.75 L29.75,33.25 L35,33.25 L35,36.75 Z M35,29.75 L29.75,29.75 L29.75,26.25 L35,26.25 L35,29.75 Z M35,22.75 L29.75,22.75 L29.75,19.25 L35,19.25 L35,22.75 Z">
                                </path>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Approval</span>
                </a>
                <div class="collapse {{ Route::is('admin.advertisers.approval.*') ? 'show' : '' }}" id="approvalMenu">
                    <ul class="nav ms-4 ps-3">
                        <li class="nav-item {{ Route::is('admin.advertisers.approval.status') && request()->route('status') == \App\Models\AdvertiserPublisher::STATUS_PENDING ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route("admin.advertisers.approval.status", ['status' => 'pending']) }}">

                                <span class="sidenav-normal"> Pending </span>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::is('admin.advertisers.approval.status') && request()->route('status') == \App\Models\AdvertiserPublisher::STATUS_ACTIVE ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route("admin.advertisers.approval.status", ['status' => 'joined']) }}">

                                <span class="sidenav-normal"> Joined </span>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::is('admin.advertisers.approval.status') && request()->route('status') == \App\Models\AdvertiserPublisher::STATUS_HOLD ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route("admin.advertisers.approval.status", ['status' => 'hold']) }}">

                                <span class="sidenav-normal"> Hold </span>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::is('admin.advertisers.approval.status') && request()->route('status') == \App\Models\AdvertiserPublisher::STATUS_REJECTED ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route("admin.advertisers.approval.status", ['status' => 'rejected']) }}">

                                <span class="sidenav-normal"> Rejected </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>


            <!-- Creatives -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Creatives</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed {{Route::is('admin.creatives.*') ? 'active' : ''}}" data-bs-toggle="collapse" href="#creativesMenu" role="button"
                    aria-expanded="false" aria-controls="creativesMenu">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 42 42" xmlns="http://www.w3.org/2000/svg">
                            <g fill="#FFFFFF" fill-rule="nonzero">
                                <path class="color-background opacity-6"
                                    d="M12.25,17.5 L8.75,17.5 L8.75,1.75 C8.75,0.78225 9.53225,0 10.5,0 L31.5,0 C32.46775,0 33.25,0.78225 33.25,1.75 L33.25,12.25 L29.75,12.25 L29.75,3.5 L12.25,3.5 L12.25,17.5 Z">
                                </path>
                                <path class="color-background"
                                    d="M40.25,14 L24.5,14 C23.53225,14 22.75,14.78225 22.75,15.75 L22.75,38.5 L19.25,38.5 L19.25,22.75 C19.25,21.78225 18.46775,21 17.5,21 L1.75,21 C0.78225,21 0,21.78225 0,22.75 L0,40.25 C0,41.21775 0.78225,42 1.75,42 L40.25,42 C41.21775,42 42,41.21775 42,40.25 L42,15.75 C42,14.78225 41.21775,14 40.25,14 Z M12.25,36.75 L7,36.75 L7,33.25 L12.25,33.25 L12.25,36.75 Z M12.25,29.75 L7,29.75 L7,26.25 L12.25,26.25 L12.25,29.75 Z M35,36.75 L29.75,36.75 L29.75,33.25 L35,33.25 L35,36.75 Z M35,29.75 L29.75,29.75 L29.75,26.25 L35,26.25 L35,29.75 Z M35,22.75 L29.75,22.75 L29.75,19.25 L35,19.25 L35,22.75 Z">
                                </path>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Creatives</span>
                </a>
                <div class="collapse {{Route::is('admin.creatives.*') ? 'show' : ''}}" id="creativesMenu">
                    <ul class="nav ms-4 ps-3">
                        <li class="nav-item {{Route::is('admin.creatives.index') ? 'active' : ''}}">
                            <a class="nav-link" href="{{ route("admin.creatives.index") }}">

                                <span class="sidenav-normal"> Coupons </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Transactions -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Transactions</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed {{Route::is('admin.transactions.*') ? 'active' : ''}}" data-bs-toggle="collapse" href="#transactionsMenu" role="button"
                    aria-expanded="false" aria-controls="transactionsMenu">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 42 42" xmlns="http://www.w3.org/2000/svg">
                            <g fill="#FFFFFF" fill-rule="nonzero">
                                <path class="color-background opacity-6"
                                    d="M12.25,17.5 L8.75,17.5 L8.75,1.75 C8.75,0.78225 9.53225,0 10.5,0 L31.5,0 C32.46775,0 33.25,0.78225 33.25,1.75 L33.25,12.25 L29.75,12.25 L29.75,3.5 L12.25,3.5 L12.25,17.5 Z">
                                </path>
                                <path class="color-background"
                                    d="M40.25,14 L24.5,14 C23.53225,14 22.75,14.78225 22.75,15.75 L22.75,38.5 L19.25,38.5 L19.25,22.75 C19.25,21.78225 18.46775,21 17.5,21 L1.75,21 C0.78225,21 0,21.78225 0,22.75 L0,40.25 C0,41.21775 0.78225,42 1.75,42 L40.25,42 C41.21775,42 42,41.21775 42,40.25 L42,15.75 C42,14.78225 41.21775,14 40.25,14 Z M12.25,36.75 L7,36.75 L7,33.25 L12.25,33.25 L12.25,36.75 Z M12.25,29.75 L7,29.75 L7,26.25 L12.25,26.25 L12.25,29.75 Z M35,36.75 L29.75,36.75 L29.75,33.25 L35,33.25 L35,36.75 Z M35,29.75 L29.75,29.75 L29.75,26.25 L35,26.25 L35,29.75 Z M35,22.75 L29.75,22.75 L29.75,19.25 L35,19.25 L35,22.75 Z">
                                </path>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Transactions</span>
                </a>
                <div class="collapse {{Route::is('admin.transactions.*') ? 'show' : ''}}" id="transactionsMenu">
                    <ul class="nav ms-4 ps-3">
                        <li class="nav-item {{Route::is('admin.transactions.*') ? 'active' : ''}}">
                            <a class="nav-link" href="{{ route("admin.transactions.index") }}">

                                <span class="sidenav-normal"> Transactions </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">

                                <span class="sidenav-normal"> Missing Transactions </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>


            <!-- Users Mansagement -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Users Mansagement</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed {{Route::is('admin.users.*') || Route::is('admin.roles.*') || Route::is('admin.permissions.*') ? 'active' : ''}}" data-bs-toggle="collapse" href="#usersMenu" role="button"
                    aria-expanded="false" aria-controls="usersMenu">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 42 42" xmlns="http://www.w3.org/2000/svg">
                            <g fill="#FFFFFF" fill-rule="nonzero">
                                <path class="color-background opacity-6"
                                    d="M12.25,17.5 L8.75,17.5 L8.75,1.75 C8.75,0.78225 9.53225,0 10.5,0 L31.5,0 C32.46775,0 33.25,0.78225 33.25,1.75 L33.25,12.25 L29.75,12.25 L29.75,3.5 L12.25,3.5 L12.25,17.5 Z">
                                </path>
                                <path class="color-background"
                                    d="M40.25,14 L24.5,14 C23.53225,14 22.75,14.78225 22.75,15.75 L22.75,38.5 L19.25,38.5 L19.25,22.75 C19.25,21.78225 18.46775,21 17.5,21 L1.75,21 C0.78225,21 0,21.78225 0,22.75 L0,40.25 C0,41.21775 0.78225,42 1.75,42 L40.25,42 C41.21775,42 42,41.21775 42,40.25 L42,15.75 C42,14.78225 41.21775,14 40.25,14 Z M12.25,36.75 L7,36.75 L7,33.25 L12.25,33.25 L12.25,36.75 Z M12.25,29.75 L7,29.75 L7,26.25 L12.25,26.25 L12.25,29.75 Z M35,36.75 L29.75,36.75 L29.75,33.25 L35,33.25 L35,36.75 Z M35,29.75 L29.75,29.75 L29.75,26.25 L35,26.25 L35,29.75 Z M35,22.75 L29.75,22.75 L29.75,19.25 L35,19.25 L35,22.75 Z">
                                </path>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Users</span>
                </a>
                <div class="collapse {{Route::is('admin.users.*') || Route::is('admin.roles.*') || Route::is('admin.permissions.*') ? 'show' : ''}}" id="usersMenu">
                    <ul class="nav ms-4 ps-3">
                        <li class="nav-item {{Route::is('admin.users.index') || Route::is('admin.users.edit') || Route::is('admin.users.create') || Route::is('admin.users.view') ? 'active' : ''}}">
                            <a class="nav-link" href="{{ route("admin.users.index") }}">

                                <span class="sidenav-normal"> Users </span>
                            </a>
                        </li>
                        <li class="nav-item {{Route::is('admin.roles.index') || Route::is('admin.roles.edit') || Route::is('admin.roles.create') ? 'active' : ''}}">
                            <a class="nav-link" href="{{ route("admin.roles.index") }}">

                                <span class="sidenav-normal"> Roles </span>
                            </a>
                        </li>
                        <li class="nav-item {{Route::is('admin.permissions.index') || Route::is('admin.permissions.edit') || Route::is('admin.permissions.create') ? 'active' : ''}}">
                            <a class="nav-link" href="{{ route("admin.permissions.index") }}">

                                <span class="sidenav-normal"> Permissions </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Settings -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Settings</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed {{Route::is('admin.settings.*') ? 'active' : ''}}" data-bs-toggle="collapse" href="#settingsMenu" role="button"
                    aria-expanded="false" aria-controls="settingsMenu">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 42 42" xmlns="http://www.w3.org/2000/svg">
                            <g fill="#FFFFFF" fill-rule="nonzero">
                                <path class="color-background opacity-6"
                                    d="M12.25,17.5 L8.75,17.5 L8.75,1.75 C8.75,0.78225 9.53225,0 10.5,0 L31.5,0 C32.46775,0 33.25,0.78225 33.25,1.75 L33.25,12.25 L29.75,12.25 L29.75,3.5 L12.25,3.5 L12.25,17.5 Z">
                                </path>
                                <path class="color-background"
                                    d="M40.25,14 L24.5,14 C23.53225,14 22.75,14.78225 22.75,15.75 L22.75,38.5 L19.25,38.5 L19.25,22.75 C19.25,21.78225 18.46775,21 17.5,21 L1.75,21 C0.78225,21 0,21.78225 0,22.75 L0,40.25 C0,41.21775 0.78225,42 1.75,42 L40.25,42 C41.21775,42 42,41.21775 42,40.25 L42,15.75 C42,14.78225 41.21775,14 40.25,14 Z M12.25,36.75 L7,36.75 L7,33.25 L12.25,33.25 L12.25,36.75 Z M12.25,29.75 L7,29.75 L7,26.25 L12.25,26.25 L12.25,29.75 Z M35,36.75 L29.75,36.75 L29.75,33.25 L35,33.25 L35,36.75 Z M35,29.75 L29.75,29.75 L29.75,26.25 L35,26.25 L35,29.75 Z M35,22.75 L29.75,22.75 L29.75,19.25 L35,19.25 L35,22.75 Z">
                                </path>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Settings</span>
                </a>
                <div class="collapse {{Route::is('admin.settings.*') ? 'show' : ''}}" id="settingsMenu">
                    <ul class="nav ms-4 ps-3">
                        <li class="nav-item {{Route::is('admin.settings.default-commission') ? 'active' : ''}}">
                            <a class="nav-link" href="{{ route("admin.settings.default-commission") }}">

                                <span class="sidenav-normal"> Default Commission </span>
                            </a>
                        </li>
                        <li class="nav-item {{Route::is('admin.settings.notification') ? 'active' : ''}}">
                            <a class="nav-link" href="{{ route("admin.settings.notification") }}">

                                <span class="sidenav-normal"> Notifications </span>
                            </a>
                        </li>

            <li class="nav-item">
              <a class="nav-link" href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <span class="sidenav-mini-icon"> </span>
                <span class="sidenav-normal"> Logout </span>
                <form id="logoutform" action="{{ route('logout') }}" method="POST" class="display-hidden">
                    {{ csrf_field() }}
                </form>
              </a>
            </li>
                    </ul>
                </div>
            </li>

        </ul>
    </div>
</aside>
