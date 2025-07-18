@extends('layouts.publisher.layout')

@section('styles')
    <style>
        .table-loader {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            /* Ensure loader is above other content */
        }

        .table-loader .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: .3em;
        }

        .ml-3 {
            margin-left: 10px;
        }

        .display-hidden {
            display: none;
        }
    </style>
@endsection

@section('scripts')

@endsection

@section('content')
            <div class="mb-5">
                <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('publisher.dashboard') }}"><i
                                            class="ri-home-5-line text-primary"></i></a></li>
                                <li class="breadcrumb-item"><a href="">Tools</a></li>
                                <li class="breadcrumb-item"><a href="">Exporting Files</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
                @include("partial.alert")
            </div>

            @if(count($exports))
                @foreach($exports->chunk(4) as $exportChunk)
                    <!--begin::Row-->
                    <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
                        @foreach($exportChunk as $export)
                            <!--begin::Col-->
                            <div class="col-md-6 col-lg-4">
                                <!--begin::Card-->
                                <div class="card shadow-sm border-0">
                                    <!--begin::Card body-->
                                    <div class="card-body d-flex flex-column align-items-center text-center">
                                        <!--begin::Download Link-->
                                        <a href="{{ \App\Helper\Methods::staticAsset("storage/{$export->path}") }}"
                                            class="d-flex flex-column align-items-center" download>
                                            <!--begin::Image-->
                                            <div class="mb-2">
                                                <img src="{{ \App\Helper\Methods::staticAsset('admin/assets/media/misc/csv.png') }}"
                                                    class="theme-light-show img-fluid" height="50" width="50" alt="CSV icon" />
                                            </div>
                                            <!--end::Image-->

                                            <!--begin::File Name-->
                                            <div class="fs-5 fw-bold mb-2 text-truncate w-100">{{ $export->name }}</div>
                                            <!--end::File Name-->
                                        </a>
                                        <!--end::Download Link-->

                                        <!--begin::Timestamp-->
                                        <div class="fs-7 fw-semibold text-muted">{{ $export->created_at->diffForHumans() }}</div>
                                        <!--end::Timestamp-->
                                    </div>
                                    <!--end::Card body-->
                                </div>
                                <!--end::Card-->
                            </div>
                            <!--end::Col-->
                        @endforeach
                    </div>
                    <!--end::Row-->
                @endforeach
            @else
                <div class="row justify-content-center my-4">
                    <div class="col-6">
                        <div class="card shadow-sm">
                            <div class="card-header pb-2">
                                <h5 class="text-md text-center mb-0">No Download Export Files</h5>
                            </div>
                            <div class="card-body pt-2">
                                <div class="mb-3">
                                    <p class="text-center text-sm text-muted">Currently, there are no files available for
                                        download in the export
                                        files section. Please use the export options in various sections to generate files.
                                        Files will remain
                                        in this section for up to 3 months.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
@endsection
