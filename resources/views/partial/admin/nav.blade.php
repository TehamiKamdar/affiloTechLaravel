
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
              <img src="{{ asset('assets/media/logos/logo.png') }}" alt="Logo" height="40" />
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner">
            <!-- Dashboard -->
            <li class="menu-item {{ \App\Helper\Methods::isAdminActiveRoute("admin.dashboard") }}">
              <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
              </a>
            </li>
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Publisher Management</span>
            </li>
            <li class="menu-item {{ \App\Helper\Methods::isAdminActiveWithOpenRoute("admin.publishers.*") }}">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Account Settings">Publishers</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item {{ \App\Helper\Methods::isAdminActiveRoute("admin.publishers.pending") }}">
                  <a href="{{ route("admin.publishers.status", ['status' => \App\Models\User::STATUS_PENDING]) }}" class="menu-link">
                    <span>Pending</span>
                  </a>
                </li>
                <li class="menu-item @if(isset($publisher->status) && $publisher->status == \App\Models\User::STATUS_HOLD) active @endif">
                  <a href="{{ route("admin.publishers.status", ['status' => \App\Models\User::STATUS_HOLD]) }}" class="menu-link">
                    <span>Hold</span>
                  </a>
                </li>
                <li class="menu-item @if(isset($publisher->status) && $publisher->status == \App\Models\User::STATUS_ACTIVE) active @endif ">
                  <a href="{{ route("admin.publishers.status", ['status' => \App\Models\User::STATUS_ACTIVE]) }}" class="menu-link">
                    <span>Active</span>
                  </a>
                </li>
                <li class="menu-item @if(isset($publisher->status) && $publisher->status == \App\Models\User::STATUS_REJECT) active @endif">
                  <a href="{{ route("admin.publishers.status", ['status' => \App\Models\User::STATUS_REJECT]) }}" class="menu-link">
                    <span>Rejected</span>
                  </a>
                </li>
              </ul>
            </li>
            
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Advertisers Management</span>
            </li>
            <li class="menu-item {{ \App\Helper\Methods::isAdminActiveWithOpenRoute("admin.advertisers.api*") ? 'active open' : '' }}">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Account Settings">Advertisers</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item {{ \App\Helper\Methods::isAdminActiveWithOpenRoute("admin.advertisers.api*") ? 'active' : '' }}">
                  <a href="{{ route("admin.advertisers.api") }}" class="menu-link">
                    <span>API Advertisers</span>
                  </a>
                </li>
              </ul>
            </li>
            <li class="menu-item {{ \App\Helper\Methods::isAdminActiveWithOpenRoute("admin.advertisers.approval.*") }}">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cube-alt"></i>
                <div data-i18n="Misc">Approval</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="{{ route("admin.advertisers.approval.status", ['status' => 'pending']) }}" class="menu-link">
                    <div data-i18n="Error">Pending</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="{{ route("admin.advertisers.approval.status", ['status' => 'joined']) }}" class="menu-link">
                    <div data-i18n="Under Maintenance">Joined</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="{{ route("admin.advertisers.approval.status", ['status' => 'hold']) }}" class="menu-link">
                    <div data-i18n="Under Maintenance">Hold</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="{{ route("admin.advertisers.approval.status", ['status' => 'rejected']) }}" class="menu-link">
                    <div data-i18n="Under Maintenance">Rejected</div>
                  </a>
                </li>
              </ul>
            </li>


            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Creatives</span>
            </li>
            <li class="menu-item {{ \App\Helper\Methods::isAdminActiveWithOpenRoute("admin.creatives.*") }}">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Account Settings">Creatives</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item {{ \App\Helper\Methods::isAdminActiveRoute('admin.creatives.index') }}">
                  <a href="{{ route("admin.creatives.index") }}" class="menu-link">
                    <span>Coupons</span>
                  </a>
                </li>
              </ul>
            </li>



            
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Transactions Management</span>
            </li>
            <li class="menu-item {{ \App\Helper\Methods::isAdminActiveWithOpenRoute("admin.transactions.*") }}">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Account Settings">Transactions</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item {{ \App\Helper\Methods::isAdminActiveRoute('admin.transactions.index') }}">
                  <a href="{{ route("admin.transactions.index") }}" class="menu-link">
                    <span>Transactions</span>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="#" class="menu-link">
                    <span>Missing Transactions</span>
                  </a>
                </li>
              </ul>
            </li>


            
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">User Management</span>
            </li>
            <li class="menu-item  {{ \App\Helper\Methods::isAdminActiveWithOpenRoute("admin.users.*") }}">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Account Settings">Users</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item {{ \App\Helper\Methods::isAdminActiveRoute('admin.users.index') }}">
                  <a href="{{ route("admin.users.index") }}" class="menu-link">
                    <span>Users</span>
                  </a>
                </li>
                <li class="menu-item {{ \App\Helper\Methods::isAdminActiveRoute('admin.roles.index') }}">
                  <a href="{{ route("admin.roles.index") }}" class="menu-link">
                    <span>Roles</span>
                  </a>
                </li>
                <li class="menu-item {{ \App\Helper\Methods::isAdminActiveRoute('admin.permissions.index') }}">
                  <a href="{{ route("admin.permissions.index") }}" class="menu-link">
                    <span>Permissions</span>
                  </a>
                </li>
              </ul>
            </li>
            
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Settings</span>
            </li>
            <li class="menu-item {{ \App\Helper\Methods::isAdminActiveWithOpenRoute("admin.settings.*") }}">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Account Settings">Settings</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item {{ \App\Helper\Methods::isAdminActiveRoute('admin.settings.default_commission') }}">
                  <a href="{{ route("admin.settings.default-commission") }}" class="menu-link">
                    <span>Default Commission</span>
                  </a>
                </li>
                <li class="menu-item {{ \App\Helper\Methods::isAdminActiveRoute('admin.settings.notification') }}">
                  <a href="{{ route("admin.settings.notification") }}" class="menu-link">
                    <span>Notifications</span>
                  </a>
                </li>
              </ul>
            </li>
            
          </ul>
        </aside>