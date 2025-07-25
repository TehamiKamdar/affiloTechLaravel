"use strict";

var KTSigninGeneral = (function () {
    var form, submitButton, validator;

    // Handle form validation
    var handleValidation = function () {
        validator = FormValidation.formValidation(form, {
            fields: {
                email: {
                    validators: {
                        regexp: {
                            regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                            message: "The value is not a valid email address",
                        },
                        notEmpty: {
                            message: "Email address is required",
                        },
                    },
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: "The password is required",
                        },
                    },
                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: ".fv-row",
                    eleInvalidClass: "", // comment to enable invalid state icons
                    eleValidClass: "", // comment to enable valid state icons
                }),
            },
        });
    };

    // Show Swal message
    var showSwalMessage = function (text, icon) {
        Swal.fire({
            text: text,
            icon: icon,
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-primary",
            },
        });
    };

    // Handle form submit
    var handleSubmit = function (isDemo) {
        submitButton.addEventListener("click", function (e) {
            e.preventDefault();

            validator.validate().then(function (status) {
                if (status == "Valid") {
                    submitButton.setAttribute("data-kt-indicator", "on");
                    submitButton.disabled = true;

                    if (isDemo) {
                        setTimeout(function () {
                            submitButton.removeAttribute("data-kt-indicator");
                            submitButton.disabled = false;
                            showSwalMessage("You have successfully logged in!", "success");

                            var redirectUrl = form.getAttribute("data-kt-redirect-url");
                            if (redirectUrl) {
                                location.href = redirectUrl;
                            }
                        }, 2000);
                    } else {
                        axios.post(form.getAttribute("action"), new FormData(form))
                            .then(function (response) {
                                form.reset();
                                showSwalMessage("You have successfully logged in!", "success");

                                var redirectUrl = form.getAttribute("data-kt-redirect-url");
                                if (redirectUrl) {
                                    location.href = redirectUrl;
                                }
                            })
                            .catch(function (error) {
                                var errorMessage = "Sorry, looks like there are some errors detected, please try again.";
                                if (error.response && error.response.data && error.response.data.message) {
                                    errorMessage = error.response.data.message;
                                }
                                showSwalMessage(errorMessage, "error");
                            })
                            .then(function () {
                                submitButton.removeAttribute("data-kt-indicator");
                                submitButton.disabled = false;
                            });
                    }
                } else {
                    showSwalMessage("Sorry, looks like there are some errors detected, please try again.", "error");
                }
            });
        });
    };

    // Check if URL is valid
    var isValidUrl = function (url) {
        try {
            new URL(url);
            return true;
        } catch (e) {
            return false;
        }
    };

    // Public functions
    return {
        init: function () {
            form = document.querySelector("#kt_sign_in_form");
            submitButton = document.querySelector("#kt_sign_in_submit");

            handleValidation();

            handleSubmit(!isValidUrl(form.getAttribute("action")));
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTSigninGeneral.init();
});
