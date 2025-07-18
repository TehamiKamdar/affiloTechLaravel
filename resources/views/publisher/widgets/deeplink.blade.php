@php
    $websites = \App\Models\Website::withAndWhereHas('users', function($user) {
        return $user->where("id", auth()->user()->id);
    })->where("status", \App\Models\Website::ACTIVE)->count();
@endphp

@if($websites)
    <!-- Profile files Bio -->
    <form action="javascript:void(0)" id="advertiserDeeplinkForm">
        <div class="card" id="deeplinkWrapper">
            <div class="card-body" id="mainDeeplinkBody">
                <div class="files-area d-flex justify-content-between align-items-center">
                    <div class="files-area__left d-flex align-items-center">
                        <div class="files-area__title">

                                <p class="mb-0 fs-14 fw-500 color-dark text-capitalize" style="font-size: 20px;font-weight: 900;">Create A Link</p>


                                <span class="color-light fs-12 d-flex " style="margin-top: 10px;">Promote any brand with a simple link.</span>

                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="form-group">

                            <select class="form-control" id="widgetAdvertiser" name="widgetAdvertiser" required style="margin-top: 10px;">
                                <option value="" selected disabled>Please Select</option>

                                @foreach(\App\Helper\PublisherData::getAdvertiserList() as $advertiserList)
                                    <option value="{{ $advertiserList['sid'] }}" @if(isset($advertiser->sid) && $advertiser->sid === $advertiserList['sid']) selected @endif data-dd="{{ $advertiserList['deeplink_enabled'] }}">{{ $advertiserList['name'] }}</option>
                                @endforeach
                            </select>
                            <div id="deepLinkContent" style="margin-top: 5px;">
                                @if(isset($advertiser->deeplink_enabled))
                                    @if($advertiser->deeplink_enabled)
                                        <div class="pt-1" style="color: green;">
                                            <i class="fas fa-check-circle"></i>
                                            <span class="icon-text ml-1">Deep Link</span>
                                        </div>
                                    @else
                                        <div class="pt-1" style="color: red;">
                                            <i class="fas fa-times-circle"></i>
                                            <span class="icon-text ml-1">Deep Link</span>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 10px;">
                            <input type="text" class="form-control" id="landing_url" name="landing_url" placeholder="Enter a Landing Page Optional" @if(isset($advertiser->deeplink_enabled) && !$advertiser->deeplink_enabled) style="display: none;" @endif>
                        </div>
                        <div class="form-group display-hidden" id="subIDContent" style="margin-top: 10px;">
                            <input type="text" class="form-control" id="sub_id" name="sub_id" placeholder="Enter a Sud ID Optional">
                        </div>
                        <div class="form-inline-action d-flex justify-content-between align-items-center" style="margin-top: 10px;">
                            <button type="submit" class="btn btn-sm text-white btn-primary btn-default btn-squared text-capitalize m-1">Create</button>
                            <!--<a href="javascript:void(0)" id="advancedOpt"><span class="atbd-tag tag-primary tag-transparented">Advanced</span></a>-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="loader-overlay display-hidden" id="showLoader">
                <div class="atbd-spin-dots spin-lg">
                    <span class="spin-dot badge-dot dot-primary"></span>
                    <span class="spin-dot badge-dot dot-primary"></span>
                    <span class="spin-dot badge-dot dot-primary"></span>
                    <span class="spin-dot badge-dot dot-primary"></span>
                </div>
            </div>
        </div>
    </form>
    <!-- Profile files End -->

    @push("extended_styles")
        <link rel="stylesheet" href="{{ \App\Helper\Static\Methods::staticAsset("vendor_assets/css/select2.min.css") }}"/>
        <!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    @endpush

<script src="{{ \App\Helper\Static\Methods::staticAsset("vendor_assets/js/jquery/jquery-3.5.1.min.js") }}"></script>
<script src="{{ \App\Helper\Static\Methods::staticAsset("vendor_assets/js/jquery/jquery-ui.js") }}"></script>
     <script src="{{ \App\Helper\Static\Methods::staticAsset("vendor_assets/js/select2.full.min.js") }}"></script>

<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>-->

<!--<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>-->



        <script>

            function copyLink(msg) {

                var element = document.getElementById(`widgetTrackingURL`);
    if (!element) {
        console.error("Element not found: " + element);
        return;
    }

    var text = element.value || element.value; // Use textContent to avoid HTML entity issues

    var tempInput = document.createElement("textarea");
    tempInput.value = text;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);

                normalMsg({"message": `${msg} Successfully Copied.`, "success": true});
            }

            function setDeeplinkContent(response)
            {
                $("#mainDeeplinkBody").addClass("border-bottom mb-5");

                let content = '';

                if(response.deeplink_link_url)
                {
                    content = `
                    <div class="form-group">
                        <input type="text" class="form-control" id="widgetTrackingURL" name="widgetTrackingURL" value="${response.deeplink_link_url}" readonly>
                    </div>
                    `;
                    if(response.deeplink_link_url != 'Generating tracking links.....')
                        content = `${content}<button type="button" onclick="copyLink('${response.deeplink_link_url}')" class="btn btn-sm mt-5 text-white btn-primary btn-default btn-squared text-capitalize m-1">Copy Deep Link</button>`;

                }
                else
                {
                    content = `
                    <div class="form-group">
                        <input type="text" class="form-control" id="widgetTrackingURL" name="widgetTrackingURL" value="${response.tracking_url_short}" readonly>
                    </div>`;

                    if(response.tracking_url != 'Generating tracking links.....')
                        content = `${content}<button type="button" onclick="copyLink('Tracking URL')" class="btn btn-sm mt-5 text-white btn-primary btn-default btn-squared text-capitalize m-1">Copy Tracking Link</button>`;
                }

                $("#deeplinkWrapper").append(`
                            <div class="card-body" id="deeplinkBottomWrapper">
                                <div class="files-area d-flex justify-content-between align-items-center">
                                    <div class="files-area__left d-flex align-items-center">
                                        <div class="files-area__title">
                                            <span class="color-light fs-12 d-flex ">Use this link to promote ${response.name} updates may take up to 5 min to propagate.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        ${content}
                                    </div>
                                </div>
                            </div>
                        `)
                $("#deeplinkContent").html("");

                $("#mainDeeplinkBody").removeClass('disableDiv');
                $("#showLoader").hide();
            }

            function loaderHTML()
            {
                return `
                <div class="spin-container text-center mt-5 mb-3">
                    <div class="atbd-spin-dots spin-md">
                        <span class="spin-dot badge-dot dot-primary"></span>
                        <span class="spin-dot badge-dot dot-primary"></span>
                        <span class="spin-dot badge-dot dot-primary"></span>
                        <span class="spin-dot badge-dot dot-primary"></span>
                    </div>
                </div>
            `
            }

            document.addEventListener("DOMContentLoaded", function () {

                $("#deeplinkContent").html(loaderHTML());

                $("#advancedOpt").click(function () {
                    if($('#subIDContent:visible').length)
                    {
                        $(this).html("<span class='atbd-tag tag-primary tag-transparented'>Advanced</span>");
                        $('#subIDContent').hide();
                    }
                    else
                    {
                        $(this).html("<span class='atbd-tag tag-primary tag-transparented'>Close</span>");
                        $('#subIDContent').show();
                    }
                });

                // $("#widgetAdvertiser").select2({
                //     placeholder: "Please Select",
                //     dropdownCssClass: "tag",
                //     allowClear: false
                // });

                $('#widgetAdvertiser').on('select2:select', function (e) {
                    let data = e.params.data;
                    if(data.element.dataset.dd == 1)
                    {
                        $("#deepLinkContent").html(`
                            <div class="pt-1" style="color: green;">
                                <i class="fas fa-check-circle"></i>
                                <span class="icon-text ml-1">Deep Link</span>
                            </div>
                        `);
                        $("#landing_url").show();
                    } else {
                        $("#deepLinkContent").html(`
                            <div class="pt-1" style="color: red;">
                                <i class="fas fa-times-circle"></i>
                                <span class="icon-text ml-1">Deep Link</span>
                            </div>
                        `);
                        $("#landing_url").hide();
                    }
                });

                $("#advertiserDeeplinkForm").submit(function () {

                    $("#deeplinkBottomWrapper").remove()
                    $("#mainDeeplinkBody").removeClass("border-bottom mb-20")
                    $("#deeplinkContent").html(loaderHTML());

                    $("#mainDeeplinkBody").addClass('disableDiv');
                    $("#showLoader").show();

                    let url = "";

                    if($("#landing_url").val())
                    {
                        url = '{{ route("publisher.deeplink.check-availability") }}';
                    }
                    else
                    {
                        url = '{{ route("publisher.tracking.check-availability") }}';
                    }
                    console.log($(this).serialize());

                    $.ajax({
                        url: url,
                        type: 'POST',
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        data: $(this).serialize(),
                        success: function (response) {
                            setTimeout(() => {
                                if(response.success)
                                {
                                    setDeeplinkContent(response);
                                } else
                                {
                                    $("#deeplinkContent").html("");
                                    console.log(response.message);
                                    normalMsg({"message": response.message, "success": response.success});
                                }
                            }, 1000);
                        },
                        error: function (response) {
                            showErrors(response)
                            $("#deeplinkContent").html("");
                        }
                    }).done(function () {
                        $("#mainDeeplinkBody").removeClass('disableDiv');
                        $("#showLoader").hide();
                    });


                });

            });

            function normalMsg(response){
                alert(response.message);
            }
        </script>
        
        @else
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h4 class="alert-heading">Error!</h4>
        <div>Please go to <a href="https://app.profitrefer.com/publisher/profile/website">website settings</a> and verify your site to Generate deeplink.</div>
        <button type="button" class="btn-sm btn-close bg-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>


@endif
