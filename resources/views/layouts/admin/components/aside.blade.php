<div class="main-sidebar sidebar-style-2" id="sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{route('admin.dashboard')}}"> <img alt="image" src="{{ asset('publisherAssets/assets/affiloTech.png') }}" class="header-logo" /> <span class="logo-name">Affilo Tech</span>
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                <a href="{{route('admin.dashboard')}}" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">Publishers management</li>
            <li class="dropdown {{ Route::is('publisher.my-advertisers') || Route::is('publisher.new-advertisers') || Route::is('publisher.find-advertisers') || Route::is('publisher.view-advertiser') ? 'active' : '' }}">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        data-feather="users"></i><span>Publishers</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Route::is('publisher.my-advertisers') || Route::is('publisher.view-advertiser') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.publishers.status', ['status' => \App\Models\User::STATUS_PENDING]) }}">Pending</a></li>
                    <li class="{{ Route::is('publisher.new-advertisers') ? 'active' : '' }}"><a class="nav-link" href="{{ route("admin.publishers.status", ['status' => \App\Models\User::STATUS_HOLD]) }}">Hold</a></li>
                    <li class="{{ Route::is('publisher.find-advertisers') ? 'active' : '' }}"><a class="nav-link" href="{{ route("admin.publishers.status", ['status' => \App\Models\User::STATUS_ACTIVE]) }}">Active</a></li>
                    <li class="{{ Route::is('publisher.find-advertisers') ? 'active' : '' }}"><a class="nav-link" href="{{ route("admin.publishers.status", ['status' => \App\Models\User::STATUS_REJECT]) }}">Rejected</a></li>
                </ul>
            </li>
            <li class="menu-header">Advertisers management</li>
            <li class="dropdown {{ Route::is('publisher.transactions') || Route::is('publisher.advertiser-performance') || Route::is('publisher.click-performance') ? 'active' : '' }}">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        data-feather="bar-chart-2"></i><span>Advertisers</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Route::is('publisher.transactions') ? 'active' : '' }}"><a class="nav-link" href="{{ route("admin.advertisers.api") }}">API Advertisers</a></li>
                    <li class="{{ Route::is('publisher.advertiser-performance') ? 'active' : '' }}"><a class="nav-link" href="{{ route("admin.advertisers.api.show_on_publisher.index") }}">API Advertisers Show</a></li>
                    <li class="{{ Route::is('publisher.click-performance') ? 'active' : '' }}"><a class="nav-link" href="{{ route("admin.advertisers.api.show_on_publisher.duplicate_record") }}">Duplicate Advertisers</a></li>
                </ul>
            </li>
            <li class="dropdown {{ Route::is('publisher.coupons') || Route::is('publisher.text-links') || Route::is('publisher.deep-links') ? 'active' : '' }}">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        data-feather="user-check"></i><span>Approval</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Route::is('publisher.coupons') ? 'active' : '' }}"><a class="nav-link" href="{{ route("admin.advertisers.approval.status", ['status' => 'pending']) }}">Pending</a></li>
                    <li class="{{ Route::is('publisher.deep-links') ? 'active' : '' }}"><a class="nav-link" href="{{ route("admin.advertisers.approval.status", ['status' => 'joined']) }}">Joined</a></li>
                    <li class="{{ Route::is('publisher.text-links') ? 'active' : '' }}"><a class="nav-link" href="{{ route("admin.advertisers.approval.status", ['status' => 'hold']) }}">Hold</a></li>
                    <li class="{{ Route::is('publisher.text-links') ? 'active' : '' }}"><a class="nav-link" href="{{ route("admin.advertisers.approval.status", ['status' => 'rejected']) }}">Rejected</a></li>
                </ul>
            </li>
            <li class="menu-header">promotional management</li>
            <li class="dropdown {{ Route::is('publisher.finance-overview') ||  Route::is('publisher.payments') ? 'active' : '' }}">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        data-feather="tag"></i><span>Creatives</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Route::is('publisher.finance-overview') ? 'active' : '' }}"><a class="nav-link" href="{{ route("admin.creatives.index") }}">Coupons</a></li>
                </ul>
            </li>
            <li class="menu-header">finance management</li>
            <li class="dropdown {{ Route::is('publisher.link-generator') ||  Route::is('publisher.api') ||  Route::is('publisher.download-export-files') ? 'active' : '' }}">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        data-feather="credit-card"></i><span>Transactions</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Route::is('publisher.link-generator') ? 'active' : '' }}"><a class="nav-link" href="{{ route("admin.transactions.index") }}">Transactions</a></li>
                    <li class="{{ Route::is('publisher.api') ? 'active' : '' }}"><a class="nav-link" href="#">Missing Transactions</a></li>
                </ul>
            </li>
            <li class="menu-header">access management</li>
            <li class="dropdown {{ Route::is('publisher.profile.*') ? 'active' : '' }}">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        data-feather="shield"></i><span>Manage</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route("admin.users.index") }}">Users</a></li>
                    <li><a class="nav-link" href="{{ route("admin.roles.index") }}">Roles</a></li>
                    <li><a class="nav-link" href="{{ route("admin.permissions.index") }}">Permissions</a></li>
                </ul>
            </li>
            <li class="menu-header">settings</li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i
                  data-feather="settings"></i><span>Settings</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route("admin.settings.default-commission") }}">Default Commission</a></li>
                <li><a class="nav-link" href="{{ route("admin.settings.notification") }}">Notifications</a></li>
              </ul>
            </li>
        </ul>
    </aside>
</div>
