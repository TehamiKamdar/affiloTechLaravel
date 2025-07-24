"use strict";

const ExportFormHandler = (function () {
    let form, submitButton, validator;

    function handleValidation() {
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'export_format': {
                        validators: {
                            notEmpty: {
                                message: 'Please select an export format.'
                            }
                        }
                    }
                },
                plugins: {
                    bootstrap: new FormValidation.plugins.Bootstrap4({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }

            }
        );
    }

    function handleSubmitAjax() {
        $(submitButton).on('click', function (e) {
            e.preventDefault();

            validator.validate().then(function (status) {
                if (status === 'Valid') {
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

                            // Reset the form (optional)
                            form.reset();

                            // Optional: reload page or close modal
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
                } else {
                    iziToast.error({
                        title: 'Validation Error',
                        message: 'Please select a valid export format.',
                        position: 'topRight',
                        timeout: 3000
                    });
                }
            });
        });
    }

    return {
        init: function () {
            form = document.querySelector('#kt_advertiser_export_in_form');
            submitButton = document.querySelector('#kt_advertiser_export_submit');

            if (!form || !submitButton) return;

            handleValidation();
            handleSubmitAjax();
        }
    };
})();

$(document).ready(function () {
    ExportFormHandler.init();
});
