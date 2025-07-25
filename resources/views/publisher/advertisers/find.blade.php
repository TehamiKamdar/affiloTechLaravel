@extends('layouts.publisher.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('publisherAssets/assets/bundles/izitoast/css/iziToast.min.css') }}">

    <style>
        label {
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
        }

        /* Modern Select Dropdown */
        .custom-select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            height: 50px;
            padding: 0.75rem 1.25rem;
            border: 2px solid #e0e3ed;
            border-radius: 8px;
            background-color: white;
            color: var(--primary-color);
            font-weight: 500;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2300a9da' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1.25rem center;
            background-size: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .custom-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(103, 119, 227, 0.2);
            outline: none;
        }

        select option {
            padding: 12px 16px;
            background-color: white;
            color: var(--primary-color);
            font-weight: 500;
            border-bottom: 1px solid #f0f2fc;
        }

        option:hover {
            background-color: var(--primary-very-light) !important;
        }

        select option:checked,
        select option:active {
            background-color: var(--primary-very-light) !important;
            color: var(--primary-color);
        }
.form-control {
            height: 50px;
            border: 2px solid #e0e3ed;
            padding: 0.75rem 1.25rem;
            color: var(--primary-color);
            font-weight: 500;
            border-radius: 8px  !important;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--primary-color);
        }
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

        .advertiser-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
        }

        .advertiser-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(103, 119, 239, 0.2) !important;
        }

        .advertiser-logo {
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .advertiser-attributes {
            margin-top: 15px;
        }

        .attribute-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid rgba(103, 119, 239, 0.1);
        }

        .attribute-label {
            font-weight: 600;
            margin-right: 5px;
            color: #555;
            flex: 1;
        }

        .attribute-value {
            color: #333;
            font-weight: 500;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('publisherAssets/assets/bundles/izitoast/js/iziToast.min.js') }}"></script>


    <script>
        let advertiserCheckboxArr = @json($advertisersCheckboxValues);
    </script>
    <script>

        $(document).ready(function () {

            const exportFieldSet = () => {
                $("#search_export").val($('#search').val());
                $("#status_export").val($("#statusFilter").val());
                $("#country_export").val($("#country").val());
                $("#advertiser_type_export").val($("#advertiserType").val());
            }

            const passURLFields = (isEmpty = false) => {
                updateAndFetch({
                    per_page: $('#per-page-select').val(),
                    page: 1,
                    search: isEmpty ? '' : $('#search').val(),
                    status: isEmpty ? '' : $("#statusFilter").val(),
                    country: isEmpty ? '' : $("#country").val(),
                    advertiser_type: isEmpty ? '' : $("#advertiserType").val(),
                });
                exportFieldSet();
            }

            const updateUrlParams = (params = {}) => {
                let url = new URL(window.location.href);
                Object.keys(params).forEach(key => url.searchParams.set(key, params[key]));
                history.pushState(null, '', url.toString());
            };

            const fetchAdvertisers = (page = 1) => {
                $('#table-loader').show();

                const data = {
                    page,
                    per_page: $('#per-page-select').val(),
                    search: $('#search').val(),
                    status: $("#statusFilter").val(),
                    country: $("#country").val(),
                    advertiser_type: $("#advertiserType").val(),
                };

                $.ajax({
                    url: '{{ route("publisher.find-advertisers") }}',
                    data: data,
                    success: function (response) {
                        advertiserCheckboxArr = response.advertisersCheckboxValues;
                        $('#advertisers-container').html(response.data);

                        $('#pagination-container').html(response.pagination);
                        $('#kt_project_users_table_info').html(`Showing ${response.from} to ${response.to} of ${response.total} entries`);
                        $('#advertiserTotal').val(response.total);
                        $('#totalExport').val(response.total);
                    },
                    error: function (xhr) {
                        alert('Error occurred: ' + xhr.statusText);
                    },
                    complete: function () {
                        $('#table-loader').hide();
                    }
                });
            };

            const updateAndFetch = (params = {}) => {
                updateUrlParams(params);
                fetchAdvertisers(params.hasOwnProperty('page') ? params.page : 1);
            };

            // Event Bindings
            $(document).on('click', '.pagination a', function (e) {
                e.preventDefault();
                $('.pagination li').removeClass('active');
                $(this).parent('li').addClass('active');
                updateAndFetch({ page: new URL($(this).attr('href')).searchParams.get('page') });
            });

            $('#statusFilter, #apply').on('change click', function () {
                passURLFields();
            });

            $('#per-page-select').on('change', function () {
                passURLFields();
            });

            $('#search').on('keyup', function () {
                passURLFields();
            });

            $("#clear").click(function () {
                $("#country, #advertiserType").val(null).trigger('change');
                passURLFields(true);
            });

            $("#exportBttn").click(function () {
                exportFieldSet();
            });

        });
    </script>
@endsection
@php
    if (isset($_GET['status'])) {
        $selected_status = $_GET['status'];
    } else {
        $selected_status = 'active';
    }
@endphp
@section('breadcrumb')
    <ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg collapse-btn">
        <li class="breadcrumb-item mt-1">
            <a href="#"><i data-feather="home"></i></a>
        </li>
        <li class="breadcrumb-item mt-1">
            <a href="#" class="text-sm">Advertisers</a>
        </li>
        <li class="breadcrumb-item mt-1 active">
            <a href="#" class="text-sm">My Advertisers</a>
        </li>
    </ol>
@endsection
@section('content')

    @include("partial.alert")
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h4>Advanced Filters</h4>
        </div>
    <div class="card-body p-3">
        <div class="row align-items-center">
            <!-- Field 1 -->
            <div class="col-md-5 col-sm-6 col-6 mb-2 mb-md-0">
                <div class="form-group mb-0">
                    <label for="countryFilter" class="form-label mb-1">Select Country</label>
                    <select class="form-control" id="country">
                        <option selected disabled>Choose Country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->iso2 }}" @if($country->iso2 == request('country')) selected @endif>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Field 2 -->
            <div class="col-md-5 col-sm-6 col-6 mb-2 mb-md-0">
                <div class="form-group mb-0">
                    <label for="advertiserType" class="form-label mb-1">Advertiser Type</label>
                    <select class="form-control" id="advertiserType">
                        <option></option>
                        <option value="{{ \App\Models\Advertiser::THIRD_PARTY }}"
                            {{ request()->advertiser_type == \App\Models\Advertiser::THIRD_PARTY ? 'selected' : '' }}>
                            {{ \App\Models\Advertiser::THIRD_PARTY_TEXT }}
                        </option>
                        <option value="{{ \App\Models\Advertiser::MANAGED_BY }}"
                            {{ request()->advertiser_type == \App\Models\Advertiser::MANAGED_BY ? 'selected' : '' }}>
                            {{ \App\Models\Advertiser::MANAGED_BY_TEXT }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Apply Button -->
            <div class="col-md-2 col-sm-12 col-12">
                <button class="btn btn-primary btn-block mt-4" type="button" id="apply">
                    <i class="fas fa-filter mr-1"></i> Apply Filter
                </button>
            </div>
        </div>
    </div>
</div>

    <div class="card">
        <div class="card-header border-0 pt-6">
    <div class="card-title w-100">
        <div class="d-flex justify-content-between align-items-center flex-wrap">

            {{-- Left Side: Search + Status Filter --}}
            <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                <div>
                    <input type="text" id="search" class="form-control"
                           placeholder="Search Advertiser"
                           value="{{ request('search') }}" />
                </div>

                <select id="statusFilter" class="form-control p-1" accesskey=""
                        style="height:2rem; width: auto;">
                    @foreach($defaultStatus as $status => $selected)
                        <option value="{{ $status }}" @if($status == $selected_status) selected @endif>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- Right Side: Filter + Export Buttons --}}
            <div class="d-flex align-items-center mt-2 mt-md-0">
                {{-- Export Button --}}
                <button type="button" id="exportBttn" class="btn btn-sm btn-success"
                        data-toggle="modal" data-target="#kt_modal_export_data">
                    <i class="fas fa-file-export mr-1"></i> Export as CSV
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Export Modal -->
<div class="modal fade" id="kt_modal_export_data" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-lg modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <div>
                    <h4 class="fw-bold">Export Advertiser Data</h4>
                </div>
                <button type="button" class="btn btn-sm btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                    <span class="text-white text-lg">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <form action="{{ route('publisher.generate-export-advertiser') }}" method="post" class="form">
                @csrf
                <div class="modal-body">

                    <div class="fw-semibold text-muted">
                        After your request is completed, the formatted file will be available in
                        <b>Tools > Download Export Files</b>.
                    </div>
                    <!-- Hidden Inputs -->
                    <input type="hidden" name="route_name" value="{{ request()->route()->getName() }}">
                    <input type="hidden" name="total" value="{{ $advertisers->total() }}">
                    <input type="hidden" name="search" id="search_export">
                    <input type="hidden" name="status" id="status_export">
                    <input type="hidden" name="country" id="country_export">
                    <input type="hidden" name="advertiser_type" id="advertiser_type_export">
                    <input type="hidden" name="categories" id="categories_export">
                    <input type="hidden" name="methods" id="methods_export">
                    <input type="hidden" name="export_format" id="export_format" value="csv">
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="reset" class="btn btn-light me-3" data-dismiss="modal">Discard</button>
                    <button type="submit" class="btn btn-outline-success">
                        <span class="indicator-label">Request to Export Data</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

        <div class="card-body py-4">
            <div id="advertisers-container">
                @include("publisher.advertisers.table.ajax", compact('advertisers'))
            </div>

            <div class="row mt-3">
                <div class="col-12 col-md-6 d-flex align-items-center justify-content-start">
                    <div class="dataTables_length" id="kt_project_users_table_length">
                        <label>
                            <select name="per_page" id="per-page-select" class="form-control">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ empty(request('per_page')) || request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </label>
                    </div>

                    <div class="dataTables_info text-sm ml-3" id="kt_project_users_table_info" role="status" aria-live="polite">
                        Showing {{ $from }} to {{ $to }} of {{ $advertisers->total() }} entries
                    </div>
                </div>

                <div class="col-12 col-md-6 d-flex align-items-center justify-content-end" id="pagination-container">
                    {{ $advertisers->withQueryString()->links('partial.publisher_pagination') }}
                </div>

            </div>
        </div>
    </div>


    {{-- Multi Apply Script --}}
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function () {
            function initializeCheckboxLogic() {
                document.addEventListener("change", function (event) {
                    if (event.target.id === "mainCheck") {
                        const mainCheck = event.target;
                        const checkboxes = document.querySelectorAll(".advertisers");
                        checkboxes.forEach(checkbox => {
                            if (!checkbox.disabled) {
                                checkbox.checked = mainCheck.checked;
                            }
                        });
                    }
                });

                // document.addEventListener("change", function (event) {
                //     if (event.target.id === "bulkCheckbox") {
                //         const bulkCheckbox = event.target;
                //         const submitButton = document.getElementById("kt_advertiser_apply_submits");

                //         if (submitButton) {
                //             submitButton.disabled = !bulkCheckbox.checked;
                //             submitButton.style.pointerEvents = bulkCheckbox.checked ? "auto" : "none";
                //             submitButton.style.display = bulkCheckbox.checked ? "block" : "none";
                //         }
                //     }
                // });
            }

            // Initialize for first load
            initializeCheckboxLogic();

            // Reinitialize logic after AJAX content update
            document.addEventListener("ajaxComplete", function () {
                console.log("Reinitializing checkbox logic after AJAX call");
                initializeCheckboxLogic();
            });
        });


        // document.addEventListener("click", function (event) {

        //     if (event.target.id === "kt_advertiser_apply_submits") {
        //         let checkedInputs = document.querySelectorAll(".advertisers:checked");
        //         let message = document.getElementById("messages");
        //         let dataTypes = [];

        //         checkedInputs.forEach(input => {
        //             let dataType = input.getAttribute("data_type");
        //             if (dataType) {
        //                 dataTypes.push(dataType);
        //             }
        //         });

        //         console.log("Selected Data Types:", dataTypes);

        //         fetch('/publisher/apply-advertiser', {
        //             method: "POST",
        //             headers: {
        //                 "Content-Type": "application/json",
        //                 "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        //             },
        //             body: JSON.stringify({
        //                 advertisers: dataTypes,
        //                 message: message ? message.value : ""  // Ensure message input exists
        //             })
        //         })
        //             .then(response => {
        //                 if (!response.ok) {
        //                     throw new Error("Failed to send data");
        //                 }
        //                 return response.json();
        //             })
        //             .then(data => {
        //                 window.location.reload();
        //             })
        //             .catch(error => {
        //                 console.error("Error:", error);
        //                 let modal = document.getElementById("kt_modal_apply_data_bulk");
        //                 if (modal) {
        //                     let modalInstance = bootstrap.Modal.getInstance(modal);
        //                     if (modalInstance) {
        //                         modalInstance.hide();
        //                     } else {
        //                         modal.style.display = "none";
        //                     }
        //                 }

        //                 let response = {
        //                     "message": "Something went wrong",
        //                     "error": true
        //                 };
        //                 normalMsg(response);
        //             });
        //     }
        // });

        // function close_modal(id) {
        //     let modal = document.getElementById("kt_modal_apply_data_" + id);
        //     if (modal) {
        //         let modalInstance = bootstrap.Modal.getInstance(modal);
        //         if (!modalInstance) {
        //             modalInstance = new bootstrap.Modal(modal);
        //         }
        //         modalInstance.hide();

        //         // Optional: clean up backdrop if needed
        //         setTimeout(() => {
        //             document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        //             document.body.classList.remove('modal-open');
        //             document.body.style = '';
        //         }, 500);
        //     }
        // }


        // document.addEventListener("click", function (event) {
        //     console.log('hello')
        //     if (event.target.id === "close_modal_bulk" || event.target.id === "close_modal_bulk1" || event.target.id === "close_modal_bulk2" || event.target.id === "close_modal_bulk3" || event.target.id === "close_modal_bulks") {
        //         let modal = document.getElementById("kt_modal_apply_data_bulk");
        //         if (modal) {
        //             let modalInstance = bootstrap.Modal.getInstance(modal);
        //             if (modalInstance) {
        //                 modalInstance.hide();
        //             } else {
        //                 modal.style.display = "none";
        //             }
        //         }
        //     }
        // });

    </script> --}}
@endsection
