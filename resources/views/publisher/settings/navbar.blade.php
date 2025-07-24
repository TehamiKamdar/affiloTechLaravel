@php
    $publisherCountry = DB::table('publishers')
        ->join('countries', 'countries.id', '=', 'publishers.location_country')
        ->where('publishers.user_id', $publisher->user_id)
        ->select('countries.*') // or whatever columns you want
        ->first();

        $publisherCity = DB::table('publishers')
        ->join('cities', 'cities.id', '=', 'publishers.location_city')
        ->where('publishers.user_id', $publisher->user_id)
        ->select('cities.*') // or whatever columns you want
        ->first();

        // $displayLocation = ($publisherCity->name ) . ', ' . ($publisherCountry->iso2);
        if ($publisherCity?->name && $publisherCountry?->iso2) {
            $displayLocation = ucfirst($publisherCity->name) . ', ' . $publisherCountry->iso2;
        } elseif (is_null($publisherCity?->name) && is_null($publisherCountry?->iso2)) {
            $displayLocation = "Location Area Not Available";
        } else {
            $displayLocation = ($publisherCity?->name ?? '-') . ', ' . ($publisherCountry?->iso2 ?? '-');
        }
@endphp

<div class="profile-header text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <img src="@if(isset(auth()->user()->publisher->image)) {{ $publisher->image ? asset('storage/' . $publisher->image) : asset('assets/media/avatars/blank.png') }} @else {{ asset('publisherAssets/assets/affiloTech.png') }} @endif"
                    alt="Profile" class="profile-avatar rounded-circle mb-3">
                <h2 class="mb-2">{{ auth()->user()->name }}</h2>
                <p class="mb-3 {{ $displayLocation=='Location Area Not Available' ? 'text-danger' : '' }}"><i class="fas fa-map-marker-alt mr-2 text-white"></i>{{ $displayLocation }}</p>
                <div class="mb-4">
                    <span class="badge badge-light mr-2">Publisher ID: {{ auth()->user()->uid }}</span>
                </div>
                {{-- <div class="d-flex justify-content-center mb-3">
                    <a href="#" class="social-icon"><i class="fab fa-twitter text-light"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in text-light"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-github text-light"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-dribbble text-light"></i></a>
                </div> --}}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column -->
    <div class="col-12">
        <!-- About Me Card -->
        <div class="profile-card">
            <div class="w-100 card-body d-flex flex-wrap justify-content-around" style="background: #fff;">
                <div class="col-10">
                    <h6><i class="fas fa-user mr-2 text-primary"></i>Intro</h6>
                    <p class="{{ $publisher->intro ? 'text-muted' : 'text-danger' }}">{{ $publisher->intro ?? 'Introduction Not Available' }}</p>

                </div>
                <div class="col-2">
                    <h6><i class="fas fa-calendar-alt mr-2 text-primary"></i>Joined</h6>
                    <p class="text-muted">{{ \Carbon\Carbon::parse($user->created_at)->format('F Y') }}</p>
                </div>
            </div>
        </div>

    </div>
</div>


<ul class="nav nav-pills mb-2" id="profileTabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{ Route::is('publisher.profile.basic-information') ? 'active' : ''}}" id="activity-tab" href="{{ route('publisher.profile.basic-information') }}">
            <i class="fas fa-info-circle mr-2"></i>Introduction
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Route::is('publisher.profile.company-information') ? 'active' : ''}}" id="projects-tab" href="{{ route('publisher.profile.company-information') }}">
            <i class="fas fa-globe mr-2"></i>Company Information
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Route::is('publisher.profile.website') ? 'active' : ''}}" id="projects-tab" href="{{ route('publisher.profile.website') }}">
            <i class="fas fa-globe mr-2"></i>Websites
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Route::is('publisher.profile.payment-billing') ? 'active' : ''}}" id="experience-tab" href="{{ route('publisher.profile.payment-billing') }}">
            <i class="fas fa-credit-card mr-2"></i>Billing
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Route::is('publisher.profile.login-information.change-email') || Route::is('publisher.profile.login-information.change-password') ? 'active' : ''}}" id="education-tab" href="{{ route('publisher.profile.login-information.change-email') }}">
            <i class="fas fa-user-lock mr-2"></i>Login Information
        </a>
    </li>
</ul>
