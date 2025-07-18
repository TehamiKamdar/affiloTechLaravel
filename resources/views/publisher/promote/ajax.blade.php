<div class="row">


    @if(count($coupons))
        @foreach($coupons as $coupon)
            @php
                $advertiser = \App\Models\Advertiser::where('advertiser_id', $coupon->advertiser_id)->first();
            @endphp
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card coupon-card">
                    <div class="bg-primary p-3">
                        <div class="ribbon">HOT</div>
                        <h5 class="coupon-code mb-1 d-none">{{ $coupon->code}} </h5>
                        <p class="coupon-desc mb-0 text-uppercase">{{ $coupon->type}}</p>
                    </div>
                    <div class="card-body">
                        <h3 class="coupon-value text-primary">{{ $coupon->code}}</h3>
                        <p class="coupon-details" title="{{ $coupon->title }}">
                            {{ \Illuminate\Support\Str::limit($coupon->title, 40, '....') }}
                        </p>
                        <div class="d-flex">
                            <p class="text-success text-sm me-3">
                                <i class="fas fa-clock me-1"></i> Starts: {{ \Carbon\Carbon::parse($coupon->start_date)->format('Y/m/d') }}
                            </p>
                            <p class="text-danger text-sm">
                                <i class="fas fa-clock me-1"></i> Expires: {{ \Carbon\Carbon::parse($coupon->end_date)->format('Y/m/d') }}
                            </p>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <button class="btn btn-primary btn-sm copy-btn" data-code="{{ $coupon->code}}">
                            <i class="fas fa-copy me-1"></i> Copy Code
                            </button>

                        </div>
                        <div class="d-flex justify-content-end align-items-center">
                            <p class="text-xs">Created By:</p>
                            <h6 class="mb-1"><a href="{{ route("publisher.view-advertiser", ['advertiser' => $advertiser->id]) }}">{{ \Illuminate\Support\Str::limit($coupon->advertiser_name, 50, '....') }}</a></h6>
                        </div>
                    </div>
                </div>
                </div>
            {{-- <tr>
                <td class="d-flex align-items-center">

                    <!--begin::User details-->
                    <div class="d-flex flex-column">
                        <a href="{{ route("publisher.view-advertiser", ['advertiser' => $advertiser->id]) }}"
                            class="text-gray-800 text-hover-primary mb-1">
                            {{ \Illuminate\Support\Str::limit($coupon->advertiser_name, 50, '....') }}
                        </a>

                    </div>
                    <!--end::User details-->
                </td>
                <td>
                    <span class="fw-bold"> {{ \Illuminate\Support\Str::limit($coupon->title, 40, '....') }}</span>
                </td>

                <td class="text-end pe-0">
                    <span>{{ $coupon->code}}</span>
                </td>

                <td class="text-end pe-0">
                    <span>{{ $coupon->type}}</span>
                </td>
                <td class="text-end pe-0">
                    <span>{{ \Carbon\Carbon::parse($coupon->start_date)->format('d-m-Y') }} -<br>
                        {{ \Carbon\Carbon::parse($coupon->end_date)->format('d-m-Y') }}</span>
                </td>

                <td class="text-end pe-0">

                    <a class="pt-2 me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_apply_data_{{ $coupon->id }}"
                        style="cursor:pointer">
                        <i class="ki-duotone ki-eye fs-2x">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </a>
                    <!--begin::Modal - Adjust Balance-->
                    <div class="modal fade" id="kt_modal_apply_data_{{ $coupon->id }}" tabindex="-1" aria-hidden="true">
                        <!--begin::Modal dialog-->
                        <div class="modal-dialog modal-dialog-centered mw-1000px">
                            <!--begin::Modal content-->
                            <div class="modal-content">
                                <!--begin::Modal header-->
                                <div class="modal-header">
                                    <!--begin::Modal title-->
                                    <h2 class="fw-bold">
                                        Coupn Details
                                    </h2>
                                    <!--end::Modal title-->
                                    <!--begin::Close-->
                                    <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                        data-kt-apply-advertiser-modal-action="close"
                                        onclick="close_modal(`{{ $coupon->id }}`)">
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

                                    <div class="dt-responsive table-responsive">
                                        <table class="table table-striped table-bordered nowrap">
                                            <tbody class="search-api" id="tableContent" style="text-align:center;">
                                                <tr>
                                                    <th>Advertiser name</th>
                                                    <td>{{$coupon->advertiser_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="min-w-100px">Offer Name</th>
                                                    <td class="min-w-100px">{{$coupon->title}}</td>
                                                </tr>
                                                <tr>
                                                    <th class="min-w-100px">Description</th>
                                                    <td class="min-w-100px">{{$coupon->description}}</td>
                                                </tr>
                                                <tr>
                                                    <th class="min-w-100px">Type</th>
                                                    <td class="min-w-100px">{{$coupon->type}}</td>
                                                </tr>
                                                <tr>
                                                    <th class="min-w-70px">Start Date - End Date</th>
                                                    <td class="min-w-100px">
                                                        {{ \Carbon\Carbon::parse($coupon->start_date)->format('d-m-Y') }} -
                                                        {{ \Carbon\Carbon::parse($coupon->end_date)->format('d-m-Y') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <!--end::Modal body-->
                            </div>
                            <!--end::Modal content-->
                        </div>
                        <!--end::Modal dialog-->
                    </div>
                    <!--end::Modal - New Card-->
                </td>


            </tr> --}}
        @endforeach
    @else
        <div class="d-flex justify-content-center">
            No Coupons Available
        </div>
    @endif
</div>
