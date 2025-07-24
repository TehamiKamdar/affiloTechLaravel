<div class="main-sidebar sidebar-style-2" id="sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{route('publisher.dashboard')}}"> <img alt="image" src="{{ asset('publisherAssets/assets/affiloTech.png') }}" class="header-logo" /> <span
                    class="logo-name">Affilo Tech</span>
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown active">
                <a href="{{route('publisher.dashboard')}}" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">advertisers management</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        data-feather="users"></i><span>Advertisers</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{route('publisher.my-advertisers')}}">My Advertisers</a></li>
                    <li><a class="nav-link" href="{{route('publisher.new-advertisers')}}">New Advertisers</a></li>
                    <li><a class="nav-link" href="{{route('publisher.find-advertisers')}}">Find Advertisers</a></li>
                </ul>
            </li>
            <li class="menu-header">reports management</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        data-feather="bar-chart-2"></i><span>Reporting</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{route('publisher.transactions')}}">Transactions</a></li>
                    <li><a class="nav-link" href="{{route('publisher.advertiser-performance')}}">Advertisers Performance</a></li>
                    <li><a class="nav-link" href="{{route('publisher.click-performance')}}">Clicks Performance</a></li>
                </ul>
            </li>
            <li class="menu-header">creativity</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        data-feather="tag"></i><span>Promotional</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{route('publisher.coupons')}}">Coupons</a></li>
                    <li><a class="nav-link" href="{{route('publisher.deep-links')}}">Deep Links</a></li>
                    <li><a class="nav-link" href="{{route('publisher.text-links')}}">Text Links</a></li>
                </ul>
            </li>
            <li class="menu-header">finance management</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        data-feather="credit-card"></i><span>Finance</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{route('publisher.finance-overview')}}">Overview</a></li>
                    <li><a class="nav-link" href="{{route('publisher.payments')}}">Payments</a></li>
                </ul>
            </li>
            <li class="menu-header">tools management</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        data-feather="paperclip"></i><span>Tools</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{route('publisher.link-generator')}}">Link Builder</a></li>
                    <li><a class="nav-link" href="{{route('publisher.api')}}">API Integration</a></li>
                    <li><a class="nav-link" href="{{route('publisher.download-export-files')}}">Download Export Files</a></li>
                </ul>
            </li>
            <li class="menu-header">settings</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        data-feather="settings"></i><span>Settings</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('publisher.profile.basic-information') }}">Profile</a></li>
                    <li><a class="nav-link" href="{{ route('publisher.profile.company-information') }}">Company</a></li>
                    <li><a class="nav-link" href="{{ route('publisher.profile.website') }}">Websites</a></li>
                    <li class="dropdown">
                        <a href="#" class="has-dropdown">Payment Info</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('publisher.profile.payment-billing') }}">Billing</a></li>
                        </ul>
                    </li>
                    <li><a class="nav-link" href="{{ route('publisher.profile.login-information.change-email') }}">Settings</a></li>
                </ul>
            </li>

        </ul>
    </aside>
</div>
