@php
    $fields = [
        'Advertiser Logo' => $advertiser->logo
            ? "<img src=\"" . \App\Helper\Methods::staticAsset("storage/{$advertiser->logo}") . "\" alt=\"{$advertiser->name}\" class=\"img-fluid img-thumbnail w-200px\">"
            : "<img src=\"" . \App\Helper\Methods::staticAsset("assets/media/logos/placeholder.jpeg") . "\" alt=\"{$advertiser->name}\" class=\"img-fluid img-thumbnail w-200px\">",
        'Is Fetch Logo' => $advertiser->is_fetchable_logo ? "Yes" : "No",
        'Fetch Logo Error' => $advertiser->fetch_logo_error ?: "-",
        'Advertiser ID' => $advertiser->id,
        'Advertiser SID' => $advertiser->sid,
        'External Advertiser SID' => $advertiser->advertiser_id,
        'Network Source ID' => $advertiser->network_source_id ?: "-",
        'Network Source' => ucwords($advertiser->source),
        'Advertiser Name' => $advertiser->name,
        'Advertiser Status' => $advertiser->status == 1 ? "Active" : ($advertiser->status == 0 ? "Inactive" : "Not Found"),
        'Advertiser URL' => "<a href=\"{$advertiser->url}\" target=\"_blank\">{$advertiser->url}</a>",
        'Click Through URL' => $advertiser->click_through_url ?: "-",
        'Currency Code' => $advertiser->currency_code ?: "-",
        'Supported Regions' => $supportedRegions ?: "-",
        'Primary Regions' => $primaryRegions ?: "-",
        'Country' => $advertiser->country ?: "-",
        'Country Full Name' => $countryFullName,
        'State' => $advertiser->state ?: "-",
        'City' => $advertiser->city ?: "-",
        'Address' => $advertiser->address ?: "-",
        'Company Name' => $advertiser->company_name ?: "-",
        'Phone Number' => $advertiser->phone_number ?: "-",
        'Custom Domain' => $advertiser->custom_domain ?: "-",
        'Average Payment Time' => $advertiser->average_payment_time ?: "-",
        'Valid Domains' => $advertiser->valid_domains ?: "-",
        'Validation Days' => $advertiser->validation_days ?: "-",
        'EPC' => $advertiser->epc ?: "-",
        'Goto Cookie Lifetime' => $advertiser->goto_cookie_lifetime ?: "-",
        'Exclusive' => $advertiser->exclusive ? "Yes" : "No",
        'Deeplink Enabled' => $advertiser->deeplink_enabled ? "Yes" : "No",
        'Tags' => $advertiser->tags ?: "-",
        'Categories' => $categories,
        'Promotional Methods' => $methods,
        'Program Restrictions' => $restrictions,
    ];
@endphp

<style>
.advertiser-profile-card {
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    border: none;
}

.advertiser-profile-card .card-body {
    padding: 1.5rem;
}

.logo-placeholder {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 120px;
    height: 120px;
    background: #f8f9fa;
    border-radius: 8px;
}

.nav-tabs {
    border-bottom: 2px solid #f1f1f1;
}

.nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
    padding: 12px 20px;
    border-radius: 0;
    position: relative;
}

.nav-tabs .nav-link.active {
    color: #f37916;
    background: transparent;
    border-bottom: 2px solid #f37916;
    margin-bottom: -2px;
}

.nav-tabs .nav-link:hover {
    color: #f37916;
    background: rgba(243, 121, 22, 0.1);
}

.tab-content .card {
    border-top: none;
    border-radius: 0 0 8px 8px;
    box-shadow: none;
}

@media (max-width: 768px) {
    .nav-tabs .nav-link {
        padding: 8px 12px;
        font-size: 14px;
    }

    .advertiser-profile-card .card-body {
        padding: 1rem;
    }
}
</style>
<div class="container-fluid">
    <div class="row">
        <!-- Advertiser Summary Card -->
        <div class="col-12 mb-4">
            <div class="card advertiser-profile-card">
                <div class="card-body">
                    <!-- Status Badge -->
                    <div class="d-flex justify-content-end mb-3">
                        <span class="badge bg-{{ $advertiser->status == 1 ? 'success' : 'secondary' }} text-white text-xs">
                            {{ $advertiser->status == 1 ? "Active" : ($advertiser->status == 0 ? "Inactive" : "Not Found") }}
                        </span>
                    </div>

                    <!-- Company Details -->
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-1">{{ $advertiser->name }}</h4>
                            <p class="mb-1 text-muted text-sm">
                                <i class="fas fa-globe me-1"></i> Network: <strong>{{ ucwords($advertiser->source) }}</strong>
                            </p>
                            @if($advertiser->average_payment_time)
                            <p class="mb-1 text-muted text-sm">
                                <i class="fas fa-clock me-1"></i> Avg Payout: <strong>{{ $advertiser->average_payment_time }}</strong>
                            </p>
                            @endif
                            @if($supportedRegions)
                            <p class="mb-1 text-muted text-sm">
                                <i class="fas fa-map-marker-alt me-1"></i> Regions:
                                @foreach(explode(',', $supportedRegions) as $region)
                                    <img src="https://flagsapi.com/{{ strtoupper(trim($region)) }}/flat/24.png"
                                         title="{{ trim($region) }}"
                                         class="img-fluid cursor-pointer ms-1"
                                         alt="{{ trim($region) }}">
                                @endforeach
                            </p>
                            @endif
                            <a href="{{ $advertiser->url }}" target="_blank" class="text-primary text-sm d-inline-flex align-items-center mt-2">
                                <i class="fas fa-external-link-alt me-1"></i> Visit Advertiser Website
                            </a>
                        </div>

                        <!-- Company Logo -->
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            @if(!empty($advertiser->fetch_logo_url))
                                <img src="{{ $advertiser->fetch_logo_url }}"
                                     alt="{{ $advertiser->name }}"
                                     class="img-fluid rounded"
                                     style="max-height: 120px;">
                                     @elseif(!empty($advertiser->logo))
                                    <img src="{{ \App\Helper\Methods::staticAsset("storage/{$advertiser->logo}") }}"
                                     alt="{{ $advertiser->name }}"
                                     class="img-fluid rounded"
                                     style="max-height: 120px;"> 
                            @else
                                <div class="logo-placeholder">
                                    <i class="fas fa-building" style="font-size: 80px; color: #f37916;"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="col-12 mb-4">
            <ul class="nav nav-tabs" id="advertiserTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">
                        <i class="fas fa-info-circle me-1"></i> Details
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="financial-tab" data-bs-toggle="tab" data-bs-target="#financial" type="button" role="tab">
                        <i class="fas fa-money-bill-wave me-1"></i> Financial
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">
                        <i class="fas fa-cog me-1"></i> Settings
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="advertiserTabsContent">
                <!-- Details Tab -->
                <div class="tab-pane fade show active" id="details" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                @foreach([
                                    'Advertiser ID',
                                    'Advertiser SID',
                                    'External Advertiser SID',
                                    'Network Source ID',
                                    'Country Full Name',
                                    'State',
                                    'City',
                                    'Address',
                                    'Company Name',
                                    'Phone Number'
                                ] as $field)
                                @if(isset($fields[$field]))
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">{{ $field }}</label>
                                    <p class="mb-0">{!! $fields[$field] !!}</p>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Tab -->
                <div class="tab-pane fade" id="financial" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                @foreach([
                                    'Currency Code',
                                    'EPC',
                                    'Average Payment Time',
                                    'Validation Days',
                                    'Goto Cookie Lifetime'
                                ] as $field)
                                @if(isset($fields[$field]))
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">{{ $field }}</label>
                                    <p class="mb-0">{!! $fields[$field] !!}</p>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Tab -->
                <div class="tab-pane fade" id="settings" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                @foreach([
                                    'Is Fetch Logo',
                                    'Fetch Logo Error',
                                    'Custom Domain',
                                    'Valid Domains',
                                    'Exclusive',
                                    'Deeplink Enabled',
                                    'Tags',
                                    'Categories',
                                    'Promotional Methods',
                                    'Program Restrictions'
                                ] as $field)
                                @if(isset($fields[$field]))
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">{{ $field }}</label>
                                    <p class="mb-0">{!! $fields[$field] !!}</p>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
