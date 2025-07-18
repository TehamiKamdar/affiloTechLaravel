@extends('layouts.publisher.layout')

@section('styles')
@endsection

@section('scripts')
@endsection

@section('content')

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="d-flex flex-column flex-center text-center p-10">
                <div class="card card-flush w-lg-650px">
                    <div class="card-body py-15 py-lg-20">
                        <!--begin::Logo-->
                        <div class="mb-13">
                            <a href="{{ route("publisher.dashboard") }}">
                                <img alt="Logo" src="{{ \App\Helper\Methods::staticAsset('assets/media/logos/logo.png') }}" class="h-40px" />
                            </a>
                        </div>
                        <!--end::Logo-->
                        <!--begin::Title-->
                        <h1 class="fw-bolder text-gray-900 mb-7">We're Launching Soon</h1>
                        <!--end::Title-->
                        <!--begin::Text-->
                        <div class="fw-semibold fs-6 text-gray-500 mb-7">This is your opportunity to get creative amazing opportunaties
                            <br>that gives readers an idea</div>
                        <!--end::Text-->
                        <!--begin::Illustration-->
                        <div class="mb-n5">
                            <img src="{{ \App\Helper\Methods::staticAsset('assets/media/auth/chart-graph.png') }}" class="mw-100 mh-300px theme-light-show" alt="">
                            <img src="{{ \App\Helper\Methods::staticAsset('assets/media/auth/chart-graph-dark.png') }}" class="mw-100 mh-300px theme-dark-show" alt="">
                        </div>
                        <!--end::Illustration-->
                    </div>
                </div>
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->

@endsection

