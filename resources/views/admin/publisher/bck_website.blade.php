@if(count($websites))

    <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
        <!--begin::Card body-->
        <div class="card-body p-9">

            @foreach($websites as $key => $website)

                <div class="m-0">
                    <!--begin::Heading-->
                    <div class="d-flex align-items-center collapsible py-3 toggle mb-0" data-bs-toggle="collapse" data-bs-target="#kt_job_9_1">
                        <!--begin::Title-->
                        <h4 class="text-gray-700 fw-bold cursor-pointer mb-0">{{ $website->name }}</h4>
                        <!--end::Title-->
                    </div>
                    <!--end::Heading-->
                    <!--begin::Body-->
                    <div id="kt_job_9_1" class="collapse @if($key == 0) show @endif ms-1">

                        <!--begin::Row-->
                        <div class="row mt-7 mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold tex-gray-800">URL</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $website->url }}</span>
                                @if($website->status == \App\Models\User::STATUS_PENDING)
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($website->status == \App\Models\User::STATUS_HOLD)
                                    <span class="badge badge-info">Hold</span>
                                @elseif($website->status == \App\Models\User::STATUS_REJECT)
                                    <span class="badge badge-danger">Rejected</span>
                                @else
                                    <span class="badge badge-success">Verified</span>
                                @endif

                                @if($website->status != \App\Models\User::STATUS_REJECT)
                                    <a href="{{ route('admin.publishers.website.statusUpdate', ['website' => $website->id, 'status' => \App\Models\User::STATUS_REJECT]) }}" class="btn btn-xs btn-danger float-end">Reject</a>
                                @endif
                                @if($website->status != \App\Models\User::STATUS_HOLD)
                                    <a href="{{ route('admin.publishers.website.statusUpdate', ['website' => $website->id, 'status' => \App\Models\User::STATUS_HOLD]) }}" class="btn btn-xs btn-info float-end mr-2">Hold</a>
                                @endif
                                @if($website->status != \App\Models\User::STATUS_ACTIVE)
                                    <a href="{{ route('admin.publishers.website.statusUpdate', ['website' => $website->id, 'status' => \App\Models\User::STATUS_ACTIVE]) }}" class="btn btn-xs btn-success float-end mr-2">Active</a>
                                @endif
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold tex-gray-800">Website ID</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $website->wid }}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold tex-gray-800">Country</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row">
                                <span class="fw-semibold text-gray-800 fs-6">{{ $website->getCountry->name }}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold tex-gray-800">Monthly Traffic</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row">
                                <span class="fw-semibold text-gray-800 fs-6">{{ $website->monthly_traffic ?? "-" }}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold tex-gray-800">Monthly Page Views</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row">
                                <span class="fw-semibold text-gray-800 fs-6">{{ $website->monthly_page_views ?? "-" }}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold tex-gray-800">Intro</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $website->intro }}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold tex-gray-800">Created At</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $website->created_at ?? "-" }}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold tex-gray-800">Last Updated</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $website->updated_at ?? "-" }}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        @if($website->status != \App\Models\User::STATUS_ACTIVE)

                            @php
                                $class = "bg-light-warning";
                                $message = "After the publisher verifies the website successfully, the website status should also be updated accordingly.";
                                if($website->status == \App\Models\User::STATUS_REJECT)
                                {
                                    $class = "bg-light-danger";
                                    $message = "The admin's has put the website on reject.";
                                }
                                elseif($website->status == \App\Models\User::STATUS_HOLD)
                                {
                                    $class = "bg-light-light";
                                    $message = "The admin's has put the website on hold.";
                                }
                            @endphp

                                <!--begin::Notice-->
                            <div class="notice d-flex {{ $class }} rounded border-warning border border-dashed p-6">
                                <!--begin::Icon-->
                                <i class="ki-duotone ki-information fs-2tx text-warning me-4">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                <!--end::Icon-->
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-stack flex-grow-1">
                                    <!--begin::Content-->
                                    <div class="fw-semibold">
                                        <h4 class="text-gray-900 fw-bold">Website status still in {{ $website->status }}!</h4>
                                        <div class="fs-6 text-gray-700">{{ $message }}</div>
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Notice-->
                        @endif
                    </div>
                    <!--end::Content-->
                    <!--begin::Separator-->
                    <div class="separator separator-dashed"></div>
                    <!--end::Separator-->
                </div>

            @endforeach

        </div>
        <!--end::Card body-->
    </div>

@else

    @php $title = "No Website Available"; @endphp
    @include("partial.no", compact('title'))

@endif
