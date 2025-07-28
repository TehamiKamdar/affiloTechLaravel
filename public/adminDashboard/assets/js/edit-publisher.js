'use strict';
$(document).ready(function() {
    $(function() {
        // [ Initialize validation ]
        $('#edit-publisher').validate({
            ignore: '.ignore, .select2-input',
            focusInvalid: false,
            rules: {
                'email': {
                    required: true,
                    email: true
                },
                'name': {
                    required: true
                },
                'password': {
                    required: false,
                    minlength: 6,
                    maxlength: 20
                },
                'password_confirmation': {
                    required: false,
                    minlength: 6,
                    equalTo: 'input[name="password"]'
                },
            },
            messages: {
                name: "The name field is required.",
                email: {
                    required: "The email field is required.",
                    email: "Please enter a valid email address.",
                },
            },
            errorPlacement: function errorPlacement(error, element) {
                var $parent = $(element).parents('.form-group');

                // Do not duplicate errors
                if ($parent.find('.jquery-validation-error').length) {
                    return;
                }

                $parent.append(
                    error.addClass('jquery-validation-error small form-text invalid-feedback')
                );
            },
            highlight: function(element) {
                var $el = $(element);
                var $parent = $el.parents('.form-group');

                $el.addClass('is-invalid');

                // Select2 and Tagsinput
                if ($el.hasClass('select2-hidden-accessible') || $el.attr('data-role') === 'tagsinput') {
                    $el.parent().addClass('is-invalid');
                }
            },
            unhighlight: function(element) {
                $(element).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
            }
        });
    });
});



// "use strict";
//
// var KTSigninGeneral = (function () {
//     var form, submitButton, validator;
//
//     // Handle form validation
//     var handleValidation = function () {
//         validator = FormValidation.formValidation(form, {
//             fields: {
//                 name: {
//                     validators: {
//                         notEmpty: {
//                             message: 'Name is required'
//                         }
//                     }
//                 },
//                 email: {
//                     validators: {
//                         regexp: {
//                             regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
//                             message: "The value is not a valid email address",
//                         },
//                         notEmpty: {
//                             message: "Email address is required",
//                         },
//                     },
//                 },
//                 password: {
//                     validators: {
//                         identical: {
//                             compare: function() {
//                                 return form.querySelector('[name="password_confirmation"]').value;
//                             },
//                             message: "Password and Confirm Password do not match",
//                         },
//                     },
//                 },
//                 confirm_password: {
//                     validators: {
//                         identical: {
//                             compare: function() {
//                                 return form.querySelector('[name="password"]').value;
//                             },
//                             message: "Password and Confirm Password do not match",
//                         },
//                     },
//                 },
//             },
//             plugins: {
//                 trigger: new FormValidation.plugins.Trigger(),
//                 bootstrap: new FormValidation.plugins.Bootstrap5({
//                     rowSelector: ".fv-row",
//                     eleInvalidClass: "", // comment to enable invalid state icons
//                     eleValidClass: "", // comment to enable valid state icons
//                 }),
//             },
//         });
//     };
//
//     // Show Swal message
//     var showSwalMessage = function (text, icon) {
//         Swal.fire({
//             text: text,
//             icon: icon,
//             buttonsStyling: false,
//             confirmButtonText: "Ok, got it!",
//             customClass: {
//                 confirmButton: "btn btn-primary",
//             },
//         });
//     };
//
//     // Handle form submit
//     var handleSubmit = function (isDemo) {
//         submitButton.addEventListener("click", function (e) {
//             e.preventDefault();
//
//             validator.validate().then(function (status) {
//                 if (status == "Valid") {
//                     submitButton.setAttribute("data-kt-indicator", "on");
//                     showSwalMessage("You have successfully updated the publisher's data!", "success");
//                     submitButton.disabled = true;
//                     setTimeout(function () {
//                         submitButton.disabled = false;
//                         document.getElementsByClassName('updateData')[0].submit();
//                     }, 2000);
//                 } else {
//                     showSwalMessage("Sorry, looks like there are some errors detected, please try again.", "error");
//                 }
//             });
//         });
//     };
//
//     // Check if URL is valid
//     var isValidUrl = function (url) {
//         try {
//             new URL(url);
//             return true;
//         } catch (e) {
//             return false;
//         }
//     };
//
//     // Public functions
//     return {
//         init: function () {
//             form = document.querySelector("#kt_publisher_form");
//             submitButton = document.querySelector("#kt_publishers_submit");
//
//             handleValidation();
//
//             handleSubmit(!isValidUrl(form.getAttribute("action")));
//         },
//     };
// })();
//
// // On document ready
// KTUtil.onDOMContentLoaded(function () {
//     KTSigninGeneral.init();
// });
