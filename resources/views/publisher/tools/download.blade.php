@extends('layouts.publisher.layout')

@section('styles')
    <style>
        .warning-alert {
            border-left: 4px solid #ffc107 !important;
            background-color: rgba(255, 193, 7, 0.08);
            border-radius: 8px;
        }

        .file-card {
            transition: all 0.25s ease;
            border-left: 4px solid var(--primary-color);
            margin-bottom: 12px;
            border-radius: 8px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .file-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(103, 119, 239, 0.15);
            border-left: 4px solid var(--primary-hover);
        }

        .badge-expiring {
            background-color: #fff3cd;
            color: #856404;
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 4px;
        }

        .file-size {
            color: #6c757d;
            font-size: 0.85rem;
        }

        .file-date {
            font-size: 0.9rem;
            color: #495057;
        }

        .export-btn {
            min-width: 110px;
            transition: all 0.2s ease;
        }

        .file-icon {
            font-size: 1.2rem;
            margin-right: 10px;
        }
    </style>
@endsection

@section('scripts')

@endsection

@section('breadcrumb')
    <ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg collapse-btn">
        <li class="breadcrumb-item mt-1">
            <a href="#"><i data-feather="home"></i></a>
        </li>
        <li class="breadcrumb-item mt-1">
            <a href="#" class="text-sm">Tools</a>
        </li>
        <li class="breadcrumb-item mt-1 active">
            <a href="#" class="text-sm">Exporting Files</a>
        </li>
    </ol>
@endsection

@section('content')
    <div class="alert warning-alert mb-4">
        <div class="d-flex align-items-start">
            <i class="fas fa-exclamation-triangle fa-lg text-warning mr-3 mt-1"></i>
            <div>
                <h5 class="alert-heading mb-2">Important Notice</h5>
                <p class="mb-2">All exported files are automatically deleted <strong>7 days after generation</strong>.</p>
                <p class="mb-0"><i class="fas fa-lightbulb mr-1"></i>Tip: Download files promptly to avoid losing access.
                </p>
            </div>
        </div>
    </div>

    <!-- Files List -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="fas fa-folder-open mr-2 text-primary"></i>Available Exports</h5>
            <span class="badge badge-primary">{{ count($exports) }} files to download</span>
        </div>
        @if(count($exports))
    @foreach($exports->chunk(4) as $exportChunk)
        <!-- File Item 1 -->
        <div class="file-card bg-white p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fas fa-file-csv file-icon text-success"></i>
                    <div>
                        <h6 class="mb-1 font-weight-bold text-dark">user_data_export_20231025.csv</h6>
                        <div class="d-flex">
                            <span class="file-date mr-3"><i class="far fa-calendar-alt mr-1"></i>Generated: Oct 25, 2023
                                14:30</span>
                            <span class="file-size"><i class="fas fa-database mr-1"></i>2.4 MB</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge badge-success mr-3">Ready</span>
                    <button class="btn btn-primary export-btn">
                        <i class="fas fa-download mr-1"></i>Download
                    </button>
                </div>
            </div>
        </div>
    </div>
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
    {{-- @include("partial.alert")

    @if(count($exports))
    @foreach($exports->chunk(4) as $exportChunk)
    <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
        @foreach($exportChunk as $export)
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex flex-column align-items-center text-center">
                    <a href="{{ \App\Helper\Methods::staticAsset(" storage/{$export->path}") }}"
                        class="d-flex flex-column align-items-center" download>
                        <div class="mb-2">
                            <img src="{{ \App\Helper\Methods::staticAsset('admin/assets/media/misc/csv.png') }}"
                                class="theme-light-show img-fluid" height="50" width="50" alt="CSV icon" />
                        </div>

                        <div class="fs-5 fw-bold mb-2 text-truncate w-100">{{ $export->name }}</div>
                    </a>

                    <div class="fs-7 fw-semibold text-muted">{{ $export->created_at->diffForHumans() }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
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
    @endif --}}
@endsection
