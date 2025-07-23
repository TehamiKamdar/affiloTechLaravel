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
    <script>
        let advertiserCheckboxArr = @json($advertisersCheckboxValues);
    </script>
   <script type="text/javascript">

        const singleSelectAdvertiser = (id) => {
            $(".form-checked-input").prop("checked", false); // Uncheck all checkboxes
            $(`#advertiser_${id}`).prop("checked", true);
        }

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
                console.log(data);
                $.ajax({
                    url: '{{ route("publisher.new-advertisers") }}',
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

            $("#clear").click(function () {
                $("#country, #advertiserType, #categories, #methods").val(null).trigger('change');
                passURLFields(true);
                console.log("Field Cleared")
            });

            $("#exportBttn").click(function () {
                exportFieldSet();
            });

        });
    </script>
@endsection
@php
if(isset($_GET['status'])){
$selected_status = $_GET['status'];
}else{
$selected_status = 'active';
}
@endphp
@section('breadcrumb')
          <ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg
									collapse-btn">
            <li class="breadcrumb-item mt-1">
              <a href="#"><i data-feather="home"></i></a>
            </li>
            <li class="breadcrumb-item mt-1">
              <a href="#" class="text-sm">Advertisers</a>
            </li>
            <li class="breadcrumb-item mt-1 active">
              <a href="#" class="text-sm">New Advertisers</a>
            </li>
          </ol>
@endsection
@section('content')

            @include("partial.alert")

            <!--begin::Card-->
            <div class="card my-5">
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex justify-content-between align-items-center my-1 flex-wrap">
                            <!-- Search Box on the Left -->
                            <div class="d-flex align-items-center position-relative" style="gap: 10px;">
                                <i class="ri-search-line position-absolute ms-3">
                                </i>
                                <input type="text" id="search" data-kt-user-table-filter="search"
                                    class="form-control-sm ps-5" placeholder="Search Advertiser"
                                    value="{{ request('search') }}" />


                                    <select id="statusFilter" class="form-select form-select-solid" data-kt-select2="true" data-close-on-select="false" data-placeholder="Select option" data-dropdown-parent="#kt_advertiser_65a1215215a0b" data-allow-clear="true" style="width:auto;">

                                @foreach($defaultStatus as $status => $selected)
                                    <option value="{{ $status }}" @if($status == $selected_status) selected @endif>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>

                            </div>

                            <!-- Buttons Group on the Right -->
                            <div class="d-flex align-items-center flex-wrap">


                                <!-- Filter Button -->
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle mx-2"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-filter me-1"></i>Filter
                                </button>
                                <div class="dropdown-menu p-3" style="min-width: 250px;">
                                    <label for="countryFilter" class="form-label mb-1">Select Country</label>
                                    <select class="form-select" id="country">
                                        <option selected disabled>Choose Country</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->iso2 }}" @if($country->iso2 == request('country'))
                                            selected @endif>{{ $country->name }}</option>
                                        @endforeach
                                    </select>

                                    <label for="advertiserType" class="form-label mb-1">Advertiser Type:</label>
                                    <select class="form-select" id="advertiserType">
                                        <option></option>
                                        <option value="{{ \App\Models\Advertiser::THIRD_PARTY }}" {{ request()->advertiser_type == \App\Models\Advertiser::THIRD_PARTY ? "selected" : "" }}>
                                            {{ \App\Models\Advertiser::THIRD_PARTY_TEXT }}
                                        </option>
                                        <option value="{{ \App\Models\Advertiser::MANAGED_BY }}" {{ request()->advertiser_type == \App\Models\Advertiser::MANAGED_BY ? "selected" : "" }}>
                                            {{ \App\Models\Advertiser::MANAGED_BY_TEXT }}
                                        </option>
                                    </select>

                                    <div class="d-flex justify-content-end mt-4">
                                        <button class="btn btn-sm btn-outline-primary me-3" id="clear">Clear</button>
                                        <button class="btn btn-sm btn-primary" id="apply">Apply</button>
                                    </div>
                                </div>

                                <!-- Export Button -->
                                <button type="button" id="exportBttn"
                                    class="btn btn-sm btn-outline-success"
                                    data-toggle="modal" data-target="#kt_modal_export_data"><i class="ri-file-upload-fill text-success me-1"></i>
                                    Export
                                </button>

                            </div>
                        </div>

                        <!--end::Toolbar-->
                        <!--begin::Group actions-->

                        <!--end::Group actions-->
                        <div class="modal fade" id="kt_modal_export_data" tabindex="-1" aria-hidden="true">
                            <!--begin::Modal dialog-->
                            <div class="modal-lg modal-dialog modal-dialog-centered mw-650px">
                                <!--begin::Modal content-->
                                <div class="modal-content">
                                    <!--begin::Modal header-->
                                    <div class="modal-header">
                                        <!--begin::Modal title-->
                                        <div>
                                            <h4 class="fw-bold">
                                                Export Advertiser Data
                                            </h4>
                                            <div class="fs-7 fw-semibold text-muted">
                                                After your request is completed, the formatted file you requested will be
                                                available for download in the <b>Tools> Download Export Files</b> section.
                                            </div>
                                        </div>
                                        <!--end::Modal title-->
                                        <!--begin::Close-->
                                        <div class="btn btn-sm btn-close bg-danger" data-dismiss="modal"
                                            data-kt-export-data-modal-action="close">
                                        </div>
                                        <!--end::Close-->
                                    </div>
                                    <!--end::Modal header-->
                                    <!--begin::Modal body-->
                                    <form class="form" novalidate="novalidate" id="kt_advertiser_export_in_form"
                                        action="{{ route('publisher.generate-export-advertiser') }}" method="post">
                                        @csrf
                                        <div class="modal-body mx-auto w-50 scroll-y">
                                            <!--begin::Form-->
                                            <input type="hidden" id="route_name" name="route_name"
                                                value="{{ request()->route()->getName() }}">
                                            <input type="hidden" id="totalExport" name="total"
                                                value="{{ $advertisers->total() }}">
                                            <input type="hidden" name="search" id="search_export">
                                            <input type="hidden" name="status" id="status_export">
                                            <input type="hidden" name="country" id="country_export">
                                            <input type="hidden" name="advertiser_type" id="advertiser_type_export">
                                            <input type="hidden" name="categories" id="categories_export">
                                            <input type="hidden" name="methods" id="methods_export">
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-10">
                                            <!--begin::Label-->
                                            <label class="required fs-6 fw-semibold form-label mb-2">Select Export Format:</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <select name="export_format" data-control="select2" data-placeholder="Select a format" data-hide-search="true" class="form-select form-select-solid fw-bold">
                                                <option></option>
                                                <option value="csv">CSV</option>
                                            </select>
                                            <!--end::Input-->
                                        </div>
                                            <!--end::Input group-->
                                            <!--begin::Actions-->

                                            <!--end::Actions-->
                                        </div>
                                        <!--end::Modal body-->
                                        <div class="modal-footer">
                                                <button type="reset" class="btn btn-light me-3"
                                                    data-bs-dismiss="modal">Discard</button>
                                                <button type="submit" class="btn btn-outline-success"
                                                    id="kt_advertiser_export_submit"
                                                    data-kt-export-data-modal-action="submit">
                                                    <span class="indicator-label">Request to Export Data</span>
                                                </button>
                                        </div>
                                    </form>
                                    <!--end::Form-->
                                </div>
                                <!--end::Modal content-->
                            </div>
                            <!--end::Modal dialog-->
                        </div>
                        <!--begin::Modal - Adjust Balance-->
                        <div class="modal fade" id="kt_modal_apply_data" tabindex="-1" aria-hidden="true">
                            <!--begin::Modal dialog-->
                            <div class="modal-dialog modal-dialog-centered mw-1000px">
                                <!--begin::Modal content-->
                                <div class="modal-content">
                                    <!--begin::Modal header-->
                                    <div class="modal-header">
                                        <!--begin::Modal title-->
                                        <h2 class="fw-bold">
                                            Selected Advertiser
                                        </h2>
                                        <!--end::Modal title-->
                                        <!--begin::Close-->
                                        <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                            data-kt-apply-advertiser-modal-action="close">
                                            <i class="ki-duotone ki-cross fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                        <!--end::Close-->
                                    </div>
                                    <!--end::Modal header-->
                                    <!--begin::Modal body-->
                                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                        <!--begin::Form-->
                                        <form class="form w-100" novalidate="novalidate"
                                            action="{{ route('publisher.apply-advertiser') }}" method="post">
                                            @csrf

                                            <div class="dt-responsive table-responsive">
                                                <table class="table table-striped table-bordered nowrap">
                                                    <tbody class="search-api" id="tableContent">

                                                    </tbody>
                                                </table>
                                            </div>

                                            <p class="font-weight-bold mt-3 text-black">Optional: Tell us about your
                                                promotional methods and general marketing plan for this merchant to help
                                                speed up approval. (Websites you'll use, PPC terms, etc.)</p>

                                            <textarea class="form-control" rows="4" cols="4" name="message"></textarea>

                                            <!--begin::Actions-->
                                            <div class="text-center mt-5">
                                                <button type="reset" class="btn btn-light me-3"
                                                    data-kt-apply-advertiser-modal-action="cancel">Cancel</button>
                                                <button type="submit" class="btn btn-light-primary"
                                                    id="kt_advertiser_apply_submit"
                                                    data-kt-apply-advertiser-modal-action="submit">
                                                    <span class="indicator-label">Apply</span>
                                                    <span class="indicator-progress">Please wait...
                                                        <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                </button>
                                            </div>
                                            <!--end::Actions-->
                                        </form>
                                        <!--end::Form-->
                                    </div>
                                    <!--end::Modal body-->
                                </div>
                                <!--end::Modal content-->
                            </div>
                            <!--end::Modal dialog-->
                        </div>
                        <!--end::Modal - New Card-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--begin::Card body-->
                <div class="card-body py-4">

                    <div id="advertisers-container">

                        @include("publisher.advertisers.table.ajax", compact('advertisers'))
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 col-md-6 d-flex align-items-center justify-content-between">
                            <div class="dataTables_length" id="kt_project_users_table_length">
                                <label>
                                    <select name="per_page" id="per-page-select"
                                        class="form-select-sm">
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ empty(request('per_page')) || request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </label>
                            </div>

                            <div class="dataTables_info text-sm" id="kt_project_users_table_info" role="status" aria-live="polite">
                                Showing {{ $from }} to {{ $to }} of {{ $advertisers->total() }} entries
                            </div>
                        </div>

                        <div class="col-12 col-md-6 d-flex align-items-center justify-content-end"
                            id="pagination-container">
                            {{ $advertisers->withQueryString()->links('partial.publisher_pagination') }}
                        </div>

                    </div>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            function initializeCheckboxLogic() {
                // Delegate checkbox selection to document
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

                document.addEventListener("change", function (event) {
                    if (event.target.id === "bulkCheckbox") {
                        const bulkCheckbox = event.target;
                        const submitButton = document.getElementById("kt_advertiser_apply_submits");

                        if (submitButton) {
                            submitButton.disabled = !bulkCheckbox.checked;
                            submitButton.style.pointerEvents = bulkCheckbox.checked ? "auto" : "none";
                            submitButton.style.display = bulkCheckbox.checked ? "block" : "none";
                        }
                    }
                });
            }

            // Initialize for first load
            initializeCheckboxLogic();

            // Reinitialize logic after AJAX content update
            document.addEventListener("ajaxComplete", function () {
                console.log("Reinitializing checkbox logic after AJAX call");
                initializeCheckboxLogic();
            });
        });


        document.addEventListener("click", function (event) {

            if (event.target.id === "kt_advertiser_apply_submits") {
                let checkedInputs = document.querySelectorAll(".advertisers:checked");
                let message = document.getElementById("messages");
                let dataTypes = [];

                checkedInputs.forEach(input => {
                    let dataType = input.getAttribute("data_type");
                    if (dataType) {
                        dataTypes.push(dataType);
                    }
                });

                console.log("Selected Data Types:", dataTypes);

                fetch('/publisher/apply-advertiser', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        advertisers: dataTypes,
                        message: message ? message.value : ""  // Ensure message input exists
                    })
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Failed to send data");
                        }
                        return response.json();
                    })
                    .then(data => {
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        let modal = document.getElementById("kt_modal_apply_data_bulk");
                        if (modal) {
                            let modalInstance = bootstrap.Modal.getInstance(modal);
                            if (modalInstance) {
                                modalInstance.hide();
                            } else {
                                modal.style.display = "none";
                            }
                        }

                        let response = {
                            "message": "Something went wrong",
                            "error": true
                        };
                        normalMsg(response);
                    });
            }
        });


      function close_modal(id) {
    let modal = document.getElementById("kt_modal_apply_data_" + id);
    if (modal) {
        let modalInstance = bootstrap.Modal.getInstance(modal);
        if (!modalInstance) {
            modalInstance = new bootstrap.Modal(modal);
        }
        modalInstance.hide();

        // Optional: clean up backdrop if needed
        setTimeout(() => {
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            document.body.style = '';
        }, 500);
    }
}

        document.addEventListener("click", function (event) {
            console.log('hello')
            if (event.target.id === "close_modal_bulk" || event.target.id === "close_modal_bulk1" || event.target.id === "close_modal_bulk2" || event.target.id === "close_modal_bulk3" || event.target.id === "close_modal_bulks") {
                let modal = document.getElementById("kt_modal_apply_data_bulk");
                if (modal) {
                    let modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) {
                        modalInstance.hide();
                    } else {
                        modal.style.display = "none";
                    }
                }
            }
        });

    </script>
@endsection
