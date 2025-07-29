"use strict";

const ExportFormHandler = (function () {
    let form, submitButton;

    function handleSubmitAjax() {
        $(submitButton).on('click', function (e) {
            e.preventDefault();

            const exportFormat = form.querySelector('[name="export_format"]').value;

            if (!exportFormat) {
                iziToast.error({
                    title: 'Validation Error',
                    message: 'Please select a valid export format.',
                    position: 'topRight',
                    timeout: 3000
                });
                return;
            }

            $(submitButton).prop('disabled', true).html(
                `<span class="spinner-border spinner-border-sm mr-2"></span> Please wait...`
            );

            const formData = new FormData(form);
            const url = $(form).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': token
                },
                success: function (response) {
                    iziToast.success({
                        title: 'Success',
                        message: 'Export request submitted. You can download the file shortly from Tools > Download Export Files.',
                        position: 'topRight',
                        timeout: 4000
                    });

                    form.reset();
                    setTimeout(() => location.reload(), 1500);
                },
                error: function () {
                    iziToast.error({
                        title: 'Error',
                        message: 'Something went wrong. Please try again.',
                        position: 'topRight',
                        timeout: 4000
                    });
                },
                complete: function () {
                    $(submitButton).prop('disabled', false).html(`<span class="indicator-label">Request to Export Data</span>`);
                }
            });
        });
    }

    return {
        init: function () {
            form = document.querySelector('#kt_advertiser_export_in_form');
            submitButton = document.querySelector('#kt_advertiser_export_submit');

            if (!form || !submitButton) return;

            handleSubmitAjax();
        }
    };
})();

$(document).ready(function () {
    ExportFormHandler.init();
});
