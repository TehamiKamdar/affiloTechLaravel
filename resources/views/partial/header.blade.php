                <div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}" data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}" data-kt-sticky-animation="false">
					<!--begin::Header container-->
					<div class="app-container container-xxl d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
                        @if(env("APP_ENV") != "local")
                            <!--begin::Logo-->
                            <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15">
                                <a href="{{ route("publisher.dashboard") }}">
                                    <img alt="Logo" src="{{ \App\Helper\Methods::staticAsset('assets/media/logos/logo-light.png') }}" class="h-20px h-lg-30px app-sidebar-logo-default" />
                                </a>
                            </div>
                            <!--end::Logo-->
                        @endif
						<!--begin::Header wrapper-->
						<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
							<!--begin::Menu wrapper-->
							<div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="{default: 'append', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
								<!--begin::Menu-->
								<div class="menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0" id="kt_app_header_menu" data-kt-menu="true">

								    <!--begin:Menu item-->
									<div class="menu-item @if(request()->route()->getName() == "publisher.dashboard") here @endif me-0 me-lg-2">
										<!--begin:Menu link-->
										<a class="menu-link" href="{{ route("publisher.dashboard") }}">
                                            <span class="menu-title">Dashboard</span>
                                        </a>
										<!--end:Menu link-->
									</div>
									<!--end:Menu item-->

									<!--begin:Menu item-->
									<div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item @if(request()->route()->getName() == "publisher.find-advertisers" || request()->route()->getName() == "publisher.my-advertisers" || request()->route()->getName() == "publisher.top-advertisers" || request()->route()->getName() == "publisher.new-advertisers" || request()->route()->getName() == "publisher.view-advertiser") here @endif menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
										<!--begin:Menu link-->
										<span class="menu-link">
											<span class="menu-title">Advertisers</span>
											<span class="menu-arrow d-lg-none"></span>
										</span>
										<!--end:Menu link-->
										<!--begin:Menu sub-->
										<div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px">
											<!--begin:Menu item-->
											<div class="menu-item">
												<!--begin:Menu link-->
												<a class="menu-link" href="{{ route("publisher.my-advertisers") }}">
													<span class="menu-title">My Advertisers</span>
												</a>
												<!--end:Menu link-->
											</div>
											<!--end:Menu item-->
											<!--begin:Menu item-->
											
											<!--end:Menu item-->
											<!--begin:Menu item-->
											<div class="menu-item">
												<!--begin:Menu link-->
												<a class="menu-link" href="{{ route("publisher.new-advertisers") }}">
													<span class="menu-title">New Advertisers</span>
												</a>
												<!--end:Menu link-->
											</div>
											<!--end:Menu item-->
											<!--begin:Menu item-->
											<div class="menu-item">
												<!--begin:Menu link-->
												<a class="menu-link" href="{{ route('publisher.find-advertisers') }}">
													<span class="menu-title">Find Advertisers</span>
												</a>
												<!--end:Menu link-->
											</div>
											<!--end:Menu item-->
										</div>
										<!--end:Menu sub-->
									</div>
									<!--end:Menu item-->
									<!--begin:Menu item-->
									<div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2 @if(request()->route()->getName() == "publisher.transactions") here @endif">
										<!--begin:Menu link-->
										<span class="menu-link">
											<span class="menu-title">Reporting</span>
											<span class="menu-arrow d-lg-none"></span>
										</span>
										<!--end:Menu link-->
										<!--begin:Menu sub-->
										<div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px">
											<!--begin:Menu item-->
											<div class="menu-item">
												<!--begin:Menu link-->
												<a class="menu-link" href="{{ route("publisher.transactions") }}">
													<span class="menu-title">Transactions</span>
												</a>
												<!--end:Menu link-->
											</div>
											<!--end:Menu item-->
											<!--begin:Menu item-->
											<div class="menu-item">
												<!--begin:Menu link-->
												<a class="menu-link" href="{{ route("publisher.advertiser-performance") }}">
													<span class="menu-title">Performance</span>
												</a>
												<!--end:Menu link-->
											</div>
											<!--end:Menu item-->
											<!--begin:Menu item-->
											<!--<div class="menu-item">-->
												<!--begin:Menu link-->
											<!--	<a class="menu-link" href="{{ route("publisher.click-performance") }}">-->
											<!--		<span class="menu-title">Click Performance</span>-->
											<!--	</a>-->
												<!--end:Menu link-->
											<!--</div>-->
											<!--end:Menu item-->
											
										</div>
										<!--end:Menu sub-->
									</div>
									<!--end:Menu item-->
									<!--begin:Menu item-->
									<div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
										<!--begin:Menu link-->
										<span class="menu-link">
											<span class="menu-title">Promote</span>
											<span class="menu-arrow d-lg-none"></span>
										</span>
										<!--end:Menu link-->
										<!--begin:Menu sub-->
										<div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px">
											<!--begin:Menu item-->
											<div class="menu-item">
												<!--begin:Menu link-->
												<a class="menu-link" href="{{ route("publisher.coupons") }}">
													<span class="menu-title">Coupons</span>
												</a>
												<!--end:Menu link-->
											</div>
											<!--end:Menu item-->
											<!--begin:Menu item-->
											<div class="menu-item">
												<!--begin:Menu link-->
												<a class="menu-link" href="{{ route("publisher.text-links") }}">
													<span class="menu-title">Text Links</span>
												</a>
												<!--end:Menu link-->
											</div>
											<!--end:Menu item-->
											<!--begin:Menu item-->
											<div class="menu-item">
												<!--begin:Menu link-->
												<a class="menu-link" href="{{ route("publisher.deep-links") }}">
													<span class="menu-title">Deep Links</span>
												</a>
												<!--end:Menu link-->
											</div>
											<!--end:Menu item-->
											
										</div>
										<!--end:Menu sub-->
									</div>
									<!--end:Menu item-->
									<!--begin:Menu item-->
									<div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
										<!--begin:Menu link-->
										<span class="menu-link">
											<span class="menu-title">Finance</span>
											<span class="menu-arrow d-lg-none"></span>
										</span>
										<!--end:Menu link-->
										<!--begin:Menu sub-->
										<div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px">
											<!--begin:Menu item-->
											<div class="menu-item">
												<!--begin:Menu link-->
												<a class="menu-link" href="{{ route("publisher.finance-overview") }}">
													<span class="menu-title">Overview</span>
												</a>
												<!--end:Menu link-->
											</div>
											<!--end:Menu item-->
											<!--begin:Menu item-->
											<div class="menu-item">
												<!--begin:Menu link-->
												<a class="menu-link" href="{{ route("publisher.payments") }}">
													<span class="menu-title">Payments</span>
												</a>
												<!--end:Menu link-->
											</div>
											<!--end:Menu item-->
										</div>
										<!--end:Menu sub-->
									</div>
									<!--end:Menu item-->
									<!--begin:Menu item-->
									<div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2 @if(request()->route()->getName() == "publisher.download-export-files") here @endif">
										<!--begin:Menu link-->
										<span class="menu-link">
											<span class="menu-title">Tools</span>
											<span class="menu-arrow d-lg-none"></span>
										</span>
										<!--end:Menu link-->
										<!--begin:Menu sub-->
										<div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px">
											<!--begin:Menu item-->
											<div class="menu-item">
												<!--begin:Menu link-->
												<a class="menu-link" href="{{ route("publisher.link-generator") }}">
													<span class="menu-title">Link Generator</span>
												</a>
												<!--end:Menu link-->
											</div>
											<!--end:Menu item-->
											<!--begin:Menu item-->
											<!--<div class="menu-item">-->
												<!--begin:Menu link-->
											<!--	<a class="menu-link" href="{{ route("publisher.feeds") }}">-->
											<!--		<span class="menu-title">Feeds</span>-->
											<!--	</a>-->
												<!--end:Menu link-->
											<!--</div>-->
											<!--end:Menu item-->
											<!--begin:Menu item-->
											<div class="menu-item">
												<!--begin:Menu link-->
												<a class="menu-link" href="{{ route("publisher.api") }}">
													<span class="menu-title">API Integration</span>
												</a>
												<!--end:Menu link-->
											</div>
											<!--end:Menu item-->
											<!--begin:Menu item-->
											<div class="menu-item">
												<!--begin:Menu link-->
												<a class="menu-link" href="{{ route("publisher.download-export-files") }}">
													<span class="menu-title">Download Export Files</span>
												</a>
												<!--end:Menu link-->
											</div>
											<!--end:Menu item-->
										</div>
										<!--end:Menu sub-->
									</div>
									<!--end:Menu item-->
								</div>
								<!--end::Menu-->
							</div>
							<!--end::Menu wrapper-->

							<!--begin::Navbar-->
							<div class="app-navbar flex-shrink-0">

                                @php
                                    $headerWebsites = auth()->user()->websites->where("status", \App\Models\Website::ACTIVE)->where('id', '!=', auth()->user()->active_website_id);
                                @endphp

                                @if(count($headerWebsites) && isset(auth()->user()->active_website->name))

                                    <div class="app-navbar-item ms-1 ms-md-4">
                                        <!--begin::Menu wrapper-->
                                        <div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-auto h-35px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                                            {{ auth()->user()->active_website->name }} (active)
                                        </div>
                                        <!--begin::My apps-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column w-100 w-sm-350px" data-kt-menu="true" style="">
                                            <!--begin::Card-->
                                            <div class="card">
                                                <!--begin::Card header-->
                                                <div class="card-header">
                                                    <!--begin::Card title-->
                                                    <div class="card-title">My Websites</div>
                                                    <!--end::Card title-->
                                                </div>
                                                <!--end::Card header-->
                                                <!--begin::Card body-->
                                                <div class="card-body py-5">
                                                    <!--begin::Scroll-->
                                                    <div class="mh-450px scroll-y me-n5 pe-5">
                                                        <!--begin::Row-->
                                                        <div class="row g-2">
                                                            @foreach($headerWebsites as $website)
                                                                <!--begin::Col-->
                                                                <div class="col-6">
                                                                    <a href="{{ route("publisher.profile.website.set", ["website" => $website->id]) }}" class="d-flex flex-column flex-center text-center text-gray-800 text-hover-primary bg-hover-light rounded py-4 px-3 mb-3">
                                                                        <span class="fw-semibold">{{ \Illuminate\Support\Str::limit($website->name, 20, '...') }}</span>
                                                                    </a>
                                                                </div>
                                                                <!--end::Col-->
                                                            @endforeach
                                                        </div>
                                                        <!--end::Row-->
                                                    </div>
                                                    <!--end::Scroll-->
                                                </div>
                                                <!--end::Card body-->
                                            </div>
                                            <!--end::Card-->
                                        </div>
                                        <!--end::My apps-->
                                        <!--end::Menu wrapper-->
                                    </div>

                                @endif

								<!--begin::Activities-->
								<div class="app-navbar-item ms-1 ms-md-4">
									<!--begin::Drawer toggle-->
									<div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px position-relative" id="kt_activities_toggle">
										<i class="ki-duotone ki-messages fs-2">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
											<span class="path5"></span>
										</i>
										<span class="bullet bullet-dot bg-success h-6px w-6px position-absolute translate-middle top-0 start-50 animation-blink"></span>
									</div>
									<!--end::Drawer toggle-->
								</div>
								<!--end::Activities-->
								<!--begin::User menu-->
								<div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
									<!--begin::Menu wrapper-->
									<div class="cursor-pointer symbol symbol-35px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
										<img src="@if(isset(auth()->user()->publisher->image)) {{ \App\Helper\Methods::staticAsset("storage/".auth()->user()->publisher->image) }} @else {{ \App\Helper\Methods::staticAsset('assets/media/avatars/blank.png') }} @endif" class="rounded-3" alt="user" />
									</div>
									<!--begin::User account menu-->
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<div class="menu-content d-flex align-items-center px-3">
												<!--begin::Avatar-->
												<div class="symbol symbol-50px me-5">
													<img alt="Logo" src="@if(isset(auth()->user()->publisher->image)) {{ \App\Helper\Methods::staticAsset("storage/".auth()->user()->publisher->image) }} @else {{ \App\Helper\Methods::staticAsset('assets/media/avatars/blank.png') }} @endif" />
												</div>
												<!--end::Avatar-->
												<!--begin::Username-->
												<div class="d-flex flex-column">
													<div class="fw-bold d-flex align-items-center fs-5">
                                                        {{ auth()->user()->name }}
                                                    </div>
													<div class="fw-semibold text-muted fs-7">
                                                        ID: {{ auth()->user()->uid }}
                                                    </div>
												</div>
												<!--end::Username-->
											</div>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu separator-->
										<div class="separator my-2"></div>
										<!--end::Menu separator-->
										<!--begin::Menu item-->
										<div class="menu-item px-5">
											<a href="{{ route("publisher.profile.basic-information") }}" class="menu-link px-5">My Profile</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-5">
											<a href="{{ route('publisher.profile.website') }}" class="menu-link px-5">
												<span class="menu-text">My Ad Spaces</span>
												<span class="menu-badge">
													<span class="badge badge-light-danger badge-circle fw-bold fs-7">{{ auth()->user()->websites->count() }}</span>
												</span>
											</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
											<a href="#" class="menu-link px-5">
												<span class="menu-title">Payments</span>
												<span class="menu-arrow"></span>
											</a>
											<!--begin::Menu sub-->
											<div class="menu-sub menu-sub-dropdown w-175px py-4">
												<!--begin::Menu item-->
												<div class="menu-item px-3">
													<a href="{{ route('publisher.profile.payment-billing') }}" class="menu-link px-5">Billing</a>
												</div>
												<!--end::Menu item-->
												<!--begin::Menu item-->
												<div class="menu-item px-3">
													<a href="{{ route('publisher.profile.payment-billing') }}" class="menu-link px-5">Payments</a>
												</div>
												<!--end::Menu item-->
												<!--begin::Menu item-->
												<div class="menu-item px-3">
													<a href="{{ route('publisher.payments') }}" class="menu-link d-flex flex-stack px-5">Invoices
													<span class="ms-2 lh-0" data-bs-toggle="tooltip" title="View your Invoices">
														<i class="ki-duotone ki-information-5 fs-5">
															<span class="path1"></span>
															<span class="path2"></span>
															<span class="path3"></span>
														</i>
													</span></a>
												</div>
												<!--end::Menu item-->
											</div>
											<!--end::Menu sub-->
										</div>
										<!--end::Menu item-->
										<!--end::Menu item-->
										<!--begin::Menu separator-->
										<div class="separator my-2"></div>
										<!--end::Menu separator-->
										<!--begin::Menu item-->
										<div class="menu-item px-5 my-1">
											<a href="{{ route('publisher.profile.login-information.change-email') }}" class="menu-link px-5">Account Settings</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-5">
                                            <a href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logoutform').submit();" class="menu-link px-5">
                                                Sign Out
                                            </a>
                                            <form id="logoutform" action="{{ route('logout') }}" method="POST" class="display-hidden">
                                                {{ csrf_field() }}
                                            </form>
										</div>
										<!--end::Menu item-->
									</div>
									<!--end::User account menu-->
									<!--end::Menu wrapper-->
								</div>
								<!--end::User menu-->
								<!--begin::Header menu toggle-->
								<div class="app-navbar-item d-lg-none ms-2 me-n2" title="Show header menu">
									<div class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px" id="kt_app_header_menu_toggle">
										<i class="ki-duotone ki-element-4 fs-1">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
									</div>
								</div>
								<!--end::Header menu toggle-->
								<!--begin::Aside toggle-->
								<!--end::Header menu toggle-->
							</div>
							<!--end::Navbar-->
						</div>
						<!--end::Header wrapper-->
					</div>
					<!--end::Header container-->
				</div>
