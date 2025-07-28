@extends("layouts.admin.layout")

@pushonce('styles')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .disabled {
            pointer-events: none;
            cursor: pointer;
            opacity: 0.7;
        }
        a.dropdown-item.active {
            color: #FFFFFF;
        }
    </style>
@endpushonce



@section("content")

    <div class="contents">

        <div class="container-fluid">
            <div class="social-dash-wrap">
                <div class="row">
                    <div class="col-lg-4">

                          <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="ri-home-5-line text-primary"></i></a></li>
                        <li class="breadcrumb-item"><a href="">Advertisers</a></li>
                        <li class="breadcrumb-item"><a href="">Api Advertiser Duplicate Record</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
                    </div>
                    <div class="col-lg-8">
                        <div class="breadcrumb-main">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-4">

                                    </div>
                                    <div class="col-lg-4">
                                    </div>
                                    <div class="col-lg-4">
                                        <select class="js-example-basic-single js-states form-control" id="assignedFilter">
                                            <option value="" disabled selected>Assigned</option>
                                            <option @if(request()->filter == "Yes") selected @endif>Yes</option>
                                            <option @if(request()->filter == "No") selected @endif>No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">


                                <table class="table table table-condensed table-bordered table-striped table-hover datatable">
                                    <thead>
                                        <tr class="userDatatable-header footable-header">
                                            <th>
                                                Advertiser URL
                                            </th>
                                            <th >
                                                Networks
                                            </th>
                                            <th>
                                                Assigned To
                                            </th>
                                            <th>
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($advertisers as $key => $advertiser)
                                            @php
                                                $assignNames = [];
                                            @endphp
                                            <tr>
                                                <td>{{ $advertiser['name'] ?? '-' }}<br>{{ $advertiser['url'] ?? '-' }}</td>
                                                <td>
                                                    @foreach($advertiser['network_names'] as $network)
                                                        @if($network['status'])
                                                            @php
                                                                $assignNames[] = $network['name'];
                                                            @endphp
                                                        @endif
                                                        <a href="{{ route("admin.advertisers.api.view", ['advertiser' => $network['id']]) }}">{{ $network['name'] }}</a> - @if($network['commission']) {{ $network['commission'] }}{{ $network['type'] }} @else N/A @endif<br>
                                                    @endforeach
                                                </td>
                                                <td id="assignedTo{{ $key }}">
                                                    @if(count($assignNames))
                                                        {{ implode(', ', $assignNames) }}
                                                    @else
                                                        Not Assigned
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-primary btn-default btn-squared dropdown-toggle" id="dropdownMenuButton{{ $key }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Assign
                                                            <i class="la la-angle-down"></i>
                                                        </button>
                                                        <div class="dropdown-menu" id="assign{{ $key }}" aria-labelledby="dropdownMenuButton{{ $key }}">
                                                            @foreach($advertiser['network_names'] as $network)
                                                                <a onclick="assignedFunc(this, `{{ $network['id'] }}`, `{{ $advertiser['url'] }}`, `{{ $key }}`)" class="dropdown-item {{ in_array($network['name'], $assignNames) ? "active" : null }}" href="javascript:void(0);">{{ $network['name'] }}</a>
                                                            @endforeach
                                                            <a onclick="unassignedFunc(this, `{{ $advertiser['url'] }}`, `{{ $key }}`)" class="dropdown-item unassign {{ count($assignNames) ? null : "active" }}" href="javascript:void(0);">Do not Show</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

    <tr class="text-center">
        <td colspan="4">
            <div class="d-flex justify-content-center mt-1 mb-20">

                {{  $advertisers->links('partial.publisher_pagination') }}
            </div>
        </td>
    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        function assignedFunc(event, id, url, rowID)
        {
            $(`#assign${rowID} .unassign`).removeClass('active');

            let status = "{{ \App\Helper\Static\Vars::ADVERTISER_AVAILABLE }}";
            if($(event).hasClass('active')) {
                $(event).removeClass("active");
                status = "{{ \App\Helper\Static\Vars::ADVERTISER_NOT_AVAILABLE }}";
            }
            else {
                $(event).addClass("active");
            }
            updateAdvertiser(id, url, status, rowID);
        }

        function unassignedFunc(event, url, rowID)
        {
            $(`#assign${rowID} .dropdown-item`).removeClass('active');
            $(event).addClass("active");
            updateAdvertiser(null, url, null, rowID);
        }

        function updateAdvertiser(id, url, status, rowID)
        {
            $.ajax({
                url: '{{ route("admin.advertisers.api.show_on_publisher.duplicate_record.store") }}',
                type: 'POST',
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                data: {id, url, status},
                success: function (response) {
                    $(`#assignedTo${rowID}`).text(response.data.source);
                },
                error: function (response) {

                }
            });
        }

        document.addEventListener("DOMContentLoaded", function () {
            $("#assignedFilter").change((event) => {
// console.log('hey')
                // Get the current URL
                let currentURL = new URL(window.location.href);

                // Add a new query parameter
                currentURL.searchParams.set('filter', event.target.value);

                // Replace the current URL with the updated URL
                window.history.replaceState({}, '', currentURL.href);

                window.location.reload();

            });
        });
    </script>

@endsection
