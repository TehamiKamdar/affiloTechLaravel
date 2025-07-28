@if(count($networks))

    <div class="card" id="kt_profile_details_view">
        <!--begin::Card body-->
        <div class="card-body p-9">

            <!--begin::Row-->
            <div class="row">
                <!--begin::Col-->
                <div class="col-lg-6 fv-row">
                    <div class="form-group">
                        <label for="source">Network List:</label>
                        <select aria-label="Select a Network" data-control="select2" data-placeholder="Select a Network..." class="form-select form-select-solid custom-select" id="source" name="source">
                            <option value="">Select a Network...</option>
                            @foreach($networks as $network)
                                <option value="{{ $network }}">{{ $network }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 fv-row">
                    <div class="form-group">
                        <label for="source">Website List:</label>
                        <select aria-label="Select a Website" data-control="select2" data-placeholder="Select a Website..." class="form-select form-select-solid custom-select" id="website" name="website">
                            <option value="">Select a Website...</option>
                            @foreach($websites as $website)
                                <option value="{{ $website->id }}">{{ $website->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->

        </div>
        <!--begin::Card body-->
    </div>

    <div class="card ">
        <!--begin::Card header-->
        <div class="card-header">
            <!--begin::Heading-->
            <div class="card-title">
                <h3>Advertiser List</h3>
            </div>
            <!--end::Heading-->

            <!--begin::Toolbar-->
            <div class="card-toolbar">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input disabled" id="selectAll" name="selectAll" disabled value="1"> Select All
                    </label>
                </div>
            </div>
            <!--end::Toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body p-0">
            <!--begin::Table wrapper-->
            <div class="table-responsive">

                <!--begin::Loader-->
                <div id="table-loader" class="table-loader" style="display: none;">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <!--end::Loader-->
                <!--begin::Table-->
                <table class="table align-middle table-row-bordered table-row-solid gy-4" id="kt_security_license_usage_table">
                    <!--begin::Tbody-->
                    <tbody class="fw-6 fw-semibold text-gray-600" id="tableContent">
                    @if(count($advertisers))
                        @include("admin.publisher.lock-unlock-network.ajax", compact('advertisers', 'from', 'to', 'perPage'))
                    @else
                        @include("admin.publisher.lock-unlock-network.empty")
                    @endif
                    </tbody>
                    <!--end::Tbody-->
                    <tfoot>
                    <tr>
                        <td>
                            <button type="button" class="btn btn-xs btn-primary disabled" id="updateBttn" disabled>Update</button>
                        </td>
                    </tr>
                    </tfoot>
                </table>
                <!--end::Table-->
            </div>
            <!--end::Table wrapper-->
        </div>
        <!--end::Card body-->
    </div>

@endif

@section('internal_styles')

    <!-- data tables css -->
    <link rel="stylesheet" href="{{ asset('adminDashboard/assets/plugins/data-tables/css/datatables.min.css') }}">

@endsection

@section('internal_scripts')
    <script src="{{ asset('adminDashboard/assets/plugins/data-tables/js/datatables.min.js') }}"></script>
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
