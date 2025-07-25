"use strict";

var KTCreateApp = function () {
    var e, t, o, r, a, i, n = [];
    return {
        init: function () {
            (e = document.querySelector("#kt_modal_create_app")) && (new bootstrap.Modal(e), t = document.querySelector("#kt_modal_create_app_stepper"),
                o = document.querySelector("#kt_modal_create_app_form"), r = t.querySelector('[data-kt-stepper-action="submit"]'), a = t.querySelector(
                    '[data-kt-stepper-action="next"]'), (i = new KTStepper(t)).on("kt.stepper.changed", (function (e) {
                    3 === i.getCurrentStepIndex() ? (r.classList.remove("d-none"), r.classList.add("d-inline-block"), a.classList.add(
                        "d-none")) : 4 === i.getCurrentStepIndex() ? (r.classList.add("d-none"), a.classList.add("d-none")) : (r.classList
                        .remove("d-inline-block"), r.classList.remove("d-none"), a.classList.remove("d-none"))
                })), i.on("kt.stepper.next", (function (e) {
                    console.log("stepper.next");
                    var t = n[e.getCurrentStepIndex() - 1];
                    t ? t.validate().then((function (t) {
                        console.log("validated!"), "Valid" == t ? e.goNext() : Swal.fire({
                            text: "Sorry, looks like there are some errors detected, please try again.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-light"
                            }
                        }).then((function () { }))
                    })) : (e.goNext(), KTUtil.scrollTop())
                })), i.on("kt.stepper.previous", (function (e) {
                    console.log("stepper.previous"), e.goPrevious(), KTUtil.scrollTop()
                })), r.addEventListener("click", (function (e) {
                    n[2].validate().then((function (t) {
                        console.log("validated!"), "Valid" == t ? (e.preventDefault(), r.disabled = !0, r.setAttribute(
                            "data-kt-indicator", "on"), setTimeout((function () {
                                r.removeAttribute("data-kt-indicator"), r.disabled = !1, i.goNext()
                            }), 2e3)) : Swal.fire({
                                text: "Sorry, looks like there are some errors detected, please try again.",
                                icon: "error",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-light"
                                }
                            }).then((function () {
                                KTUtil.scrollTop()
                            }))
                    }))
                })), n.push(FormValidation.formValidation(o, {
                    fields: {
                        website_url: {
                            validators: {
                                notEmpty: {
                                    message: "Website URL is required"
                                }
                            }
                        },
                        website_type: {
                            validators: {
                                notEmpty: {
                                    message: "Must select website type"
                                }
                            }
                        }
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger,
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: ".fv-row",
                            eleInvalidClass: "",
                            eleValidClass: ""
                        })
                    }
                })), n.push(FormValidation.formValidation(o, {
                    fields: {
                        website_intro: {
                            validators: {
                                notEmpty: {
                                    message: "Website introduction is required"
                                }
                            }
                        },
                        website_category: {
                            validators: {
                                notEmpty: {
                                    message: "Website category is required"
                                }
                            }
                        },
                        website_country: {
                            validators: {
                                notEmpty: {
                                    message: "Must select website country"
                                }
                            }
                        }
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger,
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: ".fv-row",
                            eleInvalidClass: "",
                            eleValidClass: ""
                        })
                    }
                })), n.push(FormValidation.formValidation(o, {
                    fields: {
                        company_name: {
                            validators: {
                                notEmpty: {
                                    message: "Company name is required"
                                }
                            }
                        },
                        first_name: {
                            validators: {
                                notEmpty: {
                                    message: "First name is required"
                                }
                            }
                        },
                        last_name: {
                            validators: {
                                notEmpty: {
                                    message: "Last name is required"
                                }
                            }
                        },
                        phone_number: {
                            validators: {
                                notEmpty: {
                                    message: "Phone number is required"
                                }
                            }
                        },
                        company_address: {
                            validators: {
                                notEmpty: {
                                    message: "Company address is required"
                                }
                            }
                        },
                        country: {
                            validators: {
                                notEmpty: {
                                    message: "Company country is required"
                                }
                            }
                        }
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger,
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: ".fv-row",
                            eleInvalidClass: "",
                            eleValidClass: ""
                        })
                    }
                })));

            // Add timer function after the final step submission function
            function startTimer(duration, redirectUrl) {
                let timer = duration;
                const countdownSpan = document.getElementById('countdown');

                // Update the countdown display every second
                const intervalId = setInterval(function () {
                    countdownSpan.textContent = timer;
                    timer--;

                    // Redirect when the countdown reaches 0
                    if (timer < 0) {
                        clearInterval(intervalId);
                        window.location.href = redirectUrl;
                    }
                }, 1000);
            }

            // Start the timer after final step submission
            r.addEventListener("click", function (e) {
                n[2].validate().then(function (t) {
                    console.log("validated!"), "Valid" == t ? (
                        e.preventDefault(),
                        r.disabled = !0,
                        r.setAttribute("data-kt-indicator", "on"),
                        setTimeout(function () {
                            r.removeAttribute("data-kt-indicator"),
                                r.disabled = !1,
                                i.goNext(),
                                // Start the timer for 3 seconds and redirect
                                document.getElementById('kt_modal_create_app_form').submit();
                        }, 2e3)
                    ) : Swal.fire({
                        text: "Sorry, looks like there are some errors detected, please try again.",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-light"
                        }
                    }).then(function () {
                        KTUtil.scrollTop()
                    })
                })
            });
        }
    }
}();
KTUtil.onDOMContentLoaded((function () {
    KTCreateApp.init()
}));
