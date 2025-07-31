@php
    $websites = \App\Models\Website::withAndWhereHas('users', function ($user) {
        return $user->where("id", auth()->user()->id);
    })->where("status", \App\Models\Website::ACTIVE)->count();
@endphp

@if($websites)
    <form action="javascript:void(0)" id="advertiserDeeplinkForm">

        <div class="card" id="deeplinkWrapper">
            <div class="card-header">
                <h4><i class="fas fa-link mr-2"></i>Link Builder</h4>
            </div>
            <div class="card-body" id="mainDeeplinkBody">
                <div class="form-group">
                    <select class="custom-select" id="dropdownSelect" name="widgetAdvertiser" required>
                        <option selected disabled>Select Any Advertiser..</option>
                        @foreach(\App\Helper\PublisherData::getAdvertiserList() as $advertiserList)
                            <option value="{{ $advertiserList['sid'] }}" @if(isset($advertiser->sid) && $advertiser->sid === $advertiserList['sid']) selected @endif
                                data-dd="{{ $advertiserList['deeplink_enabled'] }}">{{ $advertiserList['name'] }}</option>
                        @endforeach
                    </select>
                    <div class="mt-2" id="deeplinkStatusContainer">

                    </div>
                </div>

                <div class="form-group landing-page">
                    <label for="input1" class="text-primary"><i class="fas fa-tag mr-2"></i>Enter Landing Page
                        URL</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                        </div>
                        <input type="text" class="form-control text-black" name="landing_url" id="input1"
                            placeholder="Enter Landing Page URL..." required disabled>
                    </div>
                </div>

                <div class="form-group sub-id">
                    <label for="input2" class="text-primary"><i class="fas fa-barcode mr-2"></i>Enter Sub ID <span
                            class="text-danger">(Optional)</span></label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                        </div>
                        <input type="text" class="form-control text-black" name="sub_id" id="input2"
                            placeholder="Enter Sub ID..." required disabled>
                    </div>
                </div>

                <div class="form-group text-center mt-5">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-check mr-2"></i> Create
                    </button>
                </div>
            </div>
        </div>
    </form>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            const $dropdown = $('#dropdownSelect');
            const $statusContainer = $('#deeplinkStatusContainer');


            function updateDeeplinkStatus() {
                const $selectedOption = $dropdown.find('option:selected');
                const isDeeplinkEnabled = $selectedOption.data('dd');

                $statusContainer.empty();

                if (isDeeplinkEnabled === 1) {
                    $('#input1').removeAttr('disabled');
                    $('#input2').removeAttr('disabled');
                    $('.landing-page').removeClass('d-none').addClass('d-block');
                    $statusContainer.html(`
                        <div class="d-flex align-items-center p-2 bg-light-success rounded mb-2"
                            style="background-color: rgba(40, 167, 69, 0.1);">
                            @if (Route::is('publisher.view-advertiser'))
                            <img src="../../publisherAssets/assets/icons8-check.gif" class="rounded-circle mr-2" height="24" alt="Verified">
                            @else
                            <img src="../publisherAssets/assets/icons8-check.gif" class="rounded-circle mr-2" height="24" alt="Verified">
                            @endif
                            <span class="text-success font-weight-bold">
                                Deep Link Verified
                            </span>
                        </div>`);
                } else {
                    $('#input2').removeAttr('disabled');
                    $('.landing-page').removeClass('d-block').addClass('d-none');
                    $statusContainer.html(`
                        <div class="d-flex align-items-center p-2 bg-light-danger rounded mb-2"
                            style="background-color: rgba(167, 40, 40, 0.1);">
                            @if (Route::is('publisher.view-advertiser'))
                            <img src="../../publisherAssets/assets/icons8-cross.gif" class="rounded-circle mr-2" height="24" alt="Verified">
                            @else
                            <img src="../publisherAssets/assets/icons8-cross.gif" class="rounded-circle mr-2" height="24" alt="Verified">
                            @endif
                            <span class="text-danger font-weight-bold">
                                Deep Link Not Verified
                            </span>
                        </div>`);
                }
            }

            // Bind change event
            $dropdown.on('change', updateDeeplinkStatus);

            // Trigger on page load if value is selected
            if ($dropdown.val()) {
                updateDeeplinkStatus();
            }
        });

        function copyLink(msg) {
            const element = document.getElementById('widgetTrackingURL');

            if (!element) {
                iziToast.error({
                    title: 'Error',
                    message: 'Tracking URL not found!',
                    position: 'topRight'
                });
                return;
            }

            const text = element.value || element.textContent;

            const tempInput = document.createElement('textarea');
            tempInput.style.position = 'absolute';
            tempInput.style.left = '-9999px';
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    iziToast.success({
                        title: 'Copied',
                        message: `Link Copied to Clipboard.`,
                        position: 'topRight',
                        timeout: 3000
                    });
                } else {
                    throw new Error('Copy command unsuccessful');
                }
            } catch (err) {
                iziToast.error({
                    title: 'Error',
                    message: 'Failed to copy link.',
                    position: 'topRight'
                });
            }

            document.body.removeChild(tempInput);
        }


        function setDeeplinkContent(response) {
            $("#mainDeeplinkBody").addClass("border-bottom mb-3");

            let content = '';

            if (response.deeplink_link_url) {
                content = `
                                <div class="form-group">
                                    <input type="text" class="form-control" id="widgetTrackingURL" name="widgetTrackingURL" value="${response.deeplink_link_url}" readonly>
                                </div>
                                `;
                if (response.deeplink_link_url != 'Generating tracking links.....')
                    content = `${content}<button type="button" onclick="copyLink('${response.deeplink_link_url}')" class="btn btn-sm mt-3 text-white btn-primary btn-default btn-squared text-capitalize m-1">Copy Deep Link</button>`;

            }
            else {
                content = `
                                <div class="form-group">
                                    <input type="text" class="form-control" id="widgetTrackingURL" name="widgetTrackingURL" value="${response.tracking_url_short}" readonly>
                                </div>`;

                if (response.tracking_url != 'Generating tracking links.....')
                    content = `${content}<button type="button" onclick="copyLink('${response.tracking_url_short}')" class="btn btn-sm mt-3 text-white btn-primary btn-default btn-squared text-capitalize m-1">Copy Tracking Link</button>`;
            }

            $("#deeplinkWrapper").append(`
                                        <div class="card-body" id="deeplinkBottomWrapper">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <small class="text-muted d-block">
                                                        Use this link to promote <strong>${response.name}</strong>. Updates may take up to 5 minutes to propagate.
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    ${content}
                                                </div>
                                            </div>
                                        </div>

                                    `)
            $("#deeplinkContent").html("");

            $("#mainDeeplinkBody").removeClass('disableDiv');
            $("#showLoader").hide();
        }

        function loaderHTML() {
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
                if ($('#subIDContent:visible').length) {
                    $(this).html("<span class='atbd-tag tag-primary tag-transparented'>Advanced</span>");
                    $('#subIDContent').hide();
                }
                else {
                    $(this).html("<span class='atbd-tag tag-primary tag-transparented'>Close</span>");
                    $('#subIDContent').show();
                }
            });


            $("#advertiserDeeplinkForm").submit(function () {

                $("#deeplinkBottomWrapper").remove()
                $("#mainDeeplinkBody").removeClass("border-bottom mb-20")
                $("#deeplinkContent").html(loaderHTML());

                $("#mainDeeplinkBody").addClass('disableDiv');
                $("#showLoader").show();

                let url = "";

                if ($("#landing_url").val()) {
                    url = '{{ route("publisher.deeplink.check-availability") }}';
                }
                else {
                    url = '{{ route("publisher.tracking.check-availability") }}';
                }
                console.log($(this).serialize());

                $.ajax({
                    url: url,
                    type: 'POST',
                    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                    data: $(this).serialize(),
                    success: function (response) {
                        setTimeout(() => {
                            if (response.success) {
                                setDeeplinkContent(response);
                            } else {
                                $("#deeplinkContent").html("");
                                console.log(response.message);
                                normalMsg({ "message": response.message, "success": response.success });
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

        function normalMsg(response) {
            alert(response.message);
        }
    </script>

@else
    <div class="alert alert-danger alert-dismissible fade show d-flex justify-content-between align-items-start" role="alert">
    <div>
        <h4 class="alert-heading mb-1">Error!</h4>
        <div>
            Please go to
            <a href="https://app.profitrefer.com/publisher/profile/website" class="text-white text-underline">
                website settings
            </a>
            and verify your site to generate deeplink.
        </div>
    </div>
    <button type="button" class="btn btn-sm btn-close btn-danger ml-3 mt-1" aria-label="Close">
        <span class="text-lg text-white">&times;</span>
    </button>
</div>



@endif
