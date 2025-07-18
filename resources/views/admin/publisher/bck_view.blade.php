@extends("layouts.admin.layout")

@section("styles")
    <style>
        .mr-2 {
            margin-right: 6px;
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
            z-index: 1000; /* Ensure loader is above other content */
        }

        .table-loader .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: .3em;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();

            // Remove active class from all pagination links
            $('.pagination li').removeClass('active');

            // Add active class to the parent <li> of the clicked link
            $(this).parent('li').addClass('active');

            let currentUrl = new URL(window.location.href);

            // Set the per_page parameter in the URL and reset page to 1
            // currentUrl.searchParams.set('per_page', perPage);
            currentUrl.searchParams.set('page', 1);

            history.pushState(null, '', currentUrl);

            let source = $('#source').val();

            let page = $(this).attr('href').split('page=')[1];

            fetchAdvertisers(source, page);

        });

        $("#selectAll").change(() => {
            $('.advertiser-checkbox').prop('checked', $("#selectAll").prop('checked'));
        });

        $(document).on('change', '#per-page-select', function(e) {

            let perPage = $(this).val();
            let currentUrl = new URL(window.location.href);

            $('.checkbox').prop('checked', false);
            $('#selectAll').prop('checked', false);


            let source = $("#source").val();

            // Set the per_page parameter in the URL and reset page to 1
            currentUrl.searchParams.set('per_page', perPage);
            currentUrl.searchParams.set('page', 1);

            // Update the URL without reloading the page
            history.pushState(null, '', currentUrl.toString());

            fetchAdvertisers(source, 1, perPage);

        });

        $('.custom-select').on('select2:selecting', function(e) {
            let source = e.params.args.data.text;
            fetchAdvertisers(source, 1)
        });

        function fetchAdvertisers(source, page)
        {
            $("#selectAll").attr('disabled')
            $("#selectAll").addClass('disabled');
            $("#updateBttn").attr('disabled')
            $("#updateBttn").addClass('disabled');
            $('#table-loader').show();
            let currentUrl = new URL(window.location.href);
            let per_page = currentUrl.searchParams.get('per_page');

            let publisherId = {{ $id }};
            $.ajax({
                type: "GET",
                url: "{{ route("admin.publishers.view.lock-unlock.network-by-advertiser.ajax", ['publisher' => $id]) }}",
                data: {source, publisherId, page, per_page},
                success: function(response, status, xhr) {
                    $("#tableContent").html(response.data);
                },
                error: function(response) {
                },
                complete: function() {
                    $('#table-loader').hide();
                    $("#selectAll").removeAttr('disabled')
                    $("#selectAll").removeClass('disabled');
                    $("#updateBttn").removeAttr('disabled')
                    $("#updateBttn").removeClass('disabled');
                }
            });
        }

        $(document).on('click', '#updateBttn', function(event){

            let website = $("#website").val();
            if(website)
            {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                event.preventDefault();

                let data = { advertisers: [] };
                document.querySelectorAll('.advertiser-checkbox').forEach(function(checkbox) {
                    let advertiserId = checkbox.name.match(/\[([^\]]+)\]/)[1]; // Extract the ID from the name attribute
                    data.advertisers.push({
                        'advertiser_id': advertiserId,
                        'status': checkbox.checked ? 1 : 0
                    });
                });

                data['source'] = $("#source").val();
                data['website'] = website;

                let url = '{{ route("admin.publishers.view.lock-unlock.network-by-advertiser.store", $publisher->id) }}';
                $.ajax({
                    url: url,
                    type: 'POST',
                    headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                    data: data,
                    before: function () {
                        $('#table-loader').show();
                    },
                    success: function (response) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Lock Unlock Network By Advertiser Change Status Request',
                            text: response.message,
                            showConfirmButton: false,
                        });

                    },
                    error: function (response) {

                    },
                    complete: function() {
                        $('#table-loader').hide();
                    }
                });
            }
            else
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Website is Required.',
                    text: 'Please select a website first. This field is required.',
                    showConfirmButton: false,
                });
            }


        });

    </script>
@endsection

@section("content")

    <div class="d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-fluid">
                <!--begin::Table-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card title-->
                        <div class="card-title flex-column">
                            <h3 class="fw-bold mb-1">View Publisher ( {{ $publisher->name }} )</h3>
                        </div>
                        <!--begin::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar my-1">
                            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                                <!--begin::Nav item-->
                                <li class="nav-item mt-2">
                                    <a class="nav-link text-active-primary ms-0 me-10 py-5
                                        @if(request()->route()->getName() == "admin.publishers.view") active @endif"
                                       href="{{ route("admin.publishers.view", ['publisher' => $id]) }}">Intro
                                    </a>
                                </li>
                                <!--end::Nav item-->
                                <!--begin::Nav item-->
                                <li class="nav-item mt-2">
                                    <a class="nav-link text-active-primary ms-0 me-10 py-5
                                        @if(request()->route()->getName() == "admin.publishers.view.mediakits") active @endif"
                                       href="{{ route("admin.publishers.view.mediakits", ['publisher' => $id]) }}">Media Kits
                                    </a>
                                </li>
                                <!--end::Nav item-->
                                <!--begin::Nav item-->
                                <li class="nav-item mt-2">
                                    <a class="nav-link text-active-primary ms-0 me-10 py-5
                                        @if(request()->route()->getName() == "admin.publishers.view.websites") active @endif"
                                       href="{{ route("admin.publishers.view.websites", ['publisher' => $id]) }}">Websites
                                    </a>
                                </li>
                                <!--end::Nav item-->
                                <!--begin::Nav item-->
                                <li class="nav-item mt-2">
                                    <a class="nav-link text-active-primary ms-0 me-10 py-5
                                        @if(request()->route()->getName() == "admin.publishers.view.billing-info") active @endif"
                                       href="{{ route("admin.publishers.view.billing-info", ['publisher' => $id]) }}">Billing Information
                                    </a>
                                </li>
                                <!--end::Nav item-->
                                <!--begin::Nav item-->
                                <li class="nav-item mt-2">
                                    <a class="nav-link text-active-primary ms-0 me-10 py-5
                                        @if(request()->route()->getName() == "admin.publishers.view.payment-info") active @endif"
                                       href="{{ route("admin.publishers.view.payment-info", ['publisher' => $id]) }}">Payment Information
                                    </a>
                                </li>
                                <!--end::Nav item-->
                                <!--begin::Nav item-->
                                <li class="nav-item mt-2">
                                    <a class="nav-link text-active-primary ms-0 me-10 py-5
                                        @if(request()->route()->getName() == "admin.publishers.view.lock-unlock.network-by-advertiser") active @endif"
                                       href="{{ route("admin.publishers.view.lock-unlock.network-by-advertiser", ['publisher' => $id]) }}">Lock Unlock Network By Advertiser
                                    </a>
                                </li>
                                <!--end::Nav item-->
                            </ul>
                        </div>
                        <!--begin::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        @include("partial.admin.alert")
                        @if(request()->route()->getName() == "admin.publishers.view")
                            @include("admin.publisher.intro", compact('publisher', 'company'))
                        @elseif(request()->route()->getName() == "admin.publishers.view.mediakits")
                            @include("admin.publisher.media-kits", compact('publisher'))
                        @elseif(request()->route()->getName() == "admin.publishers.view.websites")
                            @include("admin.publisher.website", compact('publisher', 'websites'))
                        @elseif(request()->route()->getName() == "admin.publishers.view.billing-info")
                            @include("admin.publisher.billing-information", compact('publisher'))
                        @elseif(request()->route()->getName() == "admin.publishers.view.lock-unlock.network-by-advertiser")
                            @include("admin.publisher.lock-unlock-network.advertiser", compact('publisher', 'advertisers', 'from', 'to', 'perPage'))
                        @endif
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>

@endsection
